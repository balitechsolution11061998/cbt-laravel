$(document).ready(function() {
    tableSettingsPriceChange ();
});



function tableSettingsPriceChange() {
    var table = $('#tablePriceChange');

    // Check if DataTable is already initialized
    if ($.fn.DataTable.isDataTable(table)) {
        // If DataTable is already initialized, destroy the existing instance
        table.DataTable().destroy();
    }

    table.DataTable({
        "processing": true,
        "serverSide": true,
        "pagination":true,
        "ajax": {
            "url": "/settings/price-change/data",
            "type": "GET",
            "data": function(d) {
                d.search = $('#frmSearchRoles').val();
            },
            "error": function(xhr, error, thrown) {
                if (xhr.status === 404) {
                    Toastify({
                        text: "Sorry, the data is not available. Please contact support for assistance.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true
                    }).showToast();
                } else {
                    Toastify({
                        text: "An error occurred in the system. Please contact support for assistance.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true
                    }).showToast();
                }
            }
        },
        "columns": [
            {
                "data": "roles",
                "render":function(data, type, row){
                    console.log(row,'row');
                    return row.roles.name;
                }

            },
            { "data": "position" },
            { "data": "region_id" },
            {
                "data": "id",
                "render": function(data) {
                    return `
                        <button class="btn btn-success btn-sm rounded-pill" onclick="editApprovalPriceChange(${data})" data-bs-toggle="tooltip" title="Edit Approval Price Change"><i class="fas fa-edit"></i></button>
                    `;
                }
            }
        ]
    });
}


function tambahMappingPriceList(data){
    $("#mdlForm").modal('show');

    $('#mdlFormContent').html("");
    $('#mdlFormContent').append(`
    <form id="inputApproval" class="form" action="#">
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
                <select class="form-control form-control-solid" id="role_id" name="role_id"></select>
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Position</span>
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
                <input class="form-control form-control-solid" placeholder="Position..." name="position" id="position" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Regions</span>
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
                <select class="form-control form-control-solid" id="region_id" name="region_id">
                    <option value="1">Bali</option>
                    <option value="2">Lombok</option>
                    <option value="3">Makasar</option>
                    <option value="4">Head Office</option>
                </select>
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
        $("#mdlFormTitle").html("Form Add Approval");

        // Reset form fields
        $('#name').val('');
        $('#display_name').val('');
        $('#description').val('');
    }

        // Form submit event
    $('#inputApproval').on('submit', function(e) {
        // Prevent default form submission
        e.preventDefault();
        // Display loading indicator



        $('#inputApproval').validate({
            rules: {
                role_id: {
                    required: true,
                },
                position: {
                    required: true
                },
                region_id: {
                    required: true
                }
            },
            messages: {
                role_id: {
                    required: "Please enter a roles."
                },
                position: {
                    required: "Please enter a position."
                },
                region_id: {
                    required: "Please enter a region."
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
                    text: $("#id").val() != null ? 'Do you want to update settings approval price change ?' : 'Do you want to submit settings approval price change ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user confirms, submit the form
                        $.ajax({
                            url: "/settings/price-change/store", // Assuming you have a named route for storing permissions
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
                                    $('#mdlForm').modal('hide');
                                    tableSettingsPriceChange();
                                });
                                // Reset the form

                            },
                            error: function(xhr, status, error) {
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

    fetchDataRoles();

}

function fetchDataRoles(){
    $('#role_id').select2({
        placeholder: 'Enter a roles name',
        ajax: {
            url: '/roles/getAllRoles',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            },
            cache: true
        }
    });
}
