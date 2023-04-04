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
    <table class="rekap" cellpadding="8">
     
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>No Induk</th>
                <th>Email</th>
                <th>Kelas</th>
                <th>Asal Sekolah</th> 
                <th>Password</th>
                <th>Username</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
           @foreach ($data as $siswa)
               <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $siswa['nama'] }}</td>
                    <td>{{ $siswa['nisn'] }}</td>
                    <td>{{ $siswa['email'] }}</td>
                    <td>{{ $siswa['kelas'] }}</td>
                    <td>{{ $siswa['asal_sekolah'] }}</td>
                    <td>{{ $siswa['password'] }}</td>
                    <td> {{ $siswa['username'] }} </td>
                    <td> {{ $siswa['role'] }} </td>
               </tr>
           @endforeach
        </tbody>
    </table>
</body>
</html>