<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #004d99;
        }
        .student-info {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #004d99;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .student-info p {
            margin: 5px 0;
            font-size: 16px;
        }
        .student-info strong {
            color: #004d99;
        }
        .qr-code {
            text-align: center;
        }
        .qr-code img {
            max-width: 150px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>QR Code for {{ $user->name }}</h1>
        <div class="student-info">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Class:</strong> {{ $student->rombel->nama_rombel ?? 'N/A' }}</p>
            <p><strong>NIS:</strong> {{ $student->nis  ?? 'N/A' }}</p>
            <p><strong>Gender:</strong>
                {{ $student->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' }}
            </p>
        </div>
        <div class="qr-code">
            <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code">
        </div>
    </div>
</body>
</html>
