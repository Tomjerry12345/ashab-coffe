<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Status Meja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome (optional kalau mau icon print) -->
    <link rel="stylesheet" href="fontawesome">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            justify-content: center;
        }

        .card {
            height: 110px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            font-weight: bold;
            color: #fff;
            text-align: center;
        }

        .terisi {
            background: #28a745;
        }

        .kosong {
            background: #dc3545;
        }

        .btn-print {
            display: block;
            width: 100%;
            max-width: 260px;
            margin: 12px auto 0 auto;
            padding: 12px;
            border: none;
            background: #000;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border-radius: 30px;
            cursor: pointer;
            text-align: center;
        }

        .btn-print:hover {
            opacity: 0.8;
        }

        @media (max-width:480px) {
            .card {
                font-size: 24px;
                height: 100px;
            }
        }
    </style>
</head>

<body>

    <h2>STATUS SEMUA MEJA ☕</h2>

    <div class="grid">
        @foreach($mejas as $meja)
        <div class="card {{ $meja->status === 'terisi' ? 'terisi' : 'kosong' }}">
            {{ $meja->nomorMeja }}
        </div>
        @endforeach
    </div>

</body>

</html>