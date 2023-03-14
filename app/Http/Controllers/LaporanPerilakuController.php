<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListPerilaku;
use App\Models\Perilaku;
use Illuminate\Validation\Rule;

class LaporanPerilakuController extends Controller
{
    public function data(Request $request)
    {

        if ($request->req == 'get_perilaku_by_user_id_and_date') {
            $request->validate([
                'user_id' => 'required|numeric|exists:users,id',
                'tanggal' => 'required|date_format:Y-m-d|before_or_equal:today'
            ]);


            if ($perilaku = Perilaku::with('list_perilaku')->where('user_id', $request->user_id)->where('tanggal', $request->tanggal)->get()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $perilaku
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        if ($perilaku = ListPerilaku::all()) {
            return response()->json([
                'status' => 'success',
                'data' => $perilaku
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak ditemukan'
        ]);
    }

    public function write(Request $request)
    {

        if (count($request->all()) == ListPerilaku::count()) {

            $request->validate([
                '*.user_id' => 'required|numeric|exists:users,id',
                '*' => 'required|array',
                '*.tanggal' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
                '*.perilaku_id' => 'required|numeric|exists:list_perilakus,id|distinct',
                '*.nilai' => 'required|numeric|min:0|max:100',
                '*.catatan' => 'nullable|string',

            ], [
                '*.user_id.required' => 'User ID tidak boleh kosong',
                '*.user_id.numeric' => 'User ID harus berupa angka',
                '*.user_id.exists' => 'User ID tidak ditemukan',
                '*.tanggal.required' => 'Tanggal tidak boleh kosong',
                '*.tanggal.date_format' => 'Format tanggal tidak valid',
                '*.tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
                '*.perilaku_id.required' => 'Perilaku ID tidak boleh kosong',
                '*.perilaku_id.numeric' => 'Perilaku ID harus berupa angka',
                '*.perilaku_id.exists' => 'Perilaku ID tidak ditemukan',
                '*.perilaku_id.distinct' => 'Perilaku ID tidak boleh sama',
                '*.nilai.required' => 'Nilai tidak boleh kosong',
                '*.nilai.numeric' => 'Nilai harus berupa angka',
                '*.nilai.min' => 'Nilai minimal 0',
                '*.nilai.max' => 'Nilai maksimal 100',
                '*.catatan.string' => 'Catatan harus berupa teks',
            ]);
        }
        // create or update
        foreach ($request->all() as $key => $value) {
            $perilaku = Perilaku::updateOrCreate(
                [
                    'user_id' => $value['user_id'],
                    'tanggal' => $value['tanggal'],
                    'perilaku_id' => $value['perilaku_id']
                ],
                [
                    'nilai' => $value['nilai'],
                    'catatan' => $value['catatan']
                ]
            );
        }

        return response()->json([
            'message' => 'Berhasil menyimpan data',
        ]);
    }
}
