<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MutabaahYaumiyah;
use Illuminate\Validation\Rule;

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
}
