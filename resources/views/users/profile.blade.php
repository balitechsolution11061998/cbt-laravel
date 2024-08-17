<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Roboto', sans-serif;
        }
        .card {
            animation: fadeIn 1s ease-in-out;
            border-radius: 15px;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-50%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 4px solid #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(90deg, rgba(0,0,0,1) 0%, rgba(0,0,0,1) 100%);
            color: #fff;
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            padding: 20px;
        }
        .card-body {
            padding: 40px 20px;
            text-align: left;
        }
        .alert {
            font-size: 16px;
            font-weight: bold;
        }
        .info-item {
            font-size: 18px;
            margin: 10px 0;
        }
        .info-item strong {
            color: #000;
            font-family: 'Montserrat', sans-serif;
            display: inline-block;
            width: 200px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-family: 'Montserrat', sans-serif;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn i {
            margin-right: 5px;
        }
        #calendar {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger text-center" role="alert">
                    Periksa data anda terlebih dahulu.
                </div>
                <div class="card">
                    <div class="card-header">
                        PROFILE SISWA
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <img src="{{ $user->photo }}" alt="Profile Photo" class="profile-photo">
                        </div>
                        <div class="info-item"><strong>Name:</strong> {{ $user->name }}</div>
                        <div class="info-item"><strong>Email:</strong> {{ $user->email }}</div>
                        <div class="info-item">
                            <strong>NIS:</strong> {{ $user->siswa ? $user->siswa->nis : 'N/A' }}
                        </div>

                        <div class="info-item">
                            <strong>Kelas:</strong> {{ $user->siswa && $user->siswa->kelas ? $user->siswa->kelas->name : 'N/A' }}
                        </div>

                        <div class="info-item"><strong>Ujian:</strong>
                            @if($user->siswa->kelas->ujian->isNotEmpty())
                                <ul>
                                    @foreach($user->siswa->kelas->ujian as $ujian)
                                        <li>
                                            {{ $ujian->nama }} - {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y H:i') }}
                                            <br>
                                            <strong>Paket Soal:</strong> {{ $ujian->paketSoal->kode_paket }}
                                            <br>
                                            <strong>Mata Pelajaran:</strong> {{ $ujian->mataPelajaran->nama }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="info-item"><strong>Ujian Saat Ini:</strong>
                            @if($currentUjian && $currentUjian->isNotEmpty())
                                <ul>
                                    @foreach($currentUjian as $ujian)
                                        <li>
                                            {{ $ujian->nama }} - {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }}
                                            <br>
                                            <strong>Kode Paket:</strong> {{ $ujian->paketSoal->kode_paket }}
                                            <br>
                                            <strong>Nama Paket Soal:</strong> {{ $ujian->paketSoal->kode_paket }}
                                            <br>
                                            <strong>Durasi:</strong> <span id="countdown-{{ $ujian->id }}"></span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                Tidak ada ujian yang dimulai saat ini.
                            @endif
                        </div>
                        <div class="text-center mt-4">
                            @if($currentUjian && $currentUjian->isNotEmpty())
                                <button id="startExamButton" class="btn btn-primary btn-sm">
                                    <i class="fas fa-play"></i> Mulai Ujian
                                </button>
                            @else
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#calendarModal">
                                    <i class="fas fa-calendar"></i> Show Jadwal Ujian
                                </a>
                            @endif
                            <a href="#" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- {{ dd($user->siswa->rombel->ujian[0]->paketSoal->id) }} --}}
    <!-- Modal -->
    <div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="calendarModalLabel">Jadwal Ujian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach($user->siswa->kelas->ujian as $ujian)
                    {
                        title: '{{ $ujian->nama }}',
                        start: '{{ $ujian->waktu_mulai }}',
                        description: '{{ $ujian->paketSoal->kode_paket }} - {{ $ujian->paketSoal->kode_paket }}'
                    },
                    @endforeach
                ],
                eventDidMount: function(info) {
                    info.el.querySelector('.fc-event-title').innerHTML = '<i class="fas fa-calendar-alt"></i> ' + info.event.title;
                    var tooltip = new bootstrap.Tooltip(info.el, {
                        title: info.event.extendedProps.description,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                    info.el.addEventListener('mouseleave', function() {
                        tooltip.hide();
                    });
                }
            });
            var calendarModal = document.getElementById('calendarModal');
            calendarModal.addEventListener('shown.bs.modal', function() {
                calendar.render();
            });

            function showNotification(title, body) {
                if (Notification.permission === 'granted') {
                    new Notification(title, { body });
                }
            }

            // Check notification permission
            if (Notification.permission !== 'granted') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        checkForTodayExam();
                    }
                });
            } else {
                checkForTodayExam();
            }

            // Check if there's an exam today
            function checkForTodayExam() {
                @foreach($user->siswa->kelas->ujian as $ujian)
                    if (new Date('{{ $ujian->waktu_mulai }}').toDateString() === new Date().toDateString()) {
                        showNotification('Ujian Hari Ini', '{{ $ujian->nama }} - {{ $ujian->paketSoal->kode_paket }}');
                    }
                @endforeach
            }
            @if($currentUjian && $currentUjian->isNotEmpty())

            // Countdown function
            @foreach($currentUjian as $ujian)
            console.log(new Date("{{ $ujian->waktu_mulai }}").getTime());
            var countDownDate{{ $ujian->id }} = new Date("{{ $ujian->waktu_mulai }}").getTime();
            var x{{ $ujian->id }} = setInterval(function() {
                var now = new Date().getTime();
                var distance = Math.abs(countDownDate{{ $ujian->id }} - now);

                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("countdown-{{ $ujian->id }}").innerHTML = hours + "h " + minutes + "m " + seconds + "s ";

                if (distance < 0) {
                    clearInterval(x{{ $ujian->id }});
                    document.getElementById("countdown-{{ $ujian->id }}").innerHTML = "EXPIRED";
                }
            }, 1000);
            @endforeach
            @endif
        });
        @if($currentUjian && $currentUjian->isNotEmpty())

        document.getElementById('startExamButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Mulai Ujian',
                text: 'Anda yakin ingin memulai ujian?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Mulai',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the exam page
                    var nis = '{{ $user->siswa->id }}'; // Get the student's NIS
                    var ujianId = '{{ $currentUjian->first()->id }}'; // Assuming $currentUjian is not empty
                    var paketSoalId = '{{ $user->siswa->kelas->ujian[0]->paketSoal->id }}'; // Assuming $currentUjian is not empty
                    var url = '{{ url("ujian/start") }}/' + ujianId + '/' + nis+'/'+paketSoalId;
                    window.location.href = url;
                }
            });
        });
        @endif
    </script>
</body>
</html>
