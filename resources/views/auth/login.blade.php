<!DOCTYPE html>
<html>
<head>
    <title><?php echo $_ENV['APP_NAME']; ?></title>
    <!-- Site favicon -->
    <link rel="shortcut icon" href="{{ asset('login/images/favicon.ico')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700" rel="stylesheet">
    <!-- Icon Font -->
    <link rel="stylesheet" href="{{ asset('login/fonts/ionicons/css/ionicons.css') }}">
    <!-- Text Font -->
    <link rel="stylesheet" href="{{ asset('login/fonts/font.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('login/css/bootstrap.css') }}">
    <!-- Normal style CSS -->
    <link rel="stylesheet" href="{{ asset('login/css/style.css') }}">
    <!-- Normal media CSS -->
    <link rel="stylesheet" href="{{ asset('login/css/media.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastify.min.css') }}">
    <style>
        /* Add animation styles here */
        .fadeIn {
            animation: fadeInAnimation 1s ease forwards;
        }
        @keyframes fadeInAnimation {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <main class="cd-main">
        <section class="cd-section index visible ">
            <div class="cd-content style1">
                <div class="login-box d-md-flex align-items-center">
                    <h1 class="title" id="greeting">Good Morning</h1>
                    <h3 class="subtitle">Have a great journey ahead!</h3>
                    <div class="login-form-box fadeIn">
                        <div class="login-form-slider">
                            <!-- login slide start -->
                            <div class="login-slide slide login-style1">
                                <form method="POST" action="{{ route('formlogin.check_login') }}" id="sign_in_form">
                                    @csrf
                                    <div class="form-group">
                                        <label class="label">User name</label>
                                        <input type="text" class="form-control bg-dark text-light" name="username" id="username" placeholder="test ..." required>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Password</label>
                                        <input type="password" class="form-control bg-dark text-light" name="password" id="password" placeholder="12345" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="submit" value="Sign In">
                                    </div>
                                </form>


                            </div>
                            <!-- login slide end -->
                            <!-- signup slide start -->
                            <div class="signup-slide slide login-style1">
                                <div class="d-flex height-100-percentage">
                                    <div class="align-self-center width-100-percentage">
                                        <form>
                                            <div class="form-group">
                                                <label class="label">Name</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label class="label">Email</label>
                                                <input type="email" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label class="label">Password</label>
                                                <input type="password" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label class="label">Confirm Password</label>
                                                <input type="password" class="form-control">
                                            </div>
                                            <div class="form-group padding-top-15px">
                                                <input type="submit" class="submit" value="Sign Up">
                                            </div>
                                        </form>
                                        <div class="sign-up-txt">
                                            if you have an account? <a href="javascript:;" class="login-click">login</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- signup slide end -->
                            <!-- forgot password slide start -->
                            <div class="forgot-password-slide slide login-style1">
                                <div class="d-flex height-100-percentage">
                                    <div class="align-self-center width-100-percentage">
                                        <form>
                                            <div class="form-group">
                                                <label class="label">Enter your email address to reset your password</label>
                                                <input type="email" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" class="submit" value="Submit">
                                            </div>
                                        </form>
                                        <div class="sign-up-txt">
                                            if you remember your password? <a href="javascript:;" class="login-click">login</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- forgot password slide end -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <div id="cd-loading-bar" data-scale="1"></div>
    <!-- JS File -->
    <script src="js/modernizr.js"></script>
    <script type="text/javascript" src="{{ asset('login/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{ asset('login/js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('login/js/bootstrap.js')}}"></script>
    <script src="{{ asset('login/js/velocity.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('login/js/script.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/toastify-js.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>

    <script>
        @if (session('toast'))
            toastr.{{ session('toast.type') }}("{{ session('toast.message') }}", "{{ session('toast.title') }}");
        @endif
    </script>
       <script>
        document.addEventListener('DOMContentLoaded', function() {
    var greetingElement = document.getElementById('greeting');
    var hour = new Date().getHours();

    if (hour >= 5 && hour < 12) {
        greetingElement.textContent = 'Good Morning';
    } else if (hour >= 12 && hour < 18) {
        greetingElement.textContent = 'Good Afternoon';
    } else {
        greetingElement.textContent = 'Good Evening';
    }
});
    </script>
    <script>
        $(document).ready(function() {
            $("#sign_in_form").submit(function(event) {
                event.preventDefault(); // Prevent the default form submission
                grecaptcha.ready(function() {
                    grecaptcha.execute("{{ env('GOOGLE_RECAPTCHA_KEY') }}", {action: 'subscribe_newsletter'}).then(function(token) {
                        $('#contactUSForm').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                        $('#contactUSForm').unbind('submit').submit();
                    });;
                });
                var username = $("#username").val();
                var password = $("#password").val();
                var remember_me = $("#remember_me").val();

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
                        url: "{{ route('formlogin.check_login') }}",
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
