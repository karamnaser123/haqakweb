<!-- Edit Category Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modern-modal">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>
                    {{ __('Edit Category') }}
                </h5>
                <button type="button" class="btn-close modern-btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body modern-modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-name_en" class="form-label modern-label">
                                <i class="bi bi-tag me-1"></i>
                                {{ __('Name (English)') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control modern-input" id="edit-name_en" name="name_en" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-name_ar" class="form-label modern-label">
                                <i class="bi bi-tag me-1"></i>
                                {{ __('Name (Arabic)') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control modern-input" id="edit-name_ar" name="name_ar" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-parent_id" class="form-label modern-label">
                                <i class="bi bi-diagram-3 me-1"></i>
                                {{ __('Parent Category') }}
                            </label>
                            <select class="form-control modern-input" id="edit-parent_id" name="parent_id">
                                <option value="">{{ __('Main Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-image" class="form-label modern-label">
                                <i class="bi bi-image me-1"></i>
                                {{ __('Category Image') }}
                            </label>
                            <input type="file" class="form-control modern-input" id="edit-image" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div id="current-image-container" style="display: none;">
                                <label class="form-label modern-label">{{ __('Current Image') }}</label>
                                <div class="mt-2">
                                    <img id="current-image" src="" alt="Current Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modern-modal-footer">
                    <button type="button" class="btn modern-btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn modern-btn-primary" id="editFormBtn">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ __('Update Category') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
