<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListPerilaku;
use App\Models\Perilaku;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use \Mpdf\Mpdf;

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

    public function rekap(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric',
            'month' => 'required|numeric',
            'kelas' => 'required|numeric|exists:kelas,id'
        ]);

        $date = Carbon::create($request->year, $request->month, 1);
        $kelas = Kelas::find($request->kelas);

        $users = $kelas->user()
            ->orderBy('name')
            ->get();

        foreach ($users as $user) {
            $user->perilaku = $this->getLaporanPerilakuMonthly($user, $date);
        }

        $this->downloadLaporanPerilakuByClass($users, $date->translatedFormat('F Y'), $kelas);
    }

    private function getLaporanPerilakuMonthly(User $user, Carbon $date)
    {
        $perilakuCategories = ListPerilaku::all()
            ->toArray();

        $perilakuData = [];
        $period = CarbonPeriod::create($date->clone()->startOfMonth(), $date->clone()->endOfMonth());
        foreach ($perilakuCategories as $category) {
            $categorySum  = 0;
            $count = 1;
            foreach ($period as $date) {
                $perilaku = Perilaku::where('user_id', $user->id)
                    ->where('perilaku_id', $category['id'])
                    ->whereDate('tanggal', $date)
                    ->first();



                $categorySum = $categorySum + ($perilaku ? $perilaku->nilai : 0);
                $count++;
            }
            $perilakuData[$category['name']] = $categorySum / $count;
        }



        return $perilakuData;
    }

    private function downloadLaporanPerilakuByClass($user, $date, $kelas)
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
                             <h2>Rekap Data  Laporan Perilaku</h2>
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
        $pdf->WriteHTML(view('laporan-perilaku.rekap-laporan-perilaku-class', compact('user', 'date', 'kelas')));
        $pdf->Output('rekap-laporan-perilaku-' . now()->timestamp . '.pdf', 'D');
    }
}
