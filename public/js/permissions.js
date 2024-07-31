let timeoutId;
$(document).ready(function() {
    tablePermissions();
});


$('#frmSearchPermissions').on('keyup', handleKeyUp);
function handleKeyUp() {
    // Clear previous timeout (if any)
    clearTimeout(timeoutId);

    // Set a new timeout to perform the search after a delay (e.g., 500 milliseconds)
    timeoutId = setTimeout(function() {
        // Call the function to perform the search
        searchPermissions();
    }, 500); // Adjust the delay as needed
}

function searchPermissions(){
    tablePermissions();
}


function tablePermissions() {
    var table = $('#tablePermissions');
    // Check if DataTable is already initialized
    if ($.fn.DataTable.isDataTable(table)) {
        // If DataTable is already initialized, destroy the existing instance
        table.DataTable().destroy();
    }
    table.DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true, // Add this line for responsiveness
        "ajax": {
            "url": "/permissions/data",
            "type": "GET",
            "data": function(d) {
                d.search = $('#frmSearchPermissions').val();
            }
        },
        "columns": [
            { "data": "name" }, // Adjust property names as per your JSON response
            { "data": "display_name" },
            { "data": "description" },
            {
                "data": "id", // Assuming 'id' is the unique identifier for each permission
                "render": function(data, type, row, meta) {
                    return `
                        <button class="btn btn-success btn-sm rounded-pill" onclick="editPermission(${data})" data-bs-toggle="tooltip" title="Edit Permission"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm rounded-pill" onclick="deletePermission(${data})" data-bs-toggle="tooltip" title="Delete Permission"><i class="fas fa-trash"></i></button>
                    `;
                }
            }
        ],
        "language": {
            // Alternatively, you can display an image
            "emptyTable": "<img src='/img/no-data.jpg' class='img-fluid' alt='No data available' style='height:75%;'>"
        }
    });
}


function deletePermission(data) {
    // Display Swal confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete this permission. This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
        reverseButtons: true,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        // Handle Swal confirmation result
        if (result.isConfirmed) {
            // Perform the delete operation here (you can call an AJAX request)
            $.ajax({
                url: '/permissions/delete', // Adjust the URL to your delete endpoint
                type: 'DELETE', // Adjust the HTTP method as needed
                data: { id: data }, // Pass the ID of the permission to be deleted
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token in the request headers
                },
                success: function(response) {
                    // Show success message
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The permission has been deleted.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Optionally, you can reload the DataTable after successful deletion
                    tablePermissions();
                },
                error: function(xhr, status, error) {
                    // Show error message
                    Swal.fire({
                        title: xhr.responseJSON.message,
                        text: xhr.responseJSON.message,
                        icon: status,
                        timer: 1000, // Timer in milliseconds (3 seconds in this example)
                        timerProgressBar: true, // Display a progress bar as the timer counts down
                        showConfirmButton: false
                    });
                }
            });
        }
    });
}


function editPermission(data) {
    // Extract data properties (adjust property names as per your JSON response)
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to edit this permission?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        // If the user confirms
        if (result.isConfirmed) {
            // Extract data properties (adjust property names as per your JSON response)
            Swal.fire({
                title: 'Loading',
                html: '<i class="fas fa-spinner fa-spin"></i> Fetching permission data...', // HTML content with spinner icon
                icon: 'info', // Set the icon to 'info' to use the spinner
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    // Before opening the loading dialog, disable the backdrop
                    Swal.getPopup().querySelector('.swal2-actions').style.pointerEvents = 'none';
                }
            });

            // Simulate an asynchronous operation to fetch data
            setTimeout(function() {
                // Close the loading Swal
                Swal.close();

                // Extract data properties (adjust property names as per your JSON response)
                tambahPermissions(data);
            }, 1500);
        }
    });
}


