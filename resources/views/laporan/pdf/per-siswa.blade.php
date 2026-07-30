<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #0f172a; line-height: 1.4; }
        h1 { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        p.sub { color: #64748b; margin-top: 0; font-size: 11px; }
        .meta-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; margin: 10px 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background: #0f172a; color: white; font-weight: 700; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; }
        tr:nth-child(even) { background: #f8fafc; }
    </style>
</head>
<body>
    <h1>Laporan Pelanggaran Siswa</h1>
    <p class="sub">SMK Negeri 1 Rejang Lebong · Periode {{ \Carbon\Carbon::parse($dari)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d-m-Y') }}</p>

    @if(isset($kelas))
        <div class="meta-box">
            <strong>Kelas:</strong> {{ $kelas->nama }} | 
            <strong>Wali Kelas:</strong> {{ $kelas->namaWaliKelas() }}
        </div>
    @endif

    <table>
        <thead>
            <tr><th>Tanggal</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Jenis Pelanggaran</th><th>Kategori</th><th>Poin</th></tr>
        </thead>
        <tbody>
            @foreach ($pelanggaran as $p)
                <tr>
                    <td>{{ $p->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $p->siswa->nis }}</td>
                    <td>{{ $p->siswa->nama }}</td>
                    <td>{{ $p->siswa->kelas->nama }}</td>
                    <td>{{ $p->jenisPelanggaran->nama }}</td>
                    <td>{{ ucfirst($p->jenisPelanggaran->kategori) }}</td>
                    <td>{{ $p->poin_saat_ini }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 20px; color: #94a3b8; font-size: 9px;">Dicetak pada {{ now()->format('d-m-Y H:i') }} WIB melalui SIPRESI SISWA</p>
</body>
</html>