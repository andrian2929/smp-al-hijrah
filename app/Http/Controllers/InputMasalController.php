<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mpdf\Mpdf;


class InputMasalController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inputMasal(Request $request)
    {
        $faker = Faker::create();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($request->file('file'));
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $sheetCount = count($sheetData);



        $data = [];
        for ($i = 2; $i <= $sheetCount; $i++) {
            $data[] = [
                'nama'          => $sheetData[$i]['A'],
                'nisn'          => $sheetData[$i]['B'],
                'email'         => $sheetData[$i]['C'] ?? null,
                'asal_sekolah'  => $sheetData[$i]['D'],
                'kelas_id'      => $sheetData[$i]['E'],
                'password'      => '12345678',
                'username'      => $this->generateUsername(),
                'role'          => 'siswa'

            ];
        }

        $validator = Validator::make($data, [
            '*.nama'            => 'required',
            '*.nisn'            => 'required',
            '*.email'           => 'nullable|email|unique:users',
            '*.asal_sekolah'    => 'required|string',
            '*.kelas_id'        => 'required|exists:kelas,id',
            '*.username'        => 'required|unique:users'
        ], [
            '*.nama.required'           => 'Ada nama yang kosong',
            '*.nisn.required'           => 'Ada NISN yang kosong',
            '*.email.email'             => 'Ada email yang tidak valid',
            '*.email.unique'            => 'Ada email yang sudah terdaftar',
            '*.asal_sekolah.required'   => 'Ada asal sekolah yang kosong',
            '*.asal_sekolah.string'     => 'Ada asal sekolah yang tidak valid',
            '*.kelas_id.required'       => 'Ada kelas yang kosong',
            '*.kelas_id.exists'         => 'Ada kelas yang tidak tedaftar',
            '*.username.required'       => 'Ada username yang kosong',
            '*.username.unique'         => 'Ada username yang sudah terdaftar'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Unprocessable Entity',
                'message' => $validator->errors()->first()
            ], 422);
        }

        foreach ($data as $key => $value) {
            $user = User::create([
                'name'          => $value['nama'],
                'email'         => $value['email'],
                'password'      => Hash::make($value['password']),
                'username'      => $value['username'],
                'no_induk'      => $value['nisn'],
                'asal_sekolah'  => $value['asal_sekolah'],
                'role'          => $value['role'],
                'image'         => 'default.png'
            ]);

            $user->attachRole($value['role']);

            $siswa = new Siswa();
            $siswa->user_id = $user->id;
            $siswa->kelas_id = $value['kelas_id'];
            $siswa->nisn = $value['nisn'];
            $siswa->save();

            $data[$key]['kelas'] = $siswa->load('kelas')->kelas->jenjang . " - " . $siswa->load('kelas')->kelas->section;
        }

        usort($data, function ($a, $b) {
            return $a['kelas_id'] <=> $b['kelas_id'];
        });

        $mpdf = new Mpdf(['tempDir' => storage_path('app/public/temp')]);
        $mpdf->WriteHTML(view('input-massal/input-masal', compact('data')));
        $mpdf->Output('hasil-input-masal', 'D');


        $exportSheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $exportSheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Nama Lengkap');
        $sheet->setCellValue('B1', 'NISN');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Asal Sekolah');
        $sheet->setCellValue('E1', 'Kelas');
        $sheet->setCellValue('F1', 'Username');
        $sheet->setCellValue('G1', 'Password');
        $row = 2;
        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $row, $value['nama']);
            $sheet->setCellValue('B' . $row, $value['nisn']);
            $sheet->setCellValue('C' . $row, $value['email']);
            $sheet->setCellValue('D' . $row, $value['asal_sekolah']);
            $sheet->setCellValue('E' . $row, $value['kelas']);
            $sheet->setCellValue('F' . $row, $value['username']);
            $sheet->setCellValue('G' . $row, $value['password']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($exportSheet);
        $writer->save('export.xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        return response()->download('export.xlsx');
    }

    public function downloadTemplate()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        return response()->download(storage_path('app/public/template_excel/template.xlsx'));
    }

    private function generateUsername()
    {
        do {
            $username = Str::random(10);
            $user = User::where('username', $username)->exists();
        } while ($user);

        return $username;
    }
}
