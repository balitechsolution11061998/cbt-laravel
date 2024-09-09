<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Roboto', sans-serif;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            color: #fff !important;
        }

        .card {
            border-radius: 15px;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: #343a40;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 18px;
            padding: 15px;
        }

        .question-options input {
            margin-right: 10px;
        }

        .nav-button {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        .question-navigation {
            text-align: center;
            margin-top: 20px;
        }

        .question-navigation button {
            margin: 5px;
        }

        .end-exam {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            border-radius: 30px;
            /* Membuat tombol elips */
            padding: 10px 30px;
            /* Menyesuaikan padding */
            width: 100%;
            /* Menyesuaikan lebar */
        }

        .info-item {
            margin-bottom: 10px;
        }

        .profile-card {
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            position: relative;
            color: #fff;
            background: linear-gradient(135deg, rgba(52, 58, 64, 0.9), rgba(0, 0, 0, 0.5)), url('https://source.unsplash.com/1600x900/?education,exam');
            background-size: cover;
            background-position: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 15px;
            background: inherit;
            filter: blur(10px);
            z-index: -1;
        }

        .profile-card strong {
            font-family: 'Montserrat', sans-serif;
        }

        .profile-header {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .question-list li {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">UJIAN ONLINE</a>
            <button class="btn btn-warning ms-auto">LOGOUT</button>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-card">
                    <div class="profile-header">Profil Ujian</div>
                    <div class="info-item"><strong>Nama Ujian:</strong> {{ $ujian->nama }}</div>
                    <div class="info-item"><strong>Tanggal Mulai:</strong>
                        {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y H:i') }}</div>
                    <div class="info-item"><strong>Durasi:</strong> {{ $ujian->durasi }} menit</div>
                    <div class="info-item"><strong>Paket Soal:</strong> {{ $ujian->paketSoal->kode_paket }}</div>
                    <div class="info-item"><strong>Kode Paket:</strong> {{ $ujian->paketSoal->kode_paket }}</div>
                    <div class="info-item"><strong>Waktu Tersisa:</strong> <span id="countdown-timer"></span></div>
                </div>
            </div>


            <div class="col-md-8">
                <div id="question-card" class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span id="question-number">Soal No. 1</span>
                        <span id="countdown-timer"><i class="fas fa-clock"></i> <span id="time-remaining"></span></span>
                    </div>
                    <div class="card-body">
                        <p id="question-text">Hasil dari 4 log 8 + 4 log 32 adalah ...</p>
                        <img id="question-image" src="" alt="Question Image" style="display: none; max-width: 100%; height: auto;">
                        <div id="question-options" class="question-options">
                            <!-- Options will be inserted here by JavaScript -->
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-secondary nav-button" onclick="navigateQuestion(-1)">Sebelumnya</button>
                        <button class="btn btn-primary nav-button" onclick="navigateQuestion(1)">Selanjutnya</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body question-navigation">
                        <div class="mb-3">
                            <span id="answered-count" class="badge bg-success">0 dikerjakan</span>
                        </div>
                        <div id="question-buttons">
                            @foreach ($ujian->paketSoal->soals as $key => $soal)
                                <button class="btn btn-outline-primary" id="question-btn-{{ $key }}"
                                    onclick="showQuestion({{ $key }})">{{ $key + 1 }}</button>
                            @endforeach
                        </div>
                        <button class="btn btn-danger mt-3 end-exam">Akhiri Ujian</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const questions = @json($ujian->paketSoal->soals);
        const answeredQuestions = JSON.parse(localStorage.getItem('answeredQuestions')) || {};
        let currentQuestion = 0;

        function showQuestion(index) {
            currentQuestion = index;
            document.getElementById('question-number').textContent = `Soal No. ${index + 1}`;
            document.getElementById('question-text').textContent = questions[index].pertanyaan;

            // Show or hide image based on question type
            const imageElement = document.getElementById('question-image');
            console.log(questions[index],'masuk sini');

            if (questions[index].jenis === 'gambar') {
                imageElement.src = '/'.questions[index].pertanyaan_image; // Set image source
                imageElement.style.display = 'block'; // Show the image
                document.getElementById('question-options').innerHTML = ''; // Clear options for image questions
            } else {
                imageElement.style.display = 'none'; // Hide the image
                if (questions[index].jenis === 'essai') {
                    const answer = answeredQuestions[index] || '';
                    document.getElementById('question-options').innerHTML = `
                        <div class="form-group">
                            <label for="essay-answer">Jawaban:</label>
                            <textarea class="form-control" id="essay-answer" rows="4" onchange="markAnsweredEssay(${index})">${answer}</textarea>
                        </div>
                    `;
                } else {
                    const options = [
                        questions[index].pertanyaan_a,
                        questions[index].pertanyaan_b,
                        questions[index].pertanyaan_c,
                        questions[index].pertanyaan_d
                    ];

                    const optionsHtml = options.map((option, i) => `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="question${index}" id="option${String.fromCharCode(65 + i)}" ${answeredQuestions[index] === String.fromCharCode(65 + i) ? 'checked' : ''} onclick="markAnswered(${index}, '${String.fromCharCode(65 + i)}')">
                            <label class="form-check-label" for="option${String.fromCharCode(65 + i)}">
                                ${option}
                            </label>
                        </div>
                    `).join('');

                    document.getElementById('question-options').innerHTML = optionsHtml;
                }
            }
        }

        function markAnsweredEssay(index) {
            const answer = document.getElementById('essay-answer').value;
            answeredQuestions[index] = answer;
            localStorage.setItem('answeredQuestions', JSON.stringify(answeredQuestions));
            document.getElementById(`question-btn-${index}`).classList.remove('btn-outline-primary');
            document.getElementById(`question-btn-${index}`).classList.add('btn-warning');
            updateAnsweredCount();
        }

        function navigateQuestion(direction) {
            const newIndex = currentQuestion + direction;
            if (newIndex >= 0 && newIndex < questions.length) {
                showQuestion(newIndex);
            }
        }

        function markAnswered(index, option) {
            answeredQuestions[index] = option;
            localStorage.setItem('answeredQuestions', JSON.stringify(answeredQuestions));
            document.getElementById(`question-btn-${index}`).classList.remove('btn-outline-primary');
            document.getElementById(`question-btn-${index}`).classList.add('btn-warning');
            updateAnsweredCount();
        }

        function updateAnsweredCount() {
            const answeredCount = Object.keys(answeredQuestions).length;
            document.getElementById('answered-count').textContent = `${answeredCount} dikerjakan`;
        }

        function startCountdown(endTime) {
            const countdownElement = document.getElementById('countdown-timer');
            const countdownInterval = setInterval(function () {
                const now = new Date().getTime();
                const timeRemaining = Math.floor((endTime - now) / 1000);

                if (timeRemaining <= 0) {
                    clearInterval(countdownInterval);
                    countdownElement.innerHTML = 'Waktu Habis';
                    // Trigger exam submission or redirection here
                    alert('Waktu ujian telah habis!');
                    // Optionally, redirect to a completion page
                    // window.location.href = '/ujian/selesai';
                } else {
                    const minutes = Math.floor(timeRemaining / 60);
                    const seconds = timeRemaining % 60;
                    countdownElement.innerHTML = `${minutes} menit ${seconds < 10 ? '0' : ''}${seconds} detik`;
                }
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const examDuration = {{ $ujian->durasi }} * 60; // Exam duration in seconds
            const countdownElement = document.getElementById('countdown-timer');
            const storageKey = 'examCountdown';

            if (localStorage.getItem(storageKey)) {
                const savedData = JSON.parse(localStorage.getItem(storageKey));
                const now = new Date().getTime();

                if (savedData.endTime > now) {
                    startCountdown(savedData.endTime);
                } else {
                    countdownElement.innerHTML = 'Waktu Habis';
                    // Optionally handle exam timeout here
                }
            } else {
                const now = new Date().getTime();
                const endTime = now + (examDuration * 1000); // Calculate end time in milliseconds
                localStorage.setItem(storageKey, JSON.stringify({ endTime }));
                startCountdown(endTime);
            }

            window.addEventListener('beforeunload', function (event) {
                event.preventDefault();
                event.returnValue = 'Ujian belum selesai, apakah Anda yakin ingin meninggalkan halaman ini?';
                return 'Ujian belum selesai, apakah Anda yakin ingin meninggalkan halaman ini?';
            });

            showQuestion(0);
            Object.keys(answeredQuestions).forEach(index => {
                document.getElementById(`question-btn-${index}`).classList.remove('btn-outline-primary');
                document.getElementById(`question-btn-${index}`).classList.add('btn-warning');
            });
            updateAnsweredCount();

            document.querySelector('.end-exam').addEventListener('click', () => {
                const totalQuestions = questions.length;
                const answeredCount = Object.keys(answeredQuestions).length;
                const unansweredCount = totalQuestions - answeredCount;

                Swal.fire({
                    title: 'Akhiri Ujian?',
                    text: `Anda telah mengerjakan ${answeredCount} soal dan belum mengerjakan ${unansweredCount} soal. Apakah Anda yakin ingin mengakhiri ujian ini?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, akhiri',
                    cancelButtonText: 'Tidak, lanjutkan'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('/ujian/end', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    ujian_id: {{ $ujian->id }},
                                    user_id: {{ Auth::user()->id }},
                                    answeredQuestions
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Ujian diakhiri!', 'Ujian Anda telah diakhiri.', 'success')
                                        .then(() => {
                                            window.location.href = `/ujian/hasil-ujian/${data.hasil_ujian_id}`;
                                        });
                                } else {
                                    Swal.fire('Gagal!', 'Terjadi kesalahan saat mengakhiri ujian.', 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Gagal!', 'Terjadi kesalahan saat mengakhiri ujian.', 'error');
                            });
                    }
                });
            });
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
