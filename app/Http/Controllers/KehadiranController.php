<?php

namespace App\Http\Controllers;

use App\Models\KehadiranSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Guru;
use Illuminate\Support\Facades\Validator;

class KehadiranController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

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
            }]);

            if ($request->has('user_id'))
                $data = $data->where('user_id', $request->user_id);

            $data = $data->get();
        }

        if ($request->req === 'by_guru') {
            $data = Guru::select('id', 'user_id', 'nip')->with(['kehadiran' => function ($q) use ($request) {
                $q->where('tanggal', substr($request->tanggal, 0, 10));
            }, 'user' => function ($q) {
                $q->select('id', 'no_induk', 'name');
            }]);

            if ($request->guru_id == 'all') {
                $data = $data->get();
            } else {
                $data = $data->where('user_id', $request->guru_id)->get();
            }
        }

        return response()->json([
            'models' => $data
        ]);
    }

    public function write(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'model' => 'required|array',
            'model.*.user_id' => 'required|exists:users,id',
            'model.*.kehadiran' => 'required|in:izin,hadir,absen,telat',
            'model.*.keterangan' => 'nullable|string',
            'model.*.tanggal' => 'required|date_format:Y-m-d|before_or_equal:today',
        ], [
            'model.*.user_id.required' => 'User ID tidak boleh kosong',
            'model.*.user_id.exists' => 'User ID tidak ditemukan',
            'model.*.kehadiran.required' => 'Kehadiran tidak boleh kosong',
            'model.*.kehadiran.in' => 'Kehadiran tidak valid',
            'model.*.tanggal.required' => 'Tanggal tidak boleh kosong',
            'model.*.tanggal.date_format' => 'Format tanggal tidak valid',
            'model.*.tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

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
}
