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
                        $('#role').val('').trigger('change');
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
                const role = data.roles && data.roles.length > 0 ? data.roles[0].name : '';

                $('#edit_id').val(data.id);
                $('#edit-name').val(data.name);
                $('#edit-email').val(data.email);
                $('#edit-phone').val(data.phone);
                $('#edit-gender').val(data.gender);
                $('#edit-age').val(data.age);
                $('#edit-balance').val(data.balance);
                $('#edit-role').val(role).trigger('change');
                $('#edit-active').prop('checked', data.active);

                // Handle current image display
                if (data.image) {
                    $('#current-image').attr('src', data.image);
                    $('#current-image-container').show();
                } else {
                    $('#current-image-container').hide();
                }

                // Show the modal
                $('#editModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch user data.',
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
        var dataId = $(this).data('id'); // Get the car ID from the button
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
                            'There was an issue deleting the user.',
                            'error'
                        );
                    }
                });
            }
        });
    });



    $(document).on('click', '.toggle-status', function() {
        const dataId = $(this).data('id');
        let url = routes.status + '/' + dataId;

        $.ajax({
            url: url,
            type: 'POST',
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: successMessage,
                    text: response.success,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    $('#table').DataTable().ajax.reload(null, false);
                });

            },
            error: function(xhr) {
                alert(xhr.responseJSON.error); // Show error message
            }
        });
    });

    $(document).on('click', '.permissions-btn', function() {
        const dataId = $(this).data('id'); // Get user ID from the button
        let url = routes.permissions + '/' + dataId;

        // Set the user ID in the modal
        $('#userPermissionsModal').data('id', dataId);

        // Fetch the permissions for the specific user using AJAX
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }

                // Populate the permissions checkboxes
                var permissionsList = $('#userPermissionsList');
                permissionsList.empty(); // Clear previous permissions

                // Loop through permissions and create checkboxes
                response.permissions.forEach(function(permission, index) {
                    var isChecked = response.userPermissions.includes(permission.id) ? 'checked' : '';
                    var checkboxHtml = `
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input user-permission-checkbox" type="checkbox" value="${permission.id}" id="user_permission_${permission.id}" ${isChecked}>
                                <label class="form-check-label" for="user_permission_${permission.id}">
                                    <strong>${permission.display_name || permission.name}</strong>
                                    ${permission.description ? '<br><small class="text-muted">' + permission.description + '</small>' : ''}
                                </label>
                            </div>
                        </div>
                    `;
                    permissionsList.append(checkboxHtml);
                });

                // Show the modal
                $('#userPermissionsModal').modal('show');
            },
            error: function() {
                alert('Failed to fetch permissions.');
            }
        });
    });

    // Handle Select All button click for user permissions
    $('#selectAllUserBtn').on('click', function() {
        $('.user-permission-checkbox').prop('checked', true);
    });

    // Handle Unselect All button click for user permissions
    $('#unselectAllUserBtn').on('click', function() {
        $('.user-permission-checkbox').prop('checked', false);
    });

    // Handle Save User Permissions button click
    $('#saveUserPermissionsBtn').on('click', function() {
        var dataId = $('#userPermissionsModal').data('id'); // Get the user ID from the modal's stored data
        let url = routes.permissions + '/' + dataId;

        // Get the selected permissions
        var selectedPermissions = [];
        $('.user-permission-checkbox:checked').each(function() {
            selectedPermissions.push($(this).val());
        });

        // Make AJAX request to save the selected permissions
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                permissions: selectedPermissions,
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
                        $('#userPermissionsModal').modal('hide');
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
