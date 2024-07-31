let timeoutId;
$(document).ready(function() {
    tableRoles();
});


$('#frmSearchRoles').on('keyup', handleKeyUp);
function handleKeyUp() {
    // Clear previous timeout (if any)
    clearTimeout(timeoutId);

    // Set a new timeout to perform the search after a delay (e.g., 500 milliseconds)
    timeoutId = setTimeout(function() {
        // Call the function to perform the search
        tableRoles();
    }, 500); // Adjust the delay as needed
}


function tableRoles() {
    var table = $('#tableRoles');

    // Check if DataTable is already initialized
    if ($.fn.DataTable.isDataTable(table)) {
        // If DataTable is already initialized, destroy the existing instance
        table.DataTable().destroy();
    }
    table.DataTable({
        "processing": true,
        "serverSide": true,
        "paging": true,
        "responsive": true,
        "ajax": {
            "url": "/roles/data",
            "type": "GET",
            "data": function(d) {
                d.search = $('#frmSearchRoles').val();
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
                        <button class="btn btn-success btn-sm rounded-pill" onclick="editRoles(${data})" data-bs-toggle="tooltip" title="Edit Permission"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-primary btn-sm rounded-pill" onclick="setPermissions(${data})" data-bs-toggle="tooltip" title="Tambah Permissions"><i class="fa-solid fa-ruler"></i></button>
                        <button class="btn btn-danger btn-sm rounded-pill" onclick="deleteRoles(${data})" data-bs-toggle="tooltip" title="Delete Permission"><i class="fas fa-trash"></i></button>
                    `;
                }
            }
        ],


    });
}
function deleteRoles(data) {
    // Display Swal confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete this roles. This action cannot be undone.',
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
                url: '/roles/delete', // Adjust the URL to your delete endpoint
                type: 'DELETE', // Adjust the HTTP method as needed
                data: { id: data }, // Pass the ID of the permission to be deleted
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token in the request headers
                },
                success: function(response) {
                    // Show success message
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The roles has been deleted.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Optionally, you can reload the DataTable after successful deletion
                    tableRoles();
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
                    tableRoles();

                }
            });
        }
    });
}


function editRoles(data) {
    // Extract data properties (adjust property names as per your JSON response)
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to edit this roles?',
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
                html: '<i class="fas fa-spinner fa-spin"></i> Fetching roles data...', // HTML content with spinner icon
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
                tambahRoles(data);
            }, 1500);
        }
    });
}


// Function to initialize the modal and form
function initializeModalAndForm() {
    console.log("masuk sini");
    // Show modal
    $("#mdlForm").modal('show');

    // Clear the modal content
    $('#mdlFormContent').empty();

    // Append the form HTML to the modal content
    $('#mdlFormContent').append(`
        <form id="choosePermissions" class="form" action="#">
            <div class="fv-row mb-7">
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Permissions</span>
                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Descriptions is required to be unique.">
                        <i class="ki-duotone ki-information fs-7">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </label>
                <div id="checkboxContainer"></div> <!-- Container to hold checkboxes -->
            </div>
            <div class="text-center pt-15">
                <button type="reset" class="btn btn-light me-3" data-kt-permissions-modal-action="cancel">Discard</button>
                <button type="submit" class="btn btn-primary" data-kt-permissions-modal-action="submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress d-none">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
    `);

    // Set the modal title
    $("#mdlFormTitle").html("Form Add Permissions");
}

// Function to fetch and populate permissions via AJAX
function fetchAndPopulatePermissions() {
    $.ajax({
        url: "/permissions/getAllPermissions",
        type: "GET",
        dataType: "json",
        success: function(response) {
            if (response.data) {
                // Group permissions by common part of name
                var groupedPermissions = response.data.reduce(function(acc, permission) {
                    var commonName = permission.name.split('-')[0]; // Extract common part of name
                    acc[commonName] = acc[commonName] || []; // Initialize array for the group if not exists
                    acc[commonName].push(permission); // Add permission to the group
                    return acc;
                }, {});

                // Populate checkboxes with fetched permissions
                var checkboxContainer = $('#checkboxContainer');
                Object.keys(groupedPermissions).forEach(function(name) {
                    var groupName = capitalizeFirstLetter(name); // Capitalize the first letter of each word in the group name
                    checkboxContainer.append(`
                        <div class="mb-3">
                            <h5>${groupName}</h5>
                        </div>
                    `);
                    groupedPermissions[name].forEach(function(permission) {
                        checkboxContainer.append(`
                            <div class="form-check mb-2"> <!-- Add margin bottom -->
                                <input class="form-check-input" type="checkbox" value="${permission.id}" id="checkbox${permission.id}">
                                <label class="form-check-label ms-2" for="checkbox${permission.id}"> <!-- Add margin left -->
                                    ${permission.display_name}
                                </label>
                            </div>
                        `);
                    });
                });
            }
        },
        error: function(xhr, status, error) {
            handleAjaxError(xhr, status, error);
        }
    });
}

// Function to fetch and populate permissions by role via AJAX
function fetchAndPopulatePermissionsByRole(roleId) {
    $.ajax({
        url: "/permissions/getPermissionsByRole",
        type: "GET",
        dataType: "json",
        data: { role_id: roleId },
        success: function(response) {
            console.log(response,'response');
            if (response.data) {
                response.data.forEach(function(permissions) {
                    $("#checkbox" + permissions.id).prop("checked", true);
                });
            }
        },
        error: function(xhr, status, error) {
            handleAjaxError(xhr, status, error);
        }
    });
}

// Function to handle form submission
function handleFormSubmission(roleId) {
    $('#choosePermissions').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        var selectedPermissions = $('#checkboxContainer').find('input[type="checkbox"]:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedPermissions.length === 0) {
            Swal.fire({
                title: 'Validation Error',
                text: 'Please select at least one permission.',
                icon: 'error',
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to submit permissions to this role?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/permissions/submitToRole',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        role_id: roleId,
                        permissions: selectedPermissions
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Submitted!',
                            text: 'Your permissions have been submitted to the role.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $("#mdlForm").modal('hide');
                    },
                    error: function(xhr, status, error) {
                        handleAjaxError(xhr, status, error);
                    }
                });
            }
        });
    });
}

// Main function to set permissions
function setPermissions(roleId) {
    initializeModalAndForm();
    fetchAndPopulatePermissions();
    setTimeout(() => {
        fetchAndPopulatePermissionsByRole(roleId);

    }, 1000);
    handleFormSubmission(roleId);
}

// Function to handle AJAX errors
function handleAjaxError(xhr, status, error) {
    Swal.fire({
        title: xhr.responseJSON.message || 'Error',
        text: xhr.responseJSON.message || 'An error occurred during the request.',
        icon: status,
        showConfirmButton: false,
        timer: 1500
    });
}

// Function to capitalize the first letter of each word
function capitalizeFirstLetter(str) {
    return str.replace(/\b\w/g, function(char) {
        return char.toUpperCase();
    });
}


$('#dismissModal').click(function() {
    // Hide or close the modal
    $("#mdlForm").modal('hide');
});

function tambahRoles(data){
    $("#mdlForm").modal('show');

    $('#mdlFormContent').html("");
    $('#mdlFormContent').append(`
    <form id="inputRoles" class="form" action="#">
            <input type="hidden" class="form-control form-control-solid" placeholder="Enter a permission name" name="id" id="id" />
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Roles Name</span>
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
                <input class="form-control form-control-solid" placeholder="Enter a roles name" name="name" id="name" />
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
        $("#mdlFormTitle").html("Edit Roles");
        $.ajax({
            url: '/roles/' + data+'/edit', // URL to fetch data (replace with your actual endpoint)
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

                $('#id').val(response.data.id);
                $('#name').val(response.data.name);
                $('#display_name').val(response.data.display_name);
                $('#description').val(response.data.description);
            },
            error: function(xhr, status, error) {
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
        $("#mdlFormTitle").html("Form Roles");

        // Reset form fields
        $('#name').val('');
        $('#display_name').val('');
        $('#description').val('');
    }

        // Form submit event
    $('#inputRoles').on('submit', function(e) {
        // Prevent default form submission
        e.preventDefault();
        // Display loading indicator



        $('#inputRoles').validate({
            rules: {
                name: {
                    required: true,
                    rolesNameFormat: true
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
                    required: "Please enter a roles name."
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
                    text: $("#id").val() != null ? 'Do you want to update roles?' : 'Do you want to submit roles ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user confirms, submit the form
                        $.ajax({
                            url: "/roles/store", // Assuming you have a named route for storing permissions
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
                                // Handle success response
                                // For example, display success message using SweetAlert
                                Swal.fire({
                                    title: response.title,
                                    text: response.message,
                                    icon: 'success',
                                    timer: 3000, // Timer in milliseconds (3 seconds in this example)
                                    timerProgressBar: true, // Display a progress bar as the timer counts down
                                    showConfirmButton: false
                                }).then(() => {
                                    // Actions to perform when the alert is closed
                                    // For example, reset the form
                                    $('#inputRoles')[0].reset();
                                    $('#mdlForm').modal('hide');
                                    tableRoles();
                                });
                                // Reset the form

                            },
                            error: function(xhr, status, error) {
                                console.log(xhr, status, error)
                                // Handle error response
                                // For example, display error message using SweetAlert
                                Swal.fire({
                                    title:  xhr.responseJSON.title,
                                    text: xhr.responseJSON.message,
                                    icon: status,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                tableRoles();
                                $(form).find('.indicator-label').removeClass('d-none');
                                $(form).find('.indicator-progress').addClass('d-none');
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
        $.validator.addMethod("rolesNameFormat", function(value, element) {
            // Add your validation logic here to check if the value matches the desired format
            // For example, if the format is all lowercase letters separated by hyphens:
            return /^[a-z]+(-[a-z]+)*$/.test(value);
        }, "Please enter a valid roles name format (e.g., lowercase letters separated by hyphens).");
    });


}


