<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use \Mpdf\Mpdf as PDF;

use Illuminate\Http\Request;

use function PHPUnit\Framework\throwException;

class RekapDataController extends Controller
{
    public function rekapDataSiswa(Request $request)
    {
        $siswa = new Siswa();
        $siswa = $siswa->join('users', 'users.id', '=', 'siswas.user_id')->orderBy('users.name', 'asc')->join('kelas', 'kelas.id', '=', 'siswas.kelas_id');

        if ($request->has('kelas')) {
            $siswa = $siswa->where('kelas_id', $request->kelas)->get();
        } else {
            return response()->json([
                'message' => 'Kelas belum dipilih'
            ], 400);
        }

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
                             <h2>Rekap Data Siswa</h2>
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
        $mpdf->WriteHTML(view('rekap/rekap-siswa', ['siswa' => $siswa])->render());
        $mpdf->Output('rekap-siswa-' . now()->timestamp . '.pdf', 'D');
    }


    public function rekapDataGuru(Request $request)
    {
        $guru = new Guru();
        $guru = $guru->join('users', 'users.id', '=', 'gurus.user_id')->orderBy('users.name', 'asc')->get();

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
        $mpdf->WriteHTML(view('rekap/rekap-guru', ['guru' => $guru])->render());
        $mpdf->Output('rekap-guru-' . now()->timestamp . '.pdf', 'D');
    }
}
