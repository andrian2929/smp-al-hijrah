<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .rekap {
            width: 100%;
        }

        .rekap,
        .rekap th,
        .rekap td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .rekap th,
        .rekap td {
            padding: 5px;

        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    <table>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $kelas['jenjang'] }} - {{ $kelas['section'] }} </td>
        </tr>

        <tr>
            <td>Bulan/Tahun</td>
            <td>:</td>
            <td>{{ $date }}</td>
        </tr>
    </table>

    @foreach ($tahfidzData as $tahfidzItem)
        <hr>
        <h4>{{ \Carbon\Carbon::create($tahfidzItem['tanggal'])->translatedFormat('d F Y') }}</h4>

        <table class="rekap">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Surah</th>
                <th>Ayat</th>
            </tr>
            @php
                $no = 1;
            @endphp

            @foreach ($tahfidzItem['data'] as $tahfidz)
                @if (count($tahfidz['laporan_tahfidz']) == 0)
                    <tr>
                        <td class="center">{{ $no++ }}</td>
                        <td>{{ $tahfidz['user']['name'] }}</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                @else
                    @foreach ($tahfidz['laporan_tahfidz'] as $index => $laporan)
                        <tr>
                            <td class="center">{{ $no++ }}</td>
                            @if ($index == 0)
                                <td rowspan="{{ count($tahfidz['laporan_tahfidz']) }}">{{ $tahfidz['user']['name'] }}
                                </td>
                            @endif
                            <td class="center">{{ $laporan['surah'] }}</td>
                            <td class="center">{{ $laporan['ayat_start'] }} - {{ $laporan['ayat_end'] }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach

        </table>
    @endforeach

</body>

</html>
