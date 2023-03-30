<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    .rekap{
        width: 100%;
    }
    .rekap, .rekap th, .rekap td {
        border: 1px solid black;
        border-collapse: collapse;
    }
   .rekap th, .rekap td {
        padding: 5px;
    }
    table#t01 tr:nth-child(even) {
        background-color: #eee;
    }
    table#t01 tr:nth-child(odd) {
       background-color:#fff;
    }
    table#t01 th {
        background-color: black;
        color: white;
    }
</style>
<body>
    <table style="margin-bottom:0.5cm;">
        <tr>
         
            <td><b>Kelas</b><td>
            <td>:</td>
            <td>{{ $siswa[0]['jenjang'] }} - {{ $siswa[0]['section'] }}</td>
        </tr>

    </table>
    <table class="rekap" cellpadding="8">
     
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Lengkap</th>
                <th>Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Nomor Telepon</th>
            </tr>
        </thead>
        
        <tbody>
            @php
                $no = 1;
            @endphp
           @foreach ($siswa as $_siswa)
               <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $_siswa['nisn'] }}</td>
                    <td>{{ $_siswa['name'] }}</td>
                    <td>{{ $_siswa['tanggal_lahir'] }}</td>
                    <td style="width: 5px">@if ($_siswa['jenis_kelamin'] == 'laki-laki')
                        L
                       @else
                         P
                       @endif</td>
                    <td>{{ $_siswa['no_telp'] }}</td>
               </tr>
           @endforeach
        </tbody>
    </table>
</body>
</html>