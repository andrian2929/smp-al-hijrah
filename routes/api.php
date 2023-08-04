<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\InputMasalController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TahfidzSiswaController;
use App\Http\Controllers\MutabaahYaumiyahController;
use App\Http\Controllers\LaporanPerilakuController;
use App\Http\Controllers\JurnalKelasController;
use App\Models\LaporanPerilaku;
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

Route::get('/siswa/input-masal/template', [InputMasalController::class, 'downloadTemplate'])->name('siswa.input-masal.template');
Route::post('/siswa/input-masal', [InputMasalController::class, 'inputMasal'])->name('siswa.input-masal');
Route::get('/siswa/read', [SiswaController::class, 'read'])->name('siswa.read');
Route::post('/siswa/write', [SiswaController::class, 'write'])->name('siswa.write');

Route::get('/mapel/read', [MapelController::class, 'read'])->name('mapel.read');
Route::post('/mapel/write', [MapelController::class, 'write'])->name('mapel.write');

Route::get('/user/read', [UserController::class, 'read'])->name('user.read');
Route::post('/user/write', [UserController::class, 'write'])->name('user.write');

Route::get('/guru/read', [GuruController::class, 'read'])->name('guru.read');

Route::delete('orangtua/{id}', [OrangtuaController::class, 'delete'])->name('orangtua.delete');
Route::get('/orangtua/read', [OrangtuaController::class, 'read'])->name('orangtua.read');
Route::post('/orangtua/write', [OrangtuaController::class, 'write'])->name('orangtua.write');

Route::get('/kehadiran/read', [KehadiranController::class, 'read'])->name('laporan.kehadiran.read');
Route::post('/kehadiran/write', [KehadiranController::class, 'write'])->name('laporan.kehadiran.write');

Route::get('/nilai/read', [NilaiController::class, 'read'])->name('laporan.nilai.read');
Route::post('/nilai/write', [NilaiController::class, 'write'])->name('laporan.nilai.write');

Route::get('/laporan/tahfidz/rekap', [TahfidzSiswaController::class, 'rekap'])->name('laporan.tahfidz.rekap');
Route::get('/laporan/tahfidz/read', [TahfidzSiswaController::class, 'read'])->name('laporan.tahfidz.read');
Route::post('/laporan/tahfidz/write', [TahfidzSiswaController::class, 'write'])->name('laporan.tahfidz.write');
Route::delete('/laporan/tahfidz/delete/{tahfidz}', [TahfidzSiswaController::class, 'delete'])->name('laporan.tahfidz.delete');

Route::get('/laporan/mutabaah-yaumiyah/rekap/guru/{user}', [MutabaahYaumiyahController::class, 'getMutabaahYaumiyahReport'])->name('laporan.mutabaahyaumiyah.rekap.guru');
Route::get('/laporan/mutabaah-yaumiyah/rekap', [MutabaahYaumiyahController::class, 'rekap'])->name('laporan.mutabaahyaumiyah.rekap');
Route::get('/laporan/mutabaah-yaumiyah/read', [MutabaahYaumiyahController::class, 'read'])->name('laporan.mutabaahyaumiyah.read');
Route::post('/laporan/mutabaah-yaumiyah/write', [MutabaahYaumiyahController::class, 'write'])->name('laporan.mutabaahyaumiyah.write');

Route::get('/laporan/tugas-siswa/mata-pelajaran-hari/{id}', [\App\Http\Controllers\TugasController::class, 'getTugasByMataPelajaranHari'])->name('laporan.tugas-siswa.mata-pelajaran-hari');

Route::get('/laporan/perilaku/rekap', [LaporanPerilakuController::class, 'rekap'])->name('laporan.perilaku.rekap');
Route::get('/laporan/perilaku/data', [LaporanPerilakuController::class, 'data'])->name('laporan.perilaku.data');
Route::get('/laporan/perilaku/read', [LaporanPerilakuController::class, 'read'])->name('laporan.perilaku.read');
Route::post('/laporan/perilaku/write', [LaporanPerilakuController::class, 'write'])->name('laporan.perilaku.write');

Route::get('/rekap/guru', [\App\Http\Controllers\RekapDataController::class, 'rekapDataGuru']);
Route::get('/rekap/siswa', [\App\Http\Controllers\RekapDataController::class, 'rekapDataSiswa']);
Route::get('/rekap/kehadiran', \App\Http\Controllers\RekapKehadiranController::class);
Route::apiResource('jurnal-kelas', JurnalKelasController::class);

Route::get('siswa/tugas/cetak', [\App\Http\Controllers\TugasController::class, 'cetak']);
Route::apiResource('siswa/tugas', \App\Http\Controllers\TugasController::class);
Route::post('siswa/tugas/mark', [\App\Http\Controllers\TugasController::class, 'mark']);
Route::get('siswa/tugas/{tugas}/siswa', [\App\Http\Controllers\TugasController::class, 'siswa']);

Route::apiResource('piket/jurnal/guru/ketertiban', \App\Http\Controllers\KetertibanGuruController::class);

Route::apiResource('piket/jurnal/guru/pengganti', \App\Http\Controllers\GuruPenggantiController::class);

Route::apiResource('piket/jurnal/siswa', \App\Http\Controllers\JurnalSiswaController::class);

Route::apiResource('piket/tamu/kehadiran', \App\Http\Controllers\KehadiranTamuController::class);

Route::apiResource('piket/tamu/janji-temu', \App\Http\Controllers\JanjiTemuController::class);
