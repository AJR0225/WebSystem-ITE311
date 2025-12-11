<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Learning Management System' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* Main Layout Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #1a1d29;
            color: #e8e8e8;
            padding: 1rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
            flex-wrap: wrap;
        }
        .nav-links li {
            margin-left: 1.5rem;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-links a i {
            font-size: 1rem;
        }
        .nav-links a:hover {
            background-color: #2d3447;
            color: #ffffff;
        }
        .nav-links a.active {
            background-color: #4a9eff;
            color: #ffffff;
        }
        .notification-dropdown-container {
            position: relative;
            margin-left: 1rem;
        }
        .notification-dropdown {
            position: relative;
        }
        .notification-link {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e8e8e8;
            font-size: 1.3rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s;
            text-decoration: none;
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
        }
        .notification-link:hover {
            background-color: rgba(74, 158, 255, 0.1);
            color: #4a9eff;
        }
        .notification-link .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            line-height: 1.4;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .notification-menu {
            min-width: 350px;
            max-width: 400px;
            max-height: 500px;
            overflow-y: auto;
            background-color: #2d3447;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .notification-menu .dropdown-header {
            color: #e8e8e8;
            padding: 12px 16px;
            background-color: rgba(74, 158, 255, 0.1);
        }
        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }
        .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: background-color 0.2s;
        }
        .notification-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .notification-item.unread {
            background-color: rgba(74, 158, 255, 0.05);
        }
        .notification-item .notification-message {
            color: #e8e8e8;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        .notification-item .notification-time {
            color: #888888;
            font-size: 0.75rem;
        }
        .notification-item .mark-read-btn {
            margin-top: 8px;
            font-size: 0.8rem;
            padding: 4px 12px;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #e8e8e8;
            font-size: 0.9rem;
        }
        .user-role-badge {
            background: rgba(74, 158, 255, 0.2);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #4a9eff;
            font-weight: 600;
        }
        .dropdown-menu {
            position: relative;
        }
        .dropdown-trigger {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .dropdown-trigger:hover {
            background-color: #2d3447;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #2d3447;
            min-width: 220px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            margin-top: 10px;
            padding: 10px 0;
            z-index: 1000;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .dropdown-menu:hover .dropdown-content,
        .dropdown-menu:focus-within .dropdown-content {
            display: block;
        }
        .dropdown-content a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 10px 20px;
            color: #e8e8e8;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .dropdown-content a i {
            font-size: 1.1rem;
            width: 20px;
        }
        .dropdown-content a:hover {
            background-color: #3a4154;
            color: #ffffff;
        }
        .dropdown-content .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 8px 0;
        }
        .role-nav-section {
            border-left: 2px solid rgba(74, 158, 255, 0.3);
            padding-left: 15px;
            margin-left: 15px;
        }
        .role-nav-section a {
            color: #4a9eff;
        }
        .role-nav-section a:hover {
            background-color: rgba(74, 158, 255, 0.1);
            color: #5bb0ff;
        }
        .role-nav-section a.active {
            background-color: rgba(74, 158, 255, 0.2);
            color: #6bc0ff;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            .nav-links li {
                margin-left: 0;
                width: 100%;
            }
            .nav-links a {
                width: 100%;
                padding: 0.75rem 1rem;
            }
            .role-nav-section {
                border-left: none;
                padding-left: 0;
                margin-left: 0;
                border-top: 2px solid rgba(74, 158, 255, 0.3);
                padding-top: 10px;
                margin-top: 10px;
            }
            .user-menu {
                margin-left: 0;
                width: 100%;
                justify-content: space-between;
            }
            .dropdown-content {
                right: auto;
                left: 0;
                width: 100%;
            }
        }
        .hero {
            background-color: white;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 2rem;
        }
        .hero h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .hero p {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .feature-card {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .feature-card h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        footer {
            background-color: #1a1d29;
            color: #e8e8e8;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.3);
        }
        
        /* Auth Pages Styles (Login/Register) */
        body.auth-page {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-container {
            background: #0a0a0a;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8);
            padding: 35px;
            max-width: 420px;
            width: 100%;
            border: 1px solid rgba(255, 102, 0, 0.2);
        }
        .login-container {
            background: #0a0a0a;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8);
            padding: 35px;
            max-width: 420px;
            width: 100%;
            border: 1px solid rgba(255, 102, 0, 0.2);
        }
        .register-container {
            background: #0a0a0a;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8);
            padding: 35px;
            max-width: 420px;
            width: 100%;
            border: 1px solid rgba(255, 102, 0, 0.2);
        }
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-header h2,
        .register-header h2,
        .login-header h2 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 1.75rem;
            letter-spacing: 0.5px;
        }
        .auth-header p,
        .register-header p,
        .login-header p {
            color: #e0e0e0;
            margin: 0;
            font-size: 0.9rem;
            font-weight: 400;
        }
        .form-label {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 10px;
            font-size: 1rem;
            letter-spacing: 0.3px;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid rgba(255, 102, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            padding: 12px 15px;
            transition: all 0.3s;
            font-size: 1rem;
            font-weight: 400;
        }
        .form-control:focus {
            border-color: rgba(255, 102, 0, 0.5);
            background-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, 0.2);
            color: #ffffff;
            outline: none;
        }
        .form-control::placeholder {
            color: #888888;
            font-weight: 400;
        }
        .btn-register,
        .btn-login {
            background: linear-gradient(135deg, #ff6600 0%, #e55a00 100%);
            border: 1px solid rgba(255, 102, 0, 0.5);
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(255, 102, 0, 0.3);
        }
        .btn-register:hover,
        .btn-login:hover {
            background: linear-gradient(135deg, #ff7700 0%, #ff6600 100%);
            border-color: rgba(255, 102, 0, 0.7);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 102, 0, 0.5);
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .invalid-feedback {
            display: block;
            margin-top: 5px;
            color: #f44336;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .form-text {
            color: #c0c0c0;
            font-size: 0.9rem;
            font-weight: 400;
            margin-top: 8px;
        }
        .login-link,
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #d0d0d0;
        }
        .login-link a,
        .register-link a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        .login-link a:hover,
        .register-link a:hover {
            color: #ff6600;
            text-decoration: underline;
        }
        .password-toggle {
            position: relative;
        }
        .password-toggle-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #b8b8b8;
            cursor: pointer;
            padding: 0;
            z-index: 10;
            transition: color 0.3s;
        }
        .password-toggle-btn:hover {
            color: #e8e8e8;
        }
        
        /* Card styling for login/register */
        .card {
            background: #0a0a0a;
            border: 1px solid rgba(255, 102, 0, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8);
            padding: 35px;
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
        }
        
        .card h2 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 2rem;
            letter-spacing: 0.5px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid;
        }
        
        .alert-success {
            background-color: #1e3a2e;
            border-color: #2d5a3d;
            color: #a8d5ba;
        }
        
        .alert-danger {
            background-color: #3a1e1e;
            border-color: #5a2d2d;
            color: #d5a8a8;
        }
        
        /* ============================================
           DASHBOARD CSS - All Dashboard Styles
           ============================================ */
        
        /* Dashboard Dark Minimalist Design - Landscape Layout */
        body.dashboard-page {
            background: #000000;
            min-height: 100vh;
            font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            overflow-x: auto;
            margin: 0;
            padding: 0;
        }
        
        /* Admin Dashboard - Obsidian Black Background */
        body.dashboard-page.admin-view {
            background: #000000;
        }
        
        /* Admin Dashboard Wrapper */
        .admin-dashboard-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 25%, #0d0d0d 50%, #1a1a1a 75%, #000000 100%);
        }
        
        /* Dashboard Content Wrapper - Full Width (No Sidebar) */
        .dashboard-content-wrapper {
            width: 100%;
            min-height: calc(100vh - 80px);
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 25%, #0d0d0d 50%, #1a1a1a 75%, #000000 100%);
            padding: 40px 20px;
        }
        
        /* Dashboard Main Content - Full Width */
        .dashboard-main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }
        
        /* Dashboard Welcome Section */
        .dashboard-welcome-section {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .dashboard-welcome-title {
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            letter-spacing: -0.5px;
        }
        
        .dashboard-welcome-subtitle {
            color: #d0d0d0;
            font-size: 1.2rem;
            margin: 0;
            font-weight: 500;
        }
        
        /* Dashboard Content Area */
        .dashboard-content-area {
            margin-top: 30px;
        }
        
        .dashboard-container {
            background: linear-gradient(145deg, #0a0a0a 0%, #1a1a1a 50%, #0d0d0d 100%);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8), 
                        0 0 0 1px rgba(255, 255, 255, 0.05),
                        inset 0 0 100px rgba(0, 0, 0, 0.5);
            padding: 40px 60px;
            max-width: 95%;
            min-width: 1400px;
            width: 100%;
            text-align: left;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .dashboard-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        }
        
        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .stat-card {
            background: linear-gradient(145deg, #0f0f0f 0%, #1a1a1a 100%);
            border: 1px solid rgba(255, 102, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 102, 0, 0.3);
            border-color: rgba(255, 102, 0, 0.5);
            background: linear-gradient(145deg, #1a1a1a 0%, #0f0f0f 100%);
        }
        
        .stat-number {
            font-size: 2.8rem;
            font-weight: 700;
            color: #ff6600;
            margin: 8px 0 0 0;
            font-family: 'Segoe UI', 'Roboto', sans-serif;
            text-shadow: 0 2px 10px rgba(255, 102, 0, 0.4);
            letter-spacing: -1px;
        }
        
        .stat-label {
            color: #ffffff;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            margin-bottom: 12px;
            display: block;
        }
        
        /* Data Lists */
        .data-section {
            margin: 25px 0;
            text-align: left;
        }
        
        .data-section h3 {
            color: #ffffff;
            font-size: 1.3rem;
            margin-bottom: 18px;
            text-align: left;
            padding-bottom: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            border-bottom: 2px solid rgba(255, 102, 0, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .data-list {
            background: linear-gradient(145deg, #0f0f0f 0%, #1a1a1a 100%);
            border-radius: 10px;
            padding: 18px;
            max-height: 350px;
            overflow-y: auto;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .data-item {
            background: linear-gradient(145deg, #0a0a0a 0%, #151515 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }
        
        .data-item:hover {
            background: linear-gradient(145deg, #151515 0%, #1a1a1a 100%);
            border-color: rgba(255, 102, 0, 0.4);
            transform: translateX(3px);
            box-shadow: 0 4px 12px rgba(255, 102, 0, 0.2);
        }
        
        .data-item-title {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: capitalize;
            letter-spacing: 0.2px;
        }
        
        .data-item-detail {
            color: #e0e0e0;
            font-size: 0.95rem;
            margin: 8px 0;
            line-height: 1.6;
            font-weight: 400;
        }
        
        .data-item-meta {
            color: #c0c0c0;
            font-size: 0.85rem;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 102, 0, 0.2);
            font-weight: 400;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #c0c0c0;
            font-size: 1rem;
            font-weight: 400;
        }
        
        /* Dashboard Responsive */
        @media (max-width: 768px) {
            .dashboard-content-wrapper {
                padding: 20px 15px;
            }
            
            .dashboard-main-content {
                padding: 20px 15px;
            }
            
            .dashboard-welcome-title {
                font-size: 2rem;
            }
            
            .dashboard-welcome-subtitle {
                font-size: 1rem;
            }
        }

        /* Admin Courses Management Styles */
        .table-container {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: rgba(255, 255, 255, 0.05);
        }

        .data-table th {
            color: #ffffff;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table td {
            color: #e0e0e0;
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.95rem;
        }

        .data-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .description-cell {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .instructor-info {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #e0e0e0;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.status-published {
            background: rgba(76, 175, 80, 0.2);
            color: #4caf50;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .status-badge.status-draft {
            background: rgba(158, 158, 158, 0.2);
            color: #9e9e9e;
            border: 1px solid rgba(158, 158, 158, 0.3);
        }

        .status-badge.status-archived {
            background: rgba(244, 67, 54, 0.2);
            color: #f44336;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #888888;
        }

        .text-warning {
            color: #ff9800;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            background: linear-gradient(145deg, #0a0a0a 0%, #1a1a1a 100%);
            margin: 5% auto;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
        }

        .modal-small {
            max-width: 500px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-header h2 {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .close {
            color: #aaaaaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover,
        .close:focus {
            color: #ffffff;
        }

        .modal-body {
            padding: 20px 30px;
            color: #e0e0e0;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .required {
            color: #ff4444;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #ffffff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: rgba(255, 102, 0, 0.5);
            box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
        }

        .form-control::placeholder {
            color: #888888;
        }

        .error-message {
            color: #f44336;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .btn-cancel {
            background: rgba(158, 158, 158, 0.2);
            color: #9e9e9e;
            border: 1px solid rgba(158, 158, 158, 0.3);
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: rgba(158, 158, 158, 0.3);
        }

        .btn-submit {
            background: linear-gradient(135deg, #ff6600 0%, #e55a00 100%);
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 102, 0, 0.4);
        }
    </style>
    
    <script>
        // Add admin-view class to body for dashboard
        document.addEventListener('DOMContentLoaded', function() {
            <?php 
            $jsUserRole = session()->get('user_role') ?? 'student';
            $jsUserRole = strtolower($jsUserRole);
            if ($jsUserRole === 'admin'): 
            ?>
            document.body.classList.add('admin-view');
            <?php endif; ?>
        });
    </script>
    
    <?= $this->renderSection('head') ?? '' ?>
</head>
<body class="<?= isset($body_class) ? $body_class : '' ?>">
    <?php if (!isset($hide_header) || $hide_header !== true): ?>
        <?= $this->include('templates/header') ?>
    <?php endif; ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?php if (!isset($hide_footer) || $hide_footer !== true): ?>
    <footer>
        <div class="container">
            <p>&copy; 2024 Learning Management System. All rights reserved.</p>
        </div>
    </footer>
    <?php endif; ?>

    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Notification System Script -->
    <?php if (session()->get('is_logged_in') && !in_array(uri_string(), ['', 'home', 'about', 'contact', 'login', 'register'])): ?>
    <script>
    $(document).ready(function() {
        // Function to fetch and update notifications
        function fetchNotifications() {
            $.get('<?= base_url('notifications') ?>')
                .done(function(response) {
                    if (response.success) {
                        updateNotificationBadge(response.unread_count);
                        updateNotificationList(response.notifications);
                    }
                })
                .fail(function(xhr) {
                    console.error('Failed to fetch notifications:', xhr);
                });
        }
        
        // Function to update notification badge
        function updateNotificationBadge(count) {
            const $badge = $('#notificationBadge');
            const $count = $('#notificationCount');
            
            if (count > 0) {
                $badge.text(count).show();
                $count.text(count);
            } else {
                $badge.hide();
                $count.text('0');
            }
        }
        
        // Function to update notification list
        function updateNotificationList(notifications) {
            const $list = $('#notificationList');
            
            if (!notifications || notifications.length === 0) {
                $list.html(`
                    <div class="text-center p-3 text-muted">
                        <i class="bi bi-inbox"></i>
                        <p class="mb-0 mt-2">No notifications</p>
                    </div>
                `);
                return;
            }
            
            let html = '';
            notifications.forEach(function(notification) {
                const isUnread = notification.is_read == 0;
                const timeAgo = getTimeAgo(notification.created_at);
                const unreadClass = isUnread ? 'unread' : '';
                const markReadBtn = isUnread ? `
                    <button class="btn btn-sm btn-outline-primary mark-read-btn" onclick="markAsRead(${notification.id}, this)">
                        <i class="bi bi-check-circle"></i> Mark as Read
                    </button>
                ` : '';
                
                html += `
                    <div class="notification-item ${unreadClass}" data-notification-id="${notification.id}">
                        <div class="notification-message">${escapeHtml(notification.message)}</div>
                        <div class="notification-time">
                            <i class="bi bi-clock"></i> ${timeAgo}
                        </div>
                        ${markReadBtn}
                    </div>
                `;
            });
            
            $list.html(html);
        }
        
        // Function to mark notification as read
        window.markAsRead = function(notificationId, buttonElement) {
            $.post('<?= base_url('notifications/mark_read') ?>/' + notificationId)
                .done(function(response) {
                    if (response.success) {
                        // Remove the notification item
                        $(buttonElement).closest('.notification-item').fadeOut(300, function() {
                            $(this).remove();
                            
                            // Update badge count
                            const currentCount = parseInt($('#notificationBadge').text()) || 0;
                            const newCount = Math.max(0, currentCount - 1);
                            updateNotificationBadge(newCount);
                            
                            // Update count in header
                            $('#notificationCount').text(newCount);
                            
                            // If no notifications left, show empty state
                            if ($('#notificationList .notification-item').length === 0) {
                                $('#notificationList').html(`
                                    <div class="text-center p-3 text-muted">
                                        <i class="bi bi-inbox"></i>
                                        <p class="mb-0 mt-2">No notifications</p>
                                    </div>
                                `);
                            }
                        });
                    } else {
                        alert('Failed to mark notification as read: ' + (response.message || 'Unknown error'));
                    }
                })
                .fail(function(xhr) {
                    console.error('Failed to mark notification as read:', xhr);
                    alert('Failed to mark notification as read. Please try again.');
                });
        };
        
        // Function to get time ago
        function getTimeAgo(dateString) {
            const now = new Date();
            const date = new Date(dateString);
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            if (diffInSeconds < 60) {
                return 'Just now';
            } else if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                return minutes + ' minute' + (minutes > 1 ? 's' : '') + ' ago';
            } else if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                return hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
            } else if (diffInSeconds < 604800) {
                const days = Math.floor(diffInSeconds / 86400);
                return days + ' day' + (days > 1 ? 's' : '') + ' ago';
            } else {
                return date.toLocaleDateString();
            }
        }
        
        // Function to escape HTML
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
        
        // Fetch notifications on page load (when document is ready)
        fetchNotifications();
        
        // Real-time updates: Refresh notifications every 60 seconds
        // This ensures users see new notifications without manually refreshing the page
        setInterval(fetchNotifications, 60000);
        
        // Refresh notifications when dropdown is opened (for immediate updates)
        $('#notificationDropdown').on('shown.bs.dropdown', function() {
            fetchNotifications();
        });
    });
    </script>
    <?php endif; ?>
    
    <?= $this->renderSection('scripts') ?? '' ?>
</body>
</html>
