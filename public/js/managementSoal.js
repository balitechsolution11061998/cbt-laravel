$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    var table = $('#soal_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "/soal/data",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'paket_soal', name: 'paket_soal'}, // Use the name of the relationship attribute
            {data: 'jenis', name: 'jenis'},
            {data: 'pertanyaan', name: 'pertanyaan'},
            {data: 'media', name: 'media'},
            {data: 'ulang_media', name: 'ulang_media'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });


    $('[data-kt-user-table-filter="search"]').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('body').on('click', '.editSoal', function () {
        var id = $(this).data('id');
        $.get("/soal/" + id + "/edit", function (data) {
            createSoal(data);
        });
    });

    $('body').on('click', '.deleteSoal', function () {
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
                    url: "/soal/delete/" + id,
                    success: function (data) {
                        $('#soal_table').DataTable().draw();
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

    // For Soal Pilihan, you can use a similar setup if needed
});

function createSoal(data = null) {
    $('#mdlFormTitle').text(data ? 'Edit Soal' : 'Create New Soal');

    // Define the common fields
    let formHtml = `
        <form id="soalForm" name="soalForm" class="form-horizontal">
            <input type="hidden" name="id" id="id" value="${data ? data.id : ''}">
            <div class="form-group">
                <label for="paket_soal_id" class="col-sm-2 control-label">Paket Soal</label>
                <div class="col-sm-12">
                    <select class="form-control" id="paket_soal_id" name="paket_soal_id" required>
                        <option value="">Select Paket Soal</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="jenis" class="col-sm-2 control-label">Jenis</label>
                <div class="col-sm-12">
                    <select class="form-control" id="jenis" name="jenis" required>
                        <option value="">Select Jenis</option>
                        <option value="pilihan_ganda" ${data && data.jenis === 'pilihan_ganda' ? 'selected' : ''}>Pilihan Ganda</option>
                        <option value="essai" ${data && data.jenis === 'essai' ? 'selected' : ''}>Essai</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="pertanyaan" class="col-sm-2 control-label">Pertanyaan</label>
                <div class="col-sm-12">
                    <textarea class="form-control" id="pertanyaan" name="pertanyaan" placeholder="Enter Pertanyaan">${data ? data.pertanyaan : ''}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="media" class="col-sm-2 control-label">Media</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="media" name="media" placeholder="Enter Media" value="${data ? data.media : ''}">
                </div>
            </div>
            <div class="form-group">
                <label for="ulang_media" class="col-sm-2 control-label">Ulang Media</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="ulang_media" name="ulang_media" placeholder="Enter Ulang Media" value="${data ? data.ulang_media : ''}">
                </div>
            </div>
            <div id="pilihan_ganda_section" class="form-group" style="display: ${data && data.jenis === 'pilihan_ganda' ? 'block' : 'none'};">
                <label class="col-sm-2 control-label">Pilihan Ganda</label>
                <div class="col-sm-12">
                    <div id="pilihan_ganda_container">
                        ${data && data.jenis === 'pilihan_ganda' ? generatePilihanGandaFields(data) : generateEmptyPilihanGandaFields()}
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Jawaban Benar</label>
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="jawaban_a" name="jawaban_benar" value="a" ${data && data.jawaban_benar === 'a' ? 'checked' : ''}>
                                <label class="form-check-label" for="jawaban_a">A</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="jawaban_b" name="jawaban_benar" value="b" ${data && data.jawaban_benar === 'b' ? 'checked' : ''}>
                                <label class="form-check-label" for="jawaban_b">B</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="jawaban_c" name="jawaban_benar" value="c" ${data && data.jawaban_benar === 'c' ? 'checked' : ''}>
                                <label class="form-check-label" for="jawaban_c">C</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="jawaban_d" name="jawaban_benar" value="d" ${data && data.jawaban_benar === 'd' ? 'checked' : ''}>
                                <label class="form-check-label" for="jawaban_d">D</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="essai_section" class="form-group" style="display: ${data && data.jenis === 'essai' ? 'block' : 'none'};">
                <label for="jawaban_essai" class="col-sm-2 control-label">Jawaban Essai</label>
                <div class="col-sm-12">
                    <textarea class="form-control" id="jawaban_essai" name="jawaban_essai" placeholder="Enter Jawaban Essai">${data ? data.jawaban_essai : ''}</textarea>
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10 mt-3">
                <button type="submit" class="btn btn-primary" id="saveBtn" value="${data ? 'edit' : 'create'}">Save changes</button>
            </div>
        </form>
    `;

    $('#mdlFormContent').html(formHtml);
    $('#mdlForm').modal('show');

    // Populate Paket Soal dropdown
    $.ajax({
        url: '/paket-soal/options',
        type: 'GET',
        success: function(response) {
            const paketSoalSelect = $('#paket_soal_id');
            response.forEach(paketSoal => {
                paketSoalSelect.append(new Option(paketSoal.nama_paket_soal, paketSoal.id));
            });

            if (data) {
                paketSoalSelect.val(data.paket_soal_id).trigger('change');
            }
        },
        error: function(error) {
            console.log('Error fetching Paket Soal:', error);
        }
    });

    // Initialize form validation
    $('#soalForm').validate({
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
                        url: '/soal/store',
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            $(form).trigger("reset");
                            $('#mdlForm').modal('hide');
                            $('#soal_table').DataTable().draw();
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
            paket_soal_id: {
                required: true
            },
            jenis: {
                required: true
            },
            pertanyaan: {
                required: true
            },
            media: {
                required: true
            },
            ulang_media: {
                required: true
            },
            pilihan_ganda_a: {
                required: function(element) {
                    return $('#jenis').val() === 'pilihan_ganda';
                }
            },
            pilihan_ganda_b: {
                required: function(element) {
                    return $('#jenis').val() === 'pilihan_ganda';
                }
            },
            pilihan_ganda_c: {
                required: function(element) {
                    return $('#jenis').val() === 'pilihan_ganda';
                }
            },
            pilihan_ganda_d: {
                required: function(element) {
                    return $('#jenis').val() === 'pilihan_ganda';
                }
            },
            jawaban_benar: {
                required: function(element) {
                    return $('#jenis').val() === 'pilihan_ganda';
                }
            },
            jawaban_essai: {
                required: function(element) {
                    return $('#jenis').val() === 'essai';
                }
            }
        },
        messages: {
            paket_soal_id: {
                required: "Please select the Paket Soal"
            },
            jenis: {
                required: "Please select the Jenis"
            },
            pertanyaan: {
                required: "Please enter the Pertanyaan"
            },
            media: {
                required: "Please enter the Media"
            },
            ulang_media: {
                required: "Please enter the Ulang Media"
            },
            pilihan_ganda_a: {
                required: "Please enter Option A"
            },
            pilihan_ganda_b: {
                required: "Please enter Option B"
            },
            pilihan_ganda_c: {
                required: "Please enter Option C"
            },
            pilihan_ganda_d: {
                required: "Please enter Option D"
            },
            jawaban_benar: {
                required: "Please select the correct answer"
            },
            jawaban_essai: {
                required: "Please enter the answer for Essai"
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

    // Show/hide sections based on the jenis selection
    $('#jenis').change(function() {
        if ($(this).val() === 'pilihan_ganda') {
            $('#pilihan_ganda_section').show();
            $('#essai_section').hide();
        } else if ($(this).val() === 'essai') {
            $('#pilihan_ganda_section').hide();
            $('#essai_section').show();
        } else {
            $('#pilihan_ganda_section').hide();
            $('#essai_section').hide();
        }
    }).trigger('change'); // Trigger change to set the initial state
}
function editSoal(id) {
    $.ajax({
        url: `/soal/${id}/edit`, // Adjust the endpoint to match your route
        type: 'GET',
        success: function(response) {
            // Call the createSoal function with the response data to populate the form
            createSoal(response.data);
        },
        error: function(xhr) {
            console.error('Error fetching soal data:', xhr);
            Swal.fire({
                title: 'Error!',
                text: 'There was an error fetching the soal data.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}
function deleteSoal(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this Soal?",
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
                url: `/soal/delete/${id}`, // Adjust the URL to match your route
                type: 'DELETE',
                success: function (response) {
                    $('#soal_table').DataTable().draw();
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The Soal has been deleted.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                },
                error: function (response) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an error deleting the Soal.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}

function generateEmptyPilihanGandaFields() {
    return `
        <div class="form-group">
            <label for="pilihan_ganda_a" class="col-sm-2 control-label">A</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" id="pilihan_ganda_a" name="pilihan_ganda_a" placeholder="Enter Option A">
            </div>
        </div>
        <div class="form-group">
            <label for="pilihan_ganda_b" class="col-sm-2 control-label">B</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" id="pilihan_ganda_b" name="pilihan_ganda_b" placeholder="Enter Option B">
            </div>
        </div>
        <div class="form-group">
            <label for="pilihan_ganda_c" class="col-sm-2 control-label">C</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" id="pilihan_ganda_c" name="pilihan_ganda_c" placeholder="Enter Option C">
            </div>
        </div>
        <div class="form-group">
            <label for="pilihan_ganda_d" class="col-sm-2 control-label">D</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" id="pilihan_ganda_d" name="pilihan_ganda_d" placeholder="Enter Option D">
            </div>
        </div>
    `;
}
function generatePilihanGandaFields(data) {
    return `
        <div class="form-group">
            <label for="pilihan_ganda_a" class="col-sm-2 control-label">A</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" id="pilihan_ganda_a" name="pilihan_ganda_a" placeholder="Enter Option A" value="${data.pertanyaan_a}">
            </div>
        </div>
        <div class="form-group">
            <label for="pilihan_ganda_b" class="col-sm-2 control-label">B</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" id="pilihan_ganda_b" name="pilihan_ganda_b" placeholder="Enter Option B" value="${data.pertanyaan_b}">
            </div>
        </div>
        <div class="form-group">
            <label for="pilihan_ganda_c" class="col-sm-2 control-label">C</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" id="pilihan_ganda_c" name="pilihan_ganda_c" placeholder="Enter Option C" value="${data.pertanyaan_c}">
            </div>
        </div>
        <div class="form-group">
            <label for="pilihan_ganda_d" class="col-sm-2 control-label">D</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" id="pilihan_ganda_d" name="pilihan_ganda_d" placeholder="Enter Option D" value="${data.pertanyaan_d}">
            </div>
        </div>
    `;
}
