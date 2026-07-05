<?php

use App\Livewire\Barang\BarangForm;
use App\Livewire\Barang\BarangList;
use App\Livewire\Barang\BarangShow;
use App\Livewire\Barang\TandaiRusak;
use App\Livewire\Dashboard\Index as Dashboard;
use App\Livewire\Kategori\KategoriForm;
use App\Livewire\Kategori\KategoriList;
use App\Livewire\Kategori\KategoriShow;
use App\Livewire\Laporan\LaporanIndex;
use App\Livewire\Lokasi\LokasiForm;
use App\Livewire\Lokasi\LokasiList;
use App\Livewire\Lokasi\LokasiShow;
use App\Livewire\Pengaturan\PengaturanIndex;
use App\Livewire\Pengguna\PenggunaForm;
use App\Livewire\Pengguna\PenggunaList;
use App\Livewire\Pengguna\PenggunaShow;
use App\Livewire\Perbaikan\PerbaikanForm;
use App\Livewire\Perbaikan\PerbaikanList;
use App\Livewire\Stok\MutasiForm;
use App\Livewire\Stok\StokForm;
use App\Livewire\Stok\StokList;
use App\Livewire\Stok\StokShow;
use App\Livewire\Perbaikan\PerbaikanShow;
use App\Livewire\Template\TemplateForm;
use App\Livewire\Template\TemplateList;
use App\Livewire\Template\TemplateShow;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('lokasi')->name('lokasi.')->group(function () {
        Route::get('/', LokasiList::class)->name('index');
        Route::get('create', LokasiForm::class)->name('create');
        Route::get('{lokasi}', LokasiShow::class)->name('show');
        Route::get('{id}/edit', LokasiForm::class)->name('edit');
    });

    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', KategoriList::class)->name('index');
        Route::get('create', KategoriForm::class)->name('create');
        Route::get('{kategori}', KategoriShow::class)->name('show');
        Route::get('{kategori}/edit', KategoriForm::class)->name('edit');
    });

    Route::prefix('template')->name('template.')->group(function () {
        Route::get('/', TemplateList::class)->name('index');
        Route::get('create', TemplateForm::class)->name('create');
        Route::get('{template}', TemplateShow::class)->name('show');
        Route::get('{id}/edit', TemplateForm::class)->name('edit');
    });

    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', BarangList::class)->name('index');
        Route::get('create', BarangForm::class)->name('create');
        Route::get('{barang}', BarangShow::class)->name('show');
        Route::get('{item}/edit', BarangForm::class)->name('edit');
        Route::get('{id}/tandai-rusak', TandaiRusak::class)->name('tandai-rusak');
        Route::get('/import/template', [ImportController::class, 'downloadTemplate'])->name('import.template');
        Route::post('/import', [ImportController::class, 'import'])->name('import');
    });

    Route::prefix('stok')->name('stok.')->group(function () {
        Route::get('/', StokList::class)->name('index');
        Route::get('create', StokForm::class)->name('create');
        Route::get('masuk', \App\Livewire\Stok\MutasiMasuk::class)->name('masuk');
        Route::get('keluar', \App\Livewire\Stok\MutasiKeluar::class)->name('keluar');
        Route::get('{stok}', StokShow::class)->name('show');
        Route::get('{id}/edit', StokForm::class)->name('edit');
        Route::get('{id}/mutasi', MutasiForm::class)->name('mutasi');
    });

    Route::prefix('perbaikan')->name('perbaikan.')->group(function () {
        Route::get('/', PerbaikanList::class)->name('index');
        Route::get('create', PerbaikanForm::class)->name('create');
        Route::get('{perbaikan}', PerbaikanShow::class)->name('show');
        Route::get('{id}/edit', PerbaikanForm::class)->name('edit');
    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', LaporanIndex::class)->name('index');
    });

    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        Route::get('/', PenggunaList::class)->name('index');
        Route::get('create', PenggunaForm::class)->name('create');
        Route::get('{user}', PenggunaShow::class)->name('show');
        Route::get('{user}/edit', PenggunaForm::class)->name('edit');
    });

    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', PengaturanIndex::class)->name('index');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
