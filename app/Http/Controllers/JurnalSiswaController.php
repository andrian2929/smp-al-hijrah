<?php

namespace App\Http\Controllers;

use App\Models\JurnalSiswa;
use App\Models\Siswa;
use App\Models\KehadiranSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JurnalSiswaController extends Controller
{

    /**
     * @var \App\Models\KehadiranSiswa
     */
    protected $kehadiranSiswaModel;

    /**
     * @var \App\Models\JurnalSiswa
     */
    protected $jurnalSiswaModel;

    public function __construct(JurnalSiswa $jurnalSiswa, KehadiranSiswa $kehadiranSiswa)
    {
        $this->jurnalSiswaModel = $jurnalSiswa;
        $this->kehadiranSiswaModel = $kehadiranSiswa;
    }


    public function index()
    {
        $jurnalSiswa = $this->jurnalSiswaModel->with(['kehadiranSiswa.siswa.kelas', 'kehadiranSiswa.siswa.user'])->get();

        return response()->json([
            'message' => 'Berhasil mendapatkan data',
            'data' => $jurnalSiswa
        ]);
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'kehadiran' => 'required|in:izin,hadir,absen,telat',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date_format:Y-m-d|before_or_equal:today',
        ], [
            'user_id.required' => 'User ID tidak boleh kosong',
            'user_id.exists' => 'User ID tidak ditemukan',
            'kehadiran.required' => 'Kehadiran tidak boleh kosong',
            'kehadiran.in' => 'Kehadiran tidak valid',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'tanggal.date_format' => 'Format tanggal tidak valid',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->first()
            ], 422);
        }

        $kehadiran =  $this->kehadiranSiswaModel->updateOrCreate(
            [
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
            ],
            [
                'kehadiran' => $request->kehadiran,
                'keterangan' => $request->keterengan,
            ]
        );

        $jurnalSiswa =   $this->jurnalSiswaModel->updateOrCreate([
            'kehadiran_siswa_id' => $kehadiran->id,
        ], [
            'jam' => $request->jam,
        ]);

        return response()->json([
            'message' => 'Berhasil menyimpan jurnal siswa',
            'data' => $jurnalSiswa->load('kehadiranSiswa.siswa.kelas')
        ]);
    }

    public function show(JurnalSiswa $siswa)
    {
        return response()->json(
            [
                'message' => 'Berhasil mendapatkan data',
                'data'      => $siswa->load('kehadiranSiswa.siswa.kelas')
            ]

        );
    }

    public function update(JurnalSiswa $siswa)
    {
        abort(405);
    }

    public function destroy(JurnalSiswa $siswa)
    {
        $siswa->delete();
        return response()->json([
            'message' => ''
        ]);
    }
}
