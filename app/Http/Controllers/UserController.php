<?php

namespace App\Http\Controllers;

use App\Models\Orangtua;
use App\Models\OrangtuaSiswa;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Guru;
use DateTime;
use Illuminate\Validation\Rule;



class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function read(Request $request)
    {
        if ($request->req == "table") {
            $data = User::where('id', '<>', auth()->user()->id)
                ->where(function ($q) use ($request) {
                    $search = strtolower($request->search);
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('no_induk', 'like', "%$search%");
                })->with('roles')->paginate($request->per_page);
        } else if ($request->req == "roles") {
            $data = Role::all();
        } else if ($request->req == "single") {
            $data = User::findOrFail($request->id);
        } else if ($request->req == "single_full") {
            $data = User::where('id', $request->id)
                ->with('roles', 'orangtua')
                ->firstOrFail();
        }

        return response()->json([
            'models' => $data
        ]);
    }

    public function write(Request $request)
    {


        if ($request->req == 'edit') {
            $request->validate([
                'id' => 'required'
            ]);
            $user = User::findOrFail($request->id);
            if ($request->editPart == 'biografi') {
                $request->validate([
                    'name' => 'required',
                    'tempat_lahir' => 'required',
                    'tanggal_lahir' => 'required',
                    'jenis_kelamin' => 'required'
                ]);
                $user->name = $request->name;
                $user->tempat_lahir = $request->tempat_lahir;
                $user->tanggal_lahir = $request->tanggal_lahir;
                $user->jenis_kelamin = $request->jenis_kelamin;
                $user->save();
            }
            if ($request->editPart == 'ciri_fisik') {
                $user->gol_darah = $request->gol_darah;
                $user->tinggi = $request->tinggi;
                $user->berat = $request->berat;
                $user->save();
            }
            if ($request->editPart == 'alamat') {
                $request->validate([
                    'alamat' => 'required',
                    'kelurahan' => 'required',
                    'kecamatan' => 'required',
                    'kota' => 'required',
                    'provinsi' => 'required'
                ]);
                $user->alamat = $request->alamat;
                $user->kelurahan = $request->kelurahan;
                $user->kecamatan = $request->kecamatan;
                $user->kota = $request->kota;
                $user->provinsi = $request->provinsi;
                $user->save();
            }
            if ($request->editPart == 'contact') {
                $user->no_telp = $request->no_telp;
                $user->email = $request->email;
                $user->save();
            }
            if ($request->editPart == 'orangtua') {
                $request->validate([
                    'orangtua.*.name' => 'required',
                    'orangtua.*.type' => 'required',
                    'orangtua.*.status' => 'required',
                    'orangtua.*.tanggal_lahir' => 'required',
                    'orangtua.*.pekerjaan' => 'required',
                ]);
                foreach ($request->orangtua as $ortu) {
                    $obj = Orangtua::find($ortu['id']);
                    if (!$obj) {
                        $obj = new Orangtua();
                    }
                    $obj->type = $ortu['type'];
                    $obj->status = $ortu['status'];
                    $obj->name = $ortu['name'];
                    $obj->tanggal_lahir = $ortu['tanggal_lahir'];
                    $obj->pekerjaan = $ortu['pekerjaan'];
                    $obj->keterangan = $ortu['keterangan'];
                    $obj->save();
                }
            }
            if ($request->editPart == 'auth') {
                $request->validate([
                    'password' => 'required',
                    'new_password' => 'required',
                ]);
                if (!Hash::check($request->password, $user->password)) {
                    throw new Exception("Wrong Password");
                }
                $user->password = Hash::make($request->new_password);
                $user->save();
            }
            if ($request->editPart == 'upload_profile') {

                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);

                if ($request->oldImage) {
                    $oldPhoto = public_path('/img/profile_photo/' . $request->oldImage);
                    if (file_exists($oldPhoto)) {
                        unlink($oldPhoto);
                    }
                }
                $image = $request->file('image');
                $name = Str::slug($request->name) . '-' . bin2hex(Str::random(5)) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/img/profile_photo');
                $image->move($destinationPath, $name);

                $user->image = $name;
                $user->save();
            }

            if ($request->editPart == 'user_management') {
                $request->validate([
                    'username' => 'required|unique:users,username,' . $user->id,
                    'password' => 'required',
                ]);

                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->save();
            }


            return response()->json($user);
        }
        if ($request->req == 'delete') {
            $data = User::with('roles')->find($request->id);
            if ($data) {
                $data->delete();
                return response()->json([
                    'message' => 'Data berhasil dihapus'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
        }
        if ($request->req == 'add') {

            $data =  $request->validate([
                'username' => 'required|unique:users,username',
                'password' => 'required',
                'role' => 'required',
                'name' => 'required',
                'no_telp' => 'nullable|numeric',
                'gol_darah' => 'nullable|in:A,B,AB,O',
                'tinggi' => 'nullable|numeric',
                'berat' => 'nullable|numeric',
                'jenis_kelamin' => 'in:laki-laki,perempuan',
                'tanggal_lahir' => 'nullable|date_format:Y-m-d',

            ]);


            if ($request->role == 'siswa') {
                $data =  $request->validate([
                    'kelas_id' => 'required|exists:kelas,id',
                    'nisn' => 'required|unique:siswas,nisn',
                    'is_beasiswa' => 'in:0,1',
                    'asal_sekolah' => 'required|string',

                ]);

                $user = new User();
                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->no_induk = $request->no_induk;
                $user->name = $request->name;
                $user->no_telp = $request->no_telp;
                $user->gol_darah = $request->gol_darah;
                $user->tinggi = $request->tinggi;
                $user->berat = $request->berat;
                $user->jenis_kelamin = $request->jenis_kelamin;
                $user->tanggal_lahir = $request->tanggal_lahir;
                $user->image = 'default.png';
                $user->alamat = $request->alamat;
                $user->kelurahan = $request->kelurahan;
                $user->kecamatan = $request->kecamatan;
                $user->kota = $request->kota;
                $user->provinsi = $request->provinsi;
                $user->email = $request->email;
                $user->save();

                $siswa = new Siswa();
                $siswa->user_id = $user->id;
                $siswa->kelas_id = $request->kelas_id;
                $siswa->nisn = $request->nisn;
                $siswa->is_beasiswa = $request->is_beasiswa;
                $siswa->asal_sekolah = $request->asal_sekolah;
                $siswa->save();


                $role = Role::where('display_name', $request->role)->firstOrFail();
                $user->attachRole($role);
            }

            if ($request->role == 'guru') {
                $data =  $request->validate([
                    'nip' => ['required', Rule::unique('gurus', 'nip')->ignore($request->nip), 'numeric'],
                    'tanggal_bergabung' => 'date'


                ]);

                $user = new User();
                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->no_induk = $request->no_induk;
                $user->name = $request->name;
                $user->no_telp = $request->no_telp;
                $user->gol_darah = $request->gol_darah;
                $user->tinggi = $request->tinggi;
                $user->berat = $request->berat;
                $user->jenis_kelamin = $request->jenis_kelamin;
                $user->tanggal_lahir = $request->tanggal_lahir;
                $user->image = 'default.png';
                $user->alamat = $request->alamat;
                $user->kelurahan = $request->kelurahan;
                $user->kecamatan = $request->kecamatan;
                $user->kota = $request->kota;
                $user->provinsi = $request->provinsi;
                $user->email = $request->email;
                $user->save();

                $guru = new Guru();
                $guru->user_id = $user->id;
                $guru->nip = $request->nip;
                $guru->gelar = $request->gelar;
                $guru->tanggal_bergabung = $request->tanggal_bergabung;
                $guru->save();


                $role = Role::where('display_name', $request->role)->firstOrFail();
                $user->attachRole($role);
            }

            if ($request->role == "admin" || $request->role == "piket") {

                $user = new User();
                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->no_induk = $request->no_induk;
                $user->name = $request->name;
                $user->no_telp = $request->no_telp;
                $user->gol_darah = $request->gol_darah;
                $user->tinggi = $request->tinggi;
                $user->berat = $request->berat;
                $user->jenis_kelamin = $request->jenis_kelamin;
                $user->tanggal_lahir = $request->tanggal_lahir;
                $user->image = 'default.png';
                $user->alamat = $request->alamat;
                $user->kelurahan = $request->kelurahan;
                $user->kecamatan = $request->kecamatan;
                $user->kota = $request->kota;
                $user->provinsi = $request->provinsi;
                $user->email = $request->email;
                $user->save();

                $role = Role::where('display_name', $request->role)->firstOrFail();
                $user->attachRole($role);
            }





            return response()->json($request->all(), 200);


            $role = Role::where('display_name', $request->role)->firstOrFail();
            $user = new User();
            $user->name = $request->name;
            $user->no_induk = $request->no_induk;
            $user->no_telp = $request->no_telp;
            $user->gol_darah = $request->gol_darah;
            $user->alamat = $request->alamat;
            $user->tinggi = $request->tinggi;
            $user->berat = $request->berat;
            $user->username = Str::uuid();
            $user->password = Hash::make($user->username);
            $user->save();

            // change username and password to easier credentials
            $defaultStr = strtolower(explode(" ", $user->name)[0] . $user->id);
            $user->username = $defaultStr;
            $user->password = Hash::make($defaultStr);
            $user->save();
            if ($role->display_name == 'siswa') {
                $siswa = new Siswa();
                $siswa->siswa_id = $user->id;
                $siswa->kelas_id = $request->kelas_id;
                $siswa->asal_sekolah = $request->asal_sekolah;
                $siswa->save();
            }
            if ($request->ayah->name) {
                $ayah = new Orangtua();
                $ayah->name = $request->ayah->name;
                $ayah->image = $request->ayah->image;
                $ayah->save();
            }
            if ($request->ibu->name) {
                $ibu = new Orangtua();
                $ibu->name = $request->ibu->name;
                $ibu->image = $request->ibu->image;
                $ibu->save();
            }
            if ($request->wali->name) {
                $wali = new Orangtua();
                $wali->name = $request->wali->name;
                $wali->image = $request->wali->image;
                $wali->keterangan = $request->wali->keterangan;
                $wali->save();
            }
        }
    }
}
