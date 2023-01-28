<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\MataPelajaranHari;
use App\Models\Siswa;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // public function __construct() {
    //     $this->middleware(['auth:sanctum']);
    // }
    public function read(Request $request)
    {


        if ($request->req == 'all') {
            $data = Kelas::select('id', 'jenjang', 'section')->get();
        }
        if ($request->req == 'table') {
            if ($request->has('class')) {
                $data = Kelas::select()->with('wali_kelas')->withCount('siswa')->where('jenjang', $request->class)->get();
            } else {
                $data = Kelas::select()->with('wali_kelas')->withCount('siswa')->get();
            }
        }
        if ($request->req == 'single') {

            $data = Kelas::select()->with('wali_kelas')->where('id', $request->id)->firstOrFail();
        }
        if ($request->req == 'get_all_guru') {
            $data = User::select('id', 'name')->whereHas('roles', function ($q) {
                $q->where('display_name', 'guru');
            })->get();
        }
        if ($request->req == 'get_siswa') {

            $data = Siswa::select('id', 'kelas_id', 'user_id', 'nisn')
                ->where('kelas_id', $request->id)
                ->with('user', function ($q) {
                    $q->select('id', 'name', 'no_induk');
                })
                ->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%$request->search%")
                        ->orWhere('no_induk', 'LIKE', "%$request->search%");
                })
                ->get();
        }
        if ($request->req == 'get_siswa_options') {

            $data = Siswa::select('id', 'siswa_id', 'kelas_id', 'nisn')
                ->whereNull('kelas_id')
                ->with('user', function ($q) {
                    $q->select('id', 'name');
                })
                ->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%$request->search%");
                })->limit(30)->get();
        }
        if ($request->req == 'get_roster') {
            $data = MataPelajaranHari::where('kelas_id', $request->kelas_id)
                ->with([
                    'mapel',
                    'guru' => function ($q) {
                        $q->select('id', 'name');
                    }
                ])
                ->get();
        }
        return response()->json([
            'models' => $data
        ]);
    }

    public function write(Request $request)
    {
        if ($request->req == 'edit') {
            $request->validate([
                'wali_kelas_id' => 'required|exists:users,id'
            ]);
            $obj = Kelas::findOrFail($request->id);
            $obj->wali_kelas_id = $request->wali_kelas_id;
            $obj->save();
        }
        if ($request->req == 'add') {
            $request->validate([
                'jenjang' => 'required|in:VII,VIII,IX',
                'wali_kelas_id' => 'required|exists:users,id'
            ]);
            $wali_kelas = User::with('roles')->where('id', $request->wali_kelas_id)->firstOrFail();
            if ($wali_kelas->roles[0]->display_name != 'guru') {
                throw new Exception('wali kelas bersangkutan tidak dapat dipilih');
            }
            $last_kelas = Kelas::select('id', 'jenjang', 'section')->where('jenjang', $request->jenjang)->orderBy('section', 'desc')->first();
            $data = new Kelas();
            $data->jenjang = $request->jenjang;
            $data->section = $last_kelas ? ++$last_kelas->section : 'A';
            $data->wali_kelas_id = $request->wali_kelas_id;
            $data->save();
        }
        if ($request->req == 'assign_siswa') {
            $obj = Siswa::findOrFail($request->siswa_id);
            $obj->kelas_id = $request->kelas_id;
            $obj->save();
        }
        if ($request->req == 'remove_siswa') {
            $obj = Siswa::findOrFail($request->id);
            $obj->kelas_id = null;
            $obj->save();
        }
        if ($request->req == 'remove_mapel_hari') {
            $obj = MataPelajaranHari::findOrFail($request->id);
            $obj->delete();
        }
    }
}
