<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $letterRequest->template->name ?? 'Surat Resmi Desa' }}</title>
    <style>
        @page {
            margin: 2.5cm 2cm 2cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000000;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: normal;
            text-transform: uppercase;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 10pt;
            font-style: italic;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h2 {
            margin: 0;
            font-size: 14pt;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title p {
            margin: 5px 0 0 0;
            font-size: 11pt;
        }
        .content {
            margin-bottom: 20px;
            text-align: justify;
        }
        .table-data {
            width: 100%;
            margin: 15px 0 15px 20px;
            border-collapse: collapse;
        }
        .table-data td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .table-data td.label {
            width: 180px;
        }
        .signature-box {
            width: 100%;
            margin-top: 40px;
        }
        .signature-right {
            float: right;
            width: 250px;
            text-align: center;
        }
        .qr-code {
            margin: 10px auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>PEMERINTAH KABUPATEN NAGEKEO<br />KECAMATAN SOA</h3>
        <h1>DESA MENGERUDA</h1>
        <p>Alamat: Jl. Raya Mengeruda, Soa, Kabupaten Nagekeo, Nusa Tenggara Timur</p>
    </div>

    <div class="title">
        <h2>{{ $letterRequest->template->name }}</h2>
        <p>Nomor: 140 / ES / MGR / {{ date('m') }} / {{ date('Y') }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini Kepala Desa Mengeruda, Kecamatan Soa, Kabupaten Nagekeo, dengan ini menerangkan bahwa:</p>

        <table class="table-data">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: <strong>{{ strtoupper($letterRequest->user->name) }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td>: {{ $letterRequest->user->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. Telepon</td>
                <td>: {{ $letterRequest->user->phone ?? '-' }}</td>
            </tr>
            @if(is_array($letterRequest->form_data))
                @foreach($letterRequest->form_data as $key => $value)
                    <tr>
                        <td class="label">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                        <td>: {{ $value }}</td>
                    </tr>
                @endforeach
            @endif
        </table>

        <p>Orang tersebut di atas adalah benar-benar penduduk/warga Desa Mengeruda yang berdomisili di wilayah Desa Mengeruda. Surat keterangan ini diterbitkan atas permintaan yang bersangkutan untuk keperluan resmi sesuai permohonan.</p>

        <p>Demikian surat keterangan ini diberikan agar dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature-box">
        <div class="signature-right">
            <p>Mengeruda, {{ \Carbon\Carbon::parse($letterRequest->updated_at)->locale('id')->translatedFormat('d F Y') }}<br />An. Kepala Desa Mengeruda</p>
            
            <div class="qr-code">
                @if(isset($qrCodeSvg))
                    {!! $qrCodeSvg !!}
                @elseif(isset($qrCodeBase64))
                    <img src="{{ $qrCodeBase64 }}" alt="QR Code Signature" style="width: 100px; height: 100px;" />
                @endif
            </div>

            <p style="font-size: 9pt; color: #555;">Dokumen ini ditandatangani secara elektronik. Verifikasi keaslian melalui pemindaian QR Code di atas.</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
