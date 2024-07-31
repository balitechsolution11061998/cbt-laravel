function createUser() {
    // Show a loading indicator using SweetAlert2
    Swal.fire({
        title: "Loading...",
        text: "Please wait while we redirect you.",
        didOpen: () => {
            Swal.showLoading();
        },
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
    });
    console.log("masuk sini nggak aaaa");
    // Simulate the process of opening the link to create a user
    setTimeout(function () {
        // Redirect to the create user link
        window.location.href = "/users/create";
    }, 2000); // Simulated delay for demonstration
}

$(document).ready(function () {
    fetchDataUser();
});

function fetchDataUser() {
    if ($.fn.DataTable.isDataTable("#users_table")) {
        $("#users_table").DataTable().destroy();
    }

    $("#users_table").DataTable({
        responsive: true, // Enable responsive extension
        processing: true,
        serverSide: true,
        ajax: {
            url: "/users/data",
            data: {
                name: $("#name").val(),
                department: $("#department").val(),
                cabang: $("#cabang").val(),
            },
            beforeSend: function () {
                $(".spinner").show();
            },
            complete: function () {
                $(".spinner").hide();
            },
        },
        columns: [
            { data: "id", name: "id" },
            {
                data: "username",
                name: "username",
                render: function (data, type, row) {
                    return '<i class="fas fa-user"></i> ' + data;
                },
            },
            {
                data: "name",
                name: "name",
                render: function (data, type, row) {
                    return '<i class="fas fa-user"></i> ' + data;
                },
            },
            {
                data: "email",
                name: "email",
                render: function (data, type, row) {
                    return (
                        '<i class="fas fa-envelope"></i> ' +
                        data +
                        ' <button class="btn btn-primary btn-sm kirim-email" onclick="sendAccountDetails(\'' +
                        data +
                        "', 'email')\"><i class=\"fas fa-paper-plane\"></i> Kirim Email</button>"
                    );
                },
            },
            {
                data: "password_show",
                name: "password_show",
                render: function (data, type, row) {
                    return `
                        <div class="password-container">
                            <input type="password" class="form-control form-control-sm" value="${data}" readonly />
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    `;
                },
            },
            {
                data: "kode_jabatan",
                name: "kode_jabatan",
                render: function (data, type, row) {
                    if (row.jabatan === null || row.jabatan === undefined) {
                        return "Belum memiliki jabatan";
                    } else {
                        return row.jabatan.kode_jabatan;
                    }
                },
            },
            {
                data: "phone_number",
                name: "phone_number",
                render: function (data, type, row) {
                    if (data === null || data === undefined) {
                        return "Belum memiliki No Handphone";
                    } else {
                        return row.phone_number;
                    }
                },
            },
            {
                data: "photo",
                name: "photo",
                render: function (data, type, row) {
                    if (
                        data === null ||
                        data === undefined ||
                        data.trim() === ""
                    ) {
                        return '<a href="/image/logo.png" data-fancybox="gallery"><img src="/image/logo.png" alt="Default Image" class="img-fluid" style="height: 100px;"></a>';
                    } else {
                        return (
                            '<a href="' +
                            data +
                            '" data-fancybox="gallery"><img src="' +
                            data +
                            '" alt="User Photo" class="img-fluid" style="height: 100px;"></a>'
                        );
                    }
                },
            },
            {
                data: "department",
                name: "department",
                render: function (data, type, row) {
                    if (
                        row.department === null ||
                        row.department === undefined
                    ) {
                        return "Belum memiliki department";
                    } else {
                        return row.department.kode_department;
                    }
                },
            },
            {
                data: "cabang",
                name: "cabang",
                render: function (data, type, row) {
                    if (row.cabang === null || row.cabang === undefined) {
                        return "Belum memiliki cabang";
                    } else {
                        return row.cabang.name;
                    }
                },
            },
            {
                data: "status",
                name: "status",
                render: function (data, type, row) {
                    if (data === "y") {
                        return '<span class="badge badge-success badge-sm" style="color: white;"><i class="fas fa-check" style="color: white;"></i> Active</span>';
                    } else {
                        return '<span class="badge badge-secondary badge-sm" style="color: white;"><i class="fas fa-times" style="color: white;"></i> Non-active</span>';
                    }
                },
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="editUser(${row.id})"><i class="fas fa-edit"></i> Edit</a></li>
                                <li><a class="dropdown-item" href="#" onclick="deleteUser(${row.id})"><i class="fas fa-trash"></i> Delete</a></li>
                                <li><a class="dropdown-item" href="#" onclick="resetPassword(${row.id})"><i class="fas fa-key"></i> Reset Password</a></li>
                                <li><a class="dropdown-item" href="#" onclick="setRolesToUser(${row.id})"><i class="fas fa-plus-circle"></i> Tambah Roles</a></li>
                                <li><a class="dropdown-item" href="#" onclick="setJamKerja(${row.nik})"><i class="fas fa-clock"></i> Set Jam Kerja</a></li>
                                <li><a class="dropdown-item" href="#" onclick="createQRCode(${row.id})"><i class="fas fa-qrcode"></i> Create QR Code</a></li>
                            </ul>
                        </div>
                    `;
                },
            }

        ],
        drawCallback: function () {
            var table = this.api();
            var body = $(table.table().body());

            // Create a new instance of mark.js
            var instance = new Mark(body[0]);

            if (
                $("#name").val() != undefined ||
                $("#department").val() != undefined ||
                $("#cabang").val() != undefined
            ) {
                // Remove previous highlights
                instance.unmark({
                    done: function () {
                        // Highlight the search terms
                        instance.mark($("#name").val());
                        instance.mark($("#department").val());
                        instance.mark($("#cabang").val());
                    },
                });
            }
        },
        initComplete: function () {
            var rows = this.api().rows({ page: "current" }).nodes();
            $(rows)
                .css("opacity", "0")
                .slideDown("slow")
                .animate({ opacity: 1 }, { duration: "slow" });
        },
    });

    $('[data-fancybox="gallery"]').fancybox({
        // Options if needed
    });

    // Re-initialize Fancybox after DataTable redraw (if using AJAX or other redraw methods)
    $("#users_table").on("draw.dt", function () {
        $('[data-fancybox="gallery"]').fancybox({
            // Options if needed
        });
    });

    // Toggle password visibility
    $(document).on("click", ".toggle-password", function () {
        const input = $(this).siblings("input");
        const type = input.attr("type") === "password" ? "text" : "password";
        input.attr("type", type);
        $(this).toggleClass("fa-eye fa-eye-slash");
    });
}

function createQRCode(userId) {
    Swal.fire({
        title: 'Generating QR Code...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `/users/${userId}/generate-qr-code`,
        method: 'GET',
        success: function(response) {
            Swal.close(); // Close the SweetAlert2 loading modal

            // Show the QR code in a custom modal with full height and black title
            Swal.fire({
                title: 'Login QR Code',
                html: `<img src="${response.qr_code_url}" alt="QR Code" class="qr-code-image">`,
                showCloseButton: true,
                confirmButtonText: 'Close',
                customClass: {
                    popup: 'swal2-custom-modal',
                    title: 'swal2-custom-title'
                },
                didOpen: () => {
                    // Set the height of the modal container
                    document.querySelector('.swal2-popup').style.height = '100vh';
                }
            });

        },
        error: function(error) {
            Swal.close(); // Close the SweetAlert2 loading modal

            Swal.fire({
                title: 'Error',
                text: 'Failed to generate QR code.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
        }
    });
}




function setJamKerja(nik) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    Swal.fire({
        title: "Set Jam Kerja",
        text: "Apakah Anda ingin mengatur jam kerja untuk karyawan ini?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, atur sekarang!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/users/konfigurasi/" + nik + "/setjamkerja", // Your route to the setjamkerja function
                method: "GET", // Or 'POST' if that's the case
                success: function (response) {
                    console.log(response, "response");
                    if (response.status === 'success') {
                        $("#mdlForm").modal('show');
                        $("#mdlFormTitle").html("Form Set Jam Kerja");

                        // Clear the modal content
                        $('#mdlFormContent').html("");

                        // Append the form HTML to the modal content
                        $('#mdlFormContent').append(`
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nik">NIK</label>
                                            <input type="text" class="form-control" id="nik" placeholder="NIK" value="${response.karyawan.nik}">
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_karyawan">Nama Karyawan</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Karyawan" value="${response.karyawan.name}">
                                        </div>
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="harian-tab" data-bs-toggle="tab" data-bs-target="#harian" type="button" role="tab" aria-controls="harian" aria-selected="true">
                                                    <i class="fas fa-calendar-day"></i> Set Jam Kerja Harian
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="by-date-tab" data-bs-toggle="tab" data-bs-target="#by-date" type="button" role="tab" aria-controls="by-date" aria-selected="false">
                                                    <i class="fas fa-calendar-alt"></i> Set Jam Kerja By Date
                                                </button>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="harian" role="tabpanel" aria-labelledby="harian-tab">
                                                <form id="harianForm">
                                                    <div id="harian-form-groups"></div>
                                                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="by-date" role="tabpanel" aria-labelledby="by-date-tab">
                                                <div class="col-md-12">
                                                    <div class="form-floating mt-3">
                                                        <input type="month" class="form-control" id="bulan" placeholder="Bulan">
                                                        <label for="bulan">Bulan</label>
                                                    </div>
                                                </div>
                                                <form id="byDateForm">
                                                    <div class="row g-3">
                                                        <div class="col-md-5">
                                                            <div class="form-floating mt-3">
                                                                <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal">
                                                                <label for="date">Tanggal</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-floating mt-3">
                                                                <select class="form-select" id="shift" name="shift" aria-label="Pilih Jam Kerja">
                                                                    <option selected>Pilih Jam Kerja</option>
                                                                    <!-- Options will be added dynamically here -->
                                                                </select>
                                                                <label for="shift">Shift</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-end">
                                                            <button type="submit" class="btn btn-primary btn-sm mt-3 w-100" data-bs-toggle="tooltip" data-bs-placement="top" title="Simpan Jadwal By Tanggal">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="mt-4">
                                                    <table class="table table-bordered" id="dataTable">
                                                        <thead>
                                                            <tr>
                                                                <th>NIK</th>
                                                                <th>Tanggal</th>
                                                                <th>Kode Jam Kerja</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Rows will be added dynamically here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <h5>Master Jam Kerja</h5>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Kode</th>
                                                        <th>Nama</th>
                                                        <th>Awal Masuk</th>
                                                        <th>Jam Masuk</th>
                                                        <th>Akhir Masuk</th>
                                                        <th>Jam Pulang</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="jamKerjaTableBody">
                                                    <!-- Rows will be added dynamically here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);

                        // Append jam kerja rows dynamically
                        var jamKerjaRows = response.jamkerja.map(function(jk) {
                            return `
                                <tr>
                                    <td>${jk.kode_jk}</td>
                                    <td>${jk.nama_jk}</td>
                                    <td>${jk.awal_jam_masuk}</td>
                                    <td>${jk.jam_masuk}</td>
                                    <td>${jk.akhir_jam_masuk}</td>
                                    <td>${jk.jam_pulang}</td>
                                </tr>
                            `;
                        }).join('');
                        $('#jamKerjaTableBody').append(jamKerjaRows);

                        // Create options dynamically for each day of the week
                        var jamKerjaOptions = response.jamkerja.map(function(jk) {
                            return `<option value="${jk.kode_jk}">${jk.nama_jk} (${jk.jam_masuk} - ${jk.jam_pulang})</option>`;
                        }).join('');

                        // Array of day IDs
                        var days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

                        // Append the select elements for each day dynamically
                        var harianFormGroups = days.map(function(day) {
                            return `
                                <div class="form-group mt-3">
                                    <label for="${day}">${day.charAt(0).toUpperCase() + day.slice(1)}</label>
                                    <select class="form-control day-select" id="${day}" name="hari[]">
                                        <option value="">Pilih Jam Kerja</option>
                                        ${jamKerjaOptions}
                                    </select>
                                </div>
                            `;
                        }).join('');
                        $('#harian-form-groups').append(harianFormGroups);

                        // Append options to the shift select element in the By Date tab
                        $('#shift').append(jamKerjaOptions);
                        if(response.setjamkerja.length>0){
                            setSelectedValues(response.setjamkerja);
                        }
                        if(response.setJamkerjaByDate.length>0){
                            populateTable(response.setJamkerjaByDate);
                        }

                        document.getElementById('bulan').addEventListener('change', function() {
                            var monthValue = this.value;
                            var dateInput = document.getElementById('date');

                            if (monthValue) {
                                var year = monthValue.split('-')[0];
                                var month = monthValue.split('-')[1];

                                var firstDay = new Date(year, month - 1, 1);
                                var lastDay = new Date(year, month, 0);

                                var firstDayString = firstDay.toISOString().split('T')[0];
                                var lastDayString = lastDay.toISOString().split('T')[0];

                                dateInput.min = firstDayString;
                                dateInput.max = lastDayString;
                            } else {
                                dateInput.removeAttribute('min');
                                dateInput.removeAttribute('max');
                            }
                        });


                        $("#harianForm").validate({
                            rules: {
                                "hari[]": {
                                    required: function(element) {
                                        return $(element).val() === "";
                                    }
                                }
                            },
                            messages: {
                                "hari[]": {
                                    required: "Please select a time slot for each day"
                                }
                            },
                            errorPlacement: function(error, element) {
                                error.addClass("invalid-feedback");
                                if (element.prop("type") === "checkbox") {
                                    error.insertAfter(element.siblings("label"));
                                } else {
                                    error.insertAfter(element);
                                }
                            },
                            highlight: function(element, errorClass, validClass) {
                                $(element).addClass("is-invalid").removeClass("is-valid");
                            },
                            unhighlight: function(element, errorClass, validClass) {
                                $(element).removeClass("is-invalid").addClass("is-valid");
                            },
                            submitHandler: function(form) {
                                // Show SweetAlert2 confirmation dialog
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "Do you want to submit the form?",
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, submit it!',
                                    cancelButtonText: 'No, cancel!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Add nik value to the form data
                                        var formData = $(form).serializeArray();
                                        formData.push({ name: 'nik', value: $('#nik').val() });
                                        formData.push({ name: 'name', value: $('#name').val() });

                                        // Handle form submission logic here
                                        $.ajax({
                                            url: 'users/konfigurasi/store',
                                            type: 'POST',
                                            data: $.param(formData), // Serialize form data including nik
                                            success: function(response) {
                                                // Show success message
                                                Swal.fire({
                                                    title: 'Success!',
                                                    text: 'Your form has been submitted.',
                                                    icon: 'success',
                                                    showConfirmButton: false, // Hide the confirm button
                                                    timer: 2000, // Time in milliseconds (2 seconds)
                                                    willClose: () => {
                                                        // Reload the page after the success message is closed
                                                        location.reload();
                                                    }
                                                });
                                                // You can add additional actions here, like resetting the form or redirecting
                                            },
                                            error: function(xhr, status, error) {
                                                // Show error message
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'An error occurred while submitting the form. Please try again.',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK'
                                                });
                                            }
                                        });
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        Swal.fire(
                                            'Cancelled',
                                            'Your form has not been submitted.',
                                            'error'
                                        );
                                    }
                                });
                                return false; // Prevent actual form submission
                            }
                        });





                        $("#byDateForm").validate({
                            rules: {
                                bulan: {
                                    required: true
                                },
                                shift: {
                                    required: true
                                }
                            },
                            messages: {
                                bulan: "Please select a month",
                                shift: "Please select a shift"
                            },
                            errorPlacement: function(error, element) {
                                error.addClass("invalid-feedback");
                                if (element.prop("type") === "checkbox") {
                                    error.insertAfter(element.siblings("label"));
                                } else {
                                    error.insertAfter(element);
                                }
                            },
                            highlight: function(element, errorClass, validClass) {
                                $(element).addClass("is-invalid").removeClass("is-valid");
                            },
                            unhighlight: function(element, errorClass, validClass) {
                                $(element).removeClass("is-invalid").addClass("is-valid");
                            },
                            submitHandler: function(formByDate) {
                                // Show SweetAlert2 confirmation dialog
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "Do you want to submit the form?",
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, submit it!',
                                    cancelButtonText: 'No, cancel!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Handle form submission logic here
                                        var formData = [];
                                        formData.push({ name: 'nik', value: $('#nik').val() });
                                        formData.push({ name: 'name', value: $('#name').val() });
                                        formData.push({ name: 'date', value: $('#date').val() });
                                        formData.push({ name: 'shift', value: $('#shift').val() });

                                        $.ajax({
                                            url: 'users/konfigurasi/storeByDate',
                                            type: 'POST',
                                            data: $.param(formData),
                                            success: function(response) {
                                                // Show success message
                                                Swal.fire({
                                                    title: 'Success!',
                                                    text: 'Your form has been submitted.',
                                                    icon: 'success',
                                                    showConfirmButton: false,
                                                    timer: 2000, // Time in milliseconds (2 seconds)
                                                    willClose: () => {
                                                        // Reload the page after the success message is closed
                                                        location.reload();
                                                    }
                                                });

                                                // Optionally reset the form
                                                form.reset();
                                            },
                                            error: function(xhr, status, error) {
                                                // Show error message
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'An error occurred while submitting the form. Please try again.',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK'
                                                });
                                            }
                                        });
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        Swal.fire(
                                            'Cancelled',
                                            'Your form has not been submitted.',
                                            'error'
                                        );
                                    }
                                });
                                return false; // Prevent actual form submission
                            }
                        });
                    }
                    else {
                        Swal.fire({
                            title: "Gagal!",
                            text: response.message,
                            icon: "error",
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: "Terjadi Kesalahan!",
                        text: "Terjadi kesalahan saat mengatur jam kerja. Silakan coba lagi.",
                        icon: "error",
                    });
                },
            });
        }
    });
}
function populateTable(data) {
    var tableBody = $('#dataTable tbody');
    tableBody.empty(); // Clear existing rows

    data.forEach(function(item) {
        var row = `<tr>
            <td>${item.nik}</td>
            <td>${item.tanggal}</td>
            <td>${item.kode_jam_kerja}</td>
        </tr>`;
        tableBody.append(row);
    });
}

