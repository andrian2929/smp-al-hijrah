<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .rekap {}

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

    <br>

    @foreach ($user as $item)
        <h4>{{ $item->name }}</h4>
        <table class="rekap">
            <tr>
                <th>Kategori</th>
                <th>Rata-Rata</th>
            </tr>
            @foreach ($item->perilaku as $category => $average)
                <tr>
                    <td>{{ $category }}</td>
                    <td>{{ $average }}</td>
                </tr>
            @endforeach
        </table>
        <div style="page-break-after: always;"></div>
    @endforeach

</body>

</html>
