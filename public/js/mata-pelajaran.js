$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#mata_pelajaran_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/mata-pelajaran/data",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'kode', name: 'kode'},
            {data: 'nama', name: 'nama'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    $('[data-kt-user-table-filter="search"]').on('keyup', function() {
        table.search(this.value).draw();
    });

    window.createMataPelajaran = function () {
        $('#mdlFormTitle').text('Add Mata Pelajaran');
        $('#mdlFormContent').html(getMataPelajaranForm());
        $('#mdlForm').modal('show');
        initFormValidation();
    }

    $('body').on('click', '.editMataPelajaran', function () {
        var id = $(this).data('id');
        $.get("/mata-pelajaran/" + id + "/edit", function (data) {
            $('#mdlFormTitle').text('Edit Mata Pelajaran');
            $('#mdlFormContent').html(getMataPelajaranForm(data));
            $('#mdlForm').modal('show');
            initFormValidation();
        });
    });

    $('body').on('click', '.deleteMataPelajaran', function () {
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
                    url: "/mata-pelajaran/delete/" + id,
                    success: function (data) {
                        $('#mata_pelajaran_table').DataTable().draw();
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

    function getMataPelajaranForm(data = {}) {
        return `
            <form id="mataPelajaranForm" name="mataPelajaranForm" class="form-horizontal">
                <input type="hidden" name="id" value="${data.id || ''}">
                <div class="form-group">
                    <label for="kode" class="col-sm-2 control-label">Kode</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="kode" name="kode" placeholder="Enter Kode" value="${data.kode || ''}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nama" class="col-sm-2 control-label">Nama Mata Pelajaran</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Enter Nama Mata Pelajaran" value="${data.nama || ''}" required>
                    </div>
                </div>
                <div class="col-sm-offset-2 col-sm-10 mt-3">
                    <button type="submit" class="btn btn-primary" id="saveBtn" value="${data.id ? 'edit' : 'create'}">Save changes</button>
                </div>
            </form>
        `;
    }

    function initFormValidation() {
        $('#mataPelajaranForm').validate({
            submitHandler: function(form) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to save these changes?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var actionType = $('#saveBtn').val();
                        $('#saveBtn').html('Sending..');

                        $.ajax({
                            data: $(form).serialize(),
                            url: actionType == 'create' ? "/mata-pelajaran/store" : "/mata-pelajaran/update/" + $('input[name="id"]').val(),
                            type: "POST",
                            dataType: 'json',
                            success: function (data) {
                                $('#mata_pelajaran_table').DataTable().draw();
                                $('#mdlForm').modal('hide');
                                $('#saveBtn').html('Save changes');
                                Swal.fire({
                                    title: actionType == 'create' ? 'Created!' : 'Updated!',
                                    text: actionType == 'create' ? 'The record has been created.' : 'The record has been updated.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                $('#saveBtn').html('Save changes');
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'There was an error saving the record.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            }
        });
    }
});
