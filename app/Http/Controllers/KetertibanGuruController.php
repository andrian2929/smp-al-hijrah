<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\KetertibanGuru;
use PDO;

class KetertibanGuruController extends Controller
{

    public function index(Request $request)
    {
        $ketertiban_guru = KetertibanGuru::when($request->user_id, function ($q)  use ($request) {
            return $q->whereHas('guru.user', function ($q) use ($request) {
                return $q->where('id', $request->user_id);
            });
        })->when($request->startDate && $request->endDate, function ($q) use ($request) {
            return $q->whereBetween('tanggal', [$request->startDate, $request->endDate]);
        })->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'message' => 'success',
            'data'   => $ketertiban_guru->load('guru.user')
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guru_id' => 'required|exists:gurus,id',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'failed',
                'data' => $validator->errors()->first()
            ], 400);
        }

        $ketertiban_guru = KetertibanGuru::create([
            'guru_id' => $request->guru_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $ketertiban_guru
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $ketertiban_guru = KetertibanGuru::find($id);

        if (!$ketertiban_guru) {
            return response()->json([
                'message' => 'failed',
                'data' => 'Ketertiban Guru not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'guru_id' => 'required|exists:gurus,id',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'failed',
                'data' => $validator->errors()->first()
            ], 422);
        }

        $ketertiban_guru->update([
            'guru_id' => $request->guru_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $ketertiban_guru
        ], 200);
    }

    public function show($id)
    {
        $ketertiban_guru = KetertibanGuru::find($id);

        if (!$ketertiban_guru) {
            return response()->json([
                'message' => 'failed',
                'data' => 'Ketertiban Guru not found'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $ketertiban_guru->load('guru')
        ], 200);
    }

    public function destroy($id)
    {
        $ketertiban_guru = KetertibanGuru::find($id);

        if (!$ketertiban_guru) {
            return response()->json([
                'message' => 'failed',
                'data' => 'Ketertiban Guru not found'
            ], 404);
        }

        $ketertiban_guru->delete();

        return response()->json([
            'message' => 'success',
            'data' => 'Ketertiban Guru deleted'
        ], 200);
    }
}