function setSelectedValues(value) {
    value.forEach(function(item) {
        var selectElement = $(`#${item.hari.toLowerCase()}`);
        console.log(selectElement);
        selectElement.val(item.kode_jam_kerja);
    });
}

function sendAccountDetails(receiver, contactMethod) {
    // Show confirmation dialog using SweetAlert
    Swal.fire({
        title: "Send Account Details?",
        text: `Are you sure you want to send account details to ${receiver}?`,
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Yes, send it!",
        cancelButtonText: "No, cancel",
        showLoaderOnConfirm: true, // Display a loading spinner during confirm
        preConfirm: () => {
            return new Promise((resolve, reject) => {
                // AJAX request to send account details
                $.ajax({
                    url: "/users/send-account-details",
                    method: "POST",
                    data: {
                        receiver: receiver,
                        contact_method: contactMethod,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (response) {
                        resolve(response);
                    },
                    error: function (xhr, status, error) {
                        reject(error);
                    },
                });
            });
        },
        allowOutsideClick: () => !Swal.isLoading(), // Prevent closing modal while sending
    })
        .then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Sent!",
                    text: "Account details have been sent successfully.",
                    icon: "success",
                });
                // Optionally update UI or show success message to user
            }
        })
        .catch((error) => {
            Swal.fire({
                title: "Error!",
                text: "Failed to send account details.",
                icon: "error",
            });
            console.error(error); // Log the error for debugging
            // Optionally handle specific errors or show error message to user
        });
}

