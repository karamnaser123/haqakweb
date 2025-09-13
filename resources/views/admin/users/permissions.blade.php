<!-- User Permissions Modal -->
<div class="modal fade" id="userPermissionsModal" tabindex="-1" aria-labelledby="userPermissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modern-modal">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title" id="userPermissionsModalLabel">
                    <i class="bi bi-key me-2"></i>
                    {{ __('Manage User Permissions') }}
                </h5>
                <button type="button" class="btn-close modern-btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modern-modal-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ __('Select Permissions') }}</h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm modern-btn-success" id="selectAllUserBtn">
                                    <i class="bi bi-check-all me-1"></i>
                                    {{ __('Select All') }}
                                </button>
                                <button type="button" class="btn btn-sm modern-btn-danger" id="unselectAllUserBtn">
                                    <i class="bi bi-x-circle me-1"></i>
                                    {{ __('Unselect All') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="userPermissionsList">
                    <!-- Permissions will be loaded here via JavaScript -->
                </div>
            </div>
            <div class="modal-footer modern-modal-footer">
                <button type="button" class="btn modern-btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn modern-btn-primary" id="saveUserPermissionsBtn">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ __('Save Permissions') }}
                </button>
            </div>
        </div>
    </div>
</div>
