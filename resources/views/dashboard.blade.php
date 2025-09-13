@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="bi bi-sunrise me-2"></i>
                                {{ __('welcome_message', ['name' => Auth::user()->name]) }}
        </h2>
                            <p class="mb-0 opacity-75">
                                {{ __('welcome_description') }}
                            </p>
                        </div>
                        <div class="col-md-4 {{ app()->getLocale() == 'ar' ? 'text-end' : 'text-start' }}">
                            <div class="user-avatar" style="width: 80px; height: 80px; font-size: 2.5rem;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-custom stat-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-uppercase small fw-bold text-white-50">{{ __('total_users') }}</div>
                            <div class="h2 mb-0 text-white">1,234</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-custom stat-card success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-uppercase small fw-bold text-white-50">{{ __('active_stores') }}</div>
                            <div class="h2 mb-0 text-white">89</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-shop stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-custom stat-card warning">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-uppercase small fw-bold text-white-50">{{ __('today_orders') }}</div>
                            <div class="h2 mb-0 text-white">156</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cart-check stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-custom stat-card info">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-uppercase small fw-bold text-white-50">{{ __('revenue') }}</div>
                            <div class="h2 mb-0 text-white">$12,345</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="row">
        <!-- Recent Activity -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card card-custom">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2 text-primary"></i>
                        {{ __('recent_activity') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('user') }}</th>
                                    <th>{{ __('activity') }}</th>
                                    <th>{{ __('time') }}</th>
                                    <th>{{ __('status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3" style="width: 35px; height: 35px; font-size: 1rem;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">أحمد محمد</div>
                                                <small class="text-muted">ahmed@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>تسجيل دخول جديد</td>
                                    <td>منذ 5 دقائق</td>
                                    <td><span class="badge bg-success">{{ __('active') }}</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3" style="width: 35px; height: 35px; font-size: 1rem;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">فاطمة علي</div>
                                                <small class="text-muted">fatima@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>إنشاء طلب جديد</td>
                                    <td>منذ 15 دقيقة</td>
                                    <td><span class="badge bg-warning">{{ __('pending') }}</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3" style="width: 35px; height: 35px; font-size: 1rem;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">محمد حسن</div>
                                                <small class="text-muted">mohamed@example.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>تحديث الملف الشخصي</td>
                                    <td>منذ ساعة</td>
                                    <td><span class="badge bg-info">{{ __('completed') }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card card-custom">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2 text-warning"></i>
                        {{ __('quick_actions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <button class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle me-2"></i>
                            {{ __('add_new_user') }}
                        </button>
                        <button class="btn btn-success btn-lg">
                            <i class="bi bi-shop me-2"></i>
                            {{ __('manage_stores') }}
                        </button>
                        <button class="btn btn-info btn-lg">
                            <i class="bi bi-graph-up me-2"></i>
                            {{ __('view_reports') }}
                        </button>
                        <button class="btn btn-warning btn-lg">
                            <i class="bi bi-gear me-2"></i>
                            {{ __('system_settings') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info Cards -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card card-custom">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-event me-2 text-success"></i>
                        {{ __('upcoming_events') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class='mb-1'>{{ __('team_meeting') }}</h6>
                                    <small class="text-muted">غداً في 10:00 صباحاً</small>
                                </div>
                                <span class="badge bg-primary">{{ __('important') }}</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class='mb-1'>{{ __('system_update') }}</h6>
                                    <small class="text-muted">الأحد القادم</small>
                                </div>
                                <span class="badge bg-warning">{{ __('medium') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card card-custom">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2 text-info"></i>
                        {{ __('system_info') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">99.9%</h4>
                                <small class='text-muted'>{{ __('uptime') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">2.3s</h4>
                            <small class='text-muted'>{{ __('response_time') }}</small>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <small class='text-muted'>{{ __('system_version') }}:</small>
                        <small class="fw-bold">v2.1.0</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class='text-muted'>{{ __('last_update') }}:</small>
                        <small class="fw-bold">{{ now()->format('Y-m-d') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
