<?php

namespace App\Http\Controllers;




use App\Models\KehadiranSiswa;

use App\Models\Siswa;

use Illuminate\Http\Request;




class KehadiranController extends Controller
{

    public function __construct()

    {

        $this->middleware(['auth:sanctum']);
    }

    // public function read(Request $request) { 
    // if($request->req == 'daily') { 

    //     $data = Siswa::select('id', 'kelas_id', 'siswa_id') 

    //                 ->where('kelas_id', $request->kelas_id) 

    //                 ->with([ 

    //                     'kehadiran' => function($q) use($request) { 

    //                         $q->where('tanggal', substr($request->tanggal, 0, 10)); 

    //                     }, 
    //                     'user' => function($q) { 

    //                         $q->select('id', 'no_induk', 'name'); 
    //                     } 
    //                 ])->get(); 
    // } 

    //     if($request->req == 'monthly') { 

    //         $data = Siswa::select('id', 'siswa_id') 

    //                     ->where('kelas_id', $request->kelas_id) 

    //                     ->with([ 

    //                         'kehadiran' => function($q) use($request) { 

    //                             $q->whereMonth('tanggal', $request->bulan) 

    //                                 ->whereYear('tanggal', $request->tahun); 

    //                         }, 

    //                         'user' => function($q) { 

    //                             $q->select('id', 'no_induk', 'name'); 

    //                         } 

    //                     ])->get(); 

    //     } 

    //     return response()->json([ 

    //         'models' => $data 

    //     ]); 

    // } 





    public function read(Request $request)
    {

        if ($request->req == 'all') {
            $data = Siswa::select('id', 'kelas_id', 'user_id')->with('kehadiran')->get();
        }

        if ($request->req == "by_kelas") {
            $data = Siswa::select('id', 'kelas_id', 'user_id', 'nisn')->where('kelas_id', $request->kelas_id)->with(['kehadiran' => function ($q) use ($request) {
                $q->where('tanggal', substr($request->tanggal, 0, 10));
            }, 'user' => function ($q) {
                $q->select('id', 'no_induk', 'name');
            }])->get();
        }

        return response()->json([
            'models' => $data
        ]);
    }

    public function write(Request $request)
    {


        $request->validate([
            'model' => 'required|array',
            'model.*.user_id' => 'required|exists:users,id',
            'model.*.kehadiran' => 'required|in:izin,hadir,absen,telat,]',
            'model.*.keterangan' => 'nullable|string',
            'model.*.tanggal' => 'required|date_format:Y-m-d',
        ]);

        foreach ($request->model as $_model) {
            $model = KehadiranSiswa::updateOrCreate(
                [
                    'user_id' => $_model['user_id'],
                    'tanggal' => $_model['tanggal'],
                ],
                [
                    'kehadiran' => $_model['kehadiran'],
                    'keterangan' => $_model['keterangan'],
                ]
            );
        }
    }



    // public function write(Request $request)
    // {
    //     if ($request->req == 'write') {
    //         foreach ($request->kehadiran as $kehadiran => $result) {
    //             $siswa_id = substr($kehadiran, 6, -1);
    //             $obj = KehadiranSiswa::where('siswa_id', $siswa_id)
    //                 ->where('tanggal', substr($request->tanggal, 0, 10))
    //                 ->first();

    //             if (!$obj) {
    //                 $obj = new KehadiranSiswa();
    //             }

    //             $obj->siswa_id = $siswa_id;
    //             $obj->tanggal = substr($request->tanggal, 0, 10);
    //             $obj->kehadiran = $result['status'];
    //             $obj->keterangan = $result['description'];
    //             if ($result['status']) {
    //                 $obj->save();
    //             }
    //         }
    //     }
    // }
}
