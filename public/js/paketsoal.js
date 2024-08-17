$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#paket_soal_table').DataTable({
        processing: true,
        serverSide: true,
        responsive:true,
        ajax: "/paket-soal/data",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'kelas', name: 'kelas'}, // Column for Kelas
            {data: 'mata_pelajaran', name: 'mata_pelajaran'}, // Column for Mata Pelajaran
            {data: 'kode_paket', name: 'kode_paket'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });


    $('[data-kt-user-table-filter="search"]').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('body').on('click', '.editPaketSoal', function () {
        var id = $(this).data('id');
        $.get("/paket-soal/" + id + "/edit", function (data) {
            createPaketSoal(data);
        });
    });

    $('body').on('click', '.deletePaketSoal', function () {
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
                    url: "/paket-soal/delete/" + id,
                    success: function (data) {
                        $('#paket_soal_table').DataTable().draw();
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

});

function createPaketSoal(data = null) {
    $('#mdlFormTitle').text(data ? 'Edit Paket Soal' : 'Create New Paket Soal');
    $('#mdlFormContent').html(`
        <form id="paketSoalForm" name="paketSoalForm" class="form-horizontal">
            <input type="hidden" name="id" id="id" value="${data ? data.id : ''}">
            <div class="form-group">
                <label for="kode_kelas" class="col-sm-2 control-label">Kelas</label>
                <div class="col-sm-12">
                    <select class="form-control" id="kode_kelas" name="kode_kelas" required>
                        <option value="">Select Kode Kelas</option>
                    </select>
                </div>
            </div>
             <div class="form-group">
                <label for="kode_paket" class="col-sm-2 control-label">Paket Soal</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="kode_paket" name="kode_paket" placeholder="Enter Kode Paket" value="${data ? data.kode_paket : ''}" required>
                </div>
            </div>
            <div class="form-group">
                <label for="kode_mata_pelajaran" class="col-sm-2 control-label">Mata Pelajaran</label>
                <div class="col-sm-12">
                    <select class="form-control" id="kode_mata_pelajaran" name="kode_mata_pelajaran" required>
                        <option value="">Select Kode Mata Pelajaran</option>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                <div class="col-sm-12">
                    <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Enter Keterangan">${data ? data.keterangan : ''}</textarea>
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10 mt-3">
                <button type="submit" class="btn btn-primary" id="saveBtn" value="${data ? 'edit' : 'create'}">Save changes</button>
            </div>
        </form>
    `);
    $('#mdlForm').modal('show');

    // Populate Kode Kelas dropdown
    $.ajax({
        url: '/kelas/options', // Adjust the endpoint to match your backend route
        type: 'GET',
        success: function(response) {
            const kodeKelasSelect = $('#kode_kelas');
            response.forEach(kodeKelas => {
                kodeKelasSelect.append(new Option(kodeKelas.name, kodeKelas.id));
            });

            if (data) {
                kodeKelasSelect.val(data.kode_kelas).trigger('change');
            }
        },
        error: function(error) {
            console.log('Error fetching Kode Kelas:', error);
        }
    });

    // Populate Kode Mata Pelajaran dropdown
    $.ajax({
        url: '/mata-pelajaran/options', // Adjust the endpoint to match your backend route
        type: 'GET',
        success: function(response) {
            const kodeMataPelajaranSelect = $('#kode_mata_pelajaran');
            response.forEach(mataPelajaran => {
                kodeMataPelajaranSelect.append(new Option(mataPelajaran.nama, mataPelajaran.id));
            });

            if (data) {
                kodeMataPelajaranSelect.val(data.kode_mata_pelajaran).trigger('change');
            }
        },
        error: function(error) {
            console.log('Error fetching Kode Mata Pelajaran:', error);
        }
    });

    // Initialize form validation
    $('#paketSoalForm').validate({
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
                        url: data ? `/paket-soal/update/${data.id}` : '/paket-soal/store',
                        type: data ? 'POST' : 'POST',
                        dataType: 'json',
                        success: function (response) {
                            $(form).trigger("reset");
                            $('#mdlForm').modal('hide');
                            $('#paket_soal_table').DataTable().draw();
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your data has been saved.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function (response) {
                            console.log('Error:', response);
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an error saving your data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            // Display validation errors
                            if (response.responseJSON && response.responseJSON.errors) {
                                $.each(response.responseJSON.errors, function(key, value) {
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
            kode_kelas: {
                required: true
            },
            kode_mata_pelajaran: {
                required: true,
                maxlength: 255
            },
            kode_paket: {
                required: true,
                maxlength: 255
            },
            kode_paket: {
                required: true,
                maxlength: 255
            }
        },
        messages: {
            kode_kelas: {
                required: "Please select the Kode Kelas"
            },
            kode_mata_pelajaran: {
                required: "Please enter the Kode Mata Pelajaran",
                maxlength: "Kode Mata Pelajaran cannot be more than 255 characters"
            },
            kode_paket: {
                required: "Please enter the Kode Paket",
                maxlength: "Kode Paket cannot be more than 255 characters"
            },
            kode_paket: {
                required: "Please enter the Paket Soal",
                maxlength: "Nama Paket Soal cannot be more than 255 characters"
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
}


