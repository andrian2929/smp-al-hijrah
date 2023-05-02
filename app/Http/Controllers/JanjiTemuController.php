<?php

namespace App\Http\Controllers;

use App\Models\JanjiTemu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JanjiTemuController extends Controller
{
    /**
     * @var \App\Models\JanjiTemu;
     */
    protected $janjiTemuModel;

    public function __construct(JanjiTemu $janjiTemuModel)
    {
        $this->janjiTemuModel = $janjiTemuModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $janjiTemu = $this->janjiTemuModel->all();
        return response()->json([
            'message' => 'Data berhasil diambil',
            'data' => $janjiTemu
        ]);
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
                'nama_tamu' => 'required|string',
                'tujuan' => 'required|string',
                'tanggal' => 'required|date_format:Y-m-d',
                'jam' => 'required|date_format:H:i',
                'keterangan' => 'sometimes|string'
            ],
            [
                'nama_tamu.required' => 'Nama tamu harus diisi',
                'tujuan.required' => 'Tujuan harus diisi',
                'tanggal.required' => 'Tanggal harus diisi',
                'tanggal.date_format' => 'Format tanggal salah',
                'jam.required' => 'Jam harus diisi',
                'jam.date_format' => 'Format jam salah'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 400);
        }

        $janjiTemu = $this->janjiTemuModel->create($request->all());
        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $janjiTemu
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(JanjiTemu $janjiTemu)
    {
        return response()->json([
            'message' => 'Data berhasil diambil',
            'data' => $janjiTemu
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JanjiTemu $janjiTemu)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_tamu' => 'required|string',
                'tujuan' => 'required|string',
                'tanggal' => 'required|date_format:Y-m-d',
                'jam' => 'required|date_format:H:i',
                'keterangan' => 'sometimes|string'
            ],
            [
                'nama_tamu.required' => 'Nama tamu harus diisi',
                'tujuan.required' => 'Tujuan harus diisi',
                'tanggal.required' => 'Tanggal harus diisi',
                'tanggal.date_format' => 'Format tanggal salah',
                'jam.required' => 'Jam harus diisi',
                'jam.date_format' => 'Format jam salah'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $janjiTemu->update($request->all());
        return response()->json([
            'message' => 'Data berhasil diubah',
            'data' => $janjiTemu
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(JanjiTemu $janjiTemu)
    {
        $janjiTemu->delete();
        return response()->json([
            'message' => 'Data berhasil dihapus',
            'data' => $janjiTemu
        ]);
    }
}
