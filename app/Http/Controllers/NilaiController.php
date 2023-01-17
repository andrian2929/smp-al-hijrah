<?php

namespace App\Http\Controllers;

use App\Models\NilaiSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function read(Request $request) {
        if($request->req == 'table') {
            $data = Siswa::where('kelas_id', $request->kelas_id)
                        // ->whereHas('nilai_mapel', function($q) use($request) {
                        //     $q->where('semester', $request->semester);
                        //     if($request->mapel_id) {
                        //         $q = $q->where('mapel_id', $request->mapel_id);
                        //     }
                        // })
                        ->whereHas('user', function($q) use($request) {
                            $search = strtolower($request->search);
                            $q->where('name', 'LIKE', "%$search%")
                                ->orWhere('no_induk', 'LIKE', "%$search%");
                        })
                        ->with([
                            'user' => function($q) {
                                $q->select('id', 'name', 'no_induk');
                            },
                            'kelas' => function($q) {
                                $q->select('id', 'jenjang', 'section');
                            },
                            'nilai' => function($q) {
                                $q->select('id', 'siswa_id', 'semester', 'nilai');
                            }
                        ])->get();
        }
        return response()->json([
            'models' => $data
        ]);
    }
}
