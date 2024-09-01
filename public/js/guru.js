$(document).ready(function () {

    const userTypeRadios = document.getElementsByName('user_type');
        const existingUserSection = document.getElementById('existing_user_section');
        const newUserSection = document.getElementById('new_user_section');

        userTypeRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'existing') {
                    existingUserSection.classList.remove('d-none');
                    newUserSection.classList.add('d-none');
                } else {
                    existingUserSection.classList.add('d-none');
                    newUserSection.classList.remove('d-none');
                }
            });
        });

    // Initialize DataTables for Guru Management
    let table = $("#guru_table").DataTable({
        processing: true,
        serverSide: true,
        ajax: "guru/data",
        columns: [
            { data: "nik", name: "nik" },
            { data: "name", name: "name" },
            { data: "email", name: "email" },
            { data: "kelas", name: "kelas" },
            {
                data: "actions",
                name: "actions",
                orderable: false,
                searchable: false,
            },
        ],
    });

    // Function to create a Guru
    function createGuru() {
        // Open the modal for creating a Guru
        $('#guruModal').modal('show');

        // Reset the form fields
        $('#guruForm')[0].reset();

        // Set the modal title
        $('#guruModalLabel').text('Create Guru');

        // Clear any error messages
        $('.form-error').text('');
    }

    // Event listener for Create Guru button
    $('#createGuruBtn').on('click', function () {
        createGuru();
    });

    // Edit Guru
    $('body').on('click', '.edit', function () {
        var guru_id = $(this).data('id');
        $.get("/guru" +'/' + guru_id +'/edit', function (data) {
            $('#guruModalLabel').text("Edit Guru");
            $('#guruModal').modal('show');
            $('#guru_id').val(data.id);
            $('#nik').val(data.nik);
            $('#user_id').val(data.user_id);
            $('#kelas_id').val(data.kelas_id);
            $('#alamat').val(data.alamat);
        })
    });

    // Save or Update Guru
    $("#guruForm").on("submit", function (e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: "{{ route('guru.store') }}",
            method: "POST",
            data: formData,
            success: function (response) {
                $("#guruModal").modal("hide");
                table.ajax.reload();
            },
        });
    });

    // Delete Guru
    $(document).on('click', '.delete', function () {
        var guruId = $(this).data('id');

        // SweetAlert2 confirmation modal
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/guru/' + guruId,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function (response) {
                        if (response.success) {
                            // SweetAlert2 success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#guru_table').DataTable().ajax.reload(); // Reload the DataTable
                        } else {
                            // SweetAlert2 error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.error,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function (xhr) {
                        // SweetAlert2 error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to delete!',
                            text: 'An error occurred while trying to delete the Guru.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });


});
