<!-- Add Discount Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modern-modal">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ __('Add New Discount') }}
                </h5>
                <button type="button" class="btn-close modern-btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm">
                <div class="modal-body modern-modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label modern-label">
                                <i class="bi bi-tag me-1"></i>
                                {{ __('Discount Code') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control modern-input" id="code" name="code" required>
                            <small class="form-text text-muted">{{ __('Leave empty to auto-generate') }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label modern-label">
                                <i class="bi bi-percent me-1"></i>
                                {{ __('Discount Type') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-control modern-input" id="type" name="type" required>
                                <option value="percentage">{{ __('Percentage') }}</option>
                                <option value="fixed">{{ __('Fixed Amount') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="value" class="form-label modern-label">
                                <i class="bi bi-currency-dollar me-1"></i>
                                {{ __('Discount Value') }} <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control modern-input" id="value" name="value" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="scope_type" class="form-label modern-label">
                                <i class="bi bi-diagram-3 me-1"></i>
                                {{ __('Scope Type') }}
                            </label>
                            <select class="form-control modern-input" id="scope_type" name="scope_type">
                                <option value="general">{{ __('General (All Products)') }}</option>
                                <option value="product">{{ __('Specific Product') }}</option>
                                <option value="category">{{ __('Product Category') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="product_scope" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label for="product_ids" class="form-label modern-label">
                                <i class="bi bi-box me-1"></i>
                                {{ __('Select Products') }}
                            </label>
                            <select class="form-control modern-input" id="product_ids" name="product_ids[]" multiple style="height: 120px;">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name_en }} ({{ $product->store->name }})</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">{{ __('Hold Ctrl/Cmd to select multiple products') }}</small>
                        </div>
                    </div>
                    <div class="row" id="category_scope" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label for="category_ids" class="form-label modern-label">
                                <i class="bi bi-tags me-1"></i>
                                {{ __('Select Categories') }}
                            </label>
                            <select class="form-control modern-input" id="category_ids" name="category_ids[]" multiple style="height: 120px;">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">{{ __('Hold Ctrl/Cmd to select multiple categories') }}</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label modern-label">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ __('Start Date') }}
                            </label>
                            <input type="date" class="form-control modern-input" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label modern-label">
                                <i class="bi bi-calendar-x me-1"></i>
                                {{ __('End Date') }}
                            </label>
                            <input type="date" class="form-control modern-input" id="end_date" name="end_date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="max_uses" class="form-label modern-label">
                                <i class="bi bi-arrow-repeat me-1"></i>
                                {{ __('Max Uses') }}
                            </label>
                            <input type="number" class="form-control modern-input" id="max_uses" name="max_uses" min="0">
                            <small class="form-text text-muted">{{ __('0 = unlimited') }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="max_uses_per_user" class="form-label modern-label">
                                <i class="bi bi-person me-1"></i>
                                {{ __('Max Uses Per User') }}
                            </label>
                            <input type="number" class="form-control modern-input" id="max_uses_per_user" name="max_uses_per_user" min="0">
                            <small class="form-text text-muted">{{ __('0 = unlimited') }}</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="active" name="active" value="1" checked>
                                <label class="form-check-label modern-label" for="active">
                                    {{ __('Active') }}
                                </label>
                            </div>
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
                        {{ __('Add Discount') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

