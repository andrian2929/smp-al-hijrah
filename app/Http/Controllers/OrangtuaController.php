<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use Illuminate\Http\Request;

class OrangtuaController extends Controller
{
    public function __construct() {
        $this->middleware(['auth:sanctum']);
    }
    public function read(Request $request) {
        if($request->req == 'find') {
            $data = Orangtua::where('user_id', $request->user_id)->get();
        }
        response()->json([
            'models' => $data
        ]);
    }
}
