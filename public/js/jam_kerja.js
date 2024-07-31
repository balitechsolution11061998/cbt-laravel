$(document).ready(function () {
    fetchData();
});

function fetchData() {
    if ($.fn.DataTable.isDataTable("#tableJamKerja")) {
        $("#tableJamKerja").DataTable().destroy();
    }
    var table = $("#tableJamKerja").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "/jam_kerja/data",
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                searchable: false,
                orderable: false,
            },
            { data: "kode_jk", name: "kode_jk" },
            { data: "nama_jk", name: "nama_jk" },
            { data: "awal_jam_masuk", name: "awal_jam_masuk" },
            { data: "jam_masuk", name: "jam_masuk" },
            { data: "akhir_jam_masuk", name: "akhir_jam_masuk" },
            { data: "jam_pulang", name: "jam_pulang" },
            {
                data: "lintas_hari",
                name: "lintas_hari",
                render: function (data, type, row) {
                    return data == 1 ? "Yes" : "No";
                },
            },
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

function editJamKerja(val){
    console.log(val);
    Swal.fire({
        title: "Edit Jam Kerja",
        text: "Edit jam kerja with ID: " + val,
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Edit",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/jam_kerja/edit/" + val,
                type: "GET",
                success: function (response) {
                    tambahJamKerja(response);
                },
                error: function (xhr) {
                    Swal.fire(
                        "Error!",
                        "An error occurred while fetching the record data.",
                        "error"
                    );
                },
            });
        }
    });
}

function tambahJamKerja(value) {
    console.log(value);
    $("#mdlForm").modal("show");
    $("#mdlFormTitle").html("Form Jam Kerja");
    $("#mdlFormContent").html(`
        <form id="frmTambahJamKerja">
            <input type="hidden" class="form-control" id="id" placeholder="Kode Jam Kerja" name="id">
            <div class="mb-3">
                <label for="kodeJamKerja" class="form-label">Kode Jam Kerja</label>
                <input type="text" class="form-control" id="kodeJamKerja" placeholder="Kode Jam Kerja" name="kodeJamKerja">
            </div>
            <div class="mb-3">
                <label for="namaJamKerja" class="form-label">Nama Jam Kerja</label>
                <input type="text" class="form-control" id="namaJamKerja" placeholder="Nama Jam Kerja" name="namaJamKerja">
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="awalJamMasuk" class="form-label">Awal Jam Masuk</label>
                    <input type="time" class="form-control" id="awalJamMasuk" name="awalJamMasuk">
                </div>
                <div class="col">
                    <label for="jamMasuk" class="form-label">Jam Masuk</label>
                    <input type="time" class="form-control" id="jamMasuk" name="jamMasuk">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="akhirJamMasuk" class="form-label">Akhir Jam Masuk</label>
                    <input type="time" class="form-control" id="akhirJamMasuk" name="akhirJamMasuk" readonly>
                </div>
                <div class="col">
                    <label for="jamPulang" class="form-label">Jam Pulang</label>
                    <input type="time" class="form-control" id="jamPulang" name="jamPulang">
                </div>
            </div>
            <div class="mb-3">
                <label for="lintasHari" class="form-label">Lintas Hari</label>
                <select class="form-select" id="lintasHari" name="lintasHari">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-secondary me-2" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
            </div>
        </form>
    `);

    if (value !== undefined) {
        $("#id").val(value.id);
        $("#kodeJamKerja").val(value.kode_jk);
        $("#namaJamKerja").val(value.nama_jk);
        $("#awalJamMasuk").val(value.awal_jam_masuk);
        $("#jamMasuk").val(value.jam_masuk);
        $("#akhirJamMasuk").val(value.akhir_jam_masuk);
        $("#jamPulang").val(value.jam_pulang);
        $("#lintasHari").val(value.lintas_hari);
    }

    $("#jamMasuk").on("change", function () {
        var jamMasuk = $(this).val();
        var akhirJamMasuk = addMinutes(jamMasuk, 15);
        $("#akhirJamMasuk").val(akhirJamMasuk);
    });

    function addMinutes(time, minutes) {
        var parts = time.split(":");
        var hours = parseInt(parts[0]);
        var mins = parseInt(parts[1]);
        mins += minutes;
        if (mins >= 60) {
            hours += Math.floor(mins / 60);
            mins %= 60;
        }
        return pad(hours) + ":" + pad(mins);
    }

    function pad(number) {
        return (number < 10 ? "0" : "") + number;
    }

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.validator.addMethod(
        "greaterThan",
        function (value, element, param) {
            return this.optional(element) || value > $(param).val();
        },
        "Must be greater than {0}"
    );

    $.validator.addMethod(
        "lessThan",
        function (value, element, param) {
            return this.optional(element) || value < $(param).val();
        },
        "Must be less than {0}"
    );

    $("#frmTambahJamKerja").validate({
        rules: {
            kodeJamKerja: "required",
            namaJamKerja: "required",
            awalJamMasuk: {
                required: true,
                lessThan: "#jamMasuk",
            },
            jamMasuk: {
                required: true,
                greaterThan: "#awalJamMasuk",
                lessThan: "#akhirJamMasuk",
            },
            akhirJamMasuk: {
                required: true,
                greaterThan: "#jamMasuk",
            },
            jamPulang: {
                required: true,
            },
            lintasHari: "required",
        },
        messages: {
            kodeJamKerja: "Kode Jam Kerja harus diisi",
            namaJamKerja: "Nama Jam Kerja harus diisi",
            awalJamMasuk: {
                required: "Awal Jam Masuk harus diisi",
                lessThan: "Awal Jam Masuk harus kurang dari Jam Masuk",
            },
            jamMasuk: {
                required: "Jam Masuk harus diisi",
                greaterThan: "Jam Masuk harus lebih dari Awal Jam Masuk",
                lessThan: "Jam Masuk harus kurang dari Akhir Jam Masuk",
            },
            akhirJamMasuk: {
                required: "Akhir Jam Masuk harus diisi",
                greaterThan: "Akhir Jam Masuk harus lebih dari Jam Masuk",
            },
            jamPulang: {
                required: "Jam Pulang harus diisi",
            },
            lintasHari: "Lintas Hari harus diisi",
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
                        url: "/jam_kerja/store",
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

    function calculateDuration(startTime, addHours) {
        var startParts = startTime.split(":");
        var startHours = parseInt(startParts[0]);
        var startMins = parseInt(startParts[1]);
        var endHours = startHours + addHours;
        var endMins = startMins;
        if (endMins >= 60) {
            endHours += Math.floor(endMins / 60);
            endMins %= 60;
        }
        return pad(endHours) + ":" + pad(endMins);
    }

    function pad(number) {
        return (number < 10 ? "0" : "") + number;
    }

    // Set the jam pulang 9 hours from jam masuk
    $("#jamMasuk").on("change", function () {
        var jamMasuk = $(this).val();
        var jamPulang = calculateDuration(jamMasuk, 9);
        $("#jamPulang").val(jamPulang);
    });
}
