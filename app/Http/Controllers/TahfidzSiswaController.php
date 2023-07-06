<?php

namespace App\Http\Controllers;

use App\Models\TahfidzSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TahfidzSiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }
    public function read(Request $request)
    {

        if ($request->req == 'single') {
            if ($request->has('user_id')) {
                $data = TahfidzSiswa::where('user_id', $request->user_id)->with(['user' => function ($q) {
                    $q->select('id', 'name');
                }])->orderBy('tanggal', 'desc');
            } else {
                return response()->json([
                    'message' => 'User tidak ditemukan'
                ], 404);
            }
        }

        if ($request->req == 'single_by_date') {

            if ($request->has('user_id')) {

                $validator = Validator::make($request->all(), [
                    'startDate' => 'required|date_format:Y-m-d',
                    'endDate' => 'required|date_format:Y-m-d|after_or_equal:startDate',
                    'user_id' => 'required|exists:users,id'
                ], [
                    'startDate.required' => 'Tanggal awal tidak boleh kosong',
                    'startDate.date_format' => 'Format tanggal awal tidak valid',
                    'endDate.required' => 'Tanggal akhir tidak boleh kosong',
                    'endDate.date_format' => 'Format tanggal akhir tidak valid',
                    'endDate.after_or_equal' => 'Tanggal akhir tidak boleh kurang dari tanggal awal',
                    'user_id.required' => 'User ID tidak boleh kosong',
                    'user_id.exists' => 'User ID tidak ditemukan',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'message' => $validator->errors()->first()
                    ], 422);
                }

                $data = TahfidzSiswa::where('user_id', $request->user_id)->whereBetween('tanggal', [$request->startDate, $request->endDate])->with(['user' => function ($q) {
                    $q->select('id', 'name');
                }])->orderBy('tanggal', 'desc');
            } else {
                return response()->json([
                    'message' => 'User tidak ditemukan'
                ], 404);
            }
        }

        $response = [
            'data' => $data->get()
        ];

        return response()->json($response);
    }

    public function write(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'surah' => 'required',
            'ayat' => 'required|regex:/^[0-9]+-[0-9]+$/',
            'tanggal' => 'sometimes|required|date_format:Y-m-d'
        ]);

        if ($request->id) {
            $data = TahfidzSiswa::where('id', $request->id)->firstOrFail();
            $data->update([
                'user_id' => $request->user_id,
                'surah' => $request->surah,
                'ayat_start' => explode('-', $request->ayat)[0],
                'ayat_end' => explode('-', $request->ayat)[1],
            ]);
            return response()->json([
                'message' => 'Data berhasil diubah',
            ]);
        }

        $data = TahfidzSiswa::create([
            'user_id' => $request->user_id,
            'surah' => $request->surah,
            'ayat_start' => explode('-', $request->ayat)[0],
            'ayat_end' => explode('-', $request->ayat)[1],
            'tanggal' => $request->tanggal

        ]);
        return response()->json([
            'message' => 'Data berhasil ditambahkan',
        ]);
    }

    public function delete(TahfidzSiswa $tahfidz)
    {

        $tahfidz->delete();
        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
