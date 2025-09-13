<!-- Add Role Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modern-modal">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('Add New Role') }}
                </h5>
                <button type="button" class="btn-close modern-btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm" enctype="multipart/form-data">
                <div class="modal-body modern-modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label modern-label">
                                <i class="bi bi-tag me-1"></i>
                                {{ __('Role Name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control modern-input" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="display_name" class="form-label modern-label">
                                <i class="bi bi-eye me-1"></i>
                                {{ __('Display Name') }}
                            </label>
                            <input type="text" class="form-control modern-input" id="display_name" name="display_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label modern-label">
                                <i class="bi bi-text-paragraph me-1"></i>
                                {{ __('Description') }}
                            </label>
                            <textarea class="form-control modern-input" id="description" name="description" rows="3" placeholder="{{ __('Enter role description...') }}"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modern-modal-footer">
                    <button type="button" class="btn modern-btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn modern-btn-primary" id="addFormBtn">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ __('Add Role') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
