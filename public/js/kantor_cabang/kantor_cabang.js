$(document).ready(function(){
    fetchData();
});

function fetchData() {
    $('#tableKantorCabang').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '/kantor_cabang/data', // Replace with your API endpoint
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'kode_cabang', name: 'kode_cabang' },
            { data: 'name', name: 'name' },
            { data: 'provinsi.name', name: 'provinsi.name' },
            { data: 'kabupaten.name', name: 'kabupaten.name' },
            { data: 'kecamatan.name', name: 'kecamatan.name' },
            { data: 'kelurahan.name', name: 'kelurahan.name' },
            {
                data: 'radius',
                name: 'radius',
                render: function(data, type, row) {
                    return data !== null ? data : '0';
                }
            },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false, render: function(data, type, row) {
                return `
                        <a href="#" class="btn-edit btn btn-sm btn-primary" data-id="${row.id}" onclick="editCabang('${row.id}')"><i class="fas fa-edit"></i></a>
                    <a href="#" class="btn-delete btn btn-sm btn-danger" data-id="${row.id}" onclick="deleteCabang('${row.id}')"><i class="fas fa-trash-alt"></i></a>
                `;
            }}
        ],
        drawCallback: function(settings) {
            // Re-attach event listeners for action buttons after each draw



        }
    });


}

function deleteCabang(id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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
            // Perform delete action
            $.ajax({
                url: '/kantor_cabang/' + id + '/delete', // Adjust the URL to match your route
                type: 'DELETE',
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        response.success,
                        'success'
                    );
                    // Optionally reload or update your data table
                    $('#tableKantorCabang').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Could not delete the cabang.',
                        'error'
                    );
                }
            });
        }
    });
}


function editCabang(id){
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, edit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/kantor_cabang/' + id + '/edit', // Adjust the URL to match your route
                type: 'GET',
                success: function(response) {
                    tambahCabang(response.data);
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Could not fetch the record data.',
                        'error'
                    );
                }
            });
            // Perform edit action
        }
    });
}



function tambahCabang(value) {
    $("#mdlFormTitle").html("Form Cabang");
    var formContent = `
        <form id="cabangForm">
            <input type="hidden" class="form-control" id="id" name="id" placeholder="ID">
            <div class="form-group">
                <label for="kode_cabang">Kode Cabang</label>
                <input type="text" class="form-control" id="kode_cabang" name="kode_cabang" placeholder="Enter Kode Cabang" required>
            </div>
            <div class="form-group">
                <label for="name">Nama Cabang</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Nama Cabang" required>
            </div>
            <div class="form-group">
                <label for="provinsi">Provinsi</label>
                <select class="form-control" id="provinsi" name="provinsi_id" required style="width: 100%;"></select>
            </div>
            <div class="form-group">
                <label for="kabupaten">Kabupaten</label>
                <select class="form-control" id="kabupaten" name="kabupaten_id" required style="width: 100%;"></select>
            </div>
            <div class="form-group">
                <label for="kecamatan">Kecamatan</label>
                <select class="form-control" id="kecamatan" name="kecamatan_id" required style="width: 100%;"></select>
            </div>
            <div class="form-group">
                <label for="kelurahan">Kelurahan</label>
                <select class="form-control" id="kelurahan" name="kelurahan_id" required style="width: 100%;"></select>
            </div>

            <div class="form-group">
                <label for="radius">Radius (in meters)</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="radius" name="radius" placeholder="Enter Radius" required>
                    <div class="input-group-append">
                        <span class="input-group-text">m</span>
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    `;

    // Set the form content in the modal body
    $('#mdlFormContent').html(formContent);
    $("#mdlForm").modal('show');


    // If editing existing data, populate the form fields
    if (value !== undefined) {
        console.log("value",value);
        $('#id').val(value.id);
        $('#kode_cabang').val(value.kode_cabang);
        $('#name').val(value.name);
        fetchDataProvinsi(value.provinsi_id);

        // $('#provinsi').val(value.provinsi_id); // Assuming provinsi name is what you want to display
        // $('#kabupaten').val(value.kabupaten_id); // Assuming kabupaten name is what you want to display
        // $('#kecamatan').val(value.kecamatan_id); // Assuming kecamatan name is what you want to display
        // $('#kelurahan').val(value.kelurahan_id); // Assuming kelurahan name is what you want to display
        // $('#radius').val(value.radius);
    }else{
        fetchDataProvinsi();
    }

    // Initialize form validation
    $('#cabangForm').validate({
        rules: {
            kode_cabang: {
                required: true,
                minlength: 3
            },
            name: {
                required: true,
                minlength: 3
            },
            provinsi: {
                required: true
            },
            kabupaten: {
                required: true
            },
            kecamatan: {
                required: true
            },
            kelurahan: {
                required: true
            },
            radius: {
                required: true,
                number: true,
                min: 1 // Minimum radius value (adjust as needed)
            }
        },
        messages: {
            kode_cabang: {
                required: "Please enter the Kode Cabang",
                minlength: "Kode Cabang must be at least 3 characters long"
            },
            name: {
                required: "Please enter the Nama Cabang",
                minlength: "Nama Cabang must be at least 3 characters long"
            },
            provinsi: {
                required: "Please enter Provinsi"
            },
            kabupaten: {
                required: "Please enter Kabupaten"
            },
            kecamatan: {
                required: "Please enter Kecamatan"
            },
            kelurahan: {
                required: "Please enter Kelurahan"
            },
            radius: {
                required: "Please enter the Radius",
                number: "Please enter a valid number for Radius",
                min: "Radius must be at least 1 meter" // Adjust message as needed
            }
        },
        submitHandler: function(form) {
            // SweetAlert2 confirmation dialog
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
                    // Get the CSRF token from the meta tag
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Serialize form data
                    var formData = $(form).serializeArray();
                    formData.push({ name: '_token', value: csrfToken }); // Add CSRF token to form data

                    // Submit the form via AJAX or similar method
                    $.ajax({
                        type: "POST",
                        url: "/kantor_cabang/store", // Replace with your form submission URL
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // Set CSRF token in headers
                        },
                        success: function(response) {
                            // Handle successful submission
                            Swal.fire(
                                'Submitted!',
                                'Your form has been submitted.',
                                'success'
                            ).then(() => {
                                // Reload the page or update as needed
                                location.reload();
                            });
                            $('#mdlForm').modal('hide');
                        },
                        error: function(response) {
                            // Handle errors
                            Swal.fire(
                                'Error!',
                                'There was an error submitting your form.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    });
}