async function fetchOptions(url) {
    const response = await fetch(url);
    return response.json();
}

async function filterUser() {
    $("#mdlForm").modal("show");
    $("#mdlFormTitle").html("Filter User");
    $("#mdlFormContent").html("");

    const departments = await fetchOptions("/departments/data");

    const departmentOptions = departments
        .map((dept) => `<option value="${dept.id}">${dept.name}</option>`)
        .join("");

    $("#mdlFormContent").append(`
        <div class="container mt-3">
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control"  name="name" id="name" placeholder="Nama Karyawan">
                </div>
                <div class="col">
                    <select class="form-control" name="department" id="department">
                        <option value="">Departemen</option>
                        ${departmentOptions}
                    </select>
                </div>
                <div class="col">
                    <select class="form-control" name="cabang" id="cabang">
                        <option value="">Semua Cabang</option>
                        <option value="bali">Bali</option>
                    </select>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="searchUser()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    `);
}

function searchUser() {
    fetchDataUser();
    $("#mdlForm").modal("hide");
}

function setRolesToUser(user_id) {
    $("#mdlForm").modal("show");

    // Clear the modal content
    $("#mdlFormContent").html("");

    // Append the form HTML to the modal content
    $("#mdlFormContent").append(`
        <form id="chooseRoles" class="form" action="#">
            <!-- Note for the user -->
            <div class="alert alert-info" role="alert">
                Please add roles first before proceeding.
            </div>
            <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Roles</span>
                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Descriptions is required to be unique.">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
                <div id="checkboxContainer"></div> <!-- Container to hold checkboxes -->
            </div>
            <div class="text-center pt-15">
                <button type="reset" class="btn btn-light me-3" data-kt-permissions-modal-action="cancel">Discard</button>
                <button type="submit" class="btn btn-primary" data-kt-permissions-modal-action="submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress d-none">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
    `);

    // Set the modal title
    $("#mdlFormTitle").html("Form Add roles");

    // Fetch permissions via AJAX
    $.ajax({
        url: "/roles/getAllRoles",
        type: "GET",
        dataType: "json",
        success: function (response) {
            // Populate checkboxes with fetched permissions
            if (response.data) {
                var checkboxContainer = $("#checkboxContainer");
                var groupedRoles = {}; // Object to store permissions grouped by common part of name

                // Group permissions by common part of name
                response.data.forEach(function (roles) {
                    var commonName = roles.name.split("-")[0]; // Extract common part of name
                    if (!groupedRoles[commonName]) {
                        groupedRoles[commonName] = []; // Initialize array for the group if not exists
                    }
                    groupedRoles[commonName].push(roles); // Add roles to the group
                });

                // Iterate through grouped roless
                Object.keys(groupedRoles).forEach(function (name) {
                    var groupName = capitalizeFirstLetter(name); // Capitalize the first letter of each word in the group name

                    // Append group label
                    checkboxContainer.append(`
                        <div class="mb-3">
                            <h5>${groupName}</h5>
                        </div>
                    `);

                    // Append checkboxes for each roles in the group
                    groupedRoles[name].forEach(function (roles) {
                        checkboxContainer.append(`
                            <div class="form-check mb-2"> <!-- Add margin bottom -->
                                <input class="form-check-input" type="checkbox" value="${roles.id}" id="checkbox${roles.id}">
                                <label class="form-check-label ms-2" for="checkbox${roles.id}"> <!-- Add margin left -->
                                    ${roles.display_name}
                                </label>
                            </div>
                        `);
                    });
                });
            }
        },
        error: function (xhr, status, error) {
            // Optionally handle error here
            Swal.fire({
                title: xhr.responseJSON.message,
                text: xhr.responseJSON.message,
                icon: status,
                showConfirmButton: false,
                timer: 1500,
            });
        },
    });

    $.ajax({
        url: "/roles/getRolesByUser", // Replace with your actual endpoint
        type: "GET",
        dataType: "json",
        data: { user_id: user_id },
        success: function (response) {
            // Populate checkboxes with fetched permissions
            if (response.data) {
                response.data.forEach(function (roles) {
                    $("#checkbox" + roles.id).prop("checked", true);
                });
            }
        },
        error: function (xhr, status, error) {
            // Optionally handle error here
            Swal.fire({
                title: xhr.responseJSON.message,
                text: xhr.responseJSON.message,
                icon: status,
                showConfirmButton: false,
                timer: 1500,
            });
        },
    });

    $("#chooseRoles").submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        // Check if at least one checkbox is checked
        var atLeastOneChecked =
            $("#checkboxContainer").find('input[type="checkbox"]:checked')
                .length > 0;

        // If no checkbox is checked, show an alert
        if (!atLeastOneChecked) {
            Swal.fire({
                title: "Validation Error",
                text: "Please select at least one roles.",
                icon: "error",
                showConfirmButton: false,
                timer: 1500,
            });
            return; // Exit the function
        }

        // Display SweetAlert confirmation
        Swal.fire({
            title: "Are you sure?",
            text: "You are about to submit roles to this user ? ",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, submit it!",
        }).then((result) => {
            if (result.isConfirmed) {
                // Get selected permission IDs
                var selectedPermissions = $("#checkboxContainer")
                    .find('input[type="checkbox"]:checked')
                    .map(function () {
                        return $(this).val();
                    })
                    .get();

                // Submit permissions via AJAX
                $.ajax({
                    url: "/roles/submitRolesToUser", // Replace with your actual endpoint
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ), // Include CSRF token in the request headers
                    },
                    data: {
                        user_id: user_id, // Provide the role ID if needed
                        roles: selectedPermissions,
                    },
                    success: function (response) {
                        // Handle success response
                        Swal.fire({
                            title: "Submitted!",
                            text: "Your roles have been submitted to the user.",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#mdlForm").modal("hide");
                        tableUser();
                    },
                    error: function (xhr, status, error) {
                        console.error("Error submitting roles:", error);
                        // Optionally handle error here
                        Swal.fire({
                            title: "Error",
                            text: xhr.responseJSON.message,
                            icon: status,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        tableUser();
                    },
                });
            }
        });
    });
}

