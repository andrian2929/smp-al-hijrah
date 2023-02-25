<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function read(Request $request)
    {

        if ($request->req == 'all') {
            $data = User::select('id', 'name')
                ->whereHas('roles', function ($q) use ($request) {
                    $q->where('display_name', 'guru');
                })->get();
        }

        if ($request->req == 'table') {
            if ($request->has('user_id') && $request->user_id != 'all') {
                $data = User::whereHas('roles', function ($q) {
                    $q->where('display_name', 'guru');
                })->where('id', $request->user_id)->with('wali_kelas', 'guru')->get();
                return 'ok';
            } else {

                $data = User::whereHas('roles', function ($q) {
                    $q->where('display_name', 'guru');
                })->where(function ($q) use ($request) {
                    $search = strtolower($request->search);
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('no_induk', 'LIKE', "%$search%");
                })->with('wali_kelas', 'guru')->get();
            }
        }

        if ($request->req == "count") {
            $data = User::whereHas('roles', function ($q) {
                $q->where('display_name', 'guru');
            })->count();
        }

        if ($request->req == 'get_guru_by_user_id') {

            $data = Guru::where('user_id', $request->user_id)->with('user')->firstOrFail();
        }
        if ($request->req == "get_tahfidz_guru") {

            $data = Guru::where('user_id', $request->user_id)->with('laporan_tahfidz', 'user')->get();
        }
        return response()->json([
            'models' => $data
        ]);
    }
}
