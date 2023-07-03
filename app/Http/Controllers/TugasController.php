<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use App\Models\MataPelajaran;
use App\Models\MataPelajaranHari;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tugas = Tugas::with(['mataPelajaran.mapel', 'mataPelajaran.kelas']);

        if (request()->kelasId) {
            if (request()->kelasId != 'all') {
                $tugas =  $tugas->whereHas('mataPelajaran.kelas', function ($q) {
                    $q->where('kelas_id', request()->kelasId);
                });
            }
        }

        if (request()->mataPelajaran) {
            if (request()->mataPelajaran != 'all') {
                $tugas =  $tugas->where('mata_pelajaran_id', request()->mataPelajaran);
            }
        }

        $tugas = $tugas->get();

        return response()->json([
            'status' => 'success',
            'data' => $tugas,
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
        $validator = Validator::make($request->all(), [
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran_haris,id',
            'tanggal' => 'required|date_format:Y-m-d',
            'jenis_tugas' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $tugas = Tugas::create([
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'tanggal' => $request->tanggal,
            'jenis_tugas' => $request->jenis_tugas,
        ]);

        if ($tugas) {
            return response()->json([
                'status' => 'success',
                'data' => $tugas,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas gagal ditambahkan',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tugas $tuga)
    {
        $tuga->load(['mataPelajaran.mapel', 'mataPelajaran.kelas.siswa.user']);
        return response()->json([
            'status' => 'berhasil',
            'data' => $tuga
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tugas $tuga)
    {
        $validator = Validator::make($request->all(), [
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran_haris,id',
            'tanggal' => 'required|date_format:Y-m-d',
            'jenis_tugas' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $tuga->mata_pelajaran_id = $request->mata_pelajaran_id;
        $tuga->tanggal = $request->tanggal;
        $tuga->jenis_tugas = $request->jenis_tugas;
        $tuga->save();

        return response()->json([
            'status' => 'success',
            'data' => $tuga
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tugas $tuga)
    {
        $tuga->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function mark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.tugas_id' => 'required|exists:tugas,id',
            '*.siswa_id' => 'required|exists:siswas,id',
            '*.nilai' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        foreach ($request->all() as $data) {
            $tugas = Tugas::find($data['tugas_id']);
            $tugas->nilai()->syncWithoutDetaching([
                $data['siswa_id'] => [
                    'nilai' => $data['nilai'],
                ]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Nilai berhasil ditambahkan',
        ]);
    }

    public function siswa(Tugas $tugas)
    {
        $tugas->load(['nilai', 'mataPelajaran.mapel', 'mataPelajaran.kelas.siswa.user']);
        return response()->json([
            'status' => 'success',
            'data' => $tugas
        ]);
    }

    public function getTugasByMataPelajaranHari($id)
    {
        $tugas = Tugas::where('mata_pelajaran_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $tugas->load(['mataPelajaran', 'mataPelajaran.kelas.siswa.user'])
        ]);
    }

    /**
     * Print grade report based on subject and class grouply
     * 
     * @param Request $request
     *  
     */
    public function cetak(Request $request)
    {
        $mataPelajaran = MataPelajaranHari::with(['mapel', 'guru', 'kelas', 'tugas.nilai', 'siswa'])
            ->whereHas('guru', function ($query) use ($request) {
                $query->where('id', $request->guru_id);
            })
            ->where('id', '=', $request->mataPelajaran)
            ->where('kelas_id', '=', $request->kelasId)
            ->first();

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L',
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
                             <h2>Rekapitulasi Nilai Siswa</h2>
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
        $pdf->WriteHTML(view('rekap.rekap-nilai', compact('mataPelajaran'))->render());
        $pdf->Output('rekap-nilai-' . now()->timestamp . '.pdf', 'D');
    }
}
