<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrangtuaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }
    public function read(Request $request)
    {
        if ($request->req == 'find') {
            $data = Orangtua::where('user_id', $request->user_id)->get();
        }
        response()->json([
            'models' => $data
        ]);
    }

    public function write(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string',
            'noTelp' => 'nullable|numeric',
            'tipe' => 'required|in:ayah,ibu,wali',
            'tanggalLahir' => 'nullable|date_format:Y-m-d',
            'pekerjaan' => 'nullable|string',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tidak dapat menyimpan data',
                'errors' => $validator->errors()->first()
            ]);
        }

        $orangtua = Orangtua::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'hubungan' => $request->tipe
            ],
            [
                'name' => $request->nama,
                'no_hp' => $request->noTelp,
                'tanggal_lahir' => $request->tanggalLahir,
                'pekerjaan' => $request->pekerjaan,
            ]
        );

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'model' => $orangtua
        ]);
    }

    public function delete($id)
    {
        $orangtua = Orangtua::findOrFail($id);
        $orangtua->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
            'data' => $orangtua
        ]);
    }
}
