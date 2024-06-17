{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN CBT LARAVEL</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* public/css/styles.css */

        body {
            background-image: url('{{ asset('img/background.png') }}'); /* Use asset() helper for Laravel */
            background-size: cover; /* Scale the background image as large as possible */
            background-position: center; /* Center the background image */
            background-repeat: no-repeat; /* Do not repeat the background image */
            height: 100%; /* Ensures full viewport height */
            margin: 0; /* Remove default margin */
            width:100%;
            font-family: 'Arial', sans-serif; /* Optional: Set a default font family */
        }

        .card {
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .card-body {
            text-align: center;
            padding: 30px;
            background-color: #fff;
        }

        .card-body img {
            max-width: 100%;
            height: auto;
            transition: transform 0.3s ease-in-out;
        }

        .card-body img:hover {
            transform: scale(1.1);
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

        .qr-code-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 300px;
            width: 300px;
            margin: 0 auto;
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Silahkan Login Terlebih Dahulu</h2>
                    </div>
                    <div class="card-body">
                        <div class="qr-code-container" id="qrcode">
                            <!-- QR code content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        // URL to encode as QR code
           var text="{{ route('student.check_login') }}?token={{ urlencode($qrCodeData) }}";

            // Generate QR code with qrcode.js
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                text: text,
                width: 260,
                height: 260
            });
        });
    </script>
</body>
</html> --}}


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('css/toastify.min.css') }}">

    <style>
        .card {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom styles for dark theme */
        .card-body {
            background-color: #343a40;
            /* Dark background color */
        }

        .form-control {
            background-color: #495057;
            /* Darker background for form controls */
            color: #f8f9fa;
            /* Light text color */
            border: 1px solid #6c757d;
            /* Border color to match dark theme */
        }

        .form-check-input {
            background-color: #495057;
            /* Dark background for checkboxes */
            border-color: #6c757d;
            /* Border color for checkboxes */
        }

        .btn-login {
            background-color: #6c757d;
            /* Darker button background */
            color: #f8f9fa;
            /* Light text color */
        }

        .link-secondary {
            color: #adb5bd;
            /* Lighter link color for better contrast */
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

        .qr-code-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 300px;
            width: 300px;
            margin: 0 auto;
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>

<body>
    <section class="bg-light p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xxl-11">
                    <div class="card border-dark shadow-sm rounded bg-dark text-light">
                        <div class="row g-0">
                            <div class="col-12 col-md-6">
                                <img class="img-fluid rounded-start object-fit-cover" loading="lazy"
                                    src="{{ asset('/img/background.jpg') }}" alt="Welcome back you've been missed!"
                                    style="height: 100%">
                            </div>
                            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                                <div class="col-12 col-lg-11 col-xl-10">
                                    <div class="card-body p-3 p-md-4 p-xl-5">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-5">
                                                    <div class="text-center mb-4">
                                                        <a href="#!">
                                                            {{-- <img src="{{ asset('/image/logo.webp') }}"
                                                                alt="BootstrapBrain Logo" width="175" height="175"> --}}
                                                        </a>
                                                    </div>
                                                    <h4 class="text-center">Welcome back you've been missed!</h4>

                                                </div>
                                            </div>
                                        </div>

                                        <form method="POST" action="{{ route('student.check_login') }}"
                                            id="sign_in_form">
                                            @csrf

                                                            <div class="card-body">
                                                                <div class="qr-code-container" id="qrcode">
                                                                    <!-- QR code content -->
                                                                </div>
                                                            </div>


                                        </form>
                                        <div class="row">
                                            <div class="col-12">
                                                <div
                                                    class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-center mt-5">
                                                    <a href="#!" class="link-secondary text-decoration-none">Forgot
                                                        password</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/toastify-js.js') }}"></script>

    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        // URL to encode as QR code
           var text="{{ route('student.check_login') }}?token={{ urlencode($qrCodeData) }}";

            // Generate QR code with qrcode.js
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                text: text,
                width: 260,
                height: 260
            });
        });
    </script>
    <script>
        @if (session('toast'))
            toastr.{{ session('toast.type') }}("{{ session('toast.message') }}", "{{ session('toast.title') }}");
        @endif
    </script>
    <script>
        $(document).ready(function() {
            $("#sign_in_form").submit(function(event) {
                event.preventDefault(); // Prevent the default form submission

                var username = $("#username").val();
                var password = $("#password").val();
                var token = $("meta[name='csrf-token']").attr("content");

                if (username.length == "") {
                    Toastify({
                        text: "Alamat Username Wajib Diisi !",
                        duration: 3000,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        close: true
                    }).showToast();
                } else if (password.length == "") {
                    Toastify({
                        text: "Password Wajib Diisi !",
                        duration: 3000,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        close: true
                    }).showToast();
                } else {
                    $.ajax({
                        url: "{{ route('student.check_login') }}",
                        type: "POST",
                        dataType: "JSON",
                        cache: false,
                        headers: {
                            'X-CSRF-TOKEN': token, // Include CSRF token in request headers
                            'Content-Type': 'application/json' // Set Content-Type to JSON
                        },
                        data: JSON.stringify({
                            "username": username,
                            "password": password,
                        }),
                        success: function(response) {
                            // Check if the login was successful
                            if (response.success) {
                                // Show success message
                                Toastify({
                                    text: response.message,
                                    duration: 3000,
                                    gravity: "top", // `top` or `bottom`
                                    position: "right", // `left`, `center` or `right`
                                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                    close: true
                                }).showToast();
                                setTimeout(function() {
                                    window.location.href = "{{ route('home') }}";
                                }, 3000); // Redirect after 3 seconds
                            } else {
                                // Show error message
                                Toastify({
                                    text: response.message,
                                    duration: 3000,
                                    gravity: "top", // `top` or `bottom`
                                    position: "right", // `left`, `center` or `right`
                                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                    close: true
                                }).showToast();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr, status, error, 'masuk sini');
                            // Show error message
                            Toastify({
                                text: `<strong>Server Error</strong><br>${xhr.responseJSON.message}`,
                                duration: 3000,
                                gravity: "top", // `top` or `bottom`
                                position: "right", // `left`, `center` or `right`
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                close: true,
                                escapeMarkup: false // Allows HTML content in the text
                            }).showToast();
                        }
                    });
                }
            });

        });
    </script>
</body>

</html>
