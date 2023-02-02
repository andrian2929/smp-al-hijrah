<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\MataPelajaranGuru;
use App\Models\MataPelajaranHari;
use Illuminate\Http\Request;

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
        if ($request->req = '')
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
            $request->validate([
                'waktu' => 'required',
                'mapel' => 'required|exists:mata_pelajarans,id',
                'guru' => 'required|exists:users,id'
            ]);
            $obj = MataPelajaranHari::find($request->id);
            if (!$obj) {
                $obj = new MataPelajaranHari();
            }
            $obj->hari = $request->hari;
            $obj->waktu = $request->waktu;
            $obj->mapel_id = $request->mapel;
            $obj->guru_id = $request->guru;
            $obj->kelas_id = $request->kelas_id;
            $obj->save();
        }
    }
}
