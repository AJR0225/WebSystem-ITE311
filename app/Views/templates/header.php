<?php
/**
 * Dynamic Navigation Bar Template
 * 
 * This header template includes role-specific navigation items
 * accessible from anywhere in the application.
 * 
 * Features:
 * - Role-based navigation (Admin, Instructor, Student)
 * - User dropdown menu with role badge
 * - Active page highlighting
 * - Responsive design
 */

// Check if user is logged in
$isLoggedIn = session()->get('is_logged_in');
$userRole = session()->get('user_role');
$userRole = $userRole ? strtolower($userRole) : '';

// Normalize "teacher" to "instructor" for compatibility
if ($userRole === 'teacher') {
    $userRole = 'instructor';
}

// Get current URI for active link highlighting
$currentUri = uri_string();

// Check if user is on a public page (home, about, contact)
$isPublicPage = in_array($currentUri, ['', 'home', 'about', 'contact', 'login', 'register']);

// Check if user is on dashboard or admin pages
$isDashboardOrAdminPage = ($currentUri == 'dashboard' || strpos($currentUri, 'admin/') === 0 || strpos($currentUri, 'instructor/') === 0 || strpos($currentUri, 'student/') === 0);

// Fetch unread notification count for logged-in users
$unreadNotificationCount = 0;
if ($isLoggedIn && !$isPublicPage) {
    $userId = session()->get('user_id');
    if ($userId) {
        $notificationModel = new \App\Models\NotificationModel();
        // Admin sees all notifications, others see only their own
        if ($userRole === 'admin') {
            $unreadNotificationCount = $notificationModel->where('is_read', 0)->countAllResults();
        } else {
            $unreadNotificationCount = $notificationModel->getUnreadCount($userId);
        }
    }
}
?>

