<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Roboto', sans-serif;
        }
        .card {
            border-radius: 15px;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: #343a40;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            padding: 20px;
            text-align: center;
        }
        .card-body {
            padding: 40px;
        }
        .card-body p {
            font-size: 18px;
            margin-bottom: 15px;
            padding: 10px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
        }
        .card-body p:hover {
            background-color: #e9ecef;
        }
        .card-body p strong {
            font-family: 'Montserrat', sans-serif;
            min-width: 150px;
        }
        .card-body p i {
            margin-right: 15px;
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
            border-radius: 30px;
            padding: 10px 20px;
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-primary:hover {
            background-color: #495057;
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Hasil Ujian</h2>
                    </div>
                    <div class="card-body">
                        <p><i class="fas fa-book"></i><strong>&nbsp;Nama Ujian:</strong> {{ $hasilUjian->ujian->nama }}</p>
                        <p><i class="fas fa-list"></i><strong>&nbsp;Jumlah Soal:</strong> {{ $hasilUjian->ujian->paketSoal->soals->count() }}</p>
                        <p><i class="fas fa-check"></i><strong>&nbsp;Jumlah Benar:</strong> {{ $hasilUjian->jumlah_benar }}</p>
                        <p><i class="fas fa-times"></i><strong>&nbsp;Jumlah Salah:</strong> {{ $hasilUjian->jumlah_salah }}</p>
                        <p><i class="fas fa-percentage"></i><strong>&nbsp;Nilai:</strong> {{ $hasilUjian->nilai }}</p>
                        <a href="/ujian" class="btn btn-primary mt-4"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Ujian</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
