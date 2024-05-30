<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\BASidangController;
use App\Http\Controllers\BASeminarController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\RubrikPengujiController;
use App\Http\Controllers\RubrikPembimbingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//belum dalam keadaan login(belum terautentikasi)
Route::middleware(['guest'])->group(function(){
    Route::get('/', [AuthController::class,'index'])->name('login');
    Route::post('/', [AuthController::class,'login']);
});

Route::get('/home',function(){
    return redirect('/admin');
});

Route::middleware(['auth'])->group(function(){
    //login admin
    Route::get('/admin', [AuthController::class,'loginAdmin'])->middleware('userAkses:admin')->name('admin');
    // Rute logout admin
    Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
    // Rute tambah data pengguna
    Route::post('/admin/store', [AdminController::class, 'storeUser'])->name('admin.store');
    Route::get('/admin/fetch', [AdminController::class, 'fetchUsers'])->name('admin.fetch');
    Route::get('/admin/edit/{id}', [AdminController::class, 'EditUser'])->name('admin.edit');
    Route::put('/admin/update/{id}', [AdminController::class, 'updateUser'])->name('admin.update');
    Route::delete('/admin/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete');

    //Rute kelola dosen
    Route::get('/admin/keloladosen', [AdminController::class,'viewKelolaDosen'])->name('admin.keloladosen');
    //Rute store
    Route::post('/admin/storeRoleDosen', [AdminController::class, 'storeRoleDosen'])->name('admin.storeRoleDosen');
    Route::get('/admin/editRoleDosen/{id}', [AdminController::class, 'editRoleDosen'])->name('admin.editRoleDosen');
    Route::put('/admin/updateRoleDosen/{$id}', [AdminController::class, 'updateRoleDosen'])->name('admin.updateRoleDosen');
    // Route::delete('/admin/deleteRoleDosen/{$id}', [AdminController::class, 'deleteRoleDosen'])->name('admin.deleteRoleDosen');

    //Route unduh rubrik penilaian MHS dari dosen
    
});


Route::middleware(['auth'])->group(function(){
    //login dosen
    Route::get('/dosen', [AuthController::class,'loginDosen'])->middleware('userAkses:dosen')->name('dosen');
    //logout dosen
    Route::get('/dosen/logout', [AuthController::class,'logout'])->name('dosen.logout');
    //rute PROFIL DOSEN
    Route::get('/dosen/profile', [DosenController::class,'viewProfile'])->name('dosen.profile');
    //rute SEMINAR KEMAJUAN mhs di akun dosen
    Route::get('/dosen/seminar', [BASeminarController::class,'index'])->name('dosen.baSeminar');
    //rute SIDANG TA mhs di akun dosen
    Route::get('/dosen/sidang', [BASidangController::class,'index'])->name('dosen.baSidang');
    //rute RUBRIK PEMBIMBING di akun dosen
    Route::get('/dosen/rubrikBimbingan', [RubrikPembimbingController::class,'index'])->name('dosen.rubrikBimbingan');
    //rute RUBRIK PENGUJI di akun dosen
    Route::get('/dosen/rubrikPenguji', [RubrikPengujiController::class,'index'])->name('dosen.rubrikPenguji');
});

Route::middleware(['auth'])->group(function(){
    //login mahasiswa
    Route::get('/mahasiswa', [MahasiswaController::class,'index'])->middleware('userAkses:mahasiswa')->name('mahasiswa'); //rute setelah login langsung ke halaman log bimbingan di akun mahasiswa
    //store catatan bimbingan
    Route::post('/mahasiswa/storeLogBimbingan', [MahasiswaController::class,'store'])->name('mahasiswa.storeLogBimbingan');
    //logout
    Route::get('/mahasiswa/logout', [AuthController::class,'logout'])->name('mahasiswa.logout');
    //rute BA_Seminar MAHASISWA
    Route::get('/mahasiswa/BAseminarMHS', [BASeminarController::class,'indexMhs'])->name('mahasiswa.baSeminarMHS');
    //rute BA_SIDANG MAHASISWA
    Route::get('/mahasiswa/BAsidangMHS', [BASidangController::class,'indexMhs'])->name('mahasiswa.baSidangMHS');
    
});
