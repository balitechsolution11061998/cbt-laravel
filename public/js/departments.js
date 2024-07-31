$(document).ready(function() {
    var table = $("#tableDepartments").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "/departments/getData",
        columns: [
            { data: "kode_department", name: "kode_department" },
            { data: "name", name: "name" },
            { data: "descriptions", name: "descriptions" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="editDepartment(${row.id})"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" onclick="deleteDepartment(${row.id})"><i class="fas fa-trash-alt"></i></a>
                    `;
                }
            }
        ],
    });

    // Define functions to handle edit and delete actions
    window.editDepartment = function(id) {
        // Implement edit functionality here
        Swal.fire({
            title: 'Edit Department',
            text: 'Edit department with ID: ' + id,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Edit'
        }).then((result) => {
            if (result.isConfirmed) {
                // Handle the edit action here
                $.ajax({
                    url: '/departments/' + id + '/edit',
                    type: 'GET',
                    success: function(response) {
                        addDepartments(response);
                    },
                    error: function(response) {
                        Swal.fire(
                            'Error!',
                            'There was an error fetching the department data.',
                            'error'
                        );
                    }
                });
            }
        });
    };

    window.deleteDepartment = function(id) {
        // Implement delete functionality here
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
                    url: '/departments/' + id+'/delete',
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Department has been deleted.',
                            'success'
                        );
                        table.ajax.reload(null, false); // Reload DataTable without resetting pagination
                    },
                    error: function(response) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the department.',
                            'error'
                        );
                    }
                });
            }
        });
    };
});


function addDepartments(value){
    $("#mdlFormTitle").html("Form Department");
    var formContent = `
        <form id="departmentForm">
            <input type="hidden" class="form-control" id="id" name="id" placeholder="ID">
            <div class="form-group">
                <label for="kode_department">Kode Department</label>
                <input type="text" class="form-control" id="kode_department" name="kode_department" placeholder="Enter Kode Department" required>
            </div>
            <div class="form-group">
                <label for="department_name">Department Name</label>
                <input type="text" class="form-control" id="department_name" name="name" placeholder="Enter Department Name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="descriptions" rows="3" placeholder="Enter Description" required></textarea>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    `;

    // Set the form content in the modal body
    $('#mdlFormContent').html(formContent);
    $("#mdlForm").modal('show');
    if (value !== undefined) {
        $('#id').val(value.id);
        $('#kode_department').val(value.kode_department);
        $('#department_name').val(value.name);
        $('#description').val(value.descriptions);
    }
     // Initialize form validation
   // Initialize form validation
   $('#departmentForm').validate({
    rules: {
        kode_department: {
            required: true,
            minlength: 3
        },
        department_name: {
            required: true,
            minlength: 3
        },
        description: {
            required: true,
            minlength: 5
        }
    },
    messages: {
        kode_department: {
            required: "Please enter the kode department",
            minlength: "Kode department must be at least 3 characters long"
        },
        department_name: {
            required: "Please enter the department name",
            minlength: "Department name must be at least 3 characters long"
        },
        description: {
            required: "Please enter a description",
            minlength: "Description must be at least 5 characters long"
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
                    url: "/departments/store", // Replace with your form submission URL
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
                            // Reload the page
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
