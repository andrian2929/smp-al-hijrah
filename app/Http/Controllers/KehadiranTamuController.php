<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KehadiranTamu;
use Illuminate\Support\Facades\Validator;

class KehadiranTamuController extends Controller
{

    /**
     * @var \App\Models\KehadiranTamu;
     */
    protected $kehadiranTamuModel;

    public function __construct(KehadiranTamu $kehadiranTamuModel)
    {
        $this->kehadiranTamuModel = $kehadiranTamuModel;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->kehadiranTamuModel->all();
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
            $request->all(),
            [
                'nama' => 'required|string',
                'tanggal' => 'required|date_format:Y-m-d',
                'jam' => 'required|date_format:H:i',
                'keterangan' => 'sometimes|string'
            ],
            [
                'nama.required' => 'Nama harus diisi',
                'tanggal.required' => 'Tanggal harus diisi',
                'tanggal.date_format' => 'Format tanggal salah',
                'jam.required' => 'Jam harus diisi',
                'jam.date_format' => 'Format jam salah'

            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $kehadiranTamu =  $this->kehadiranTamuModel->create([
            'nama' => $request->nama,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'keterangan' => $request->keterangan
        ]);

        return response()->json([
            'message' => 'Kehadiran tamu berhasil ditambahkan',
            'data' => $kehadiranTamu
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(KehadiranTamu $kehadiran)
    {
        return response()->json([
            'message' => 'Data kehadiran tamu berhasil didapatkan',
            'data' => $kehadiran
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\KehadiranTamu
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KehadiranTamu $kehadiran)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'sometimes|required|string',
                'tanggal' => 'sometimes|required|date_format:Y-m-d',
                'jam' => 'sometimes|required|date_format:H:i',
                'keterangan' => 'sometimes|string'
            ],
            [
                'name.required' => 'Nama harus diisi',
                'tanggal.required' => 'Tanggal harus diisi',
                'tanggal.date_format' => 'Format tanggal salah',
                'jam.required' => 'Jam harus diisi',
                'jam.date_format' => 'Format jam salah'

            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $kehadiran->update([
            'nama' => $request->nama,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'keterangan' => $request->keterangan
        ]);

        return response()->json([
            'message' => 'Kehadiran tamu berhasil diubah',
            'data' => $kehadiran
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KehadiranTamu
     * @return \Illuminate\Http\Response
     */
    public function destroy(KehadiranTamu $kehadiran)
    {
        $kehadiran->delete();

        return response()->json([
            'message' => 'Kehadiran tamu berhasil dihapus'
        ]);
    }
}