function capitalizeFirstLetter(string) {
    return string.replace(/\b\w/g, function (match) {
        return match.toUpperCase();
    });
}

function deleteUser(userId) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            // Make an AJAX request to delete the user
            $.ajax({
                url: "/users/delete/" + userId,
                type: "DELETE",
                success: function (result) {
                    Swal.fire("Deleted!", "User has been deleted.", "success");
                    // Refresh the DataTable
                    fetchDataUser();
                },
                error: function (xhr) {
                    Swal.fire(
                        "Error!",
                        "There was an error deleting the user.",
                        "error"
                    );
                },
            });
        }
    });
}

function editUser(value) {
    Swal.fire({
        title: "Edit User",
        text: "Are you sure you want to edit this user?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, edit it!",
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                // Simulate API call or any asynchronous operation
                setTimeout(() => {
                    resolve();
                }, 1000); // Adjust delay as needed
            });
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Open edit link using jQuery
            window.location.href = "/users/" + value + "/edit";
        }
    });
}

function resetPassword(value) {
    Swal.fire({
        title: "Are you sure?",
        text: "This action will reset the user's password.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, reset it!",
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `/users/reset-password/${value}`,
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (response) {
                        resolve(response);
                    },
                    error: function (xhr, status, error) {
                        reject("Error resetting password: " + error);
                    },
                });
            });
        },
    })
        .then((result) => {
            if (result.isConfirmed) {
                // Display Swal (SweetAlert) success message
                Swal.fire({
                    title: "Password Reset!",
                    text: "Password reset email sent successfully.",
                    icon: "success",
                    timer: 3000, // Set duration in milliseconds (e.g., 3000 for 3 seconds)
                    showConfirmButton: false, // Hide the "OK" button
                });

                // Display Toastify message
                Toastify({
                    text: "Password reset email sent successfully.",
                    duration: 3000, // Display duration in milliseconds
                    close: true,
                    gravity: "bottom", // Display position: 'top' or 'bottom'
                    position: "right", // Display position: 'left', 'center', or 'right'
                    backgroundColor:
                        "linear-gradient(to right, #00b09b, #96c93d)", // Custom background color
                }).showToast();

                // Reload the page after a delay
                setTimeout(function () {
                    location.reload();
                }, 3000);
            }
        })
        .catch((error) => {
            Swal.fire("Error!", error, "error");
        });
}
