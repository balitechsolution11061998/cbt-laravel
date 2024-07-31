$(document).ready(function() {
    tablePriceChange();
});

function uploadPriceChange(){
    Swal.fire({
        title: 'Uploading Price List',
        html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            // Simulate loading process, replace with your actual upload process
            setTimeout(() => {
                // Once loading is complete, open your modal here
                $('#mdlForm').modal('show');
                $('#mdlFormTitle').html("Upload File Price Change");
                // Ensure the CSRF token is included properly
                $('#mdlFormContent').html(``);
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $('#mdlFormContent').html(`    <form id="dropzoneForm" class="dropzone" action="/pricelist/upload" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="${csrfToken}">
        <div class="dz-message" data-dz-message>
            <span>Drag and drop files here or click to upload</span><br>
            <i class="far fa-file-pdf fa-4x text-danger mt-2"></i>
        </div>
    </form>`);

                // Initialize Dropzone
                Dropzone.autoDiscover = false;
                var myDropzone = new Dropzone("#dropzoneForm", {
                    url: "/price-change/upload",
                    paramName: "file",
                    maxFilesize: 10, // MB
                    addRemoveLinks: true,
                    acceptedFiles: ".csv,.xlsx", // Specify accepted file types
                    dictDefaultMessage: "Drop files here or click to upload",
                    dictRemoveFile: "Remove file",
                    success: function (file, response) {
                        // Handle success
                        console.log(response);
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: 'success',
                            timer: 1000, // Timer in milliseconds (3 seconds in this example)
                            timerProgressBar: true, // Display a progress bar as the timer counts down
                            showConfirmButton: false
                        });
                        $('#mdlForm').modal('hide');
                        tablePriceChange();

                    },
                    error: function (file, response) {
                        // Handle error
                        console.log(response);
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            icon: response.icon,
                            timer: 3000, // Timer in milliseconds (3 seconds in this example)
                            timerProgressBar: true, // Display a progress bar as the timer counts down
                            showConfirmButton: false
                        });
                    }
                });

                Swal.close();
            }, 2000); // Change the time to match your loading process
        }
    });
}

function showDownloadModal() {
    Swal.fire({
        title: "Download Format Import",
        text: "Apakah Anda ingin mengunduh format import dari excel atau csv?",
        icon: "info",
        showConfirmButton: true,
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading indicator
            Swal.fire({
                title: "Downloading...",
                text: "Please wait while the file is being downloaded.",
                icon: "info",
                showConfirmButton: false,
                allowOutsideClick: false
            });

            // Initiate the download process
            window.location.href = "/price-change/download";

            // Simulate a delay for download completion (for demonstration purposes)
            setTimeout(() => {
                Swal.fire({
                    title: "Success!",
                    text: "The file has been downloaded successfully.",
                    icon: "success",
                    showConfirmButton: true
                });
            }, 3000); // Adjust the timeout duration as needed
        }
    });
}


function detailPriceChange(data) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to show this price change?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Extract data properties (adjust property names as per your JSON response)
            Swal.fire({
                title: 'Loading',
                html: '<i class="fas fa-spinner fa-spin"></i> Fetching price change data...', // HTML content with spinner icon
                icon: 'info', // Set the icon to 'info' to use the spinner
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    // Before opening the loading dialog, disable the backdrop
                    Swal.getPopup().querySelector('.swal2-actions').style.pointerEvents = 'none';
                }
            });
            setTimeout(function() {
                // Close the loading Swal
                Swal.close();

                // Extract data properties (adjust property names as per your JSON response)
                tambahPriceList(data);
            }, 1500);
        }
    });
}

