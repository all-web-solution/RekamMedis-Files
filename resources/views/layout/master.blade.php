<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikasi Apotek Singkut Farma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --primary-bg: #ecfdf5;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--gray-50);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--white) 0%, var(--gray-50) 100%);
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            text-align: center;
            position: relative;
        }

        .sidebar-header h3 {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .sidebar-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            margin-top: 5px;
        }

        .sidebar-header .logo-icon {
            font-size: 40px;
            color: white;
            margin-bottom: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-title {
            padding: 10px 25px;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: var(--gray-600);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            margin: 4px 0;
        }

        .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 18px;
        }

        .menu-item span {
            font-weight: 500;
        }

        .menu-item:hover {
            background: var(--primary-bg);
            color: var(--primary);
        }

        .menu-item.active {
            background: var(--primary-bg);
            color: var(--primary);
            border-right: 4px solid var(--primary);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary);
        }

        /* Main Content */
        .main-container {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background: var(--white);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--gray-600);
            cursor: pointer;
        }

        .page-info h2 {
            font-size: 20px;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        .page-info p {
            font-size: 13px;
            color: var(--gray-500);
            margin: 5px 0 0 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .date-badge {
            background: var(--primary-bg);
            padding: 8px 16px;
            border-radius: 12px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 500;
        }

        .date-badge i {
            margin-right: 8px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Main Content Area */
        .main-content {
            padding: 30px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(5, 150, 105, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.1);
            border-color: var(--primary-light);
        }

        .stat-card h3 {
            font-size: 14px;
            color: var(--gray-500);
            margin-bottom: 10px;
            font-weight: 500;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-bg);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--primary);
        }

        /* Cards */
        .card-modern {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header-modern {
            padding: 18px 24px;
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-header-modern h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-800);
        }

        .card-header-modern h3 i {
            color: var(--primary);
            margin-right: 10px;
        }

        /* Buttons */
        .btn-icon,
        .btn-primary,
        .btn-secondary,
        .btn-warning,
        .btn-danger {
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-icon {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background: var(--gray-300);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
        }

        .btn-icon:hover,
        .btn-primary:hover,
        .btn-warning:hover,
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
            padding: 0;
        }

        .table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .table-modern th {
            text-align: left;
            padding: 16px 20px;
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-200);
            font-size: 13px;
        }

        .table-modern td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-600);
        }

        .table-modern tr:hover {
            background: var(--primary-bg);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        /* Alerts */
        .alert-success,
        .alert-error {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-left: 4px solid var(--primary);
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        /* Modal */
        .modal-glass {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            justify-content: center;
            align-items: center;
            z-index: 1100;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content-glass {
            background: var(--white);
            border-radius: 24px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header-glass {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-glass h3 {
            margin: 0;
            color: var(--gray-800);
            font-weight: 600;
        }

        .modal-header-glass h3 i {
            color: var(--primary);
            margin-right: 10px;
        }

        .modal-body-glass {
            padding: 24px;
        }

        .modal-footer-glass {
            padding: 16px 24px;
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: var(--gray-50);
            border-radius: 0 0 24px 24px;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: var(--gray-400);
            transition: all 0.3s ease;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .close-modal:hover {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        /* Forms */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .input-group-custom {
            display: flex;
            flex-direction: column;
        }

        .input-group-custom label {
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 13px;
        }

        .input-group-custom label i {
            color: var(--primary);
            margin-right: 6px;
        }

        .input-group-custom input,
        .input-group-custom select,
        .input-group-custom textarea {
            padding: 10px 14px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .input-group-custom input:focus,
        .input-group-custom select:focus,
        .input-group-custom textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        /* Filter Section */
        .filter-section {
            background: var(--white);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-section h4 {
            color: var(--gray-800);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .filter-section h4 i {
            color: var(--primary);
            margin-right: 8px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 12px;
            color: var(--gray-500);
        }

        .filter-input {
            padding: 10px 14px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        /* Detail Item */
        .detail-item {
            padding: 12px 0;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
        }

        .detail-item strong {
            width: 140px;
            color: var(--gray-700);
            font-weight: 600;
        }

        .detail-item span {
            color: var(--gray-600);
            flex: 1;
        }

        /* Chart Container */
        .chart-container {
            padding: 20px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
            }

            .sidebar-header h3,
            .sidebar-header p,
            .menu-item span {
                display: none;
            }

            .menu-item i {
                margin-right: 0;
                font-size: 20px;
            }

            .menu-item {
                justify-content: center;
                padding: 12px 0;
            }

            .menu-title {
                text-align: center;
                padding: 10px 0;
                font-size: 10px;
            }

            .main-container {
                margin-left: 80px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -280px;
            }

            .sidebar.show {
                left: 0;
            }

            .main-container {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .detail-item {
                flex-direction: column;
            }

            .detail-item strong {
                width: 100%;
                margin-bottom: 5px;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/native-ui-fixes.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    @include('layout.sidebar')

    <!-- Main Container -->
    <div class="main-container">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="page-info">
                <h2>@yield('page-title', 'Dashboard')</h2>
                <p>@yield('page-description', 'Selamat datang di sistem manajemen klinik')</p>
            </div>
            <div class="user-info">
                <div class="date-badge">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="currentDate"></span>
                </div>
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Set current date
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('id-ID', options);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/delete-confirmation.js') }}"></script>
    @stack('scripts')
</body>

</html>
