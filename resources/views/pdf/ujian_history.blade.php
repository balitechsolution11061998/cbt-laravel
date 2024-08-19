<!DOCTYPE html>
<html>
<head>
    <title>History Ujian</title>
</head>
<body>
    <h1>History Ujian</h1>
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>NIS</th>
                <th>Siswa Name</th>
                <th>Kelas</th>
                <th>Tanggal Ujian</th>
                <th>Mata Pelajaran</th>
                <th>Jumlah Benar</th>
                <th>Jumlah Salah</th>
                <th>Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ujianHistories as $history)
            <tr>
                <td>{{ $history->siswa_nis }}</td>
                <td>{{ $history->siswa_name }}</td>
                <td>{{ $history->kelas_name }}</td>
                <td>{{ $history->created_at }}</td>
                <td>{{ $history->nama_pelajaran }}</td>
                <td>{{ $history->jumlah_benar }}</td>
                <td>{{ $history->jumlah_salah }}</td>
                <td>{{ $history->total_nilai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
