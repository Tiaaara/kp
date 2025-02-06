<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NewsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureAdmin;
use Inertia\Inertia;
use App\Http\Controllers\SejarahController;
use App\Http\Controllers\VisiMisiController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PejabatController;
use App\Http\Controllers\OPDController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LambangController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PopupController;


Route::get('/popup', [PopupController::class, 'index'])->name('popup.index');
Route::post('/popup', [PopupController::class, 'store'])->name('popup.store');
Route::put('/popup/{id}', [PopupController::class, 'update'])->name('popup.update');
Route::delete('/popup/{id}', [PopupController::class, 'destroy'])->name('popup.destroy');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/edit/{id}', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::get('/news/{id}', [NewsController::class, 'show'])->where('id', '[0-9]+')->name('news.show');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/upload-image', [NewsController::class, 'uploadImage']);
    Route::get('/news/{kategori}', [NewsController::class, 'showByCategory']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');



Route::get('/', [BerandaController::class, 'index'])->name('beranda');


Route::get('/laporan_keuangan', [DocumentController::class, 'index'])->name('Laporan_Keuangan.index');
Route::get('/unduh/{id}', [DocumentController::class, 'download'])->name('Laporan_Keuangan.download'); // Download dokumen

Route::get('/upload-document', [DocumentController::class, 'uploadPage'])->name('documents.upload');
Route::post('/upload-document', [DocumentController::class, 'store'])->name('documents.store');
Route::put('/upload-document/{id}', [DocumentController::class, 'update'])->name('documents.update');
Route::delete('/upload-document/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');

// Halaman daftar pejabat daerah
Route::get('/PejabatDaerah', [PejabatController::class, 'index'])->name('PejabatDaerah.index');

// Halaman daftar pejabat (local-official)
Route::get('/local_official', [PejabatController::class, 'localOfficial'])->name('pejabat.localOfficial');
Route::get('/local_official/create', [PejabatController::class, 'create'])->name('pejabat.create');
Route::post('/local_official', [PejabatController::class, 'store'])->name('pejabat.store');
Route::get('/local_official/edit/{id}', [PejabatController::class, 'edit'])->name('pejabat.edit');
Route::put('/local_official/edit/{id}', [PejabatController::class, 'update'])->name('pejabat.update');
Route::delete('/local_official/{id}', [PejabatController::class, 'destroy'])->name('pejabat.destroy');

// Halaman daftar OPD
Route::get('/OPD', [OPDController::class, 'index'])->name('OPD.index');

// Halaman daftar OPD (lgo)
Route::get('/lgo', [OPDController::class, 'lgo'])->name('opd.lgo');
Route::get('/lgo/create', [OPDController::class, 'create'])->name('opd.create');
Route::post('/lgo', [OPDController::class, 'store'])->name('opd.store');
Route::get('/lgo/edit/{id}', [OPDController::class, 'edit'])->name('opd.edit');
Route::put('/lgo/{id}', [OPDController::class, 'update'])->name('opd.update');
Route::delete('/lgo/{id}', [OPDController::class, 'destroy'])->name('opd.destroy');

// Halaman daftar kecamatan
Route::get('/Kecamatan', [KecamatanController::class, 'index'])->name('Kecamatan.index');

// Halaman daftar Kecamatan (subdistrict)
Route::get('/subdistrict', [KecamatanController::class, 'subdistrict'])->name('kecamatan.subdistrict');
Route::get('/subdistrict/create', [KecamatanController::class, 'create'])->name('kecamatan.create');
Route::post('/subdistrict', [KecamatanController::class, 'store'])->name('kecamatan.store');
Route::get('/subdistrict/edit/{id}', [KecamatanController::class, 'edit'])->name('kecamatan.edit');
Route::put('/subdistrict/{id}', [KecamatanController::class, 'update'])->name('kecamatan.update');
Route::delete('/subdistrict/{id}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');

Route::get('/LambangDaerah', [PageController::class, 'LambangDaerah'])->name('LambangDaerah');

Route::get('/VisiMisi', [VisiMisiController::class, 'index'])->name('VisiMisi');

Route::get('/Sejarah', [SejarahController::class, 'index'])->name('Sejarah');

Route::get('/BeritaPemerintah', [PageController::class, 'BeritaPemerintah'])->name('BeritaPemerintah');
Route::get('/BeritaDaerah', [PageController::class, 'BeritaDaerah'])->name('BeritaDaerah');



Route::get('/DataStatistik', [DocumentController::class, 'DataStatistik'])->name('DataStatistik');
Route::get('/PeraturanBupati', [DocumentController::class, 'PeraturanBupati'])->name('PeraturanBupati');

Route::get('/LapKeu2020', [DocumentController::class, 'LaporanKeuangan2020'])->name('LapKeu2020');
Route::get('/LapKeu2021', [DocumentController::class, 'LaporanKeuangan2021'])->name('LapKeu2021');
Route::get('/LapKeu2022', [DocumentController::class, 'LaporanKeuangan2022'])->name('LapKeu2022');
Route::get('/LapKeu2023', [DocumentController::class, 'LaporanKeuangan2023'])->name('LapKeu2023');
Route::get('/LapKeu2024', [DocumentController::class, 'LaporanKeuangan2024'])->name('LapKeu2024');
Route::get('/LapKeu2025', [DocumentController::class, 'LaporanKeuangan2025'])->name('LapKeu2025');

Route::get('/PPID', [PageController::class, 'PPID'])->name('PPID');
Route::get('/Kontak', [PageController::class, 'Kontak'])->name('Kontak');

require __DIR__.'/auth.php';