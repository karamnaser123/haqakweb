$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle scope type change for add modal
    $('#scope_type').on('change', function() {
        var scopeType = $(this).val();

        // Hide all scope sections
        $('#product_scope, #category_scope').hide();
        $('#product_ids, #category_ids').val('').trigger('change');

        // Show relevant scope section
        if (scopeType === 'product') {
            $('#product_scope').show();
        } else if (scopeType === 'category') {
            $('#category_scope').show();
        }
    });

    // Handle scope type change for edit modal
    $('#edit-scope_type').on('change', function() {
        var scopeType = $(this).val();

        // Hide all scope sections
        $('#edit-product_scope, #edit-category_scope').hide();
        $('#edit-product_ids, #edit-category_ids').val('').trigger('change');

        // Show relevant scope section
        if (scopeType === 'product') {
            $('#edit-product_scope').show();
        } else if (scopeType === 'category') {
            $('#edit-category_scope').show();
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
                        $('#scope_type').val('general').trigger('change');
                        $('#product_scope, #category_scope').hide();
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
                $('#edit-code').val(data.code);
                $('#edit-type').val(data.type);
                $('#edit-value').val(data.value);

                // Handle scope type
                let scopeType = 'general';
                if (data.product_ids && data.product_ids.length > 0) {
                    scopeType = 'product';
                } else if (data.category_ids && data.category_ids.length > 0) {
                    scopeType = 'category';
                }
                $('#edit-scope_type').val(scopeType).trigger('change');

                // Set product or category based on scope
                if (data.product_ids && data.product_ids.length > 0) {
                    $('#edit-product_ids').val(data.product_ids);
                }
                if (data.category_ids && data.category_ids.length > 0) {
                    $('#edit-category_ids').val(data.category_ids);
                }

                // Show the appropriate scope section
                $('#edit-product_scope, #edit-category_scope').hide();
                if (scopeType === 'product') {
                    $('#edit-product_scope').show();
                } else if (scopeType === 'category') {
                    $('#edit-category_scope').show();
                }

                $('#edit-start_date').val(data.start_date);
                $('#edit-end_date').val(data.end_date);
                $('#edit-max_uses').val(data.max_uses);
                $('#edit-max_uses_per_user').val(data.max_uses_per_user);
                $('#edit-active').prop('checked', data.active == 1);

                // Show the modal
                $('#editModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch discount data.',
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
                        $('#edit-scope_type').val('general').trigger('change');
                        $('#edit-product_scope, #edit-category_scope').hide();
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
        var dataId = $(this).data('id'); // Get the discount ID from the button
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
                            'There was an issue deleting the discount.',
                            'error'
                        );
                    }
                });
            }
        });
    });

});
