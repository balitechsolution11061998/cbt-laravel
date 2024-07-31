$(document).ready(function () {
    fetchData();
});

function fetchData() {
    if ($.fn.DataTable.isDataTable("#tableCuti")) {
        $("#tableCuti").DataTable().destroy();
    }
    var table = $("#tableCuti").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "/cuti/data",
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                searchable: false,
                orderable: false,
            },
            { data: "kode_cuti", name: "kode_cuti" },
            { data: "nama_cuti", name: "nama_cuti" },
            { data: "jumlah_hari", name: "jumlah_hari" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
    });


}

function deleteJamKerja(val){
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete record with ID: " + val + "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            // Handle the delete action here
            $.ajax({
                url: "/jam_kerja/delete/" + val,
                type: "DELETE",
                success: function (response) {
                    Swal.fire(
                        "Deleted!",
                        "Record has been deleted.",
                        "success"
                    );
                    fetchData();
                },
                error: function (xhr) {
                    Swal.fire(
                        "Error!",
                        "An error occurred while deleting the record.",
                        "error"
                    );
                },
            });
        }
    });
}




function editCuti(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to edit this cuti?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, edit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Fetch data and show the edit form
            $.ajax({
                url: '/cuti/' + id + '/edit',
                method: 'GET',
                success: function(response) {
                    // Assuming you have a modal to show the edit form
                    tambahCuti(response)
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to fetch the data!'
                    });
                }
            });
        }
    });
}


function tambahCuti(value) {
    $("#mdlForm").modal("show");
    $("#mdlFormTitle").html("Form Cuti");
    $("#mdlFormContent").html(`
        <form id="frmTambahCuti">
            <input type="hidden" class="form-control" id="id" placeholder="Id" name="id">
            <div class="mb-3">
                <label for="kodeCuti" class="form-label">Kode Cuti</label>
                <input type="text" class="form-control" id="kodeCuti" placeholder="Kode Cuti" name="kodeCuti">
            </div>
            <div class="mb-3">
                <label for="namaCuti" class="form-label">Nama Cuti</label>
                <input type="text" class="form-control" id="namaCuti" placeholder="Nama Cuti" name="namaCuti">
            </div>
            <div class="mb-3">
                <label for="jumlahHari" class="form-label">Jumlah Hari</label>
                <input type="number" class="form-control" id="jumlahHari" placeholder="Jumlah Hari" name="jumlahHari">
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
            </div>
        </form>
    `);
    console.log(value,'value');
    if (value !== undefined) {
        $("#id").val(value.cuti.id);
        $("#kodeCuti").val(value.cuti.kode_cuti);
        $("#namaCuti").val(value.cuti.nama_cuti);
        $("#jumlahHari").val(value.cuti.jumlah_hari);
    }

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#frmTambahCuti").validate({
        rules: {
            kodeCuti: "required",
            namaCuti: "required",
            jumlahHari: {
                required: true,
                number: true,
                min: 1,
            },
        },
        messages: {
            kodeCuti: "Kode Cuti harus diisi",
            namaCuti: "Nama Cuti harus diisi",
            jumlahHari: {
                required: "Jumlah Hari harus diisi",
                number: "Jumlah Hari harus berupa angka",
                min: "Jumlah Hari minimal adalah 1",
            },
        },
        errorClass: "error",
        validClass: "valid",
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);
        },
        submitHandler: function (form, event) {
            event.preventDefault(); // Prevent default form submission

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to submit the form?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, submit it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to submit the form data to the server
                    $.ajax({
                        url: "/cuti/store",
                        method: "POST",
                        data: $(form).serialize(),
                        success: function (response) {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: response.success,
                                timer: 2000,
                                showConfirmButton: false,
                            }).then(() => {
                                location.reload(); // Reload the page after success
                            });
                        },
                        error: function (response) {
                            let errorMessage = "Something went wrong!";
                            if (response.status === 409) {
                                errorMessage = response.responseJSON.error;
                            } else if (
                                response.responseJSON &&
                                response.responseJSON.error
                            ) {
                                errorMessage = response.responseJSON.error;
                            }
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: errorMessage,
                            });
                        },
                    });
                }
            });
        },
    });

}

function deleteCuti(id) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
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
            $.ajax({
                url: '/cuti/' + id + '/delete',
                method: 'DELETE',
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Data cuti has been deleted.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#tableCuti').DataTable().ajax.reload();
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to delete data!'
                    });
                }
            });
        }
    });
}