function tambahPriceList(data){
    var dataItemPriceChange = [];
    $("#mdlForm").modal('show');

    $('#mdlFormContent').html("");
    $('#mdlFormContent').append(`
    <form id="inputPriceChange" class="form" action="#">
            <input type="hidden" class="form-control form-control-solid" placeholder="Enter a permission name" name="id" id="id" />
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Active Date</span>
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
                <input type="date" class="form-control form-control-solid" placeholder="Enter a active date" name="active_date" id="active_date" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Pricelist Name</span>
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
                <input class="form-control form-control-solid" placeholder="Enter a price list desc" name="pricelist_desc" id="pricelist_desc" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <div class="fv-row mb-7">
                <div class="col-md-12" id="btnPlusPriceChange">
                    <a href="#" class="btn btn-sm btn-primary font-12" title="Add" id="addBtn"><i class="fa fa-plus"></i> Add Item</a>
                </div>
                <br>
                <table class="table table-bordered table-striped table-sm table-hover nowrap responsive" id="pricelist">
                    <thead class="text-center" style="font-size: 15px;">
                        <tr>
                            <th>BARCODE</th>
                            <th id="item_desc_clmn" style="display_none">ITEM DESC</th>
                            <th>OLD COST</th>
                            <th>NEW COST</th>
                            <th id="action_clmn"> ...</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <!-- Reset Checkbox -->
            <div class="form-check mb-4" id="checkboxReject" style="display:none;">
                <input class="form-check-input" type="checkbox" value="" id="rejectCheckboxId">
                <label class="form-check-label" for="rejectCheckboxId">
                    Klik di sini untuk menambahkan alasan saat menolak.
                </label>
            </div>

            <div class="fv-row mb-7" id="reasonDiv" style="display:none;">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold form-label mb-2">
                    <span class="required">Reason</span>
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
                <textarea class="form-control form-control-solid" placeholder="Enter a reason" name="reason" id="reason"></textarea>
                <!--end::Input-->
            </div>

            <div class="text-center pt-15" id="btnPriceList">
                <button type="reset" class="btn btn-light me-3" data-kt-permissions-modal-action="cancel">Discard</button>
                <button type="submit" class="btn btn-primary" data-kt-permissions-modal-action="submit">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress d-none">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
            <div class="text-center pt-15" id="btnApprovePriceList" style="display:none;">
                <button type="submit" class="btn btn-success btn-sm" data-kt-permissions-modal-action="submit" id="btnApprovePriceChange">
                    <span class="indicator-label">Approve</span>
                    <span class="indicator-progress d-none">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <button type="submit" class="btn btn-danger btn-sm" data-kt-permissions-modal-action="submit" id="btnRejectPriceChange" style="display:none;">
                    <span class="indicator-label">Reject</span>
                    <span class="indicator-progress d-none">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>

            <!--end::Actions-->
        </form>
    `);

    var activeDateInput = document.getElementById('active_date');

    // Dapatkan tanggal saat ini dalam format ISO (YYYY-MM-DD)
    var today = new Date().toISOString().split('T')[0];

    // Setel nilai atribut min dari input date ke tanggal saat ini
    activeDateInput.setAttribute('min', today);

    $('.new-cost').on('keyup', function() {
        console.log("masuk sini");
        // Ambil nilai dari input
        var newValue = $(this).val();

        // Konversi nilai ke integer atau float untuk validasi
        var numericValue = parseFloat(newValue.replace(/[^\d.-]/g, ''));

        // Validasi jika nilai sama dengan 0
        if (numericValue === 0) {
            // Tampilkan pesan kesalahan
            Toastify({
                text: "New cost cannot be 0. Please enter a valid amount.",
                duration: 3000,
                gravity: "bottom",
                position: "right",
                backgroundColor: "red",
            }).showToast();

            // Kosongkan atau atur nilai input kembali ke nilai sebelumnya
            $(this).val('');
        }
    });

    $('#rejectCheckboxId').on('change',function() {
        // Periksa apakah checkbox dicentang
        console.log("masuk sini1");

        if ($(this).is(':checked')) {
            // Jika ya, tampilkan input alasan
            $('#reasonDiv').show();
            $("#btnRejectPriceChange").show();
        } else {
            // Jika tidak, sembunyikan input alasan
            $('#reasonDiv').hide();
            $("#btnRejectPriceChange").hide();
        }
    });


    var rowIdx = 1;

    $("#pricelist tbody").on("click", ".remove", function ()
        {
            // Getting all the rows next to the row
            // containing the clicked button
            var child = $(this).closest("tr").nextAll();
            // Iterating across all the rows
            // obtained to change the index
            child.each(function () {
            // Getting <tr> id.
            var id = $(this).attr("id");

            // Getting the <p> inside the .row-index class.
            var idx = $(this).children(".row-index").children("p");

            // Gets the row number from <tr> id.
            var dig = parseInt(id.substring(1));

            // Modifying row index.
            idx.html(`${dig - 1}`);

            // Modifying row id.
            $(this).attr("id", `R${dig - 1}`);
        });

        // Removing the current row.
        $(this).closest("tr").remove();

        // Decreasing total number of rows by 1.
        rowIdx = rowIdx-1;
    });

    $("#addBtn").on("click", function ()
    {
            // Adding a row inside the tbody.
            var newRow = `
                <tr id="R${rowIdx+1}">
                    <td style="width: 30%;"> <!-- Adjust width as needed -->
                        <select name="barcode[]" class="form-control select" style="width: 100%" required>
                            <option value=""> ...</option>
                        </select>
                    </td>
                    <td style="width: 30%;" id="item-desc">
                        <input class="form-control rupiah item-desc" type="text" name="item_desc[]" style="background: white;" readonly>
                    </td>
                    <td style="width: 30%;"> <!-- Adjust width as needed -->
                        <input class="form-control rupiah old-cost" type="text" name="old_cost[]" style="background: white;" readonly>
                    </td>
                    <td style="width: 30%;"> <!-- Adjust width as needed -->
                        <input class="form-control rupiah new-cost" type="text" id="rp${++rowIdx}" name="new_cost[]" required>
                    </td>
                    <td style="width: 10%;" class="text-center"> <!-- Adjust width as needed -->
                        <button class="btn btn-sm btn-danger remove"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            $("#pricelist tbody").append(newRow);
            $("#item-desc").hide();
            $('.select').select2();

            // Fetch data for select element
            $.ajax({
                url: '/items/getDataItemSupplierBySupplier',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    dataItemPriceChange = response.data;

                    if (response && response.data) {
                        var selectElement = $('#R' + rowIdx).find('select[name="barcode[]"]');
                        selectElement.empty(); // Clear existing options
                        selectElement.append('<option value="">Select Barcode</option>'); // Add default option

                        $.each(response.data, function(index, item) {
                            selectElement.append('<option value="' + item.upc + '">' + item.upc + '-' + item.sku_desc + '</option>'); // Add option for each data item
                        });

                        // Initialize Select2 AFTER options are added, with dropdownParent set to #mdlForm
                        selectElement.select2({
                            dropdownParent: $('#mdlForm')
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });


        // Event listener for changing select
        $("#pricelist tbody").on("change", 'select[name="barcode[]"]', function() {
            var selectedOption = $(this).find(':selected');
            var item_descInput = $(this).closest('tr').find('.item-desc');
            var oldCostInput = $(this).closest('tr').find('.old-cost');
            var barcode = selectedOption.val();

            const priceslist = dataItemPriceChange;

            const price = priceslist.find(function(list) {
                return list.upc == barcode;
            });
            oldCostInput.val(price.unit_cost);
            item_descInput.val(price.sku_desc);
        });




        // FORMAT RP
        var rupiah = document.getElementById('rp' +rowIdx);
        rupiah.addEventListener('keyup', function(e)
        {
            rupiah.value = formatRupiah(this.value);
        });

        function formatRupiah(angka, prefix)
        {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split    = number_string.split(','),
                sisa     = split[0].length % 3,
                rupiah     = split[0].substr(0, sisa),
                ribuan     = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    });

    if (data) {
        // Change modal title to "Edit Permissions"
        $("#mdlFormTitle").html("Show Price List");
        $.ajax({
            url: '/price-change/' + data+'/show', // URL to fetch data (replace with your actual endpoint)
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

                $('#id').val(response.data.price_change.id);
                $('#active_date').val(response.data.price_change.active_date);
                $('#pricelist_desc').val(response.data.price_change.pricelist_desc);
                var tableBody = $('#pricelist tbody');
                $("#item_desc_clmn").show();
                $("#action_clmn").hide();
                tableBody.empty(); // Clear existing rows
                response.data.price_change.price_list_details.forEach(function(detail, index) {
                    var newRow = `
                        <tr id="R${rowIdx+1}">
                            <td>
                                <input type="text" class="form-control" name="barcode[]" value="${detail.barcode}" readonly >
                            </td>
                            <td>
                                <input type="text" class="form-control rupiah old-cost" name="item_desc[]" value="${detail.item_desc}" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control rupiah old-cost" name="old_cost[]" value="${detail.old_cost}" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control rupiah new-cost" name="new_cost[]" value="${detail.new_cost || ''}" required>
                            </td>

                        </tr>`;
                    tableBody.append(newRow);
                });
                $("#btnPriceList").hide();
                $("#btnPlusPriceChange").hide();

                for (var i = 0; i < response.data.permissions.length; i++) {
                    if (response.data.permissions[i] == 'cost_change-approval' && response.data.price_change.status != "approve" && response.data.price_change.status != "reject") {
                            $("#btnApprovePriceList").show();
                            $("#checkboxReject").show();
                    }
                    else{
                        $("#btnApprovePriceList").hide();
                        $("#checkboxReject").hide();

                    }

                }
                if (response.data.price_change.history.length > 0) {
                // Append the timeline template
                $("#mdlFormContent").append(`
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title">History</h2>
                                <div id="content">
                                    <ul class="timeline">
                                        ${
                                            response.data.price_change.history.map(event => `
                                                <li class="event" data-date="${formatDate(event.created_at)}">
                                                    <div class="event-icon"><i class="fas fa-clock"></i></div>
                                                    <div class="event-content">
                                                        <h3>${capitalizeFirstLetter(event.status)}</h3>
                                                        <p>Pengajuan Price change anda telah di ${capitalizeFirstLetter(event.status)} pada tanggal ${formatDate(event.created_at)}${event.status === 'reject' ? ` dengan alasan: ${event.reason}` : ''}</p>
                                                    </div>
                                                </li>
                                            `).join('')
                                        }
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                } else {
                    // Add image or other content when there is no data
                    $("#mdlFormContent").append(`
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                <h2 class="card-title">History Approval</h2>
                                <p>No data available</p>
                                </div>
                            </div>
                        </div>
                    `);
                }

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
        $("#mdlFormTitle").html("Form Price List");
        $("#item_desc_clmn").hide();

        // Reset form fields
        $('#name').val('');
        $('#display_name').val('');
        $('#description').val('');
    }

    $("#btnApprovePriceChange").on('click',function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to approve the price change.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            // If user confirms, proceed with the action
            if (result.isConfirmed) {
                $.ajax({
                    url: "/price-change/approve", // Assuming you have a named route for storing permissions
                    type: 'POST',
                    data: $("#inputPriceChange").serialize(), // Serialize form data
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
                            $('#inputPriceChange')[0].reset();
                            $('#mdlForm').modal('hide');
                            tablePriceChange();
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
                        tablePriceChange();
                        $(form).find('.indicator-label').removeClass('d-none');
                        $(form).find('.indicator-progress').addClass('d-none');
                    },
                    complete: function() {
                        // Hide loading indicator
                        $(this).find('.indicator-progress').addClass('d-none');
                    }
                });
            }
        });
    });

    $("#btnRejectPriceChange").on('click',function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to reject the price change.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            // If user confirms, proceed with the action
            if (result.isConfirmed) {
                $.ajax({
                    url: "/price-change/reject", // Assuming you have a named route for storing permissions
                    type: 'POST',
                    data: $("#inputPriceChange").serialize(), // Serialize form data
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
                            $('#inputPriceChange')[0].reset();
                            $('#mdlForm').modal('hide');
                            tablePriceChange();
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
                        tablePriceChange();
                        $(form).find('.indicator-label').removeClass('d-none');
                        $(form).find('.indicator-progress').addClass('d-none');
                    },
                    complete: function() {
                        // Hide loading indicator
                        $(this).find('.indicator-progress').addClass('d-none');
                    }
                });
            }
        });
    });

        // Form submit event
    $('#inputPriceChange').on('submit', function(e) {
        // Prevent default form submission
        e.preventDefault();
        // Display loading indicator



        $('#inputPriceChange').validate({
            rules: {
                active_date: {
                    required: true,
                },
                pricelist_desc: {
                    required: true,
                },

            },
            messages: {
                active_date: {
                    required: "Please enter a active date."
                },
                pricelist_desc: {
                    required: "Please enter a price list."
                },
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
                    text: $("#id").val() != null ? 'Do you want to update price list?' : 'Do you want to submit price list ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user confirms, submit the form
                        $.ajax({
                            url: "/price-change/store", // Assuming you have a named route for storing permissions
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
                                    $('#inputPriceChange')[0].reset();
                                    $('#mdlForm').modal('hide');
                                    tablePriceChange();
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
                                tablePriceChange();
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


function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', options);
}

// Helper function to capitalize the first letter of a string
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


function tablePriceChange() {
    var table = $('#tablePriceChange');

    // Check if DataTable is already initialized
    if ($.fn.DataTable.isDataTable(table)) {
        // If DataTable is already initialized, destroy the existing instance
        table.DataTable().destroy();
    }

    table.DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/price-change/data",
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
            { "data": "pricelist_no" },
            { "data": "pricelist_desc" },
            {
                "data": "active_date",
                "render": function(data) {
                    var date = new Date(data);
                    var options = { year: 'numeric', month: 'long', day: '2-digit' };
                    return date.toLocaleDateString('id-ID', options);
                }
            },
            { "data": "supplier_id" },
            {
                "data": "supplier",
                "render": function(data, type, row) {
                    return data !== null ? row.suppliers.supp_name : "-";
                }
            },
            {
                "data": "user",
                "render": function(data, type, row) {
                    if (row.users !== null && row.users.name !== null) {
                        return '<span class="badge badge-pretty custom-info-badge">' +
                               '<i class="fas fa-user mr-1"></i>' + row.users.name + '</span>';
                    } else {
                        return '<div class="spinner-border text-info" role="status">' +
                               '<span class="sr-only">Loading...</span></div>';
                    }
                }
            },
            {
                "data": "status",
                "render": function(data) {
                    if (data === 'progress') {
                        return '<span class="badge badge-pretty custom-badge custom-progress"><i class="fas fa-spinner fa-spin mr-1"></i>Progress</span>';
                    } else if (data === 'approve') {
                        return '<span class="badge badge-pretty custom-approve"><i class="fas fa-check mr-1"></i>Approve</span>';
                    } else if (data === 'reject') {
                        return '<span class="badge badge-pretty custom-reject"><i class="fas fa-times mr-1"></i>Reject</span>';
                    } else {
                        return '<span class="badge badge-pretty custom-progress"><i class="fas fa-spinner fa-spin mr-1"></i>Progress</span>';
                    }
                }
            },
            {
                "data": "id",
                "render": function(data) {
                    return `
                        <button class="btn btn-success btn-sm rounded-pill" onclick="detailPriceChange(${data})" data-bs-toggle="tooltip" title="Detail Price Change"><i class="fas fa-edit"></i></button>
                    `;
                }
            }
        ]
    });
}


