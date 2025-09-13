<!-- Edit Product Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modern-modal">
            <div class="modal-header modern-modal-header">
                <h5 class="modal-title modern-modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>
                    {{ __('Edit Product') }}
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
                            <label for="edit-category_id" class="form-label modern-label">
                                <i class="bi bi-diagram-3 me-1"></i>
                                {{ __('Category') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-control modern-input edit-category-select2" id="edit-category_id" name="category_id" required>
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-store_id" class="form-label modern-label">
                                <i class="bi bi-shop me-1"></i>
                                {{ __('Store') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-control modern-input edit-store-select2" id="edit-store_id" name="store_id" required>
                                <option value="">{{ __('Select Store') }}</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="edit-price" class="form-label modern-label">
                                <i class="bi bi-currency-dollar me-1"></i>
                                {{ __('Price') }} <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" class="form-control modern-input" id="edit-price" name="price" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit-discount" class="form-label modern-label">
                                <i class="bi bi-percent me-1"></i>
                                {{ __('Discount') }}
                            </label>
                            <input type="number" step="0.01" class="form-control modern-input" id="edit-discount" name="discount">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit-stock" class="form-label modern-label">
                                <i class="bi bi-box me-1"></i>
                                {{ __('Stock') }} <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control modern-input" id="edit-stock" name="stock" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-description_en" class="form-label modern-label">
                                <i class="bi bi-text-paragraph me-1"></i>
                                {{ __('Description (English)') }}
                            </label>
                            <textarea class="form-control modern-input" id="edit-description_en" name="description_en" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-description_ar" class="form-label modern-label">
                                <i class="bi bi-text-paragraph me-1"></i>
                                {{ __('Description (Arabic)') }}
                            </label>
                            <textarea class="form-control modern-input" id="edit-description_ar" name="description_ar" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit-images" class="form-label modern-label">
                                <i class="bi bi-images me-1"></i>
                                {{ __('Add More Images') }}
                            </label>
                            <input type="file" class="form-control modern-input" id="edit-images" name="images[]" accept="image/*" multiple>
                            <div class="form-text">{{ __('You can select multiple images to add') }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div id="current-images-container" style="display: none;">
                                <label class="form-label modern-label">
                                    <i class="bi bi-images me-1"></i>
                                    {{ __('Current Images') }}
                                </label>
                                <div class="row mt-3" id="current-images">
                                    <!-- Current images will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-label modern-label">
                                <i class="bi bi-star me-1"></i>
                                {{ __('Product Features') }}
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit-active" name="active">
                                        <label class="form-check-label" for="edit-active">
                                            {{ __('Active') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit-featured" name="featured">
                                        <label class="form-check-label" for="edit-featured">
                                            {{ __('Featured') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit-new" name="new">
                                        <label class="form-check-label" for="edit-new">
                                            {{ __('New') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit-best_seller" name="best_seller">
                                        <label class="form-check-label" for="edit-best_seller">
                                            {{ __('Best Seller') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit-top_rated" name="top_rated">
                                        <label class="form-check-label" for="edit-top_rated">
                                            {{ __('Top Rated') }}
                                        </label>
                                    </div>
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
                        {{ __('Update Product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
