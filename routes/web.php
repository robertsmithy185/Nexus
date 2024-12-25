<?php

use App\Http\Controllers\BlogControllers;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MainControllers;
use App\Http\Controllers\PengurusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UkmController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\AnggotaUkmController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MainControllers::class, 'dashboard'])->name('home');
Route::get('/login', [LoginController::class, 'login_view'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/edit_profil', [MainControllers::class, 'edit_profil'])->name('edit');
Route::post('/update_profil', [MainControllers::class, 'update_profil'])->name('update');
Route::get('/profil/{name}', [MainControllers::class, 'profil'])->name('profil_user');
Route::get('/ukm', [MainControllers::class, 'ukm'])->name('ukm');
Route::get('/ukm/view', [MainControllers::class, 'view_ukm']);
Route::get('/ukm/{nama}', [MainControllers::class, 'show'])->name('ukm.show');

Route::middleware(['pengurus'])->group(function () {
    Route::get('/pengurus', [MainControllers::class, 'pengurus'])->name('pengurus');
    Route::get('/pengurus/buka-pendaftaran', [MainControllers::class, 'open'])->name('open');
    Route::post('/pengurus/buka-pendaftaran', [PengurusController::class, 'add_link'])->name('add_link');
    Route::get('/pengurus/add-blog', [MainControllers::class, 'add_blog'])->name('add_blog');
    Route::post('/pengurus/add-blog', [BlogControllers::class, 'store'])->name('post_blog');
    Route::get('/pengurus/add-anggota', [MainControllers::class, 'add_anggota'])->name('add_anggota');
    Route::post('/pengurus/add-anggota', [AnggotaUkmController::class, 'store'])->name('anggota.store');
    Route::get('/pengurus/add-informasi', [MainControllers::class, 'add_informasi'])->name('add_informasi');
    Route::post('/informasi/add-informasi', [InformasiController::class, 'add_informasi'])->name('informasi.store');
    
});
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [MainControllers::class, 'admin'])->name('admin_home');
    Route::get('/admin/add_pengurus', [MainControllers::class, 'add_pengurus']);
    Route::post('/admin/add_ukm', [UkmController::class, 'store'])->name('ukm.store');
    Route::get('/admin/add_ukm', [UkmController::class, 'add_ukm'])->name('ukm.add_ukm');
    Route::get('/admin/add-mahasiswa', [MahasiswaController::class, 'create'])->name('user.create');
    Route::post('/admin/add-mahasiswa', [MahasiswaController::class, 'add_pengguna'])->name('user.store');
    Route::get('/admin/add-pengurus', [PengurusController::class, 'create_pengurus'])->name('pengurus.create');
    Route::post('/admin/add-pengurus', [PengurusController::class, 'add_pengurus'])->name('pengurus.store');
});

Route::middleware(['mahasiswa'])->group(function () {
    Route::get('/informasi', [MainControllers::class, 'view_informasi'])->name('informasi');
    Route::get('/informasi/not-found', [MainControllers::class, 'not_informsi'])->name('not-informasi');
    Route::get('/informasi/{nama}', [MainControllers::class, 'snow_informasi'])->name('snow.informasi');
});


Route::get('/view-blog/{judul}', [MainControllers::class, 'view_blog'])->name('blog');
Route::get('/chatbot', [MainControllers::class, 'chatbot'])->name('chatbot');
Route::get('/keluar', [MainControllers::class, 'logout'])->name('logout');

Route::get('/contoh', function () {
    return view('contoh');
});