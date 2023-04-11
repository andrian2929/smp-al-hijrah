<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\GuruPengganti;

class GuruPenggantiController extends Controller
{
    /**
     * @var \App\Models\GuruPengganti
     */
    protected $guruPenggantiModel;

    public function __construct(GuruPengganti $guruPenggantiModel)
    {
        $this->guruPenggantiModel = $guruPenggantiModel;
    }

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guruPengganti = $this->guruPenggantiModel->all();
        return response()->json([
            'message' => 'success',
            'data' => $guruPengganti->load('guru', 'guruPengganti', 'mataPelajaran')
        ], 200);
    }

    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $guruPengganti = $this->guruPenggantiModel->find($id);
        if (!$guruPengganti) {
            return response()->json([
                'message' => 'failed',
                'data' => 'Guru Pengganti not found'
            ], 404);
        }
        return response()->json([
            'message' => 'success',
            'data' => $guruPengganti->load('guru', 'guruPengganti', 'mataPelajaran')
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guru_id'           => 'required|exists:gurus,id',
            'guru_pengganti_id' => 'required|exists:gurus,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran_haris,id',
            'tanggal'           => 'required|date',
            'jam'               => 'required|date_format:H:i',
            'keterangan'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'failed',
                'data' => $validator->errors()->first()
            ], 422);
        }

        $guruPengganti = $this->guruPenggantiModel->create([
            'guru_id'           => $request->guru_id,
            'guru_pengganti_id' => $request->guru_pengganti_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'tanggal'           => $request->tanggal,
            'jam'               => $request->jam,
            'keterangan'        => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $guruPengganti
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'guru_id'           => 'required|exists:gurus,id',
            'guru_pengganti_id' => 'required|exists:gurus,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran_haris,id',
            'tanggal'           => 'required|date',
            'jam'               => 'required|date_format:H:i',
            'keterangan'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'failed',
                'data' => $validator->errors()->first()
            ], 422);
        }

        $guruPengganti = $this->guruPenggantiModel->find($id);
        if (!$guruPengganti) {
            return response()->json([
                'message' => 'failed',
                'data' => 'Guru Pengganti not found'
            ], 404);
        }

        $guruPengganti->update([
            'guru_id'           => $request->guru_id,
            'guru_pengganti_id' => $request->guru_pengganti_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'tanggal'           => $request->tanggal,
            'jam'               => $request->jam,
            'keterangan'        => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $guruPengganti
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $guruPengganti = $this->guruPenggantiModel->find($id);
        if (!$guruPengganti) {
            return response()->json([
                'message' => 'failed',
                'data' => 'Guru Pengganti not found'
            ], 404);
        }

        $guruPengganti->delete();

        return response()->json([
            'message' => 'success',
            'data' => 'Guru Pengganti deleted'
        ], 200);
    }
}
