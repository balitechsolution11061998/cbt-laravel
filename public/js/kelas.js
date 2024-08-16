$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#kelas_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/kelas/data",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false}, // Ensure this column is correctly configured
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Search filter
    $('[data-kt-user-table-filter="search"]').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('body').on('click', '.editKelas', function () {
        var id = $(this).data('id');
        $.get("/kelas/" + id + "/edit", function (data) {
            createKelas(data);
        });
    });

    $('body').on('click', '.deleteKelas', function () {
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
                    url: "/kelas/delete/" + id,
                    success: function (data) {
                        $('#kelas_table').DataTable().draw();
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


function createKelas(data = null) {
    $('#mdlFormTitle').text(data ? 'Edit Kelas' : 'Create New Kelas');
    $('#mdlFormContent').html(`
        <form id="kelasForm" name="kelasForm" class="form-horizontal">
            <input type="hidden" name="id" id="id" value="${data ? data.id : ''}">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="${data ? data.name : ''}" required="">
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10 mt-3">
                <button type="submit" class="btn btn-primary" id="saveBtn" value="${data ? 'edit' : 'create'}">Save changes</button>
            </div>
        </form>
    `);
    $('#mdlForm').modal('show');

    // Initialize form validation
    $('#kelasForm').validate({
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
                        url: "/kelas/store",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $(form).trigger("reset");
                            $('#mdlForm').modal('hide');
                            $('#kelas_table').DataTable().draw();
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
            name: {
                required: true,
                maxlength: 255
            },

        },
        messages: {
            name: {
                required: "Please enter a name",
                maxlength: "Name cannot be more than 255 characters"
            },

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

$('body').on('click', '.editKelas', function () {
    var id = $(this).data('id');
    $.get("/kelas/" + id + "/edit", function (data) {
        createKelas(data);
    });
});
