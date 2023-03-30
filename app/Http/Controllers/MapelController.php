<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\MataPelajaranHari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MapelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }
    public function read(Request $request)
    {
        if ($request->req == 'all') {
            $data = MataPelajaran::select('id', 'name', 'gurus')->get();
            foreach ($data as $obj) {
                $obj['gurus_user'] = $obj->gurusUser;
            }
        }
        if ($request->req == 'table') {
            $data = MataPelajaran::select()
                ->where('name', 'LIKE', "%$request->search%");
            if ($request->jenjang) {
                $data = $data->where('jenjang', $request->jenjang);
            }
            $data = $data->get();
            foreach ($data as $obj) {
                $obj['gurus_user'] = $obj->gurusUser;
            }
        }
        if ($request->req == 'single') {
            $data = MataPelajaran::where('id', $request->id)->firstOrFail();
            $data['gurus_user'] = $data->gurusUser;
        }

        if ($request->req == 'get_mapel_by_guru_id') {
            $mapel = MataPelajaranHari::select('id', 'hari', 'waktu_mulai', 'waktu_selesai', 'mapel_id', 'guru_id', 'kelas_id')
                ->where('guru_id', $request->guru_id)
                ->with('mapel')->with('kelas');
            if ($request->kelas_id) {
                $mapel = $mapel->where('kelas_id', $request->kelas_id);
            }
            $data = $mapel->get();

            // $mapel = MataPelajaran::select('id', 'name', 'gurus')->whereHas('hari')->with('hari')->get();
            // $data = $mapel->filter(function ($item) use ($request) {
            //     $gurus = json_decode($item->gurus);
            //     if (!is_array($gurus))
            //         return false;
            //     return in_array($request->guru_id, $gurus);
            // });
            // $data = array_values($data->toArray());

        }
        return response()->json([
            'models' => $data
        ]);
    }

    public function write(Request $request)
    {
        if ($request->req == 'write') {
            $request->validate([
                'name' => 'required',
                'jenjang' => 'required|in:VII,VIII,IX'
            ]);
            $obj = MataPelajaran::find($request->id);
            if (!$obj) {
                $obj = new MataPelajaran();
            }
            $obj->name = $request->name;
            $obj->description = $request->description;
            $obj->kkm = $request->kkm;
            $obj->jenjang = $request->jenjang;
            // json of guru
            $obj->gurus = json_encode($request->gurus);
            $obj->save();
        }
        if ($request->req == 'delete') {
            $data = MataPelajaran::findOrFail($request->id);
            $hari = MataPelajaranHari::where('mapel_id', $request->id);
            if ($hari)
                $hari->delete();
            $data->delete();
        }
        if ($request->req == 'add_mapel_to_roster') {
            $validator = Validator::make($request->all(), [
                'waktu_mulai' => 'required|date_format:H:i',
                'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
                'mapel' => 'required|exists:mata_pelajarans,id',
                'guru' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $obj = MataPelajaranHari::find($request->id);
            if (!$obj) {
                $obj = new MataPelajaranHari();
            }
            $obj->hari = $request->hari;
            $obj->waktu_mulai = $request->waktu_mulai;
            $obj->waktu_selesai = $request->waktu_selesai;
            $obj->mapel_id = $request->mapel;
            $obj->guru_id = $request->guru;
            $obj->kelas_id = $request->kelas_id;
            $obj->save();
        }
    }
}
