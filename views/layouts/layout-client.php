<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?? 'Du Lịch Thông Minh' ?></title>
    <link href="<?= asset('css/frontend/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= asset('css/frontend/fonts-fix.css') ?>" rel="stylesheet">
    <style>
        :root {
            --primary: #1e40af;
            --secondary: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 20px;
            color: #fff !important;
        }

        .navbar a {
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #fff !important;
        }

        .container-main {
            max-width: 1200px;
        }

        .dashboard-header {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .header-content h1 {
            color: var(--primary);
            margin-bottom: 10px;
            font-weight: bold;
        }

        .header-content p {
            color: #666;
            margin: 0;
            font-size: 16px;
        }

        .quick-actions {
            margin-top: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action.primary {
            background: var(--primary);
            color: white;
        }

        .btn-action.primary:hover {
            background: #1e3a8a;
            color: white;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.4);
        }

        .btn-action.secondary {
            background: var(--secondary);
            color: white;
        }

        .btn-action.secondary:hover {
            background: #d97706;
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            font-size: 36px;
            margin-bottom: 15px;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        .stat-icon.total {
            background: rgba(30, 64, 175, 0.1);
            color: var(--primary);
        }

        .stat-icon.pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stat-icon.confirmed {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .stat-icon.completed {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        .bookings-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .section-title {
            color: var(--primary);
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            border-bottom: 3px solid var(--secondary);
            padding-bottom: 15px;
        }

        .bookings-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bookings-table thead {
            background: #f8f9fa;
        }

        .bookings-table th {
            padding: 15px;
            text-align: left;
            color: var(--primary);
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bookings-table tr:hover {
            background: #f8f9fa;
        }

        .bookings-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        .bookings-table td.text-center {
            text-align: center;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .status-confirmed {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .status-completed {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .status-cancelled {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .empty-message {
            text-align: center;
            padding: 50px 20px;
            color: #999;
        }

        .empty-message i {
            font-size: 60px;
            margin-bottom: 20px;
            color: #ddd;
            display: block;
        }

        .empty-message p {
            margin: 10px 0;
            font-size: 15px;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-left-color: var(--success);
            color: #059669;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border-left-color: var(--danger);
            color: #dc2626;
        }

        footer {
            background: #2d3748;
            color: white;
            padding: 20px 0;
            margin-top: 40px;
            text-align: center;
        }

        /* ===================== PROFILE PAGE STYLES ===================== */
        .profile-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            overflow: hidden;
            margin: 20px auto;
            max-width: 600px;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .profile-icon-wrapper {
            position: relative;
            margin: 0 auto 20px;
        }

        .profile-icon-large {
            width: 90px;
            height: 90px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            border: 3px solid rgba(255,255,255,0.3);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .profile-header h1 {
            margin: 15px 0 8px;
            font-size: 28px;
            font-weight: 700;
        }

        .profile-header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .profile-body {
            padding: 40px 30px;
        }

        .profile-section {
            margin-bottom: 35px;
        }

        .section-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f1f3;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary);
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
            background: rgba(30, 64, 175, 0.02);
        }

        .form-control-info {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-divider {
            border-top: 2px solid #f0f1f3;
            margin: 30px 0;
        }

        .form-errors {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(220, 38, 38, 0.95) 100%);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 24px;
            color: white;
        }

        .form-errors strong {
            display: block;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .form-errors ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .form-errors li {
            margin-bottom: 6px;
            font-size: 14px;
            padding-left: 24px;
            position: relative;
        }

        .form-errors li:before {
            content: "✓";
            position: absolute;
            left: 0;
            opacity: 0.8;
        }

        .form-errors li:last-child {
            margin-bottom: 0;
        }

        .password-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.95) 0%, rgba(37, 99, 235, 0.95) 100%);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 13px;
            color: white;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 35px;
            padding-top: 20px;
            border-top: 2px solid #f0f1f3;
        }

        .btn-submit,
        .btn-cancel {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary) 0%, #1e3a8a 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 64, 175, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-cancel {
            background: #f3f4f6;
            color: var(--primary);
            border: 2px solid #e5e7eb;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
            border-color: var(--primary);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 16px 20px;
            border-left: 4px solid;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%);
            border-left-color: #10b981;
            color: white;
        }

        .alert-success i {
            font-size: 20px;
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(220, 38, 38, 0.95) 100%);
            border-left-color: #ef4444;
            color: white;
        }

        .alert-error i {
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .profile-container {
                margin: 10px;
            }

            .profile-header {
                padding: 30px 20px;
            }

            .profile-header h1 {
                font-size: 24px;
            }

            .profile-body {
                padding: 25px 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-submit,
            .btn-cancel {
                width: 100%;
            }
        }

        /* ===================== PENDING BOOKINGS STYLES ===================== */
        .pending-bookings-section {
            margin-bottom: 40px !important;
            background: linear-gradient(135deg, #fff7e6 0%, #ffe8cc 100%) !important;
            border: 2px solid #ff6b35 !important;
            border-radius: 12px !important;
            padding: 25px !important;
        }

        .pending-bookings-section .section-title {
            color: #ff6b35 !important;
            margin-bottom: 20px !important;
            border-bottom: none !important;
            padding-bottom: 0 !important;
        }

        .pending-bookings-list {
            margin-top: 20px;
        }

        .pending-booking-card {
            background: white !important;
            padding: 20px !important;
            margin-bottom: 15px !important;
            border-radius: 8px !important;
            border-left: 5px solid #ff6b35 !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .pending-booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
        }

        .pending-booking-card > div:first-child {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }

        .pending-booking-card p {
            margin: 0;
        }

        .btn-confirm,
        .btn-detail {
            padding: 12px !important;
            border: none !important;
            border-radius: 6px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s !important;
            font-size: 14px !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
            color: white !important;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3) !important;
        }

        .btn-detail {
            background: #f0f0f0 !important;
            color: #333 !important;
        }

        .btn-detail:hover {
            background: #e0e0e0 !important;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .pending-booking-card > div:first-child {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .pending-booking-card > div:last-child {
                flex-direction: column;
            }

            .btn-confirm,
            .btn-detail {
                flex: 1 !important;
            }
        }

    </style>
</head>
<body>
    <?php require_once ROOT_PATH . 'views/layouts/navbar-client.php'; ?>

    <!-- Page Content -->
    <?php require_once $contentPath; ?>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2026 Du Lịch Thông Minh. Tất cả quyền lợi được bảo vệ.</p>
            <p>
                <a href="<?= url('client_contact') ?>" style="color: #fff;">Liên Hệ</a> |
                <a href="<?= url('client_about') ?>" style="color: #fff;">Về Chúng Tôi</a>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?= asset('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= asset('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>

