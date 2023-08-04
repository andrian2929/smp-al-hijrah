<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MutabaahYaumiyah;
use Illuminate\Validation\Rule;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use \Mpdf\Mpdf;


class MutabaahYaumiyahController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function read(Request $request)
    {

        if ($request->req == 'get_mutabaahyaumiyah_by_user_id_and_date') {
            $data = MutabaahYaumiyah::with(['user' => function ($q) {
                $q->select('id', 'name');
            }])
                ->where('user_id', $request->user_id)
                ->where('tanggal', $request->tanggal);
        }

        return response()->json([
            'data' => $data->first()
        ]);
    }

    public function write(Request $request)
    {
        if ($request->req == 'write_mutabaahyaumiyah_by_user_id_and_date') {
            $request->validate([
                'AlMatsurat' => 'required|boolean',
                'Dhuha' => 'required|boolean',
                'MembacaBuku' => 'required|boolean',
                'Olahraga' => 'required|boolean',
                'QiyamulLail' => 'required|boolean',
                'ShalatAshar' => 'required|boolean',
                'ShalatDzuhur' => 'required|boolean',
                'ShalatIsya' => 'required|boolean',
                'ShalatMaghrib' => 'required|boolean',
                'ShalatSubuh' => 'required|boolean',
                'ShoumSunnah' => 'required|boolean',
                'TilawahQuran' => 'required|boolean',
                'Tanggal' => 'required|date_format:Y-m-d|before_or_equal:today',
            ]);

            $mutabaahyaumiyah = MutabaahYaumiyah::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'tanggal' => $request->Tanggal
                ],
                [
                    'qiyamul_lail' => $request->QiyamulLail,
                    'dhuha' => $request->Dhuha,
                    'tilawah_quran' => $request->TilawahQuran,
                    'membaca_buku' => $request->MembacaBuku,
                    'olahraga' => $request->Olahraga,
                    'al_matsurat' => $request->AlMatsurat,
                    'shoum_sunnah' => $request->ShoumSunnah,
                    'shalat_subuh' => $request->ShalatSubuh,
                    'shalat_dzuhur' => $request->ShalatDzuhur,
                    'shalat_ashar' => $request->ShalatAshar,
                    'shalat_maghrib' => $request->ShalatMaghrib,
                    'shalat_isya' => $request->ShalatIsya,
                ]
            );

            return response()->json([
                'message' => 'Data mutabaah yaumiyah berhasil ditambahkan',
            ], 201);
        }

        $request->validate([
            'AlMatsurat' => 'required|boolean',
            'Dhuha' => 'required|boolean',
            'MembacaBuku' => 'required|boolean',
            'Olahraga' => 'required|boolean',
            'QiyamulLail' => 'required|boolean',
            'ShalatAshar' => 'required|boolean',
            'ShalatDzuhur' => 'required|boolean',
            'ShalatIsya' => 'required|boolean',
            'ShalatMaghrib' => 'required|boolean',
            'ShalatSubuh' => 'required|boolean',
            'ShoumSunnah' => 'required|boolean',
            'TilawahQuran' => 'required|boolean',
            'Tanggal' => ['required', 'date_format:Y-m-d', 'before_or_equal:today', Rule::unique('mutabaah_yaumiyah', 'tanggal')->where(function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })]

        ], [
            'Tanggal.date_format' => 'Format tanggal salah, gunakan format Y-m-d',
            'Tanggal.required' => 'Tanggal tidak boleh kosong',
            'Tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'Tanggal.unique' => 'Tanggal sudah ada',
        ]);

        $mutabaahyaumiyah = MutabaahYaumiyah::create([
            'qiyamul_lail' => $request->QiyamulLail,
            'dhuha' => $request->Dhuha,
            'tilawah_quran' => $request->TilawahQuran,
            'membaca_buku' => $request->MembacaBuku,
            'olahraga' => $request->Olahraga,
            'al_matsurat' => $request->AlMatsurat,
            'shoum_sunnah' => $request->ShoumSunnah,
            'shalat_subuh' => $request->ShalatSubuh,
            'shalat_dzuhur' => $request->ShalatDzuhur,
            'shalat_ashar' => $request->ShalatAshar,
            'shalat_maghrib' => $request->ShalatMaghrib,
            'shalat_isya' => $request->ShalatIsya,
            'tanggal' => $request->Tanggal,
            'user_id' => $request->user_id
        ]);

        return response()->json([
            'message' => 'Data mutabaah yaumiyah berhasil ditambahkan',
        ], 201);
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
        $user = $kelas->user()
            ->orderBy('name')
            ->get();


        // $user->each(function ($item) use ($date) {
        //     $item->mutabaah_yaumiyah = $this->getMutaBaahYaumiyahMonthly($item, $date);
        // });


        foreach ($user as $item) {
            $this->getMutaBaahYaumiyahMonthly($item, $date);
        }


        // $date = Carbon::create($request->year, $request->month)->translatedFormat('F Y');
        // $this->downloadMutabaahYaumiyahByClass($user, $date, $kelas);
    }


    public function getMutabaahYaumiyahReport(Request $request, User $user)
    {
        $request->validate([
            'tanggal' => 'sometimes|date_format:Y-m-d'
        ]);

        $tanggal = Carbon::parse($request->tanggal ?? now()->format('Y-m-d'));

        $mutabaahYaumiyahData = collect($this->getMutaBaahYaumiyahMonthly($user, $tanggal));

        $mutabaahYaumiyah = $mutabaahYaumiyahData->map(function ($item) {
            return collect($item)->sum();
        });

        return response()->json([
            'data' => $mutabaahYaumiyah
        ]);
    }

    private function getMutaBaahYaumiyahMonthly($user, Carbon $date)
    {
        $categories = [
            'Shalat Subuh'  => 'shalat_subuh',
            'Shalat Dzuhur' => 'shalat_dzuhur',
            'Shalat Ashar' => 'shalat_ashar',
            'Shalat Maghrib' => 'shalat_maghrib',
            'Shalat Isya' => 'shalat_isya',
            'Qiyamul Lail' => 'qiyamul_lail',
            'Dhuha' => 'dhuha',
            'Tilawah Quran' => 'tilawah_quran',
            'Membaca Buku' => 'membaca_buku',
            'Olahraga' => 'olahraga',
            'Al Matsurat' => 'al_matsurat',
            'Shoum Sunnah' => 'shoum_sunnah',
        ];

        $startDay = $date->clone()
            ->startOfMonth();
        $endDay = $date->clone()
            ->endOfMonth();

        $period = CarbonPeriod::create($startDay, $endDay);

        $mutabaahYaumiyah = [];
        foreach ($period as $date) {
            $dailyData = MutabaahYaumiyah::whereDate('tanggal', $date)
                ->where('user_id', $user->id)
                ->first();
            $mutabaahYaumiyahData = [];
            foreach ($categories as $category => $value) {
                $mutabaahYaumiyahData[$value] = $dailyData->$value ?? 0;
            }


            $mutabaahYaumiyah[$date->day] = $mutabaahYaumiyahData;
        }

        return $mutabaahYaumiyah;
    }


    private function downloadMutabaahYaumiyahByClass($user, $date, $kelas)
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
                             <h2>Rekap Data Mutabaah Yaumiyah</h2>
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
        $pdf->WriteHTML(view('mutabaah-yaumiyah.rekap-mutabaah-yaumiyah-class', compact('user', 'date', 'kelas')));
        $pdf->Output('rekap-mutabaah-yaumiyah-' . now()->timestamp . '.pdf', 'D');
    }
}
