function createUser() {
    // Show a loading indicator using SweetAlert2
    Swal.fire({
        title: "Loading...",
        text: "Please wait while we redirect you.",
        didOpen: () => {
            Swal.showLoading();
        },
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
    });
    console.log("masuk sini nggak aaaa");
    // Simulate the process of opening the link to create a user
    setTimeout(function () {
        // Redirect to the create user link
        window.location.href = "/users/create";
    }, 2000); // Simulated delay for demonstration
}

$(document).ready(function () {
    fetchDataItem(null);
    $('#items').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        fetchDataItem(searchText);
    });
});

function fetchDataItem(search) {

    if ($.fn.DataTable.isDataTable("#items_table")) {
        $("#items_table").DataTable().destroy();
    }

    $("#items_table").DataTable({
        responsive: true, // Enable responsive extension
        processing: true,
        serverSide: true,
        ajax: {
            url:"/items/data",
            data:{
                search:search,
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 404) {
                    Toastify({
                        text: "Error 404: Link not found.",
                        duration: 3000, // Duration in milliseconds
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        backgroundColor: "#f44336", // Red color
                    }).showToast();
                }
            }
        },
        columns: [
            { data: "id", name: "id" },
            {
                data: "supplier",
                name: "supplier",
                render: function (data, type, row) {
                    const combinedText = row.supplier + " - " + row.sup_name;
                    const maxLength = 50; // Adjust as needed
                    const truncatedText = combinedText.length > maxLength ? combinedText.substr(0, maxLength) + '...' : combinedText;
                    const readMoreLink = combinedText.length > maxLength ? '<span class="read-more">Read more</span>' : '';
                    return '<span class="truncated-text">' + truncatedText + '</span>' + readMoreLink;
                },
            },
            {
                data: "sku",
                name: "sku",
                render: function (data, type, row) {
                    return '<a href="#" class="sku-link number-font" data-toggle="tooltip" title="Click to show description">' + data + '</a>';
                },
            },
            {
                data: "sku_desc",
                name: "sku_desc",
                visible: false // Initially hide the description column
            },

            {
                data: "upc",
                name: "upc",
                render: function (data, type, row) {
                    return '<span class="number-font">'+data+'</span>'

                },
            },
            {
                data: "unit_cost",
                name: "unit_cost",
                render: function (data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        return '<span class="number-font">Rp ' + formatRupiah(data) + '</span>';
                    }
                    return data;
                },
            },
            {
                data: null,
                name: "create_info",
                render: function (data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        var createInfo = row.create_id + ' - ' + row.create_date;
                        return createInfo;
                    }
                    return data.create_id; // Return just the create_id for other types (sort, etc.)
                },
            },

            {
                data: null,
                name: "update_info",
                render: function (data, type, row) {
                    if (row.last_update_id != null) {
                        var updateInfo = row.last_update_id + ' - ' + row.last_update_date;
                        return updateInfo;
                    }else{
                        return "Not Found Data";
                    }
                },
            },



        ],
    });

    $(document).on('click', '.sku-link', function (e) {
        e.preventDefault();
        var rowIndex = $(this).closest('tr').index();
        var skuDesc = $('#items_table').DataTable().row(rowIndex).data().sku_desc;

        // Create Intro.js steps for guiding the user
        var steps = [
            {
                title: '<span style="color: black;">SKU Description</span>',
                intro:
                    '<strong>SKU:</strong> ' + $(this).text() + '<br>' +
                    '<strong>Description:</strong> ' + skuDesc,
                position: 'bottom'
            }
        ];

        // Start the Intro.js tour
        introJs().setOptions({
            steps: steps,
            showProgress: true,
            scrollToElement: true,
            overlayOpacity: 0.5
        }).start();
    });



    $('[data-fancybox="gallery"]').fancybox({
        // Options if needed
    });

    // Re-initialize Fancybox after DataTable redraw (if using AJAX or other redraw methods)
    $('#users_table').on('draw.dt', function() {
        $('[data-fancybox="gallery"]').fancybox({
            // Options if needed
        });
    });

    // Toggle password visibility
    $(document).on("click", ".toggle-password", function () {
        const input = $(this).siblings("input");
        const type = input.attr("type") === "password" ? "text" : "password";
        input.attr("type", type);
        $(this).toggleClass("fa-eye fa-eye-slash");
    });

        // Swal confirmation and link opening for Edit button

}


function deleteUser(userId) {
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
            // Make an AJAX request to delete the user
            $.ajax({
                url: '/users/delete/' + userId,
                type: 'DELETE',
                success: function(result) {
                    Swal.fire(
                        'Deleted!',
                        'User has been deleted.',
                        'success'
                    );
                    // Refresh the DataTable
                    fetchDataUser();
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'There was an error deleting the user.',
                        'error'
                    );
                }
            });
        }
    });
}

function editUser(value){
    Swal.fire({
        title: "Edit User",
        text: "Are you sure you want to edit this user?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, edit it!",
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                // Simulate API call or any asynchronous operation
                setTimeout(() => {
                    resolve();
                }, 1000); // Adjust delay as needed
            });
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Open edit link using jQuery
            window.location.href = "/users/" + value + "/edit";
        }
    });
}

function resetPassword(value) {
    Swal.fire({
        title: "Are you sure?",
        text: "This action will reset the user's password.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, reset it!",
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `/users/reset-password/${value}`,
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (response) {
                        resolve(response);
                    },
                    error: function (xhr, status, error) {
                        reject("Error resetting password: " + error);
                    },
                });
            });
        },
    })
        .then((result) => {
            if (result.isConfirmed) {
                // Display Swal (SweetAlert) success message
                Swal.fire({
                    title: "Password Reset!",
                    text: "Password reset email sent successfully.",
                    icon: "success",
                    timer: 3000, // Set duration in milliseconds (e.g., 3000 for 3 seconds)
                    showConfirmButton: false, // Hide the "OK" button
                });

                // Display Toastify message
                Toastify({
                    text: "Password reset email sent successfully.",
                    duration: 3000, // Display duration in milliseconds
                    close: true,
                    gravity: "bottom", // Display position: 'top' or 'bottom'
                    position: "right", // Display position: 'left', 'center', or 'right'
                    backgroundColor:
                        "linear-gradient(to right, #00b09b, #96c93d)", // Custom background color
                }).showToast();

                // Reload the page after a delay
                setTimeout(function () {
                    location.reload();
                }, 3000);
            }
        })
        .catch((error) => {
            Swal.fire("Error!", error, "error");
        });
}
