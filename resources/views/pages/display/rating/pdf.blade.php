<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #004d4d;
            padding: 4px;
        }

        th {
            background: #e6f2f2;
        }

        .footer {
            margin-top: 10px;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <center>
        <img src="{{ public_path('images/pku/logo.png') }}" width="90">
        <h2 style="margin-bottom: 2px">REKAP RATING PENILAIAN<br>RUMAH SAKIT PKU MUHAMMADIYAH SUKOHARJO</h2>
        <h3 style="color:rgb(214, 66, 66)"><i>Dicetak pada {{ now()->locale('id')->format('d-m-Y H:i:s') }} WIB</i></h3>
    </center>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Poin</th>
                <th>Deskripsi</th>
                <th>Tgl. Input (Lama ⇨ Baru)</th>
            </tr>
        </thead>

        <tbody>
            @php
                $no = 1;
            @endphp

            @if ($rating->isEmpty())
                <tr>
                    <td colspan="4" style="text-align: center; font-style: italic;"><center>Tidak ada data rating untuk bulan ini</center></td>
                </tr>
            @else
                @foreach ($rating as $row)
                    <tr>
                        <td><center>{{ $no++ }}</center></td>
                        <td><center>{{ $row->rating }}</center></td>
                        @if ($row->rating == 5)
                            <td><center>Sangat Baik</center></td>
                        @else
                            @if ($row->rating == 4)
                                <td><center>Baik</center></td>
                            @else
                                @if ($row->rating == 3)
                                    <td><center>Cukup</center></td>
                                @else
                                    @if ($row->rating == 2)
                                        <td><center>Kurang</center></td>
                                    @else
                                        <td><center>Sangat Kurang</center></td>
                                    @endif
                                @endif
                            @endif
                        @endif
                        <td><center>{{ $row->created_at }}</center></td>
                    </tr>
                @endforeach
            @endif

        </tbody>
    </table>

</body>

</html>
