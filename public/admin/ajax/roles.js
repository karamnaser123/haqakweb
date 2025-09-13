$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#addFormBtn').on('click', function(e) {
        e.preventDefault();

        // Get form data from the actual form
        let formData = new FormData($('#addForm')[0]);
        let url = routes.store;

        // Clear previous error messages
        $('.form-error').remove();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#addModal').modal('hide');
                        $('#addForm')[0].reset();
                        $('#table').DataTable().ajax.reload(null, false);
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) { // Laravel validation error code
                    let errors = xhr.responseJSON.errors;
                    for (const [field, messages] of Object.entries(errors)) {
                        // Find the input element and append the error message
                        let input = $(`[name="${field}"]`);
                        let errorMessage = `<span class="form-error text-danger">${messages[0]}</span>`;
                        input.after(errorMessage);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            }
        });
    });

    $(document).on('click', '.edit-data', function() {
        let dataId = $(this).data('id');
        let url = routes.edit + '/' + dataId
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                const data = response.data;

                $('#edit_id').val(data.id);
                $('#edit-name').val(data.name);
                $('#edit-display_name').val(data.display_name);
                $('#edit-description').val(data.description);

                // Show the modal
                $('#editModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch role data.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });

    $('#editFormBtn').on('click', function(e) {
        e.preventDefault();

        // Get form data from the actual form
        let formData = new FormData($('#editForm')[0]);
        let dataId = $('#edit_id').val();
        let url = routes.update + '/' + dataId;

        // Reset validation states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Clear previous error messages
        $('.form-error').remove();

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#editModal').modal('hide');
                        $('#editForm')[0].reset();
                        $('#table').DataTable().ajax.reload(null, false);
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) { // Laravel validation error code
                    let errors = xhr.responseJSON.errors;
                    for (const [field, messages] of Object.entries(errors)) {
                        let input = $('[name="' + field + '"]'); // Correct selector for input field by name

                        // Prevent appending duplicate error messages
                        if (input.next('.form-error').length === 0) {
                            let errorMessage = '<span class="form-error text-danger">' + messages[0] + '</span>';
                            input.after(errorMessage); // Append the error message after the input field
                        }
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            }
        });
    });

    $(document).on('click', '.delete-data', function() {
        var dataId = $(this).data('id'); // Get the role ID from the button
        let url = routes.destroy + '/' + dataId;

        // Show SweetAlert confirmation
        Swal.fire({
            title: areyousure,
            text: confirmDelete,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: deleteTitle,
            cancelButtonText: cancelTitle,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url, // The route for the destroy action
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: successMessage,
                                text: response.success,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                $('#table').DataTable().ajax.reload(null, false);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Show error message
                        Swal.fire(
                            'Error!',
                            'There was an issue deleting the role.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    $(document).on('click', '.permissions-btn', function() {
        const dataId = $(this).data('id'); // Get role ID from the button
        let url = routes.permissions + '/' + dataId;

        // Set the role ID in the hidden input field inside the modal
        $('#permissionsModal').data('id', dataId); // Store the ID on the modal itself

        // Fetch the permissions for the specific role using AJAX
        $.ajax({
            url: url, // Replace with the correct URL
            method: 'GET',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }

                // Populate the permissions checkboxes
                var permissionsList = $('#permissionsList');
                permissionsList.empty(); // Clear previous permissions

                // Loop through permissions and create checkboxes
                response.permissions.forEach(function(permission, index) {
                    var isChecked = response.rolePermissions.includes(permission.id) ? 'checked' : '';
                    var checkboxHtml = `
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox" type="checkbox" value="${permission.id}" id="permission_${permission.id}" ${isChecked}>
                                <label class="form-check-label" for="permission_${permission.id}">
                                    <strong>${permission.display_name || permission.name}</strong>
                                    ${permission.description ? '<br><small class="text-muted">' + permission.description + '</small>' : ''}
                                </label>
                            </div>
                        </div>
                    `;
                    permissionsList.append(checkboxHtml);
                });

                // Show the modal
                $('#permissionsModal').modal('show');
            },
            error: function() {
                alert('Failed to fetch permissions.');
            }
        });
    });

    // Handle Select All button click
    $('#selectAllBtn').on('click', function() {
        $('.permission-checkbox').prop('checked', true);
    });

    // Handle Unselect All button click
    $('#unselectAllBtn').on('click', function() {
        $('.permission-checkbox').prop('checked', false);
    });

    // Handle Save Permissions button click
    $('#savePermissionsBtn').on('click', function() {
        var dataId = $('#permissionsModal').data('id'); // Get the role ID from the modal's stored data
        let url = routes.permissions + '/' + dataId;

        // Get the selected permissions
        var selectedPermissions = [];
        $('.permission-checkbox:checked').each(function() {
            selectedPermissions.push($(this).val());
        });

        // Make AJAX request to save the selected permissions
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                permissions: selectedPermissions, // This will be an empty array if no permissions are selected
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: successMessage,
                        text: response.success,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#permissionsModal').modal('hide');
                        $('#table').DataTable().ajax.reload(null, false);
                    });
                }
            },
            error: function() {
                alert('Failed to save permissions.');
            }
        });
    });

});
