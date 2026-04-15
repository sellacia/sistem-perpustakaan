<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Denda</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1e293b;
            font-size: 12px;
            margin: 0;
            padding: 24px;
            background: #ffffff;
        }
        .card {
            border: 1px solid #dbeafe;
            border-radius: 18px;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%);
            color: #fff;
            padding: 22px 24px;
        }
        .eyebrow {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.8;
            margin-bottom: 8px;
        }
        .title {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }
        .subtitle {
            font-size: 11px;
            opacity: 0.85;
            margin-top: 6px;
        }
        .content {
            padding: 24px;
        }
        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .meta td {
            padding: 8px 0;
            vertical-align: top;
        }
        .label {
            width: 140px;
            color: #64748b;
        }
        .value {
            font-weight: 600;
            color: #0f172a;
        }
        .amount-box {
            margin-top: 20px;
            border: 1px dashed #fb923c;
            background: #fff7ed;
            border-radius: 14px;
            padding: 18px;
            text-align: center;
        }
        .amount-box .amount-label {
            color: #9a3412;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .amount-box .amount {
            color: #c2410c;
            font-size: 28px;
            font-weight: 700;
            margin-top: 8px;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }
        .status-belum_bayar, .status-menunggu_konfirmasi {
            background: #fef3c7;
            color: #92400e;
        }
        .status-sudah_bayar {
            background: #dcfce7;
            color: #166534;
        }
        .footer {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $statusMap = [
            'belum_bayar' => 'Belum Bayar',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'sudah_bayar' => 'Sudah Dibayar',
        ];
        $status = $denda->peminjaman->status_denda ?? $denda->status;
    @endphp

    <div class="card">
        <div class="header">
            <div class="eyebrow">Perpustakaan Digital</div>
            <h1 class="title">Struk Denda</h1>
            <div class="subtitle">Bukti rincian denda keterlambatan pengembalian buku</div>
        </div>

        <div class="content">
            <table class="meta">
                <tr>
                    <td class="label">No. Struk</td>
                    <td class="value">#DENDA-{{ str_pad((string) $denda->id, 5, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Cetak</td>
                    <td class="value">{{ now()->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Anggota</td>
                    <td class="value">{{ $denda->peminjaman->anggota->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Buku</td>
                    <td class="value">{{ $denda->peminjaman->buku->judul ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Pinjam</td>
                    <td class="value">{{ \Carbon\Carbon::parse($denda->peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Batas Kembali</td>
                    <td class="value">{{ \Carbon\Carbon::parse($denda->peminjaman->tanggal_wajib_kembali)->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Kembali</td>
                    <td class="value">{{ $denda->peminjaman->display_tanggal_kembali ? \Carbon\Carbon::parse($denda->peminjaman->display_tanggal_kembali)->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Keterlambatan</td>
                    <td class="value">{{ $denda->terlambat }} hari</td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td class="value">
                        <span class="status status-{{ $status }}">{{ $statusMap[$status] ?? ucfirst($status) }}</span>
                    </td>
                </tr>
            </table>

            <div class="amount-box">
                <div class="amount-label">Total Denda</div>
                <div class="amount">Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</div>
            </div>

            <div class="footer">
                Dicetak oleh {{ auth()->user()->name ?? 'Petugas' }}<br>
                Sistem Manajemen Perpustakaan
            </div>
        </div>
    </div>
</body>
</html>
