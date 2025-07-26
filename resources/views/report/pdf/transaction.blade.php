<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.css" rel="stylesheet">
    <style>
        .text-center {
            text-align: center;
        }

        table {
            font-size: 8pt;
        }

        .table-header {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px;
            font-weight: bold;
            font-size: 12pt;
            text-align: center;
        }

        .table-content {
            border-spacing: 0;
            border: 1px solid #000;
        }

        .table-content th {
            border: 1px solid #000;
            padding: 5pt;
            font-weight: bold;
        }

        .table-content td {
            border: 1px solid #000;
            padding: 5pt;
        }
    </style>
</head>

<body>

    <table class="table-header">
        <tr>
            <td style="width: 25%">
                <img src="{{ base_path() . '/public/np.jpg' }}" alt="" class="img img-fluid"
                    style="width: 150px">
            </td>
            <td style="width: 50%">
                <table>
                    <tr>
                        <td class="text-center">PT PEMBANGKITAN JAWA BALI UNIT BISNIS JASA 0&M PAITON</td>
                    </tr>
                    <tr>
                        <td class="text-center">PJB INTEGRATED MANAGEMENT SYSTEM</td>
                    </tr>
                    <tr>
                        <td class="text-center">FORMULIR</td>
                    </tr>
                    <tr>
                        <td class="text-center">BON PERMINTAAN / PENGELUARAN BARANG (PLR)</td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr>
                        <td>Nomor Dokumen</td>
                        <td>:</td>
                        <td>FMO 0G.1.3.1</td>
                    </tr>
                    <tr>
                        <td>Tanggal Terbit</td>
                        <td>:</td>
                        <td>18 Oktober 2016</td>
                    </tr>
                    <tr>
                        <td>Revisi</td>
                        <td>:</td>
                        <td>00</td>
                    </tr>
                    <tr>
                        <td>Halaman</td>
                        <td>:</td>
                        <td>1 dari 1</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="margin-top: 2rem"></div>

    <table style="width: 100%">
        <tr>
            <td>
                <table>
                    <tr>
                        <td>No Permintaan</td>
                        <td>:</td>
                        <td>{{ $transaction->request_number }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ $transaction->created_at }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%">
                <table>
                    <tr>
                        <td>Type Permintaan</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Dibutuhkan Tanggal</td>
                        <td>:</td>
                        <td>{{ $transaction->request_date }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="margin-bottom: 2rem"></div>

    <table style="width: 100%;border: 1px solid #000;border-spacing:0">
        <tr>
            <td style="border: 1px solid #000;">
                <table>
                    <tr>
                        <td>Diminta Oleh (SPV)</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                </table>
            </td>
            <td style="border: 1px solid #000;">
                <table>
                    <tr>
                        <td>Kode Perkiraan / Kontrak</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>No. WO / Proyek</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Prioritas</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid #000;">
                <table>
                    <tr>
                        <td>Disetujui Oleh (Manager)</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                </table>
            </td>
            <td style="border: 1px solid #000;width: 65%">
                <table>
                    <tr>
                        <td>Bidang yang memerlukan</td>
                        <td>:</td>
                        <td>{{ $transaction->user?->bidang?->name }}</td>
                    </tr>
                    <tr>
                        <td>Untuk keperluan</td>
                        <td>:</td>
                        <td>{{ $transaction->description }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="margin-top: 2rem"></div>

    <table class="table-content">
        <thead>
            <tr>
                <th>No. Item</th>
                <th>Item Number Bin Lokasi</th>
                <th>Nama Barang / Spesifikasi / Manufacture / Part Number</th>
                <th>Qty satuan</th>
                <th>Jumlah Tersedia (SOH)</th>
                <th>Jumlah Diminta</th>
                <th>Jumlah Dilayani</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->details as $item)
                <tr>
                    <th>{{ $loop->index + 1 }}</th>
                    <th>{{ $item->item->warehouse->name }}</th>
                    <th>{{ $item->item->name }}</th>
                    <th>{{ $item->item->unit->name }}</th>
                    <th>{{ $item->quantityFormatted }}</th>
                    <th>{{ $item->quantityFormatted }}</th>
                    <th>{{ $item->quantityFormatted }}</th>
                    <th></th>
                </tr>
            @endforeach
        </tbody>


    </table>

    <div style="margin-top: 3rem;"></div>

    <table style="width: 100%; border: 2px solid #000; border-spacing: 0; font-size: 8pt">
        <tr>
            <td style="padding:5px;text-align:center;border-right: 2px solid #000;"><strong>Dikeluarkan / Diterima Oleh
                    <br />Supervisor Gudang</strong></td>
            <td style="padding:5px;text-align:center;border-right: 2px solid #000;"><strong>Tanggal <br/> Dikeluarkan /
                    Diterima</strong></td>
            <td style="padding:5px;text-align:center;border-right: 2px solid #000;"><strong>Diambil / Diterima
                    Oleh <br/> User Peminta,</strong></td>
            <td style="padding:5px;text-align:center;"><strong>Mengetahui</strong></td>
        </tr>
        <tr>
            <td style="padding:30px;text-align:center;border-right: 2px solid #000;"></td>
            <td style="padding:30px;text-align:center;border-right: 2px solid #000;"></td>
            <td style="padding:30px;text-align:center;border-right: 2px solid #000;"></td>
            <td style="padding:30px;text-align:center;"></td>
        </tr>
        <tr>
            <td style="padding:5px;text-align:center;border-right: 2px solid #000;">( ..................................
                )</td>
            <td style="padding:5px;text-align:center;border-right: 2px solid #000;"></td>
            <td style="padding:5px;text-align:center;border-right: 2px solid #000;">( ..................................
                )</td>
            <td style="padding:5px;text-align:center;">( .................................. )</td>
        </tr>
    </table>

</body>

</html>
