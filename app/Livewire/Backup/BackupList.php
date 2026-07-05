<?php

namespace App\Livewire\Backup;

use App\Services\BackupService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\Process\Exception\ProcessFailedException;

#[Layout('layouts.app')]
#[Title('Backup & Restore Database')]
class BackupList extends Component
{
    use WithPagination;

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
        $this->authorize('backup.view');
    }

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
                ->causedBy(auth()->user())
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
                ->causedBy(auth()->user())
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
            ->causedBy(auth()->user())
            ->event('exported')
            ->withProperties([
                'filename' => $filename,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Mengunduh file backup {$filename}.");

        $this->redirectRoute('backup.download', ['filename' => $filename]);
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
                    ->causedBy(auth()->user())
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
                ->causedBy(auth()->user())
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

        return view('livewire.backup.backup-list', compact('backups', 'stats'));
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
