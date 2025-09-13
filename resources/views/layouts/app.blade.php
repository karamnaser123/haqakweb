<!DOCTYPE html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"> --}}
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">


    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }

        [dir="ltr"] body {
            font-family: 'Figtree', sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            width: 280px;
        }

        /* Mobile Sidebar Styles */
        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed !important;
                top: 0;
                left: 0;
                width: 280px;
                height: 100vh;
                z-index: 1050;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                will-change: transform;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                overflow-y: auto;
            }

            #sidebar.show {
                transform: translateX(0) !important;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
                will-change: opacity, visibility;
            }

            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            /* Adjust main content for mobile */
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            /* Prevent body scroll when sidebar is open */
            body.sidebar-open {
                overflow: hidden;
            }
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 10px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        [dir="ltr"] .sidebar .nav-link:hover {
            transform: translateX(-5px);
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
            margin-left: 280px;
            width: calc(100% - 280px);
        }

        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #e9ecef;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* User Avatar Small */
        .user-avatar-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        /* DataTable Custom Styles */
        .dataTables_wrapper {
            padding: 20px;
        }

        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 20px;
        }

        .dataTables_length label,
        .dataTables_filter label {
            font-weight: 500;
            color: #495057;
        }

        .dataTables_length select,
        .dataTables_filter input {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
        }

        .dataTables_length select:focus,
        .dataTables_filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Table Styles */
        #user-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        #user-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 15px 10px;
            border: none;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #user-table thead th:first-child {
            border-top-left-radius: 10px;
        }

        #user-table thead th:last-child {
            border-top-right-radius: 10px;
        }

        #user-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        #user-table tbody tr:hover {
            background-color: #f8f9ff;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #user-table tbody td {
            padding: 15px 10px;
            vertical-align: middle;
            border: none;
            font-size: 14px;
        }

        /* Action Buttons */
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .btn-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            border: none;
            color: #212529;
        }

        /* Badge Styles */
        .badge {
            font-size: 11px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
        }

        /* Pagination Styles */
        .dataTables_paginate {
            margin-top: 20px;
        }

        .dataTables_paginate .paginate_button {
            padding: 8px 12px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dataTables_paginate .paginate_button:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        /* Info Display */
        .dataTables_info {
            color: #6c757d;
            font-size: 14px;
            margin-top: 20px;
        }

        /* Loading Animation */
        .dataTables_processing {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #dee2e6;
            border-radius: 10px;
            color: #495057;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dataTables_wrapper {
                padding: 10px;
            }

            #user-table thead th,
            #user-table tbody td {
                padding: 10px 5px;
                font-size: 12px;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 10px;
            }
        }

        /* RTL Support */
        [dir="rtl"] #user-table {
            text-align: right;
        }

        [dir="rtl"] .dataTables_filter input {
            text-align: right;
        }
    </style>

    <!-- Modern DataTable Styles -->
    <style>
        /* Modern Header Card */
        .modern-header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(102, 126, 234, 0.4);
            color: white;
            overflow: hidden;
            position: relative;
            margin-bottom: 30px;
        }

        .modern-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .modern-title {
            font-size: 2.8rem;
            font-weight: 800;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            margin-bottom: 10px;
        }

        /* Modern Table Card */
        .modern-table-card {
            background: white;
            border: none;
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            margin: 30px;
            padding: 20px;
        }

        .modern-table-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .modern-table-header {
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border: none;
            padding: 40px 45px;
            border-bottom: 2px solid #e3e6f0;
        }

        .modern-subtitle {
            color: #2d3748;
            font-weight: 700;
            font-size: 1.4rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Modern Button Styles */
        .modern-btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 700;
            color: white;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .modern-btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .modern-btn-primary:hover::before {
            left: 100%;
        }

        .modern-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.6);
        }

        .modern-btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            color: white;
            box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modern-btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(17, 153, 142, 0.6);
        }

        .modern-btn-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            color: white;
            box-shadow: 0 6px 20px rgba(240, 147, 251, 0.4);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modern-btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(240, 147, 251, 0.6);
        }

        /* Modern Table Styles */
        .modern-table-container {
            padding: 30px;
            background: #fafbfc;
            margin: 20px;
            border-radius: 15px;
        }

        .modern-table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
        }

        .modern-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            text-align: center;
            padding: 30px 25px;
            border: none;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            vertical-align: middle;
        }

        .modern-table thead th:first-child {
            border-top-left-radius: 0;
        }

        .modern-table thead th:last-child {
            border-top-right-radius: 0;
        }

        .modern-table thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.8) 50%, rgba(255, 255, 255, 0.3) 100%);
        }


        .modern-table tbody tr {
            transition: all 0.4s ease;
            border-bottom: 1px solid #f1f3f4;
            position: relative;
        }

        .modern-table tbody tr::before {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: scaleY(0);
            transition: transform 0.4s ease;
        }

        .modern-table tbody tr:hover::before {
            transform: scaleY(1);
        }

        .modern-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            transform: translateX(8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .modern-table tbody td {
            padding: 30px 25px;
            vertical-align: middle;
            border: none;
            font-size: 15px;
            position: relative;
            font-weight: 500;
            text-align: center;
        }

        /* Action Buttons */
        .btn-action {
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 10px;
            margin: 0 4px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-action::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.4s ease;
        }

        .btn-action:hover::before {
            width: 120px;
            height: 120px;
        }

        .btn-action:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        /* Badge Styles */
        .badge-modern {
            font-size: 11px;
            padding: 10px 18px;
            border-radius: 25px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .badge-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }

        /* DataTable Controls */
        .dataTables_wrapper {
            padding: 50px 60px;
            background: #fafbfc;
            margin: 20px;
            border-radius: 20px;
        }

        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 40px;
        }

        .dataTables_length label,
        .dataTables_filter label {
            font-weight: 700;
            color: #2d3748;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .dataTables_length select,
        .dataTables_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 14px;
            transition: all 0.4s ease;
            background: white;
            font-weight: 500;
        }

        .dataTables_length select:focus,
        .dataTables_filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
            transform: translateY(-2px);
        }

        /* Pagination */
        .dataTables_paginate {
            margin-top: 45px;
        }

        .dataTables_paginate .paginate_button {
            padding: 12px 18px;
            margin: 0 4px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.4s ease;
            font-weight: 600;
            background: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dataTables_paginate .paginate_button:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        /* Info Display */
        .dataTables_info {
            color: #718096;
            font-size: 16px;
            margin-top: 40px;
            font-weight: 600;
        }

        /* Loading Animation */
        .dataTables_processing {
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            color: #4a5568;
            font-weight: 700;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modern-table-container {
                padding: 0;
            }

            .modern-table thead th,
            .modern-table tbody td {
                padding: 20px 10px;
                font-size: 12px;
            }

            .btn-action {
                padding: 8px 12px;
                font-size: 11px;
            }

            .modern-title {
                font-size: 2.2rem;
            }
        }

        /* RTL Support */
        [dir="rtl"] .modern-table tbody tr:hover {
            transform: translateX(-8px);
        }

        [dir="rtl"] .dataTables_filter input {
            text-align: right;
        }

        /* Animation for table rows */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modern-table tbody tr {
            animation: fadeInUp 0.6s ease forwards;
        }

        .modern-table tbody tr:nth-child(1) {
            animation-delay: 0.1s;
        }

        .modern-table tbody tr:nth-child(2) {
            animation-delay: 0.2s;
        }

        .modern-table tbody tr:nth-child(3) {
            animation-delay: 0.3s;
        }

        .modern-table tbody tr:nth-child(4) {
            animation-delay: 0.4s;
        }

        .modern-table tbody tr:nth-child(5) {
            animation-delay: 0.5s;
        }

        /* Glow effect for buttons */
        .modern-btn-primary:focus,
        .modern-btn-success:focus,
        .modern-btn-danger:focus {
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        }

        /* Select2 Custom Styling - Match with modern-input */
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            background-color: #fff !important;
            padding: 0 !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding: 0.375rem 0.75rem !important;
            line-height: 1.5 !important;
            color: #495057 !important;
            font-size: 1rem !important;
        }

        .select2-container .select2-selection--single .select2-selection__placeholder {
            color: #6c757d !important;
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 8px !important;
            top: 1px !important;
        }

        .select2-container .select2-selection--single .select2-selection__arrow b {
            border-color: #6c757d transparent transparent transparent !important;
            border-style: solid !important;
            border-width: 5px 4px 0 4px !important;
            height: 0 !important;
            left: 50% !important;
            margin-left: -4px !important;
            margin-top: -2px !important;
            position: absolute !important;
            top: 50% !important;
            width: 0 !important;
        }

        .select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6c757d transparent !important;
            border-width: 0 4px 5px 4px !important;
        }

        .select2-container--open .select2-selection--single {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        }

        .select2-dropdown {
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            background-color: #fff !important;
        }

        .select2-results__option {
            padding: 0.375rem 0.75rem !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #667eea !important;
            color: #fff !important;
        }

        .select2-results__option[aria-selected=true] {
            background-color: #e9ecef !important;
            color: #495057 !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 1rem !important;
        }

        /* Focus states to match modern-input */
        .select2-container--focus .select2-selection--single {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        }

        /* Make sure it looks exactly like modern-input */
        .category-select2,
        .edit-category-select2,
        .store-select2,
        .edit-store-select2 {
            display: none !important;
        }
    </style>


    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-12 px-0" style="width: 280px;">
              @include('layouts.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-12 px-0">
                <div class="main-content">
                    <!-- Top Navbar -->
                    <nav class="navbar navbar-expand-lg navbar-custom">
                        <div class="container-fluid">
                            <!-- Mobile Sidebar Toggle -->
                            <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle"
                                aria-label="Toggle Sidebar">
                                <i class="bi bi-list"></i>
                            </button>

                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarNav">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav me-auto">
                                    <li class="nav-item">
                                        <span class="navbar-text">
                                            <i class="bi bi-calendar3 me-2"></i>
                                            {{ now()->format('Y-m-d') }}
                                        </span>
                                    </li>
                                </ul>

                                <ul class="navbar-nav">
                                    <!-- Language Switcher -->
                                    <li class="nav-item me-3 d-flex align-items-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('locale', 'ar') }}"
                                                class="btn btn-sm {{ app()->getLocale() == 'ar' ? 'btn-dark' : 'btn-outline-secondary' }}">
                                                العربية
                                            </a>
                                            <a href="{{ route('locale', 'en') }}"
                                                class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-dark' : 'btn-outline-secondary' }}">
                                                English
                                            </a>
                                        </div>
                                    </li>

                                    <!-- User Menu -->
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                            role="button" data-bs-toggle="dropdown">
                                            <div class="user-avatar me-2">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            {{ Auth::user()->name }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i
                                                        class="bi bi-person me-2"></i>{{ __('profile') }}</a></li>
                                            <li><a class="dropdown-item" href="#"><i
                                                        class="bi bi-gear me-2"></i>{{ __('settings') }}</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('logout') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <!-- Page Content -->
                    <div class="p-4">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>



    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- JsBarcode for barcode generation -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Mobile Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Toggle sidebar function
            function toggleSidebar() {
                const isOpen = sidebar.classList.contains('show');
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');

                // Prevent body scroll when sidebar is open on mobile
                if (window.innerWidth < 992) {
                    if (isOpen) {
                        document.body.classList.remove('sidebar-open');
                    } else {
                        document.body.classList.add('sidebar-open');
                    }
                }
            }

            // Toggle sidebar on button click
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleSidebar();
            });

            // Close sidebar when clicking overlay
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.classList.remove('sidebar-open');
            });

            // Close sidebar when clicking on nav links (mobile only)
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Only close on mobile devices
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        document.body.classList.remove('sidebar-open');
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    // On desktop, ensure sidebar is always visible and overlay is hidden
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.classList.remove('sidebar-open');
                } else {
                    // On mobile, ensure sidebar starts hidden
                    if (!sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        document.body.classList.remove('sidebar-open');
                    }
                }
            });

            // Close sidebar when clicking outside (on mobile)
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    const isClickInsideSidebar = sidebar.contains(e.target);
                    const isClickOnToggle = sidebarToggle.contains(e.target);

                    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        document.body.classList.remove('sidebar-open');
                    }
                }
            });

            // Prevent sidebar toggle from closing sidebar when clicking inside
            sidebar.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

    <script>
        // Define language variables
        var areyousure = '{{ __('Are you sure') }}';
        var confirmDelete = '{{ __("You wont be able to revert this!") }}';
        var deleteTitle = '{{ __('Yes, delete it!') }}';
        var cancelTitle = '{{ __('Cancel') }}';
        var successMessage = '{{ __('Success!') }}';
    </script>



    @stack('scripts')
</body>

</html>
