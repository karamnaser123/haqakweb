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
                        $('#category_id').val('').trigger('change');
                        $('#store_id').val('').trigger('change');
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
                $('#edit-price').val(data.price);
                $('#edit-discount').val(data.discount);
                $('#edit-stock').val(data.stock);
                $('#edit-description_en').val(data.description_en);
                $('#edit-description_ar').val(data.description_ar);

                $('#edit-category_id').val(data.category_id).trigger('change');
                $('#edit-store_id').val(data.store_id).trigger('change');



                // Handle boolean fields
                $('#edit-active').prop('checked', data.active == 1);
                $('#edit-featured').prop('checked', data.featured == 1);
                $('#edit-new').prop('checked', data.new == 1);
                $('#edit-best_seller').prop('checked', data.best_seller == 1);
                $('#edit-top_rated').prop('checked', data.top_rated == 1);

                // Handle current images display
                if (data.product_images && data.product_images.length > 0) {
                    $('#current-images-container').show();
                    $('#current-images').empty();
                    data.product_images.forEach(function(image) {
                        let imageHtml = `
                            <div class="col-md-3 mb-3">
                                <div class="position-relative d-inline-block image-container">
                                    <img src="${image.image}" alt="Product Image" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #e9ecef;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute image-delete-btn"
                                            style="top: -8px; right: -8px; width: 28px; height: 28px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10; border: 2px solid white;"
                                            onclick="deleteImage(${image.id})" title="Delete Image">
                                        <i class="fas fa-times" style="font-size: 12px;"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        $('#current-images').append(imageHtml);
                    });
                } else {
                    $('#current-images-container').hide();
                }

                // Show the modal
                $('#editModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch product data.',
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
        var dataId = $(this).data('id'); // Get the product ID from the button
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
                            'There was an issue deleting the product.',
                            'error'
                        );
                    }
                });
            }
        });
    });

});

// Function to delete individual product images
function deleteImage(imageId) {
    Swal.fire({
        title: areyousure,
        text: 'Are you sure you want to delete this image?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: deleteTitle,
        cancelButtonText: cancelTitle,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: routes.deleteImage + '/' + imageId,
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
                            // Reload the edit modal to refresh images
                            $('.edit-data').trigger('click');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'There was an issue deleting the image.',
                        'error'
                    );
                }
            });
        }
    });
}
