<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Pelajar - Kelas {{ $kelas->nama }}</title>
    <style>
        @page { size: A4; margin: 8mm; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; }

        .toolbar { padding: 6mm 8mm 0; }
        .toolbar button {
            background: #1d4ed8; color: white; border: none; padding: 8px 20px;
            border-radius: 6px; font-size: 13px; cursor: pointer;
        }
        .toolbar span { margin-left: 10px; font-size: 12px; color: #64748b; }

        .grid { display: flex; flex-wrap: wrap; gap: 4mm; padding: 6mm 8mm; }
        .pasangan { display: flex; gap: 2mm; page-break-inside: avoid; }

        /* Kartu PORTRAIT: 54mm x 85.6mm (CR80) */
        .kartu {
            width: 54mm; height: 85.6mm; border-radius: 3mm;
            position: relative; overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,.15); background: white;
            display: flex; flex-direction: column;
        }

        /* ===== SISI DEPAN ===== */
        .depan .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 55%, #2563eb 100%);
            color: white; padding: 1.8mm 2mm 1.2mm; text-align: center; flex-shrink: 0;
        }
        .logo-emblem { width: 6.5mm; height: 6.5mm; margin: 0 auto 0.3mm; }
        .logo-emblem img { width: 100%; height: 100%; object-fit: contain; }
        .header .sekolah { font-size: 5pt; font-weight: 700; text-transform: uppercase; letter-spacing: .2px; line-height: 1.1; padding: 0 1mm; }
        .header .judul { font-size: 6.2pt; font-weight: 800; letter-spacing: 0.5px; margin-top: 0.4mm; }

        .depan .body { flex: 1; display: flex; flex-direction: column; align-items: center; padding: 1.2mm 2.5mm 0.8mm; min-height: 0; overflow: hidden; }
        .foto-box {
            width: 12.5mm; height: 15mm; border-radius: 1.2mm; background: #eef2ff;
            border: 0.3mm solid #c7d2fe; overflow: hidden; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; margin-bottom: 0.8mm;
        }
        .foto-box img { width: 100%; height: 100%; object-fit: cover; }
        .foto-placeholder { font-size: 11pt; color: #94a3b8; }

        .nama-siswa {
            font-size: 7.2pt; font-weight: 800; color: #1e293b; text-align: center;
            line-height: 1.1; margin-bottom: 0.8mm; width: 100%; flex-shrink: 0;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }

        .info-rows { width: 100%; flex-shrink: 0; }
        .info-row { display: flex; align-items: baseline; font-size: 4.8pt; color: #475569; padding: 0.2mm 0; }
        .info-row .lbl { width: 12mm; flex-shrink: 0; color: #64748b; font-weight: 600; }
        .info-row .sep { width: 1.5mm; flex-shrink: 0; }
        .info-row .val {
            flex: 1; min-width: 0; font-weight: 700; color: #0f172a;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .depan .footer {
            flex-shrink: 0; padding: 1mm 2mm 1.5mm; display: flex; flex-direction: column; align-items: center;
            border-top: 0.3mm dashed #e2e8f0; margin-top: auto;
        }
        .qr-box { background: white; padding: 0.8mm; border: 0.3mm solid #e2e8f0; border-radius: 1mm; line-height: 0; }
        .qr-box svg, .qr-box img { width: 22mm !important; height: 22mm !important; display: block; }
        .qr-caption { font-size: 4.5pt; font-weight: 700; color: #1d4ed8; margin-top: 0.6mm; letter-spacing: .2px; }
        .validitas { font-size: 3.8pt; color: #94a3b8; margin-top: 0.2mm; }

        /* ===== SISI BELAKANG ===== */
        .belakang { padding: 3.5mm 3.5mm; border: 0.4mm solid #cbd5e1; color: #1e293b; }
        .belakang h3 { font-size: 6pt; margin: 0 0 1.5mm; color: #1d4ed8; text-transform: uppercase; letter-spacing: .4px; }
        .belakang p { font-size: 5.2pt; line-height: 1.45; margin: 0 0 1.8mm; color: #475569; }
        .belakang .field-label { color: #1d4ed8; font-weight: 700; }
        .barcode-mini { font-family: 'Courier New', monospace; font-size: 6pt; letter-spacing: 2px; color: #334155; text-align: center; margin: 2mm 0; }
        .ttd-area { margin-top: auto; padding-top: 1.5mm; display: flex; justify-content: space-between; gap: 2mm; }
        .ttd-box { font-size: 4.8pt; text-align: center; flex: 1; min-width: 0; }
        .ttd-title { color: #475569; }
        .ttd-line { border-top: 0.3mm solid #94a3b8; margin-top: 1mm; }
        .ttd-name { font-weight: 700; color: #1e293b; margin-top: 0.6mm; padding: 0 1mm; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ttd-nip { font-size: 4pt; color: #64748b; margin-top: 0.2mm; }

        @media print { .toolbar { display: none; } body { background: white; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">🖨️ Cetak Semua Kartu (Depan + Belakang)</button>
        <span>{{ $siswa->count() }} kartu — Kelas {{ $kelas->nama }}</span>
    </div>

    <div class="grid">
        @foreach ($siswa as $s)
            @php $kartu = $s->kartuAktif->firstWhere('tipe', 'qr'); @endphp
            @if ($kartu)
                <div class="pasangan">
                    {{-- SISI DEPAN --}}
                    <div class="kartu depan">
                        <div class="header">
                            <div class="logo-emblem"><img src="{{ $logoSekolahGlobal ?? '' }}" alt="Logo"></div>
                            <div class="sekolah">{{ $namaSekolahGlobal ?? 'SEKOLAH' }}</div>
                            <div class="judul">KARTU TANDA PELAJAR</div>
                        </div>

                        <div class="body">
                            <div class="foto-box">
                                @if ($s->foto)
                                    <img src="{{ asset('storage/'.$s->foto) }}" alt="Foto">
                                @else
                                    <span class="foto-placeholder">👤</span>
                                @endif
                            </div>

                            <div class="nama-siswa">{{ $s->nama }}</div>

                            <div class="info-rows">
                                <div class="info-row"><span class="lbl">NIS</span><span class="sep">:</span><span class="val">{{ $s->nis ?? '-' }}</span></div>
                                <div class="info-row"><span class="lbl">NISN</span><span class="sep">:</span><span class="val">{{ $s->nisn ?? '-' }}</span></div>
                                <div class="info-row"><span class="lbl">Tgl Lahir</span><span class="sep">:</span><span class="val">{{ $s->tanggal_lahir?->format('d-m-Y') ?? '-' }}</span></div>
                                <div class="info-row"><span class="lbl">Kelas</span><span class="sep">:</span><span class="val">{{ $kelas->nama ?? '-' }}</span></div>
                                <div class="info-row"><span class="lbl">Prodi</span><span class="sep">:</span><span class="val">{{ $kelas->jurusan->nama ?? '-' }}</span></div>
                                <div class="info-row"><span class="lbl">Alamat</span><span class="sep">:</span><span class="val">{{ $s->alamat ?? '-' }}</span></div>
                            </div>
                        </div>

                        <div class="footer">
                            <div class="qr-box">{!! \App\Support\QrCodeGenerator::svg($kartu->kode, 120) !!}</div>
                            <div class="qr-caption">SCAN UNTUK ABSENSI</div>
                            <div class="validitas">Berlaku T.P. {{ $tahunAjaranAktif->nama ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- SISI BELAKANG --}}
                    <div class="kartu belakang">
                        <h3>Ketentuan Kartu</h3>
                        <p>
                            Kartu ini adalah identitas resmi milik <strong>{{ $namaSekolahGlobal ?? 'Sekolah' }}</strong> dan wajib
                            dibawa setiap hari untuk keperluan presensi masuk &amp; pulang sekolah. Kehilangan kartu
                            wajib segera dilaporkan ke Tata Usaha untuk penerbitan kartu pengganti.
                        </p>
                        <p><span class="field-label">Alamat:</span> {{ $s->alamat ?? '-' }}</p>
                        <p><span class="field-label">Orang Tua/Wali:</span> {{ $s->orangTua?->name ?? '-' }}</p>

                        <div class="barcode-mini">{{ $s->nis }}</div>

                        <div class="ttd-area">
                            <div class="ttd-box">
                                <div class="ttd-title">Kepala Sekolah,</div>
                                
                                <!-- Area Tanda Tangan & Cap Sekolah Tersinkronisasi -->
                                <div style="position: relative; height: 12mm; margin: 0.5mm 0; display: flex; justify-content: center; align-items: center;">
                                    @if (!empty($pengaturanKartu?->cap_sekolah))
                                        <img src="{{ asset('storage/' . $pengaturanKartu->cap_sekolah) }}" style="position: absolute; left: 8px; top: -3px; height: 12mm; opacity: 0.8; object-fit: contain;">
                                    @endif
                                    @if (!empty($pengaturanKartu?->tanda_tangan))
                                        <img src="{{ asset('storage/' . $pengaturanKartu->tanda_tangan) }}" style="position: relative; height: 10mm; object-fit: contain; z-index: 2;">
                                    @endif
                                </div>

                                <div class="ttd-line"></div>
                                <div class="ttd-name">{{ $kepalaSekolah?->name ?? ($pengaturanKartu?->nama_kepsek ?? '(...........................)') }}</div>
                                <div class="ttd-nip">NIP. {{ $kepalaSekolah?->nip_nik ?? ($pengaturanKartu?->nip_kepsek ?? '-') }}</div>
                            </div>
                            <div class="ttd-box">
                                <div class="ttd-title">Pemilik Kartu,</div>
                                <div style="height: 12mm; margin: 0.5mm 0;"></div>
                                <div class="ttd-line"></div>
                                <div class="ttd-name">{{ $s->nama }}</div>
                                <div class="ttd-nip" style="visibility: hidden;">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</body>
</html>