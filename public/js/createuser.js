$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Get the current URL path
    var urlPath = window.location.pathname;

    // Split the URL path by '/'
    var urlParts = urlPath.split('/');

    // Extract the user ID (assuming it's the third segment in the URL)
    var userId = urlParts[2];

    // Now you have the userId, you can use it in your AJAX request or any other logic
    console.log('User ID:', userId);


    var formContent = document.createElement("div");
    formContent.innerHTML = `
    <form id="formUser" class="form">
        <!--begin::Scroll-->
        <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
            <input type="hidden" name="id" id="id" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Input ID" />
            <div class="row">
                <!-- Column 1 -->
                <div class="col-md-6">
                      <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">Username</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="username" id="username" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Username" />
                        <!-- Error message placeholder -->
                        <div id="username-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">Name</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="name" id="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter your name" />
                        <!-- Error message placeholder -->
                        <div id="name-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">Email</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="email" name="email" id="email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" />
                        <!-- Error message placeholder -->
                        <div id="email-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group for password-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">Password</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter your password" />
                            <button type="button" id="generatePassword" class="btn btn-primary btn-sm">
                                <i class="fas fa-random"></i>
                            </button>
                            <button type="button" id="togglePassword" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <!-- Error message placeholder -->
                        <div id="password-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">Confirm Password</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div class="input-group">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Re-enter your password" />
                        </div>
                        <!-- Error message placeholder -->
                        <div id="confirm-password-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>

                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">Departments</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div class="input-group">
                            <select id="departments" name="departments" class="form-control form-control-solid mb-3 mb-lg-0">
                                <option value="">Select a department</option>
                            </select>
                        </div>
                        <!-- Error message placeholder -->
                        <div id="departments-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>

                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">Jabatan</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <div class="input-group">
                            <select id="selectJabatan" name="jabatan" class="form-control form-control-solid mb-3 mb-lg-0">
                                <option value="">Select a jabatan</option>
                            </select>
                        </div>
                        <!-- Error message placeholder -->
                        <div id="jabatan-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>

                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">No Handphone</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="no_handphone" id="no_handphone" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="0812345678" />
                        <!-- Error message placeholder -->
                        <div id="no_handphone_error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>
                     <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Status</label>
                        <div class="form-group mt-3">
                            <div class="pretty-checkbox">
                                <input type="checkbox" id="toggleActive" name="status" />
                                <label for="toggleActive">Active</label>
                            </div>
                        </div>
                        <!-- Error message placeholder -->
                        <div id="status_error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-md-6">
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">NIK</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="nik" id="nik" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="1111222233334444" />
                        <!-- Error message placeholder -->
                        <div id="nik-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>

                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">Join Date</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="date" name="join_date" id="join_date" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Please Input Join Date" />
                        <!-- Error message placeholder -->
                        <div id="join_date_error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>

                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">Address</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <textarea name="address" id="address" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter your address"></textarea>
                        <!-- Error message placeholder -->
                        <div id="address-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>

                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="required fw-semibold fs-6 mb-2">About Us</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <textarea name="about_us" id="about_us" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Tell us about yourself"></textarea>
                        <!-- Error message placeholder -->
                        <div id="about_us-error" class="text-danger"></div>
                        <!--end::Input-->
                    </div>

                     <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Cabang</label>
                        <select id="cabang" name="cabang" class="form-control form-control-solid mb-3 mb-lg-0 "></select>
                        <div id="cabang-error" class="text-danger"></div>
                    </div>

                    <div class="col-md-6 mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Upload Photo</label>
                        <div class="drop-zone">
                            <span class="drop-zone__prompt">Drag & Drop your photo here or click to upload</span>
                            <input type="file" name="photo" id="photo" class="drop-zone__input" accept="image/*">
                        </div>
                        <!-- Error message placeholder -->
                        <div id="photo-error" class="text-danger"></div>
                    </div>

                </div>
            </div>


        </div>
        <!--end::Scroll-->
        <!--begin::Actions-->
        <div class="text-center pt-10">
            <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel" onclick="kembali()">Kembali</button>
            <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                <span class="indicator-label">Submit</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
`;




    // Append the form content to the element with the ID 'mdlFormContent'
    document.getElementById("formUsers").appendChild(formContent);
    fetchDepartments();
    fetchJabatan();
    fetchCabang();

    document.querySelectorAll('.drop-zone__input').forEach(inputElement => {
        const dropZoneElement = inputElement.closest('.drop-zone');

        dropZoneElement.addEventListener('click', () => {
            inputElement.click();
        });

        inputElement.addEventListener('change', () => {
            if (inputElement.files.length) {
                updateThumbnail(dropZoneElement, inputElement.files[0]);
            }
        });

        dropZoneElement.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZoneElement.classList.add('drop-zone--over');
        });

        ['dragleave', 'dragend'].forEach(type => {
            dropZoneElement.addEventListener(type, () => {
                dropZoneElement.classList.remove('drop-zone--over');
            });
        });

        dropZoneElement.addEventListener('drop', (e) => {
            e.preventDefault();

            if (e.dataTransfer.files.length) {
                inputElement.files = e.dataTransfer.files;
                updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
            }

            dropZoneElement.classList.remove('drop-zone--over');
        });
    });


    $("#formUser").validate({
        rules: {
            username: {
                required: true,
                minlength: 7,
                maxlength: 10,
            },
            name: {
                required: true,
                minlength: 8,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 8,
                strongPassword: true, // Custom rule for strong password
            },
            confirm_password: {
                required: true,
                equalTo: "#password", // Validation to match password and confirm password
            },
            departments: {
                required: true,
            },
            jabatan: {
                required: true,
            },
            cabang: {
                required: true,
            },
            no_handphone: {
                required: true,
                minlength: 12,
                maxlength: 14,
                digits: true
            },
            nik: {
                required: true,
                minlength: 7,
                maxlength: 10,
                digits: true
            },
            join_date: {
                required: true,
                date: true
            }
        },
        messages: {
            username: {
                required: "Please enter a username name",
                minlength: "Username must be at least 7 characters long",
                maxlength: "Username must be exactly 10 digits long",
            },
            name: {
                required: "Please enter a full name",
                minlength: "Full name must be at least 8 characters long",
            },
            email: {
                required: "Please enter an email address",
                email: "Please enter a valid email address",
            },
            password: {
                required: "Please enter a password",
                minlength: "Password must be at least 8 characters long",
                strongPassword: "Password must contain at least one uppercase letter, one lowercase letter, one digit, and one of the following characters: @",
            },
            confirm_password: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match",
            },
            departments: {
                required: "Please select departments",
            },
            jabatan: {
                required: "Please select jabatan",
            },
            cabang: {
                required: "Please select cabang",
            },
            no_handphone: {
                required: "Please enter your phone number",
                minlength: "Phone number must be at least 14 digits long",
                digits: "Phone number must contain only digits"
            },
            nik: {
                required: "Please enter your NIK",
                minlength: "NIK must be exactly 7 digits long",
                maxlength: "NIK must be exactly 10 digits long",
                digits: "NIK must contain only digits"
            },
            join_date: {
                required: "Please enter your join date",
                date: "Please enter a valid date"
            }
        },
        errorPlacement: function (error, element) {
            var name = element.attr("name");
            error.appendTo($("#" + name + "-error"));
        },
        submitHandler: function (form) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to submit the form?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Toastify({
                        text: "Form submitted successfully!",
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        duration: 3000,
                    }).showToast();

                    // Prepare form data
                    var formData = new FormData(form);

                    // Perform AJAX POST request
                    $.ajax({
                        type: 'POST',
                        url: '/users/store', // Replace with your Laravel route
                        data: formData,
                        processData: false, // Prevent jQuery from converting the data
                        contentType: false, // Set content type to false
                        success: function (response) {
                            Toastify({
                                text: "Form submitted successfully.",
                                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                duration: 3000,
                                close: true,
                                gravity: "bottom",
                                position: "right",
                            }).showToast();

                            setTimeout(function() {
                                window.location.href = '/users'; // Replace with your desired URL
                            }, 3000);
                        },
                        error: function (error) {
                            console.error('Error:', error);
                            Toastify({
                                text: "An error occurred while submitting the form.",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                duration: 3000,
                                close: true,
                                gravity: "bottom",
                                position: "right",
                            }).showToast();

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'An error occurred while submitting the form.',
                            });
                        }
                    });
                }
            });
        },
        invalidHandler: function (event, validator) {
            console.log(event, validator);
            let emptyFields = [];
            $.each(validator.errorList, function (index, error) {
                if (!$(error.element).val()) {
                    emptyFields.push(error.element);
                }
            });
            if (emptyFields.length > 0) {
                Toastify({
                    text: "Please fill out all required fields.",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    duration: 3000,
                }).showToast();
            } else {
                Toastify({
                    text: "Please correct the errors in the form.",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    duration: 3000,
                }).showToast();
            }
        },
    });


    // Add custom method for strong password validation
    $.validator.addMethod("strongPassword", function (value, element) {
        return this.optional(element) ||
            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@])[A-Za-z\d@]{8,}$/.test(value);
    }, "Password must contain at least one uppercase letter, one lowercase letter, one digit, and one of the following characters: @");

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const passwordIcon = this.querySelector('i');
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = "password";
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    });

    // Generate password
    document.getElementById('generatePassword').addEventListener('click', function () {
        const password = generatePassword();
        document.getElementById('password').value = password;

        // Display a toast notification for the generated password
        Toastify({
            text: "Password generated successfully!",
            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
            duration: 3000,
        }).showToast();
    });

    if(userId != "create"){
        setTimeout(() => {
            fetchUserData(userId);
        }, 3000);
    }

    $('#toggleActive').change(function() {
        var isActive = $(this).is(':checked');
    });

});

