$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#siswa_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/siswa/data",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'rombel.nama_rombel', name: 'rombel.nama_rombel'},
            {data: 'nama', name: 'nama'},
            {data: 'nis', name: 'nis'},
            {data: 'jenis_kelamin', name: 'jenis_kelamin'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    $('[data-kt-user-table-filter="search"]').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('body').on('click', '.editSiswa', function () {
        var id = $(this).data('id');
        $.get("/siswa/" + id + "/edit", function (data) {
            createSiswa(data);
        });
    });

    $('body').on('click', '.deleteSiswa', function () {
        var id = $(this).data("id");

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "DELETE",
                    url: "/siswa/delete/" + id,
                    success: function (data) {
                        $('#siswa_table').DataTable().draw();
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The record has been deleted.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error deleting the record.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

  // Import Siswa
  $('body').on('click', '.importSiswa', function () {
    $('#importModal').modal('show');
});

$('#importForm').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);

    Swal.fire({
        title: 'Importing...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "/siswa/import",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#importModal').modal('hide');
            $('#siswa_table').DataTable().draw();
            Swal.fire({
                title: 'Imported!',
                text: 'Data has been imported successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        },
        error: function (data) {
            console.log('Error:', data);
            Swal.fire({
                title: 'Error!',
                text: 'There was an error importing the data.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
});


function createQRCode(userId, studentName, studentClass, nis, gender) {
    console.log(userId, studentName, studentClass, nis, gender); // For debugging

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

            // Show the QR code in a custom modal with student data
            Swal.fire({
                title: `QR Code for ${studentName}`,
                html: `
                    <div style="text-align: center; color: black;">
                        <p style="color: black;"><strong>Name:</strong> ${studentName}</p>
                        <p style="color: black;"><strong>Class:</strong> ${studentClass}</p>
                        <p style="color: black;"><strong>NIS:</strong> ${nis}</p>
                        <p style="color: black;"><strong>Gender:</strong> ${gender}</p>
                        <img src="${response.qr_code_url}" alt="QR Code" style="max-width: 80%; height: auto;">
                        <br>
                        <a href="/users/${userId}/download-qr-code-pdf" class="btn btn-primary" style="margin-top: 20px;">
                            Download QR Code with Data (PDF)
                        </a>
                    </div>
                `,
                showCloseButton: true,
                confirmButtonText: 'Close',
                customClass: {
                    popup: 'swal2-custom-modal-white', // Custom class for white background
                    title: 'swal2-custom-title-black'  // Custom class for black title text
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



function createSiswa(data = null) {
    $('#mdlFormTitle').text(data ? 'Edit Siswa' : 'Create New Siswa');
    $('#mdlFormContent').html(getSiswaForm(data));
    $('#mdlForm').modal('show');

    $('#siswaForm').validate({
        submitHandler: function(form) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save the changes?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Saving...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        data: $(form).serialize(),
                        url: "/siswa/store",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $(form).trigger("reset");
                            $('#mdlForm').modal('hide');
                            $('#siswa_table').DataTable().draw();
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your data has been saved.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an error saving your data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            if (data.responseJSON && data.responseJSON.errors) {
                                $.each(data.responseJSON.errors, function(key, value) {
                                    var input = $('[name=' + key + ']');
                                    input.addClass('is-invalid');
                                    input.after('<div class="invalid-feedback">' + value + '</div>');
                                });
                            }
                        }
                    });
                }
            });
        },
        rules: {
            nama: {
                required: true,
                maxlength: 255
            },
            nis: {
                required: true,
                maxlength: 100
            },
            email: {
                required: true,
                email: true,
                maxlength: 255
            },
            password: {
                required: function() {
                    return !data; // Only required if creating a new record
                },
                minlength: 8
            }
        },
        messages: {
            nama: {
                required: "Please enter a name",
                maxlength: "Name cannot be more than 255 characters"
            },
            nis: {
                required: "Please enter a NIS",
                maxlength: "NIS cannot be more than 100 characters"
            },
            email: {
                required: "Please enter an email",
                email: "Please enter a valid email",
                maxlength: "Email cannot be more than 255 characters"
            },
            password: {
                required: "Please enter a password",
                minlength: "Password must be at least 8 characters"
            }
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorElement: 'div',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);
        }
    });

    // Populate Rombel options dynamically
    $.get('/rombel/options', function(options) {
        var select = $('#rombel_id');
        select.empty(); // Clear existing options
        options.forEach(function(option) {
            select.append(new Option(option.nama_rombel, option.id));
        });
        if (data) {
            select.val(data.rombel_id);
        }
    });
}


function getSiswaForm(data) {
    return `
        <form id="siswaForm" name="siswaForm" class="form-horizontal">
            <input type="hidden" name="id" id="id" value="${data ? data.id : ''}">
            <div class="form-group">
                <label for="rombel_id" class="col-sm-2 control-label">Rombel</label>
                <div class="col-sm-12">
                    <select class="form-control" id="rombel_id" name="rombel_id" required="">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="nama" class="col-sm-2 control-label">Nama</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Enter Nama" value="${data ? data.nama : ''}" required="">
                </div>
            </div>
            <div class="form-group">
                <label for="nis" class="col-sm-2 control-label">NIS</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="nis" name="nis" placeholder="Enter NIS" value="${data ? data.nis : ''}" required="">
                </div>
            </div>
            <div class="form-group">
                <label for="jenis_kelamin" class="col-sm-2 control-label">Jenis Kelamin</label>
                <div class="col-sm-12">
                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required="">
                        <option value="L" ${data && data.jenis_kelamin === 'L' ? 'selected' : ''}>L</option>
                        <option value="P" ${data && data.jenis_kelamin === 'P' ? 'selected' : ''}>P</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-12">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="${data ? data.email : ''}" required="">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-12">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" ${data ? '' : 'required'}>
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10 mt-3">
                <button type="submit" class="btn btn-primary" id="saveBtn" value="${data ? 'edit' : 'create'}">Save changes</button>
            </div>
        </form>
    `;
}

