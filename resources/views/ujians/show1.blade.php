<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Ujian</title>
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
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .card-header {
            background: linear-gradient(90deg, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 1) 100%);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            padding: 20px;
            text-align: center;
        }
        .card-body {
            padding: 20px;
        }
        .info-item {
            font-size: 18px;
            margin: 10px 0;
        }
        .info-item strong {
            display: inline-block;
            width: 200px;
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        Detail Ujian
                    </div>
                    <div class="card-body">
                        <div class="info-item"><strong>Nama Ujian:</strong> {{ $ujian->nama }}</div>
                        <div class="info-item"><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y H:i') }}</div>
                        <div class="info-item"><strong>Durasi:</strong> {{ $ujian->durasi }} menit</div>
                        <div class="info-item"><strong>Paket Soal:</strong> {{ $ujian->paketSoal->kode_paket }}</div>
                        <div class="info-item"><strong>Kode Paket:</strong> {{ $ujian->paketSoal->kode_paket }}</div>
                        <hr>
                        <h5 class="mt-4">Soal</h5>
                        <ul>
                            @foreach($ujian->paketSoal->soals as $soal)
                                <li>
                                    <strong>{{ $soal->pertanyaan }}</strong>
                                    {{-- <ul>
                                        @foreach($soal->jawabanPilihan as $jawaban)
                                            <li>{{ $jawaban }}</li>
                                        @endforeach
                                    </ul> --}}
                                    <strong>Jawaban Benar:</strong> {{ $soal->jawaban_benar }}
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-center mt-4">
                            <a href="{{ route('ujian.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Ujian List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