function updateThumbnail(dropZoneElement, file) {
    let thumbnailElement = dropZoneElement.querySelector('.drop-zone__thumb');

    // Remove prompt
    if (dropZoneElement.querySelector('.drop-zone__prompt')) {
        dropZoneElement.querySelector('.drop-zone__prompt').remove();
    }

    // First time - there is no thumbnail element, so lets create it
    if (!thumbnailElement) {
        thumbnailElement = document.createElement('div');
        thumbnailElement.classList.add('drop-zone__thumb');
        dropZoneElement.appendChild(thumbnailElement);
    }

    thumbnailElement.dataset.label = file.name;

    // Show thumbnail for image files
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();

        reader.readAsDataURL(file);
        reader.onload = () => {
            thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
        };
    } else {
        thumbnailElement.style.backgroundImage = null;
    }
}


function kembali() {
    window.location.href = "/users/index"; // Replace with your target URL
}




function fetchUserData(userId) {
    $.ajax({
        url: '/users/' + userId + '/dataEdit',
        method: 'GET',
        success: function(response) {
            $('#id').val(response.id);
            $('#username').val(response.username);
            $('#nik').val(response.nik);
            $('#join_date').val(response.join_date);
            $('#address').val(response.alamat);
            $('#about_us').val(response.about_us);

            $('#name').val(response.name);
            $('#email').val(response.email);
            $('#no_handphone').val(response.phone_number);
            $('#password').val(response.password_show);
            $('#confirm_password').val(response.password_show);

            $('#departments').val(response.kode_dept).trigger('change');
            $('#selectJabatan').val(response.kode_jabatan).trigger('change');
            if (response.status === 'y') {
                $('input[name="status"]').prop('checked', true);
            } else {
                $('input[name="status"]').prop('checked', false);
            }
            $('#cabang').val(response.kode_cabang).trigger('change');

        },
        error: function(xhr, status, error) {
            console.error('Error fetching user data:', error);
        }
    });
}


