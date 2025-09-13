@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card modern-header-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 modern-title">
                                <i class="bi bi-box-seam me-2"></i>
                                {{ __('products') }}
                            </h2>
                            <p class="text-white-50 mb-0">{{ __('Manage Products') }}</p>
                        </div>
                        <div>
                            <button class="btn modern-btn-primary" id="addProduct" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                {{ __('Add Product') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="card modern-table-card">
                <div class="card-header modern-table-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 modern-subtitle">
                            <i class="bi bi-table me-2"></i>
                            {{ __('Products List') }}
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn modern-btn-success" id="exportExcel">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Excel
                            </button>
                            <button class="btn modern-btn-danger" id="exportPdf">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive modern-table-container">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script src="{{ asset('admin/ajax/products.js?v=' . time()) }}"></script>

    <script>
        $(document).ready(function() {
            $('.category-select2').select2({
                width: '100%',
                dropdownParent: $('#addModal'),
            });
            $('.edit-category-select2').select2({
                width: '100%',
                dropdownParent: $('#editModal'),
            });
            $('.store-select2').select2({
                width: '100%',
                dropdownParent: $('#addModal'),
            });
            $('.edit-store-select2').select2({
                width: '100%',
                dropdownParent: $('#editModal'),
            });
        });
    </script>

    <script>
        // Initialize Select2 for category and store dropdowns

        // Define routes
        var routes = {
            store: '{{ route("products.store") }}',
            edit: '{{ route("products.edit") }}',
            update: '{{ route("products.update") }}',
            destroy: '{{ route("products.destroy") }}',
            deleteImage: '{{ route("products.deleteImage") }}',
        };

        // Define messages
        var successMessage = '{{ __("Success") }}';
        var areyousure = '{{ __("Are you sure?") }}';
        var confirmDelete = '{{ __("You won\'t be able to revert this!") }}';
        var deleteTitle = '{{ __("Yes, delete it!") }}';
        var cancelTitle = '{{ __("Cancel") }}';

        $(document).ready(function() {
            // Export functionality
            $('#exportExcel').on('click', function() {
                $('#table').DataTable().button('.buttons-excel').trigger();
            });

            $('#exportPdf').on('click', function() {
                $('#table').DataTable().button('.buttons-pdf').trigger();
            });

            // Custom search functionality
            $('#table_filter input').attr('placeholder', '{{ __("Search Products") }}...');

            // Add modern classes to action buttons
            $('#table').on('draw.dt', function() {
                $('.btn-sm').addClass('btn-action');
                $('.badge').addClass('badge-modern');
            });

            // Add hover effects
            $('.modern-table tbody tr').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );

            // Add click animation to buttons
            $('.modern-btn-primary, .modern-btn-success, .modern-btn-danger').on('click', function() {
                $(this).addClass('animate__animated animate__pulse');
                setTimeout(() => {
                    $(this).removeClass('animate__animated animate__pulse');
                }, 1000);
            });
        });
    </script>
@endpush

@if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('product-add'))
@include('admin.products.create')
@endif
@if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('product-update'))
@include('admin.products.edit')
@endif
@endsection
