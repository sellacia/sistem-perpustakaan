<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Buku</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #fff;
        }

        /* HEADER */
        .header {
            background: #1d4ed8;
            color: white;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .header-left h1 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .header-left p {
            font-size: 11px;
            opacity: 0.8;
            margin-top: 3px;
        }
        .header-right {
            text-align: right;
            font-size: 10px;
            opacity: 0.85;
        }

        /* INFO SECTION */
        .info-section {
            display: flex;
            gap: 12px;
            margin: 0 24px 16px;
        }
        .info-box {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 14px;
            background: #f9fafb;
        }
        .info-box .label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .info-box .value {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
        }
        .info-box .sub {
            font-size: 9px;
            color: #6b7280;
            margin-top: 2px;
        }
        .info-box.red .value { color: #dc2626; }
        .info-box.green .value { color: #16a34a; }
        .info-box.orange .value { color: #ea580c; }

        /* PERIODE */
        .periode-bar {
            margin: 0 24px 14px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 10px;
            color: #1d4ed8;
        }

        /* TABLE */
        .table-wrap {
            margin: 0 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background: #1e40af;
            color: white;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        tbody tr:hover {
            background: #eff6ff;
        }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .text-red { color: #dc2626; }
        .text-green { color: #16a34a; }
        .text-gray { color: #9ca3af; }

        /* BADGE */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 600;
            border: 1px solid;
        }
        .badge-green  { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe; }
        .badge-red    { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
        .badge-yellow { background: #fef9c3; color: #92400e; border-color: #fde68a; }
        .badge-indigo { background: #e0e7ff; color: #3730a3; border-color: #c7d2fe; }
        .badge-gray   { background: #f3f4f6; color: #4b5563; border-color: #e5e7eb; }

        /* FOOTER */
        .footer {
            margin: 24px 24px 0;
            border-top: 1px solid #e5e7eb;
            padding-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .footer-left {
            font-size: 10px;
            color: #6b7280;
        }
        .footer-right {
            text-align: center;
            font-size: 10px;
            color: #374151;
        }
        .signature-line {
            width: 120px;
            border-top: 1px solid #374151;
            margin-top: 40px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            <h1>📚 Laporan Peminjaman Buku</h1>
            <p>Sistem Manajemen Perpustakaan Digital</p>
        </div>
        <div class="header-right">
            <p>Dicetak oleh: {{ auth()->user()->name }}</p>
            <p>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y – HH:mm') }} WIB</p>
        </div>
    </div>

    {{-- Periode info --}}
    @if(request('mulai') && request('sampai'))
    <div class="periode-bar">
        📅 Periode: <strong>{{ \Carbon\Carbon::parse(request('mulai'))->isoFormat('D MMMM Y') }}</strong>
        s.d. <strong>{{ \Carbon\Carbon::parse(request('sampai'))->isoFormat('D MMMM Y') }}</strong>
    </div>
    @else
    <div class="periode-bar">
        📅 Periode: <strong>Semua Data</strong> – Total {{ $data->count() }} transaksi
    </div>
    @endif

    {{-- Statistik --}}
    @php
        $totalDenda = $data->sum(fn($d) => $d->dendaData->jumlah_denda ?? 0);
        $selesai    = $data->whereIn('status', ['selesai', 'dikembalikan'])->count();
        $terlambat  = $data->where('status', 'terlambat')->count();
    @endphp
    <div class="info-section">
        <div class="info-box">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ $data->count() }}</div>
            <div class="sub">peminjaman</div>
        </div>
        <div class="info-box green">
            <div class="label">Selesai</div>
            <div class="value">{{ $selesai }}</div>
            <div class="sub">transaksi</div>
        </div>
        <div class="info-box red">
            <div class="label">Terlambat</div>
            <div class="value">{{ $terlambat }}</div>
            <div class="sub">transaksi</div>
        </div>
        <div class="info-box orange">
            <div class="label">Total Denda</div>
            <div class="value" style="font-size:13px;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
            <div class="sub">belum/sudah bayar</div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:28px;">No</th>
                    <th>Nama Anggota</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Wajib Kembali</th>
                    <th>Tgl Kembali</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $d)
                <tr>
                    <td class="text-center text-gray">{{ $loop->iteration }}</td>
                    <td class="font-bold">{{ $d->anggota->name ?? '-' }}</td>
                    <td>{{ $d->buku->judul ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($d->tanggal_wajib_kembali)->format('d/m/Y') }}</td>
                    <td>{{ $d->tanggal_kembali ? \Carbon\Carbon::parse($d->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        @if ($d->status == 'selesai')
                            <span class="badge badge-green">Selesai</span>
                        @elseif ($d->status == 'dikembalikan')
                            <span class="badge badge-indigo">Dikembalikan</span>
                        @elseif ($d->status == 'terlambat')
                            <span class="badge badge-red">Terlambat</span>
                        @elseif ($d->status == 'dipinjam')
                            <span class="badge badge-blue">Dipinjam</span>
                        @elseif ($d->status == 'menunggu')
                            <span class="badge badge-yellow">Menunggu</span>
                        @else
                            <span class="badge badge-gray">{{ ucfirst($d->status) }}</span>
                        @endif
                    </td>
                    <td class="text-right {{ ($d->dendaData->jumlah_denda ?? 0) > 0 ? 'text-red font-bold' : 'text-gray' }}">
                        @if (($d->dendaData->jumlah_denda ?? 0) > 0)
                            Rp {{ number_format($d->dendaData->jumlah_denda, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-gray" style="padding: 20px;">
                        Tidak ada data laporan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-left">
            <p>Total: <strong>{{ $data->count() }}</strong> transaksi | Denda: <strong>Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong></p>
            <p style="margin-top:3px;">Dokumen ini digenerate otomatis oleh sistem.</p>
        </div>
        <div class="footer-right">
            <p>Mengetahui,</p>
            <div class="signature-line"></div>
            <p>( Petugas Perpustakaan )</p>
        </div>
    </div>

</body>
</html>
