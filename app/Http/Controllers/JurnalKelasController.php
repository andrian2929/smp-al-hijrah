<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalKelas;
use Illuminate\Support\Facades\Validator;

class JurnalKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jurnalKelas = JurnalKelas::with('kelas', 'guru')->get();

        if ($request->has('id_guru')) {
            $jurnalKelas = JurnalKelas::with('kelas', 'guru', 'mapel')->where('id_guru', $request->id_guru)->get();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $jurnalKelas,
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            [
                'id_kelas' => 'required|exists:kelas,id',
                'id_guru' => 'required|exists:users,id',
                'id_mapel' => 'required|exists:mata_pelajaran_haris,id',
                'catatan' => 'required|string',
            ],
            [
                'id_kelas.required' => 'Kelas tidak boleh kosong',
                'id_kelas.exists' => 'Kelas tidak ditemukan',
                'id_guru.required' => 'Guru tidak boleh kosong',
                'id_guru.exists' => 'Guru tidak ditemukan',
                'catatan.required' => 'Catatan tidak boleh kosong',
                'catatan.string' => 'Catatan harus berupa string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Data tidak valid',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $jurnalKelas = JurnalKelas::create([
            'id_kelas' => $request->id_kelas,
            'id_guru' => $request->id_guru,
            'id_mapel' => $request->id_mapel,
            'catatan' => $request->catatan,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jurnal kelas berhasil ditambahkan',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:jurnal_kelas,id',
            ],
            [
                'id.required' => 'ID tidak boleh kosong',
                'id.exists' => 'ID tidak ditemukan',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Data tidak valid',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $jurnalKelas = JurnalKelas::with('kelas', 'guru', 'mapel')->find($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $jurnalKelas,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            [
                'id_kelas' => 'required|exists:kelas,id',
                'id_guru' => 'required|exists:users,id',
                'id_mapel' => 'required|exists:mata_pelajaran_haris,id',
                'catatan' => 'required|string',
            ],
            [
                'id_kelas.required' => 'Kelas tidak boleh kosong',
                'id_kelas.exists' => 'Kelas tidak ditemukan',
                'id_guru.required' => 'Guru tidak boleh kosong',
                'id_guru.exists' => 'Guru tidak ditemukan',
                'catatan.required' => 'Catatan tidak boleh kosong',
                'catatan.string' => 'Catatan harus berupa string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Data tidak valid',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $jurnalKelas = JurnalKelas::find($id);
        $jurnalKelas->update([
            'id_kelas' => $request->id_kelas,
            'id_guru' => $request->id_guru,
            'id_mapel' => $request->id_mapel,
            'catatan' => $request->catatan,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jurnal kelas berhasil diubah',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:jurnal_kelas,id',
            ],
            [
                'id.required' => 'ID tidak boleh kosong',
                'id.exists' => 'ID tidak ditemukan',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Data tidak valid',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $jurnalKelas = JurnalKelas::find($id);
        $jurnalKelas->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jurnal kelas berhasil dihapus',
        ], 201);
    }
}
