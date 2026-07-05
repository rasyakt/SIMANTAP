<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupService
{
    protected string $disk;
    protected string $backupDir;

    public function __construct()
    {
        $this->disk = 'local';
        $this->backupDir = 'backups';
    }

    public function getDisk()
    {
        return Storage::disk($this->disk);
    }

    public function backupDir(): string
    {
        return $this->backupDir;
    }

    public function getDbConfig(): array
    {
        return [
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'database' => config('database.connections.mysql.database'),
            'username' => config('database.connections.mysql.username'),
            'password' => config('database.connections.mysql.password'),
        ];
    }

    public function mysqldumpPath(): string
    {
        return $this->findExecutable('mysqldump');
    }

    public function mysqlPath(): string
    {
        return $this->findExecutable('mysql');
    }

    protected function findExecutable(string $name): string
    {
        $paths = [
            "\"C:\\laragon\\bin\\mysql\\mysql-8.4.3-winx64\\bin\\{$name}.exe\"",
            "\"C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\{$name}.exe\"",
            $name,
        ];

        return $paths[0];
    }

    public function createBackup(): array
    {
        $config = $this->getDbConfig();
        $filename = 'backup-' . $config['database'] . '-' . now()->format('Y-m-d_H-i-s') . '.sql';
        $filepath = $this->getDisk()->path($this->backupDir . '/' . $filename);

        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $command = sprintf(
            '%s --host=%s --port=%s --user=%s --protocol=TCP %s %s > %s',
            $this->mysqldumpPath(),
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            $config['password'] ? '--password=' . escapeshellarg($config['password']) : '',
            escapeshellarg($config['database']),
            escapeshellarg($filepath)
        );

        $process = $this->createProcess($command);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            if (file_exists($filepath)) {
                @unlink($filepath);
            }
            throw new ProcessFailedException($process);
        }

        $size = file_exists($filepath) ? filesize($filepath) : 0;

        return [
            'filename' => $filename,
            'path' => $filepath,
            'size' => $size,
            'size_formatted' => $this->formatSize($size),
            'created_at' => now(),
        ];
    }

    public function getBackupFiles(): array
    {
        $disk = $this->getDisk();

        if (!$disk->exists($this->backupDir)) {
            return [];
        }

        $files = $disk->files($this->backupDir);
        $backups = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $timestamp = $disk->lastModified($file);
                $size = $disk->size($file);
                $filename = basename($file);

                $backups[] = [
                    'filename' => $filename,
                    'path' => $file,
                    'size' => $size,
                    'size_formatted' => $this->formatSize($size),
                    'created_at' => \Carbon\Carbon::createFromTimestamp($timestamp),
                ];
            }
        }

        usort($backups, fn($a, $b) => $b['created_at']->timestamp <=> $a['created_at']->timestamp);

        return $backups;
    }

    public function deleteBackup(string $filename): bool
    {
        $disk = $this->getDisk();
        $path = $this->backupDir . '/' . $filename;

        if ($disk->exists($path)) {
            return $disk->delete($path);
        }

        return false;
    }

    public function getBackupPath(string $filename): ?string
    {
        $path = $this->backupDir . '/' . $filename;
        $disk = $this->getDisk();

        if ($disk->exists($path)) {
            return $disk->path($path);
        }

        return null;
    }

    protected function createProcess(string $command): Process
    {
        $env = null;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $env = [
                'SYSTEMROOT' => getenv('SYSTEMROOT') ?: 'C:\\Windows',
                'PATH' => getenv('PATH'),
            ];
        }

        return Process::fromShellCommandline($command, null, $env);
    }

    public function restoreBackup(string $filename): array
    {
        $filepath = $this->getBackupPath($filename);

        if (!$filepath) {
            throw new \RuntimeException("File backup '{$filename}' tidak ditemukan.");
        }

        $config = $this->getDbConfig();

        $command = sprintf(
            '%s --host=%s --port=%s --user=%s --protocol=TCP %s %s < %s',
            $this->mysqlPath(),
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            $config['password'] ? '--password=' . escapeshellarg($config['password']) : '',
            escapeshellarg($config['database']),
            escapeshellarg($filepath)
        );

        $process = $this->createProcess($command);
        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return [
            'success' => true,
            'message' => "Database berhasil dipulihkan dari file '{$filename}'.",
        ];
    }

    protected function formatSize(int $bytes): string
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
