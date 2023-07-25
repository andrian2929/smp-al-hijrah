<?php

namespace App\Http\Controllers;

use App\Models\TahfidzSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Kelas;
use \Mpdf\Mpdf;

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

    public function rekap(Request $request)
    {
        $tahfidzData = [];

        if ($request->req === 'by_class') {

            $numberofDays = Carbon::create($request->year, $request->month)->daysInMonth;
            $kelas = Kelas::find($request->kelas);
            for ($i = 1; $i <= $numberofDays; $i++) {

                $tahfidzData[$i - 1]['tanggal'] = Carbon::create($request->year, $request->month, $i)->format('Y-m-d');
                $tahfidzData[$i - 1]['data'] = Siswa::with(['user' => function ($q) {
                    $q->select('id', 'name');
                }])->with(['laporan_tahfidz' => function ($q) use ($request, $i) {
                    $q->where('tanggal', Carbon::create($request->year, $request->month, $i)->format('Y-m-d'));
                }])->with('kelas')->when($request->kelas, function ($q) use ($request) {
                    $q->whereHas('kelas', function ($q) use ($request) {
                        $q->where('id', $request->kelas);
                    });
                })->when($request->user_id, function ($q) use ($request) {
                    $q->where('user_id', $request->user_id);
                })->get();
            }

            $date = Carbon::create($request->year, $request->month)->translatedFormat('F Y');

            $this->downloadTahfidzByClass($tahfidzData, $date, $kelas);
        }

        if ($request->req == "by_user") {

            $numberofDays = Carbon::create($request->year, $request->month)->daysInMonth;
            $siswa = Siswa::find($request->user_id);
            for ($i = 1; $i <= $numberofDays; $i++) {

                $tahfidzData[$i - 1]['tanggal'] = Carbon::create($request->year, $request->month, $i)->format('Y-m-d');
                $tahfidzData[$i - 1]['data'] = Siswa::with(['user' => function ($q) {
                    $q->select('id', 'name');
                }])->with(['laporan_tahfidz' => function ($q) use ($request, $i) {
                    $q->where('tanggal', Carbon::create($request->year, $request->month, $i)->format('Y-m-d'));
                }])->with('kelas')->where('user_id', $request->user_id)->first();
            }

            $date = Carbon::create($request->year, $request->month)->translatedFormat('F Y');

            $totalAyat = 0;

            foreach ($tahfidzData as $data) {
                foreach ($data['data']['laporan_tahfidz'] as $laporan) {
                    $surah[] = $laporan->surah;
                    $totalAyat += $laporan->ayat_end - $laporan->ayat_start + 1;
                }
            }



            $totalSurah = count(array_unique($surah));


            $this->downloadTahfidzByUser($tahfidzData, $date, $siswa);
        }
    }

    private function downloadTahfidzByClass($tahfidzData, $date, $kelas)
    {

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 40,
            'margin_bottom' => 10,
            'tempDir' => storage_path('tempdir')
        ]);

        $pdf->SetHTMLHeader(
            '
                <table border="0" width="100%" align="center">
                    <tr>
                        <td  align="center">
                             <h2>Rekap Data Guru</h2>
                        </td>
                    </tr>
                    <tr>
                        <td  align="center">
                            <h3>SMP IT Al-Hijrah Medan</h3>
                        </td>
                    </tr>
                    <tr>
                        <td  align="center">
                            <p>Jl. Perhubungan, Laut Dendang, Kec. Percut Sei Tuan, Kab. Deli Serdang, Sumatera Utara, 20371</p>
                        </td>
                     </tr>
                </table>
            '
        );

        $pdf->WriteHTML(view('tahfidz.rekap-tahfidz-class', compact('tahfidzData', 'date', 'kelas')));

        $pdf->Output('rekap-tahfidz-' . now()->timestamp . '.pdf', 'D');
    }

    private function downloadTahfidzByUser($tahfidzData, $date, $siswa)
    {

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 40,
            'margin_bottom' => 10,
            'tempDir' => storage_path('tempdir')
        ]);

        $pdf->SetHTMLHeader(
            '<table border="0" width="100%" align="center">
                    <tr>
                        <td  align="center">
                             <h2>Rekap Data Guru</h2>
                        </td>
                    </tr>
                    <tr>
                        <td  align="center">
                            <h3>SMP IT Al-Hijrah Medan</h3>
                        </td>
                    </tr>
                    <tr>
                        <td  align="center">
                            <p>Jl. Perhubungan, Laut Dendang, Kec. Percut Sei Tuan, Kab. Deli Serdang, Sumatera Utara, 20371</p>
                        </td>
                     </tr>
                </table>
            '
        );

        $pdf->WriteHTML(view('tahfidz.rekap-tahfidz-user', compact('tahfidzData', 'date', 'siswa')));

        $pdf->Output('rekap-tahfidz-' . now()->timestamp . '.pdf', 'D');
    }
}
