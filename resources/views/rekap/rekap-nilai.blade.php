<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        .rekap,
        .rekap th,
        .rekap td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>


<body>

    <table>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $mataPelajaran->kelas->jenjang }} - {{ $mataPelajaran->kelas->section }}</td>
        </tr>

        <tr>
            <td>Jumlah</td>
            <td>:</td>
            <td>{{ count($mataPelajaran->siswa) }}</td>
        </tr>

        <tr>
            <td>Mata Pelajaran</td>
            <td>:</td>
            <td>{{ $mataPelajaran->mapel->name }}</td>
        </tr>

        <tr>
            <td>Guru</td>
            <td>:</td>
            <td>{{ $mataPelajaran->guru->name }}</td>
        </tr>
    </table>

    <table class="rekap">
        <thead>
            <tr align="center">
                <th rowspan="2">No</th>
                <th rowspan="2">Nama</th>
                <th colspan="{{ count($mataPelajaran->tugas) }}">Nilai</th>
            </tr>
            <tr>
                @foreach ($mataPelajaran->tugas as $tugas)
                    <th>{{ strtoupper($tugas->jenis_tugas) }}</th>
                @endforeach
            <tr>
        </thead>

        <tbody>

            @foreach ($mataPelajaran->siswa as $siswa)
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td>{{ $siswa->name }}</td>
                    @foreach ($mataPelajaran->tugas as $tugas)
                        @if (count($tugas->nilai) == 0)
                            <td align="center">-</td>
                        @else
                            @php
                                $nilai = $tugas->nilai->first(function ($item) use ($siswa) {
                                    return $item->user_id === $siswa->id;
                                });
                                
                            @endphp
                            <td align="center">{{ $nilai->pivot->nilai }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>



    </table>

</body>

</html>
