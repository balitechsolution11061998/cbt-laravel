$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#rombel_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/rombel/data",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'kelas.name', name: 'kelas.name'},
            {data: 'nama_rombel', name: 'nama_rombel'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    $('[data-kt-user-table-filter="search"]').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('body').on('click', '.editRombel', function () {
        var id = $(this).data('id');
        $.get("/rombel/" + id + "/edit", function (data) {
            createRombel(data);
        });
    });

    $('body').on('click', '.deleteRombel', function () {
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
                    url: "/rombel/delete/" + id,
                    success: function (data) {
                        $('#rombel_table').DataTable().draw();
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

function createRombel(data = null) {
    $('#mdlFormTitle').text(data ? 'Edit Rombel' : 'Create New Rombel');
    $('#mdlFormContent').html(`
        <form id="rombelForm" name="rombelForm" class="form-horizontal">
            <input type="hidden" name="id" id="id" value="${data ? data.id : ''}">
            <div class="form-group">
                <label for="kelas_id" class="col-sm-2 control-label">Kelas</label>
                <div class="col-sm-12">
                    <select class="form-control" id="kelas_id" name="kelas_id" required="">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="nama_rombel" class="col-sm-2 control-label">Nama Rombel</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="nama_rombel" name="nama_rombel" placeholder="Enter Nama Rombel" value="${data ? data.nama_rombel : ''}" required="">
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10 mt-3">
                <button type="submit" class="btn btn-primary" id="saveBtn" value="${data ? 'edit' : 'create'}">Save changes</button>
            </div>
        </form>
    `);
    $('#mdlForm').modal('show');

    // Initialize form validation
    $('#rombelForm').validate({
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
                        url: "/rombel/store",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $(form).trigger("reset");
                            $('#mdlForm').modal('hide');
                            $('#rombel_table').DataTable().draw();
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
                            // Display validation errors
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
            nama_rombel: {
                required: true,
                maxlength: 255
            }
        },
        messages: {
            nama_rombel: {
                required: "Please enter a name",
                maxlength: "Name cannot be more than 255 characters"
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

    // Populate Kelas options dynamically
    $.get('/kelas/options', function(options) {
        var select = $('#kelas_id');
        options.forEach(function(option) {
            select.append(new Option(option.name, option.id));
        });
        if (data) {
            select.val(data.kelas_id);
        }
    });
}
