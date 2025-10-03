<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        /* Reset sederhana */
        * {
            box-sizing: border-box
        }

        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            margin: 30px;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Tombol print (disembunyikan saat print) */
        .btn-print {
            text-align: center;
            margin-bottom: 18px;
        }

        @media print {
            .btn-print {
                display: none
            }
        }

        /* Header kop */
        .kop {
            text-align: center;
            margin-bottom: 8px;
        }

        .kop .logo {
            position: absolute;
            left: 30px;
            top: 25px;
            width: 90px;
        }

        .kop .head-text {
            margin-left: 0;
            display: block;
        }

        .kop h1 {
            font-size: 18px;
            margin-bottom: 2px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .kop .subtitle {
            font-size: 12px;
            margin-bottom: 6px;
        }

        .separator {
            border-top: 2px solid #000;
            margin: 10px 0 18px 0;
        }

        /* bagian meta (No / Lamp / Hal) kiri - tanggal kanan */
        .meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .meta .left {
            width: 60%;
        }

        .meta .right {
            width: 38%;
            text-align: right;
        }

        .meta .left table {
            border-collapse: collapse;
        }

        .meta .left td.label {
            width: 80px;
            vertical-align: top;
            padding: 2px 6px 2px 0;
        }

        .meta .left td.value {
            padding: 2px 0;
        }

        /* isi surat */
        .content p {
            margin: 8px 0;
            text-align: justify;
            text-justify: inter-word;
        }

        /* detail (hari/tanggal/waktu/tempat) */
        .detail {
            margin-top: 12px;
            margin-bottom: 18px;
        }

        .detail table {
            width: 60%;
            border-collapse: collapse;
        }

        .detail td.label {
            width: 30%;
            vertical-align: top;
            padding: 4px 8px 4px 0;
            font-weight: bold;
        }

        .detail td.value {
            padding: 4px 0;
        }

        /* tanda tangan di kanan bawah */
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }

        .signature .block {
            text-align: right;
            width: 40%;
        }

        .signature .name {
            margin-top: 60px;
            font-weight: bold;
        }

        .signature .nip {
            margin-top: 4px;
            font-size: 12px;
        }

        /* footer kecil (opsional) */
        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #333;
        }

        /* agar hasil print rapi, set ukuran kertas ketika pakai print browser (opsional) */
        @page {
            size: A4;
            margin: 20mm;
        }
    </style>
</head>

<body>

    <div class="btn-print">
        <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>

    <div class="kop">
        {{-- logo kiri (ubah path sesuai lokasi file di public/) --}}
        <img src="{{ asset('images/logo fps-01.png') }}" alt="logo" class="logo">

        <div class="head-text">
            <h1>PT. FASYA PRATAMA SOLUSINDO</h1>
            <div class="subtitle pl-4" style="font-size:12px;">Cibubur Country, Ruko food plaza blok 6 33<br>Cikeas
                Udik, Bogor Regency, Jawa Barat<br> Tel/Fax: +62 812-9902-0971 · Email: info@fasyasolusindo.com</div>
            <br>
        </div>
    </div>

    <div class="separator"></div>

    <div class="meta">
        <div class="left">
            <table>
                <tr>
                    <td class="label">Hal</td>
                    <td class="value">: {{ $hal ?? 'Peminjaman Asset' }}</td>
                </tr>
            </table>
        </div>

        <div class="right">
            {{-- format tanggal: misal "Jakarta, 25 Juni 2019" --}}
            <div>{{ $lokasi ?? 'Jakarta' }},
                {{ \Carbon\Carbon::parse($tanggal_surat ?? now())->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    <div class="content">
        <p>Kepada Yth.</p>
        <p><strong>{{ $penerima ?? 'Manajemen PT. Fasya Pratama Solusindo' }}</strong></p>
        <p>Di Tempat</p>
        <br>
        <p>Assalamu’alaikum Wr. Wb.</p>

        <p>{{ $intro ?? 'Dengan ini, kami menginformasikan bahwa telah terjadi permintaan peminjaman asset. Berikut rincian peminjaman yang dimaksud:' }}
        </p>

        <div class="detail">
            <table>
                <tr>
                    <td class="label">Nama Peminjam</td>
                    <td class="value">: {{ $peminjaman->nama_peminjam }}</td>
                </tr>

                <tr>
                    <td class="label">Nama Asset</td>
                    <td class="value">
                        :
                        @if (!empty($assetsText))
                            {{ $assetsText }}
                        @else
                            -
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="label">Tanggal Pinjam</td>
                    <td class="value">:
                        {{ $peminjaman->tanggal_pinjam
                            ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y')
                            : '-' }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Keperluan</td>
                    <td class="value">: {{ $peminjaman->keperluan ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <p>{{ $closing ?? 'Dengan ini saya menyatakan bahwa seluruh barang yang tercantum di atas telah diterima dalam kondisi baik dan lengkap. Apabila di kemudian hari ditemukan adanya kerusakan atau kehilangan, saya bersedia untuk mengganti dengan barang baru yang setara dan dalam kondisi baik, sesuai dengan ketentuan yang berlaku.' }}
        </p>
        <br><br><br><br><br>
        <div class="signature d-flex justify-content-between">
            <div class="block text-start">
                <div>Peminjam,</div> <br>
                {{-- <img src="{{ asset('images/ttd_kiri.png') }}" alt="ttd" style="width:140px; margin-top:12px;"> --}}
                <div class="name mt-5">{{ $peminjaman->nama_peminjam }}</div>
            </div>

            <div class="block text-end">
                <div>Yang mengetahui,</div> <br>
                {{-- <img src="{{ asset('images/ttd_kanan.png') }}" alt="ttd" style="width:140px; margin-top:12px;"> --}}
                <div class="name mt-5">Manajemen</div>
            </div>
        </div>

    </div>

    <div class="footer">
        {{-- opsional catatan kecil --}}
        <small>{{ $footer ?? '' }}</small>
    </div>

</body>

</html>
