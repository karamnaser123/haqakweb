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
                        $('#parent_id').val('').trigger('change');
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
                $('#edit-name_en').val(data.name_en);
                $('#edit-name_ar').val(data.name_ar);

                // Update parent dropdown options
                $('#edit-parent_id').empty();
                $('#edit-parent_id').append('<option value="">{{ __("Main Category") }}</option>');

                // Add categories excluding current category and its children
                if (response.categories) {
                    response.categories.forEach(function(category) {
                        if (category.id != data.id) {
                            $('#edit-parent_id').append('<option value="' + category.id + '">' + category.name_en + '</option>');
                        }
                    });
                }

                $('#edit-parent_id').val(data.parent_id).trigger('change');

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
                    text: 'Failed to fetch category data.',
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
        var dataId = $(this).data('id'); // Get the category ID from the button
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
                            'There was an issue deleting the category.',
                            'error'
                        );
                    }
                });
            }
        });
    });

});
