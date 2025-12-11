<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<?php 
// Get user role for conditional display
$userRole = $user_role ?? $user['role'] ?? 'student';
$userRole = strtolower($userRole);
// Normalize "teacher" to "instructor" for compatibility
if ($userRole === 'teacher') {
    $userRole = 'instructor';
}
?>

<!-- DASHBOARD CONTENT - Full Width (No Sidebar, Uses Header Navigation) -->
<div class="dashboard-content-wrapper">
    <div class="dashboard-main-content">
        <!-- Welcome Section -->
        <div class="dashboard-welcome-section">
            <?php if ($userRole === 'admin'): ?>
                <h1 class="dashboard-welcome-title">Welcome Admin!</h1>
                <p class="dashboard-welcome-subtitle">System Control Panel</p>
            <?php elseif ($userRole === 'instructor'): ?>
                <h1 class="dashboard-welcome-title">Welcome Instructor!</h1>
                <p class="dashboard-welcome-subtitle">Teaching Dashboard</p>
            <?php else: ?>
                <h1 class="dashboard-welcome-title">Welcome Student!</h1>
                <p class="dashboard-welcome-subtitle">Learning Dashboard</p>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-content-area">
    
    <?php 
    // CONDITIONAL CONTENT BASED ON USER ROLE
    // ============================================
    ?>
    
    <?php if ($userRole === 'admin'): ?>
        <!-- ADMIN DASHBOARD CONTENT -->
        <?php if (isset($db_data) && !empty($db_data)): ?>
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Users</div>
                    <div class="stat-number"><?= esc($db_data['total_users'] ?? 0) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Courses</div>
                    <div class="stat-number"><?= esc($db_data['total_courses'] ?? 0) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Instructors</div>
                    <div class="stat-number"><?= esc($db_data['total_instructors'] ?? 0) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Students</div>
                    <div class="stat-number"><?= esc($db_data['total_students'] ?? 0) ?></div>
                </div>
            </div>
        <?php else: ?>
            <div class="data-section">
                <div class="empty-state">Welcome to the Admin Dashboard. Use the navigation above to manage the system.</div>
            </div>
        <?php endif; ?>
        
    <?php elseif ($userRole === 'instructor'): ?>
        <!-- INSTRUCTOR DASHBOARD CONTENT -->
        <?php if (isset($db_data) && !empty($db_data)): ?>
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">My Courses</div>
                    <div class="stat-number"><?= esc($db_data['total_my_courses'] ?? 0) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">My Students</div>
                    <div class="stat-number"><?= esc($db_data['total_my_students'] ?? 0) ?></div>
                </div>
            </div>
            
            <!-- My Courses -->
            <?php if (isset($db_data['my_courses']) && !empty($db_data['my_courses'])): ?>
                <div class="data-section">
                    <h3>My Courses</h3>
                    <div class="data-list">
                        <?php foreach ($db_data['my_courses'] as $course): ?>
                            <div class="data-item">
                                <div class="data-item-title"><?= esc($course['title']) ?></div>
                                <div class="data-item-detail"><?= esc(substr($course['description'] ?? '', 0, 100)) ?>...</div>
                                <div class="data-item-meta">
                                    Status: <?= esc(ucfirst($course['status'] ?? 'draft')) ?> | 
                                    Created: <?= esc(date('M d, Y', strtotime($course['created_at'] ?? 'now'))) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="data-section">
                    <div class="empty-state">You haven't created any courses yet.</div>
                </div>
            <?php endif; ?>
            
            <!-- My Students -->
            <?php if (isset($db_data['my_students']) && !empty($db_data['my_students'])): ?>
                <div class="data-section">
                    <h3>My Students</h3>
                    <div class="data-list">
                        <?php foreach ($db_data['my_students'] as $student): ?>
                            <div class="data-item">
                                <div class="data-item-title"><?= esc($student['student_name'] ?? 'Unknown') ?></div>
                                <div class="data-item-detail">Email: <?= esc($student['student_email'] ?? 'N/A') ?></div>
                                <div class="data-item-detail">Course: <?= esc($student['course_title'] ?? 'N/A') ?></div>
                                <div class="data-item-meta">
                                    Enrollment Date: <?= esc(date('M d, Y', strtotime($student['enrollment_date'] ?? 'now'))) ?> | 
                                    Status: <?= esc(ucfirst($student['status'] ?? 'enrolled')) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="data-section">
                    <div class="empty-state">No students enrolled in your courses yet.</div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- STUDENT DASHBOARD CONTENT -->
        <?php if (isset($db_data) && !empty($db_data)): ?>
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Enrolled Courses</div>
                    <div class="stat-number"><?= esc($db_data['total_enrolled_courses'] ?? 0) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Available Courses</div>
                    <div class="stat-number"><?= esc($db_data['total_available_courses'] ?? 0) ?></div>
                </div>
            </div>
            
            <!-- Enrolled Courses -->
            <?php if (isset($db_data['enrolled_courses']) && !empty($db_data['enrolled_courses'])): ?>
                <div class="data-section">
                    <h3>My Enrolled Courses</h3>
                    <div class="data-list">
                        <?php foreach ($db_data['enrolled_courses'] as $enrollment): ?>
                            <div class="data-item">
                                <div class="data-item-title"><?= esc($enrollment['title']) ?></div>
                                <div class="data-item-detail"><?= esc(substr($enrollment['description'] ?? '', 0, 100)) ?>...</div>
                                <div class="data-item-detail">Instructor: <?= esc($enrollment['instructor_name'] ?? 'N/A') ?></div>
                                <div class="data-item-meta">
                                    Enrolled: <?= esc(date('M d, Y', strtotime($enrollment['enrollment_date'] ?? 'now'))) ?> | 
                                    Status: <?= esc(ucfirst($enrollment['status'] ?? 'enrolled')) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="data-section">
                    <div class="empty-state">You are not enrolled in any courses yet.</div>
                </div>
            <?php endif; ?>
            
            <!-- Available Courses -->
            <?php if (isset($db_data['available_courses']) && !empty($db_data['available_courses'])): ?>
                <div class="data-section">
                    <h3>Available Courses</h3>
                    <div class="data-list">
                        <?php foreach ($db_data['available_courses'] as $course): ?>
                            <div class="data-item">
                                <div class="data-item-title"><?= esc($course['title']) ?></div>
                                <div class="data-item-detail"><?= esc(substr($course['description'] ?? '', 0, 100)) ?>...</div>
                                <div class="data-item-meta">
                                    Status: <?= esc(ucfirst($course['status'] ?? 'draft')) ?> | 
                                    Created: <?= esc(date('M d, Y', strtotime($course['created_at'] ?? 'now'))) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="data-section">
                    <div class="empty-state">No available courses at the moment.</div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

