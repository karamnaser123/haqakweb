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
                                <i class="bi bi-percent me-2"></i>
                                {{ __('Discounts') }}
                            </h2>
                            <p class="text-white-50 mb-0">{{ __('Manage Discount Codes') }}</p>
                        </div>
                        <div>
                            <button class="btn modern-btn-primary" id="addDiscount" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                {{ __('Add Discount') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Discounts Table -->
    <div class="row">
        <div class="col-12">
            <div class="card modern-table-card">
                <div class="card-header modern-table-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 modern-subtitle">
                            <i class="bi bi-table me-2"></i>
                            {{ __('Discounts List') }}
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
    <script src="{{ asset('admin/ajax/discounts.js?v=' . time()) }}"></script>

    <script>
        // Define routes
        var routes = {
            store: '{{ route("discounts.store") }}',
            edit: '{{ route("discounts.edit") }}',
            update: '{{ route("discounts.update") }}',
            destroy: '{{ route("discounts.destroy") }}',
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
            $('#table_filter input').attr('placeholder', '{{ __("Search Discounts") }}...');

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

@if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('discount-add'))
@include('admin.discounts.create')
@endif
@if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('discount-update'))
@include('admin.discounts.edit')
@endif
@endsection
