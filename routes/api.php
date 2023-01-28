<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TahfidzSiswaController;
use App\Http\Controllers\MutabaahYaumiyahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'index']);
Route::get('/logout', [LoginController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    $user->role = $request->user()->roles;
    return $user;
});
Route::middleware('auth:sanctum')->get('/authenticated', function (Request $request) {
    return $request->user()->roles;
});

Route::get('/kelas/read', [KelasController::class, 'read'])->name('kelas.read');
Route::post('/kelas/write', [KelasController::class, 'write'])->name('kelas.write');

Route::get('/siswa/read', [SiswaController::class, 'read'])->name('siswa.read');
Route::post('/siswa/write', [SiswaController::class, 'write'])->name('siswa.write');

Route::get('/mapel/read', [MapelController::class, 'read'])->name('mapel.read');
Route::post('/mapel/write', [MapelController::class, 'write'])->name('mapel.write');

Route::get('/user/read', [UserController::class, 'read'])->name('user.read');
Route::post('/user/write', [UserController::class, 'write'])->name('user.write');

Route::get('/guru/read', [GuruController::class, 'read'])->name('guru.read');

Route::get('/orangtua/read', [OrangtuaController::class, 'read'])->name('orangtua.read');
Route::post('/orangtua/write', [OrangtuaController::class, 'write'])->name('orangtua.write');

Route::get('/kehadiran/read', [KehadiranController::class, 'read'])->name('laporan.kehadiran.read');
Route::post('/kehadiran/write', [KehadiranController::class, 'write'])->name('laporan.kehadiran.write');

Route::get('/nilai/read', [NilaiController::class, 'read'])->name('laporan.nilai.read');
Route::post('/nilai/write', [NilaiController::class, 'write'])->name('laporan.nilai.write');

Route::get('/laporan/tahfidz/read', [TahfidzSiswaController::class, 'read'])->name('laporan.tahfidz.read');
Route::post('/laporan/tahfidz/write', [TahfidzSiswaController::class, 'write'])->name('laporan.tahfidz.write');

Route::get('/laporan/mutabaah-yaumiyah/read', [MutabaahYaumiyahController::class, 'read'])->name('laporan.mutabaahyaumiyah.read');
Route::post('/laporan/mutabaah-yaumiyah/write', [MutabaahYaumiyahController::class, 'write'])->name('laporan.mutabaahyaumiyah.write');
