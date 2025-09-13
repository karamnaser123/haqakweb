<!-- Add User Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modern-header-card">
                <h5 class="modal-title text-white" id="addModalLabel">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    {{ __('add_user') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold">
                                <i class="bi bi-person me-1"></i>
                                {{ __('name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <!-- Email Field -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="bi bi-envelope me-1"></i>
                                {{ __('email') }} <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <!-- Phone Field -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-bold">
                                <i class="bi bi-phone me-1"></i>
                                {{ __('phone') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>

                        <!-- Password Field -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-bold">
                                <i class="bi bi-lock me-1"></i>
                                {{ __('password') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Gender Field -->
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label fw-bold">
                                <i class="bi bi-gender-ambiguous me-1"></i>
                                {{ __('gender') }}
                            </label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">{{ __('select_gender') }}</option>
                                <option value="male">{{ __('male') }}</option>
                                <option value="female">{{ __('female') }}</option>
                            </select>
                        </div>

                        <!-- Age Field -->
                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label fw-bold">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ __('age') }}
                            </label>
                            <input type="number" class="form-control" id="age" name="age" min="1" max="120">
                        </div>

                        <!-- Role Field -->
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label fw-bold">
                                <i class="bi bi-shield-check me-1"></i>
                                {{ __('role') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">{{ __('select_role') }}</option>
                                <option value="admin">{{ __('admin') }}</option>
                                <option value="store">{{ __('store') }}</option>
                                <option value="moderator">{{ __('moderator') }}</option>
                                <option value="user">{{ __('user') }}</option>
                            </select>
                        </div>

                        <!-- Balance Field -->
                        <div class="col-md-6 mb-3">
                            <label for="balance" class="form-label fw-bold">
                                <i class="bi bi-wallet2 me-1"></i>
                                {{ __('balance') }}
                            </label>
                            <input type="number" class="form-control" id="balance" name="balance" step="0.01" min="0" value="0">
                        </div>

                        <!-- Image Field -->
                        <div class="col-12 mb-3">
                            <label for="image" class="form-label fw-bold">
                                <i class="bi bi-image me-1"></i>
                                {{ __('profile_image') }}
                            </label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">{{ __('image_requirements') }}</div>
                        </div>

                        <!-- Active Status -->
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label fw-bold" for="active">
                                    <i class="bi bi-toggle-on me-1"></i>
                                    {{ __('active_user') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>
                    {{ __('cancel') }}
                </button>
                <button type="button" class="btn modern-btn-primary" id="addFormBtn">
                    <i class="bi bi-plus-circle me-1"></i>
                    {{ __('add_user') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const passwordField = $('#password');
        const toggleIcon = $(this).find('i');

        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            toggleIcon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            toggleIcon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
});
</script>
