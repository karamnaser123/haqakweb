<!-- Edit Role Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modern-modal">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>
                    {{ __('Edit Role') }}
                </h5>
                <button type="button" class="btn-close modern-btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body modern-modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-name" class="form-label modern-label">
                                <i class="bi bi-tag me-1"></i>
                                {{ __('Role Name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control modern-input" id="edit-name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-display_name" class="form-label modern-label">
                                <i class="bi bi-eye me-1"></i>
                                {{ __('Display Name') }}
                            </label>
                            <input type="text" class="form-control modern-input" id="edit-display_name" name="display_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="edit-description" class="form-label modern-label">
                                <i class="bi bi-text-paragraph me-1"></i>
                                {{ __('Description') }}
                            </label>
                            <textarea class="form-control modern-input" id="edit-description" name="description" rows="3" placeholder="{{ __('Enter role description...') }}"></textarea>
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
                        {{ __('Update Role') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
