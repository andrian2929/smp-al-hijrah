<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // public function __construct() {
    //     $this->middleware(['auth:sanctum']);
    // }
    public function read(Request $request)
    {


        // Cek apakah request dari tabel
        if ($request->req == 'table') {

            // Cek apakah ada request search
            if ($request->search) {
                // Ambil data siswa berdasarkan search
                $data = Siswa::select()->with('user', 'kelas')
                    ->whereHas('user', function ($q) use ($request) {
                        $search = strtolower($request->search);
                        $q->where('name', 'LIKE', "%$search%")
                            ->orWhere('no_induk', 'LIKE', "%$search%")
                            ->orWhere('nisn', 'LIKE', "%$search%");
                    });
            } else {
                // Ambil data seluruh siswa
                $data = Siswa::with('user', 'kelas');
            }




            // return response()->json([
            //     'models' => $data
            // ]);

            // Cek apakah ada request kelas_id
            if ($request->kelas_id) {
                $data = $data->where('kelas_id', $request->kelas_id);
            }
            $data = $data->get();
        } else if ($request->req == 'single') {
            $data = Siswa::where('id', $request->id)->with('user', 'kelas')->firstOrFail();
        } else if ($request->req == 'open_siswa') {
            $data = Siswa::where('kelas_id', null)->with('user', function ($q) {
                return $q->select('id', 'no_induk', 'name');
            })->select('id', 'user_id')->get();
        } else if ($request->req == 'count') {
            $data = User::with(['roles' => function ($q) {
                $q->where('display_name', 'siswa');
            }])->count();
        } // get siswa by user id at user table

        else if ($request->req == 'get_siswa_by_user_id') {
            $data = Siswa::where('user_id', $request->user_id)->with('user', 'kelas')->firstOrFail();
        }
        return response()->json([
            'models' => $data
        ]);
    }

    public function write(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $siswa = Siswa::find($request->user_id);
        $siswa->kelas_id = $request->kelas_id;
        $siswa->save();
        return response()->json($siswa);
    }
}
