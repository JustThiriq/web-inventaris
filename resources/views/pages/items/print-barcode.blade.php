<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cetak Barcode - {{ $item->name }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 1cm;
                font-family: Arial, sans-serif;
            }

            .barcode-container {
                text-align: center;
                margin-bottom: 1cm;
            }

            .barcode-label {
                font-size: 14pt;
                margin-top: 10px;
            }
        }

        .barcode-container {
            page-break-inside: avoid;
            text-align: center;
        }

        img {
            max-width: 100%;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="barcode-container">
        <img src="{{ $item->barcodeUrl }}" alt="Barcode">
        <div class="barcode-label">
            {{ $item->name }}<br>
            <strong>{{ $item->barcode }}</strong>
        </div>
    </div>
</body>

</html>
