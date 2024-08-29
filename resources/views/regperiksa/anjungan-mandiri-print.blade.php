<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script>
        function printPage() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 1000);
        }
        window.onload = printPage;
    </script>
</head>
<style>
    @media print {
        @page {
            margin: 3px;
        }

        body {
            margin: 3px;
        }
    }

    .text-center {
        text-align: center;
    }

    /* .small-text {
        font-size: 14px;
    } */

    .medium-text {
        font-size: 16px;
    }

    .large-text {
        font-size: 26px;
    }

    .extra-large-text {
        font-size: 32px;
    }

    .bold-hr {
        border: 1px;
        border-top: 2px solid black;
        width: 100%;
    }

    td {
        vertical-align: top;
    }

    body {
        font-family: 'Arial', sans-serif;
    }
</style>

<body>
    <br>
    <table width="350px">
        <tr>
            <td class="text-center">
                {{ $setting->nama_instansi }}
            </td>
        </tr>
        <tr>
            <td class="text-center">
                {{ $setting->alamat_instansi }}, {{ $setting->kabupaten }}
            </td>
        </tr>
        <tr>
            <td class="text-center">
                {{ $setting->kontak }}
                <hr class="bold-hr">
            </td>
        </tr>
        <tr>
            <td class="text-center">
                BUKTI REGISTRASI PENDAFTARAN
            </td>
        </tr>
    </table>
    <table width="350px">
        <tr>
            <td class="small-text v-top" width="100px">Tanggal</td>
            <td class="small-text v-top">:</td>
            <td class="small-text">{{ $pasien->tgl_registrasi . ' ' . $pasien->jam_reg }}</td>
        </tr>
        <tr>
            <td class="small-text v-top">No. Antrian</td>
            <td class="small-text v-top">:</td>
            <td class="large-text">{{ $pasien->no_reg }}</td>
        </tr>
        <tr>
            <td class="small-text v-top">Penjamin </td>
            <td class="small-text v-top">:</td>
            <td class="small-text">{{ $pasien->png_jawab }}</td>
        </tr>
        <tr>
            <td class="small-text v-top">Nama </td>
            <td class="small-text v-top">:</td>
            <td class="small-text">{{ $pasien->nm_pasien }}</td>
        </tr>
        <tr>
            <td class="small-text v-top">No RM </td>
            <td class="small-text v-top">:</td>
            <td class="small-text">{{ $pasien->no_rkm_medis }}</td>
        </tr>
        <tr>
            <td class="small-text v-top">Alamat </td>
            <td class="small-text v-top">:</td>
            <td class="small-text v-top">{{ $pasien->alamat }}</td>
        </tr>
        <tr>
            <td class="small-text v-top">Umur </td>
            <td class="small-text v-top">:</td>
            <td class="small-text">{{ $pasien->umur }}</td>
        </tr>
        <tr>
            <td class="small-text v-top">Ruangan </td>
            <td class="small-text v-top">:</td>
            <td class="small-text">{{ $pasien->nm_poli }}</td>
        </tr>
        <tr>
            <td class="small-text v-top">Dokter </td>
            <td class="small-text v-top">:</td>
            <td class="small-text">{{ $pasien->nm_dokter }}</td>
        </tr>
    </table>
    <table width="350px">
        <tr>
            <td class="text-center small-text">
                <hr class="bold-hr">
                Terimakasih Atas Kepercayaan Anda Kepada <br>{{ $setting->nama_instansi }}
            </td>
        </tr>
    </table>
</body>

</html>