<header>
    <div class="container">
        <nav>
            <?php if (!$isDashboardOrAdminPage): ?>
                <!-- Logo only on public pages -->
                <div class="logo">LMS</div>
            <?php endif; ?>
            <ul class="nav-links">
                <?php if (!$isDashboardOrAdminPage): ?>
                    <!-- Common Navigation Items (Visible only on Public Pages) -->
                    <li>
                        <a href="<?= base_url() ?>" class="<?= ($currentUri == '' || $currentUri == 'home') ? 'active' : '' ?>">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('about') ?>" class="<?= ($currentUri == 'about') ? 'active' : '' ?>">
                            <i class="bi bi-info-circle"></i> About
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('contact') ?>" class="<?= ($currentUri == 'contact') ? 'active' : '' ?>">
                            <i class="bi bi-envelope"></i> Contact
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if ($isLoggedIn && !$isPublicPage): ?>
                    <?php if ($isDashboardOrAdminPage): ?>
                        <!-- Dashboard Link (Visible to All Logged-in Users on Dashboard/Admin Pages) -->
                        <li class="role-nav-section">
                            <a href="<?= base_url('dashboard') ?>" class="<?= ($currentUri == 'dashboard') ? 'active' : '' ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        
                        <?php if ($userRole === 'admin'): ?>
                            <!-- Admin-Specific Navigation Items (Only on Dashboard/Admin Pages) -->
                            <li class="role-nav-section">
                                <a href="<?= base_url('admin/announcement') ?>" class="<?= (strpos($currentUri, 'admin/announcement') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-megaphone"></i> Announcement
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('admin/manage-user') ?>" class="<?= (strpos($currentUri, 'admin/manage-user') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-people"></i> Manage User
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('admin/create-user') ?>" class="<?= (strpos($currentUri, 'admin/create-user') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-person-plus"></i> Create User
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('admin/courses') ?>" class="<?= (strpos($currentUri, 'admin/courses') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-book"></i> Courses
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('admin/materials') ?>" class="<?= (strpos($currentUri, 'admin/materials') !== false || strpos($currentUri, 'admin/course') !== false && strpos($currentUri, '/upload') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-file-earmark-arrow-up"></i> Materials
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('logout') ?>" onclick="return confirm('Are you sure you want to log out?');" style="color: #ff4444;">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        <?php elseif ($userRole === 'instructor'): ?>
                            <!-- Instructor-Specific Navigation Items (Only on Dashboard/Instructor Pages) -->
                            <li class="role-nav-section">
                                <a href="<?= base_url('instructor/courses') ?>" class="<?= (strpos($currentUri, 'instructor/courses') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-book"></i> My Courses
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('instructor/students') ?>" class="<?= (strpos($currentUri, 'instructor/students') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-people"></i> Students
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('instructor/grades') ?>" class="<?= (strpos($currentUri, 'instructor/grades') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-clipboard-check"></i> Grades
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('instructor/quizzes') ?>" class="<?= (strpos($currentUri, 'instructor/quizzes') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-question-circle"></i> Quizzes
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('instructor/assignments') ?>" class="<?= (strpos($currentUri, 'instructor/assignments') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-file-earmark-text"></i> Assignments
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('instructor/materials') ?>" class="<?= (strpos($currentUri, 'instructor/materials') !== false || strpos($currentUri, 'admin/course') !== false && strpos($currentUri, '/upload') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-file-earmark-arrow-up"></i> Materials
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('logout') ?>" onclick="return confirm('Are you sure you want to log out?');" style="color: #ff4444;">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        <?php else: ?>
                            <!-- Student-Specific Navigation Items (Only on Dashboard/Student Pages) -->
                            <li class="role-nav-section">
                                <a href="<?= base_url('student/courses') ?>" class="<?= (strpos($currentUri, 'student/courses') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-book"></i> My Courses
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('student/enrollments') ?>" class="<?= (strpos($currentUri, 'student/enrollments') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-list-check"></i> Enrollments
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('student/grades') ?>" class="<?= (strpos($currentUri, 'student/grades') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-trophy"></i> Grades
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('student/assignments') ?>" class="<?= (strpos($currentUri, 'student/assignments') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-file-earmark-text"></i> Assignments
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('student/quizzes') ?>" class="<?= (strpos($currentUri, 'student/quizzes') !== false) ? 'active' : '' ?>">
                                    <i class="bi bi-question-circle"></i> Quizzes
                                </a>
                            </li>
                            <li class="role-nav-section">
                                <a href="<?= base_url('logout') ?>" onclick="return confirm('Are you sure you want to log out?');" style="color: #ff4444;">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- Notification Dropdown (Only for logged-in users) -->
                    <?php if ($isLoggedIn && !$isPublicPage): ?>
                        <li class="notification-dropdown-container">
                            <div class="dropdown notification-dropdown">
                                <a href="#" class="notification-link dropdown-toggle" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                                    <i class="bi bi-bell"></i>
                                    <span class="badge bg-danger notification-badge" id="notificationBadge" style="display: none;">0</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationDropdown" id="notificationMenu">
                                    <li class="dropdown-header">
                                        <strong>Notifications</strong>
                                        <span class="badge bg-primary ms-2" id="notificationCount">0</span>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <div id="notificationList" class="notification-list">
                                            <div class="text-center p-3 text-muted">
                                                <i class="bi bi-inbox"></i>
                                                <p class="mb-0 mt-2">No notifications</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-center" href="<?= base_url('notifications') ?>">
                                            <small>View all notifications</small>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    <?php endif; ?>
                    
                    <!-- User Menu Dropdown (Only on Dashboard/Admin Pages, NOT on public pages) -->
                    <li class="user-menu">
                        <div class="user-info">
                            <span><?= esc(session()->get('user_name') ?? 'User') ?></span>
                            <span class="user-role-badge"><?= esc(ucfirst($userRole ?: 'student')) ?></span>
                        </div>
                        <div class="dropdown-menu">
                            <div class="dropdown-trigger">
                                <i class="bi bi-person-circle"></i>
                                <span>Menu</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div class="dropdown-content">
                                <a href="<?= base_url('dashboard') ?>">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                                <a href="<?= base_url('profile') ?>">
                                    <i class="bi bi-person"></i> My Profile
                                </a>
                                <?php if ($userRole === 'instructor'): ?>
                                    <div class="divider"></div>
                                    <a href="<?= base_url('instructor/courses') ?>">
                                        <i class="bi bi-book"></i> My Courses
                                    </a>
                                <?php else: ?>
                                    <div class="divider"></div>
                                    <a href="<?= base_url('student/courses') ?>">
                                        <i class="bi bi-book"></i> My Courses
                                    </a>
                                <?php endif; ?>
                                <div class="divider"></div>
                                <a href="<?= base_url('logout') ?>" onclick="return confirm('Are you sure you want to log out?');">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </div>
                        </div>
                    </li>
                <?php else: ?>
                    <!-- Guest Navigation -->
                    <li>
                        <a href="<?= base_url('login') ?>" class="<?= ($currentUri == 'login') ? 'active' : '' ?>">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('register') ?>" class="<?= ($currentUri == 'register') ? 'active' : '' ?>">
                            <i class="bi bi-person-plus"></i> Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