function showError(message) {
    const errorElement = document.getElementById('departments-error');
    errorElement.textContent = message;
}

// Function to generate a random password
function generatePassword() {
    const length = 12;
    const charset =
        "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
    let password = "";
    for (let i = 0, n = charset.length; i < length; ++i) {
        password += charset.charAt(Math.floor(Math.random() * n));
    }
    return password;
}

function fetchCabang() {
    fetch('/cabang/data')  // Replace with your actual API endpoint
        .then(response => response.json())
        .then(data => {
            if (data.items) {
                populateCabang(data.items);
            } else {
                showError('No cabang found.');
            }
        })
        .catch(error => {
            showError('Error fetching cabang.');
        });
}

function fetchJabatan() {
    fetch('/jabatan/data')  // Replace with your actual API endpoint
        .then(response => response.json())
        .then(data => {
            if (data.items) {
                populateJabatan(data.items);
            } else {
                showError('No jabatan found.');
            }
        })
        .catch(error => {
            showError('Error fetching jabatan.');
        });
}

function fetchDepartments() {
    fetch('/departments/data')  // Replace with your actual API endpoint
        .then(response => response.json())
        .then(data => {
            if (data && Array.isArray(data)) {
                populateDepartments(data);
            } else {
                showError('No departments found.');
            }
        })
        .catch(error => {
            console.error('Error fetching departments:', error);
            showError('Error fetching departments.');
        });
}

function populateJabatan(jabatan) {
    const jabatanDropdown = document.getElementById('selectJabatan');
    jabatan.forEach(jabatan => {
        const option = document.createElement('option');
        option.value = jabatan.id;  // Assuming each department has an 'id' field
        option.textContent = jabatan.name;  // Assuming each department has a 'name' field
        jabatanDropdown.appendChild(option);
    });
}

function populateCabang(cabang) {
    const cabangDropdown = document.getElementById('cabang');
    const namesSet = new Set();

    cabang.forEach(cabang => {
        if (!namesSet.has(cabang.name)) {  // Check if the name is already added
            const option = document.createElement('option');
            option.value = cabang.id;  // Assuming each cabang has an 'id' field
            option.textContent = cabang.name;  // Assuming each cabang has a 'name' field
            cabangDropdown.appendChild(option);
            namesSet.add(cabang.name);  // Add the name to the set
        }
    });
}


// Function to populate departments dropdown
function populateDepartments(departments) {
    const departmentsDropdown = document.getElementById('departments');
    departments.forEach(department => {
        const option = document.createElement('option');
        option.value = department.id;  // Assuming each department has an 'id' field
        option.textContent = department.name;  // Assuming each department has a 'name' field
        departmentsDropdown.appendChild(option);
    });
}



