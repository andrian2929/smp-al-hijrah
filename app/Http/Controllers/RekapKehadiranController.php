<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use \Mpdf\Mpdf as PDF;
use Illuminate\Http\Request;
use Carbon\Carbon;



class RekapKehadiranController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $siswa = new Siswa();

        if ($request->has('month') && $request->has('year')) {
            $numOfTheDay = Carbon::create($request->year, $request->month, 1)->daysInMonth;
            $monthName = Carbon::create($request->year, $request->month, 1)->locale('id')->monthName;


            $rekap = [];
            if ($request->has('kelas')) {
                for ($i = 1; $i <= $numOfTheDay; $i++) {
                    $rekap[$i] = $siswa->with(['user' => function ($q) {
                        $q->select('id', 'no_induk', 'name');
                    }])->with(['kehadiran' => function ($query) use ($request, $i) {
                        $query->where('tanggal', Carbon::create($request->year, $request->month, $i)->format('Y-m-d'));
                    }])->whereHas('kelas', function ($q) use ($request) {
                        $q->where('id', $request->kelas);
                    })->join('users', 'users.id', '=', 'siswas.user_id')->orderBy('users.name', 'asc')->get();
                }

                $this->downloadPDFKelas($rekap, $monthName, $numOfTheDay, $request->year, $request->month);
            }

            if ($request->has('siswa')) {
                for ($i = 1; $i <= $numOfTheDay; $i++) {
                    $rekap[$i] = $siswa->with(['user' => function ($q) {
                        $q->select('id', 'no_induk', 'name');
                    }])->with(['kehadiran' => function ($query) use ($request, $i) {
                        $query->where('tanggal', Carbon::create($request->year, $request->month, $i)->format('Y-m-d'));
                    }])->where('user_id', $request->siswa)->join('users', 'users.id', '=', 'siswas.user_id')->orderBy('users.name', 'asc')->get();
                }

                $this->downloadPDFSiswa($rekap, $monthName, $numOfTheDay, $request->year, $request->month);
            }
        }
    }


    private function downloadPDFKelas($rekap, $monthName, $numOfTheDay, $year, $month)
    {

        $mpdf = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 40,
            'margin_bottom' => 10,
            'tempDir' => storage_path('tempdir')
        ]);
        $mpdf->SetHTMLHeader(
            '
            <table border="0" width="100%" align="center">
                <tr>
                    <td  align="center">
                         <h2>Rekap Kehadiran Siswa</h2>
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
        $mpdf->WriteHTML(view('rekap/rekap-kehadiran-kelas', ['rekap' => $rekap, 'date' => ['monthName' => $monthName, 'year' => $year, 'month' => $month], 'numOfTheDay' => $numOfTheDay])->render());
        $mpdf->Output('rekap-kehadiran-kelas-' . now()->timestamp . '.pdf', 'D');
    }

    private function downloadPDFSiswa($rekap, $monthName, $numOfTheDay, $year, $month)
    {

        $mpdf = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 40,
            'margin_bottom' => 10,
            'tempDir' => storage_path('tempdir')
        ]);
        $mpdf->SetHTMLHeader(
            '
            <table border="0" width="100%" align="center">
                <tr>
                    <td  align="center">
                         <h2>Rekap Kehadiran Siswa</h2>
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
        $mpdf->WriteHTML(view('rekap/rekap-kehadiran-siswa', ['rekap' => $rekap, 'date' => ['monthName' => $monthName, 'year' => $year, 'month' => $month], 'numOfTheDay' => $numOfTheDay])->render());
        $mpdf->Output('rekap-kehadiran-siswa-' . now()->timestamp . '.pdf', 'D');

        $mpdf->Output();
    }
}
