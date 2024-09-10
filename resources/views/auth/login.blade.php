<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Computer Based Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastify.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('/image/logo2.jpg')}}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');

body{
    font-family: 'Poppins', sans-serif;
    background: #ececec;
}

/*------------ Login container ------------*/

.box-area{
    width: 930px;
}

/*------------ Right box ------------*/

.right-box{
    padding: 40px 30px 40px 40px;
}

/*------------ Custom Placeholder ------------*/

::placeholder{
    font-size: 16px;
}

.rounded-4{
    border-radius: 20px;
}
.rounded-5{
    border-radius: 30px;
}

/*------------ For small screens------------*/

@media only screen and (max-width: 768px){

     .box-area{
        margin: 0 10px;
     }
     .left-box{
        height: 100px;
        overflow: hidden;
     }
     .right-box{
        padding: 20px;
     }

}

.rounded-image-container {
    border-radius: 10%; /* Makes the container a circle */
    overflow: hidden; /* Hides the overflow */
    width: 350px; /* Container size */
    height: 350px; /* Container size */
    display: flex; /* Centers the image */
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem; /* Space below the container */
    position: relative; /* For positioning the image */
}

.rounded-image {
    border-radius: 50%; /* Makes the image a circle */
    width: 80%; /* Adjust size relative to container */
    height: 80%; /* Adjust size relative to container */
    object-fit: cover; /* Ensures the image covers the container without distortion */
    position: absolute; /* Center within container */
}

.left-box-content {
    opacity: 0; /* Start hidden */
    transform: translateY(30px); /* Start from below */
    animation: slideUp 1.5s forwards; /* Animation */
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px); /* Start from below */
    }
    to {
        opacity: 1;
        transform: translateY(0); /* End at normal position */
    }
}

.spacing {
    margin-bottom: 2rem; /* Adjust the space between elements */
}

.text-animate {
    opacity: 0; /* Start hidden */
    animation: fadeIn 2s forwards, slideUp 1.5s forwards; /* Combine animations */
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.8); /* Scale down */
    }
    to {
        opacity: 1;
        transform: scale(1); /* Scale up */
    }
}
    </style>
</head>
<body>

    <!----------------------- Main Container -------------------------->

     <div class="container d-flex justify-content-center align-items-center min-vh-100">

    <!----------------------- Login Container -------------------------->

       <div class="row border rounded-5 p-3 bg-white shadow box-area">

    <!--------------------------- Left Box ----------------------------->

       <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #103cbe;">
            <div class="rounded-image-container left-box-content">
                <img src="{{ asset('image/logo2.png') }}" class="rounded-image">
            </div>
           <p class="text-white fs-2 text-animate" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">CBT</p>
           <small class="text-white text-wrap text-center text-animate" style="width: 17rem;font-family: 'Courier New', Courier, monospace;">Sistem Informasi Ujian Online Madrasah Aliyah Al Furqan</small>
       </div>

    <!-------------------- ------ Right Box ---------------------------->

       <div class="col-md-6 right-box">
          <div class="row align-items-center">
                <div class="header-text mb-4 spacing">
                     <h2>Selamat Datang</h2>
                     <p>COMPUTER BASIC TEST.</p>
                     {{-- <p class="fs-5">Welcome to our system</p> --}}
                </div>
                <form method="POST" action="{{ route('formlogin.check_login') }}" id="sign_in_form">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Please Insert Username" name="username" id="username" required>
                    </div>
                    <div class="input-group mb-1">
                        <input type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password" name="password" id="password" required>
                    </div>
                    {{-- <div class="input-group mb-5 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="formCheck">
                            <label for="formCheck" class="form-check-label text-secondary"><small>Remember Me</small></label>
                        </div>
                        <div class="forgot">
                            <small><a href="#">Forgot Password?</a></small>
                        </div>
                    </div> --}}
                    <div class="input-group mb-3">
                        <input type="submit" class="submit btn btn-lg btn-primary w-100 fs-6" value="LOGIN">
                    </div>
                </form>
          </div>
       </div>

      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6LezFiAqAAAAAAIQqHIoGA8el3_Z-0gV64CGb_Ly"></script>    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/toastify-js.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#sign_in_form").submit(function(event) {
                event.preventDefault(); // Prevent the default form submission

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