function tambahPermissions(data){
    $("#mdlForm").modal('show');

    $('#mdlFormContent').html("");
    $('#mdlFormContent').append(`
    <form id="inputPermissions" class="form" action="#">
            <input type="hidden" class="form-control form-control-solid" placeholder="Enter a permission name" name="id" id="id" />
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Permission Name</span>
                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Permission names is required to be unique." id="permissions_name_txt">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-solid" placeholder="Enter a permission name" name="name" id="name" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Display Name</span>
                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Display name is required to be unique.">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-solid" placeholder="Enter a display name" name="display_name" id="display_name" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Descriptions</span>
                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Descriptions is required to be unique.">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-solid" placeholder="Enter a descriptions" name="description" id="description" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->


            <div class="text-center pt-15">
                <button type="reset" class="btn btn-light me-3" data-kt-permissions-modal-action="cancel">Discard</button>
                <button type="submit" class="btn btn-primary" data-kt-permissions-modal-action="submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress d-none">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
            <!--end::Actions-->
        </form>
    `);

    if (data) {
        // Change modal title to "Edit Permissions"
        $("#mdlFormTitle").html("Edit Permissions");
        $.ajax({
            url: '/permissions/' + data+'/edit', // URL to fetch data (replace with your actual endpoint)
            type: 'GET',
            success: function(response) {
                // Call tambahPermissions function with the retrieved data
                Swal.fire({
                    title: response.title,
                    text: response.message,
                    icon: 'success',
                    timer: 1000, // Timer in milliseconds (3 seconds in this example)
                    timerProgressBar: true, // Display a progress bar as the timer counts down
                    showConfirmButton: false
                });

                $('#id').val(response.id);
                $('#name').val(response.name);
                $('#display_name').val(response.display_name);
                $('#description').val(response.description);
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
                // Handle error
                Swal.fire({
                    title: xhr.responseJSON.message,
                    text: xhr.responseJSON.message,
                    icon: status,
                    timer: 1000, // Timer in milliseconds (3 seconds in this example)
                    timerProgressBar: true, // Display a progress bar as the timer counts down
                    showConfirmButton: false
                });
                $("#mdlForm").modal('hide');
            }
        });
        // Extract data properties and populate the form fields

    } else {
        // Change modal title to "Form Permissions"
        $("#mdlFormTitle").html("Form Permissions");

        // Reset form fields
        $('#name').val('');
        $('#display_name').val('');
        $('#descriptions').val('');
    }

        // Form submit event
    $('#inputPermissions').on('submit', function(e) {
        // Prevent default form submission
        e.preventDefault();
        // Display loading indicator



        $('#inputPermissions').validate({
            rules: {
                name: {
                    required: true,
                    permissionsNameFormat: true
                },
                display_name: {
                    required: true
                },
                description: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter a permission name."
                },
                display_name: {
                    required: "Please enter a display name."
                },
                description: {
                    required: "Please enter a description."
                }
            },
            errorPlacement: function(error, element) {
                // Set red border on the input field when validation fails
                $(element).addClass('is-invalid');
                $(element).closest('.fv-row').find('[data-bs-toggle="popover"]').popover('dispose');
                $(element).closest('.fv-row').find('[data-bs-toggle="popover"]').popover({
                    content: error.text(),
                    trigger: 'manual',
                    placement: 'right'
                }).popover('show');
            },
            success: function(label, element) {
                // Remove error message popover on success
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
                $(element).closest('.fv-row').find('[data-bs-toggle="popover"]').popover('dispose');
            },
            highlight: function(element, errorClass, validClass) {
                // Set red border on the input field when validation fails
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                // Remove red border from the input field when validation passes
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                // Display loading indicator
                $(form).find('.indicator-label').addClass('d-none');
                $(form).find('.indicator-progress').removeClass('d-none');
                $(form).find('.indicator-progress').css('display', 'block');

                Swal.fire({
                    title: 'Are you sure?',
                    text: $("#id").val() != null ? 'Do you want to update permissions?' : 'Do you want to submit permissions ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user confirms, submit the form
                        $.ajax({
                            url: "/permissions/store", // Assuming you have a named route for storing permissions
                            type: 'POST',
                            data: $(form).serialize(), // Serialize form data
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token in the request headers
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                // Show loading indicator
                                $(this).find('.indicator-progress').removeClass('d-none');
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: response.title,
                                    text: response.message,
                                    icon: 'success',
                                    timer: 3000, // Timer in milliseconds (3 seconds in this example)
                                    timerProgressBar: true, // Display a progress bar as the timer counts down
                                    showConfirmButton: false
                                }).then(() => {
                                    $('#inputPermissions')[0].reset();
                                    $('#mdlForm').modal('hide');
                                    tablePermissions();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: xhr.responseJSON.title,
                                    text: xhr.responseJSON.message,
                                    icon: status,
                                    timer: 3000, // Timer in milliseconds (3 seconds in this example)
                                    timerProgressBar: true, // Display a progress bar as the timer counts down
                                    showConfirmButton: false
                                }).then(() => {
                                    $('#inputPermissions')[0].reset();
                                    $('#mdlForm').modal('hide');
                                    tablePermissions();
                                });
                            },
                            complete: function() {
                                // Hide loading indicator
                                $(this).find('.indicator-progress').addClass('d-none');
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // If user cancels, hide loading indicator
                        $(form).find('.indicator-progress').addClass('d-none');
                    }
                });
            }

        });
        $.validator.addMethod("permissionsNameFormat", function(value, element) {
            // Add your validation logic here to check if the value matches the desired format
            // For example, if the format is all lowercase letters separated by hyphens:
            return /^[a-z]+(-[a-z]+)*$/.test(value);
        }, "Please enter a valid permission name format (e.g., lowercase letters separated by hyphens).");
    });


}


