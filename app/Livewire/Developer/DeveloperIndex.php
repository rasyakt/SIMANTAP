<?php

namespace App\Livewire\Developer;

use App\Services\BackupService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\Process\Exception\ProcessFailedException;

#[Layout('layouts.app')]
#[Title('Developer Tools')]
class DeveloperIndex extends Component
{
    use WithPagination;

    // --- System Info Logic ---
    public array $systemInfo = [];

    // --- Log Logic ---
    public array $recentLogs = [];

    // --- Backup Logic ---
    public bool $creatingBackup = false;
    public ?string $createResult = null;
    public ?string $createError = null;

    public bool $showRestoreModal = false;
    public ?string $restoreFilename = null;
    public string $restoreConfirmText = '';
    public bool $restoring = false;

    public bool $showDeleteModal = false;
    public ?string $deleteFilename = null;
    public string $deleteConfirmText = '';
    public bool $deleting = false;

    public string $search = '';

    public function mount(): void
    {
        $this->authorize('backup.view'); // Reusing existing permission for admin access
        $this->loadSystemInfo();
        $this->loadRecentLogs();
    }

    public function loadSystemInfo(): void
    {
        $dbVersion = 'Unknown';
        try {
            $pdo = DB::connection()->getPdo();
            $dbVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
        } catch (\Exception $e) {
            // Ignore
        }
        
        $dbDriver = DB::connection()->getDriverName();

        $this->systemInfo = [
            'PHP Version' => PHP_VERSION,
            'Laravel Version' => app()->version(),
            'Server OS' => php_uname('s') . ' ' . php_uname('r') . ' (' . php_uname('m') . ')',
            'Web Server' => $_SERVER['SERVER_SOFTWARE'] ?? php_sapi_name(),
            'Database' => ucfirst($dbDriver) . ' ' . $dbVersion,
            'Timezone' => config('app.timezone'),
            'Environment' => app()->environment(),
        ];
    }

    public function loadRecentLogs(): void
    {
        $logPath = storage_path('logs/laravel.log');
        if (!File::exists($logPath)) {
            $this->recentLogs = [];
            return;
        }

        try {
            $file = new \SplFileObject($logPath, 'r');
            $file->seek(PHP_INT_MAX);
            $lastLine = $file->key();
            $start = max(0, $lastLine - 2000); // Read last 2000 lines
            $file->seek($start);
            
            $errors = [];
            while (!$file->eof()) {
                $line = $file->current();
                if (is_string($line) && str_contains($line, '.ERROR:')) {
                    // Extract just the error message part for cleaner display
                    $errors[] = trim($line);
                }
                $file->next();
            }
            
            // Get last 10 and reverse so newest is first
            $this->recentLogs = array_reverse(array_slice($errors, -10));
        } catch (\Exception $e) {
            $this->recentLogs = ['Gagal membaca log: ' . $e->getMessage()];
        }
    }

    // --- Backup Methods (Copied from BackupList) ---
    
    public function createBackup(): void
    {
        $this->authorize('backup.create');

        $this->creatingBackup = true;
        $this->createResult = null;
        $this->createError = null;

        try {
            $service = app(BackupService::class);
            $result = $service->createBackup();

            $this->createResult = "Backup berhasil: {$result['filename']} ({$result['size_formatted']})";

            activity('backup')
                ->causedBy(\Illuminate\Support\Facades\Auth::user())
                ->event('created')
                ->withProperties([
                    'filename' => $result['filename'],
                    'size' => $result['size'],
                    'size_formatted' => $result['size_formatted'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Membuat backup database {$result['filename']} ({$result['size_formatted']}).");
        } catch (\Exception $e) {
            $this->createError = 'Gagal membuat backup: ' . $e->getMessage();

            activity('backup')
                ->causedBy(\Illuminate\Support\Facades\Auth::user())
                ->event('created')
                ->withProperties([
                    'error' => $e->getMessage(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Gagal membuat backup database: {$e->getMessage()}.");
        } finally {
            $this->creatingBackup = false;
        }
    }

    public function downloadBackup(string $filename): void
    {
        $this->authorize('backup.view');

        $service = app(BackupService::class);
        $filepath = $service->getBackupPath($filename);

        if (!$filepath) {
            session()->flash('error', "File backup '{$filename}' tidak ditemukan.");
            return;
        }

        activity('backup')
            ->causedBy(\Illuminate\Support\Facades\Auth::user())
            ->event('exported')
            ->withProperties([
                'filename' => $filename,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Mengunduh file backup {$filename}.");

        $this->redirectRoute('developer.download', ['filename' => $filename]);
    }

    public function confirmDelete(string $filename): void
    {
        $this->authorize('backup.delete');

        $this->deleteFilename = $filename;
        $this->deleteConfirmText = '';
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteFilename = null;
        $this->deleteConfirmText = '';
    }

    public function deleteBackup(): void
    {
        $this->authorize('backup.delete');

        if (!$this->deleteFilename) {
            return;
        }

        $this->deleting = true;

        try {
            $service = app(BackupService::class);
            $deleted = $service->deleteBackup($this->deleteFilename);

            if ($deleted) {
                activity('backup')
                    ->causedBy(\Illuminate\Support\Facades\Auth::user())
                    ->event('deleted')
                    ->withProperties([
                        'filename' => $this->deleteFilename,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ])
                    ->log("Menghapus file backup {$this->deleteFilename}.");

                session()->flash('success', "File backup '{$this->deleteFilename}' berhasil dihapus.");
            } else {
                session()->flash('error', "File backup '{$this->deleteFilename}' tidak ditemukan.");
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus backup: ' . $e->getMessage());
        } finally {
            $this->deleting = false;
            $this->cancelDelete();
        }
    }

    public function confirmRestore(string $filename): void
    {
        $this->authorize('backup.restore');

        $this->restoreFilename = $filename;
        $this->restoreConfirmText = '';
        $this->showRestoreModal = true;
    }

    public function cancelRestore(): void
    {
        $this->showRestoreModal = false;
        $this->restoreFilename = null;
        $this->restoreConfirmText = '';
    }

    public function restoreBackup(): void
    {
        $this->authorize('backup.restore');

        if (!$this->restoreFilename) {
            return;
        }

        $this->restoring = true;

        try {
            $service = app(BackupService::class);
            $result = $service->restoreBackup($this->restoreFilename);

            activity('backup')
                ->causedBy(\Illuminate\Support\Facades\Auth::user())
                ->event('restored')
                ->withProperties([
                    'filename' => $this->restoreFilename,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Memulihkan database dari file backup {$this->restoreFilename}.");

            session()->flash('success', $result['message']);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memulihkan database: ' . $e->getMessage());
        } finally {
            $this->restoring = false;
            $this->cancelRestore();
        }
    }

    public function render()
    {
        $service = app(BackupService::class);
        $backups = collect($service->getBackupFiles());

        if ($this->search) {
            $backups = $backups->filter(fn($b) => str_contains(strtolower($b['filename']), strtolower($this->search)));
        }

        $dbConfig = $service->getDbConfig();

        $stats = [
            'total' => $backups->count(),
            'total_size' => $backups->sum('size'),
            'total_size_formatted' => $this->formatBytes($backups->sum('size')),
            'latest' => $backups->first()['created_at'] ?? null,
            'database' => $dbConfig['database'],
        ];

        return view('livewire.developer.developer-index', compact('backups', 'stats'));
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
