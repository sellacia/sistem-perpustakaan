<!DOCTYPE html>
<html>

<head>
    <title>Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            color: #1e73be;
        }

        .subtitle {
            font-size: 12px;
            color: #555;
        }

        hr {
            border: 1px solid #1e73be;
            margin: 10px 0 20px;
        }

        .info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #1e73be;
            color: white;
            padding: 8px;
        }

        table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .status {
            padding: 3px 6px;
            border-radius: 5px;
            color: white;
            font-size: 10px;
        }

        .dipinjam {
            background: orange;
        }

        .selesai {
            background: green;
        }

        .footer {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <div class="title">LAPORAN PEMINJAMAN BUKU</div>
        <div class="subtitle">Sistem Manajemen Perpustakaan Digital</div>
    </div>

    <hr>

    <!-- INFO -->
    <div class="info">
        <p><b>Tanggal Laporan:</b> {{ date('d-m-Y H:i') }}</p>
        <p><b>Total Data:</b> {{ count($laporan) }}</p>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($laporan as $i => $data)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $data->anggota->name ?? '-' }}</td>
                    <td>{{ $data->buku->judul }}</td>
                    <td>{{ $data->tanggal_pinjam }}</td>
                    <td>{{ $data->tanggal_kembali ?? '-' }}</td>
                    <td>
                        <span class="status {{ $data->status == 'dipinjam' ? 'dipinjam' : 'selesai' }}">
                            {{ $data->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer" style="text-align: right; margin-top: 40px;">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><b>Kepala Perpustakaan</b></p>
    </div>

</body>

</html>
