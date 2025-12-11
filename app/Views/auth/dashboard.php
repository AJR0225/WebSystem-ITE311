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
        <?php 
        // Ensure db_data is set and has required keys
        if (!isset($db_data)) {
            $db_data = [];
        }
        if (!isset($db_data['enrolled_courses'])) {
            $db_data['enrolled_courses'] = [];
        }
        if (!isset($db_data['available_courses'])) {
            $db_data['available_courses'] = [];
        }
        if (!isset($db_data['total_enrolled_courses'])) {
            $db_data['total_enrolled_courses'] = 0;
        }
        if (!isset($db_data['total_available_courses'])) {
            $db_data['total_available_courses'] = 0;
        }
        ?>
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Enrolled Courses</div>
                <div class="stat-number"><?= esc($db_data['total_enrolled_courses']) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Available Courses</div>
                <div class="stat-number"><?= esc($db_data['total_available_courses']) ?></div>
            </div>
        </div>
            
            <!-- Enrolled Courses Section -->
            <div class="data-section">
                <h3>My Enrolled Courses</h3>
                <div id="enrolled-courses-container">
                    <?php if (isset($db_data['enrolled_courses']) && !empty($db_data['enrolled_courses'])): ?>
                        <div class="list-group mb-4" id="enrolled-courses-list">
                            <?php foreach ($db_data['enrolled_courses'] as $enrollment): ?>
                                <div class="list-group-item list-group-item-action enrolled-course-item" data-course-id="<?= esc($enrollment['course_id'] ?? '') ?>" style="background: linear-gradient(145deg, #0f0f0f 0%, #1a1a1a 100%); border: 1px solid rgba(255, 102, 0, 0.2); border-radius: 8px; margin-bottom: 12px; padding: 20px;">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-2" style="color: #ffffff; font-weight: 600;"><?= esc($enrollment['title'] ?? 'Untitled Course') ?></h5>
                                            <p class="mb-2" style="color: #d0d0d0; font-size: 0.95rem;">
                                                <?= esc(substr($enrollment['description'] ?? 'No description available', 0, 150)) ?><?= strlen($enrollment['description'] ?? '') > 150 ? '...' : '' ?>
                                            </p>
                                            <div class="mt-2">
                                                <small class="text-muted" style="color: #888888;">
                                                    <i class="bi bi-person"></i> Instructor: <?= esc($enrollment['instructor_name'] ?? 'N/A') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3" style="border-top: 1px solid rgba(255, 102, 0, 0.2);">
                                        <small class="text-muted" style="color: #888888;">
                                            <i class="bi bi-calendar-check"></i> Enrolled: <?= esc(date('M d, Y', strtotime($enrollment['enrollment_date'] ?? 'now'))) ?> | 
                                            <span class="badge" style="background: rgba(255, 102, 0, 0.3); color: #ff6600; padding: 4px 8px; border-radius: 4px;">
                                                <?= esc(ucfirst($enrollment['status'] ?? 'enrolled')) ?>
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info" id="no-enrollments-message" style="background: rgba(255, 102, 0, 0.1); border: 1px solid rgba(255, 102, 0, 0.3); color: #d0d0d0; padding: 20px; border-radius: 8px;">
                            <i class="bi bi-info-circle"></i> You are not enrolled in any courses yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Available Courses Section -->
            <div class="data-section">
                <h3>Available Courses</h3>
                <div id="available-courses-container">
                    <?php if (isset($db_data['available_courses']) && !empty($db_data['available_courses'])): ?>
                        <div class="row g-3" id="available-courses-list">
                            <?php foreach ($db_data['available_courses'] as $course): ?>
                                <div class="col-md-6 col-lg-4 available-course-card" data-course-id="<?= esc($course['id']) ?>">
                                    <div class="card h-100" style="background: linear-gradient(145deg, #0f0f0f 0%, #1a1a1a 100%); border: 1px solid rgba(255, 102, 0, 0.2); border-radius: 10px;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title" style="color: #ffffff; font-weight: 600; margin-bottom: 12px;">
                                                <?= esc($course['title'] ?? 'Untitled Course') ?>
                                            </h5>
                                            <p class="card-text flex-grow-1" style="color: #d0d0d0; font-size: 0.9rem; margin-bottom: 15px;">
                                                <?= esc(substr($course['description'] ?? 'No description available', 0, 100)) ?><?= strlen($course['description'] ?? '') > 100 ? '...' : '' ?>
                                            </p>
                                            <div class="mb-3">
                                                <small class="text-muted" style="color: #888888;">
                                                    <i class="bi bi-calendar"></i> Created: <?= esc(date('M d, Y', strtotime($course['created_at'] ?? 'now'))) ?>
                                                </small>
                                            </div>
                                            <button 
                                                type="button"
                                                class="btn btn-primary enroll-btn w-100" 
                                                data-course-id="<?= esc($course['id']) ?>"
                                                data-course-title="<?= esc($course['title'] ?? 'Untitled Course') ?>"
                                                data-course-description="<?= esc($course['description'] ?? 'No description available') ?>"
                                                style="background: linear-gradient(135deg, #ff6600 0%, #e55a00 100%); border: none; color: #ffffff; font-weight: 600; padding: 10px; border-radius: 6px; transition: all 0.3s ease;"
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 15px rgba(255, 102, 0, 0.4)'"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                                <i class="bi bi-plus-circle"></i> Enroll
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info" style="background: rgba(255, 102, 0, 0.1); border: 1px solid rgba(255, 102, 0, 0.3); color: #d0d0d0; padding: 20px; border-radius: 8px;">
                            <i class="bi bi-info-circle"></i> No available courses at the moment.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Alert Container for Messages -->
            <div id="enrollment-alert-container" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;"></div>
    <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php if ($userRole === 'student' || $userRole === ''): ?>
<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Function to show Bootstrap alert
    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        $('#enrollment-alert-container').html(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $('#enrollment-alert-container .alert').fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
    
    // Function to add enrolled course to the list dynamically
    function addEnrolledCourse(courseData) {
        const enrolledList = $('#enrolled-courses-list');
        const noEnrollmentsMsg = $('#no-enrollments-message');
        
        // Remove "no enrollments" message if it exists
        if (noEnrollmentsMsg.length) {
            noEnrollmentsMsg.remove();
        }
        
        // Create enrolled courses list if it doesn't exist
        if (!enrolledList.length) {
            $('#enrolled-courses-container').html('<div class="list-group mb-4" id="enrolled-courses-list"></div>');
        }
        
        // Format enrollment date
        const enrollmentDate = courseData.enrollment_date ? new Date(courseData.enrollment_date) : new Date();
        const formattedDate = enrollmentDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        
        const courseHtml = `
            <div class="list-group-item list-group-item-action enrolled-course-item" data-course-id="${courseData.id || courseData.course_id}" style="background: linear-gradient(145deg, #0f0f0f 0%, #1a1a1a 100%); border: 1px solid rgba(255, 102, 0, 0.2); border-radius: 8px; margin-bottom: 12px; padding: 20px;">
                <div class="d-flex w-100 justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="mb-2" style="color: #ffffff; font-weight: 600;">${courseData.title || 'Untitled Course'}</h5>
                        <p class="mb-2" style="color: #d0d0d0; font-size: 0.95rem;">
                            ${(courseData.description || 'No description available').substring(0, 150)}${(courseData.description || '').length > 150 ? '...' : ''}
                        </p>
                        <div class="mt-2">
                            <small class="text-muted" style="color: #888888;">
                                <i class="bi bi-person"></i> Instructor: ${courseData.instructor_name || 'N/A'}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="mt-3 pt-3" style="border-top: 1px solid rgba(255, 102, 0, 0.2);">
                    <small class="text-muted" style="color: #888888;">
                        <i class="bi bi-calendar-check"></i> Enrolled: ${formattedDate} | 
                        <span class="badge" style="background: rgba(255, 102, 0, 0.3); color: #ff6600; padding: 4px 8px; border-radius: 4px;">
                            ${courseData.status || 'Enrolled'}
                        </span>
                    </small>
                </div>
            </div>
        `;
        
        // Prepend new course to the list (most recent first)
        $('#enrolled-courses-list').prepend(courseHtml);
        
        // Update statistics
        const currentCount = parseInt($('.stat-card:first .stat-number').text()) || 0;
        $('.stat-card:first .stat-number').text(currentCount + 1);
    }
    
    // Handle enroll button clicks
    $('.enroll-btn').on('click', function(e) {
        e.preventDefault(); // Prevent default form submission behavior
        
        const $button = $(this);
        const courseId = $button.data('course-id');
        const courseTitle = $button.data('course-title') || 'Untitled Course';
        const courseDescription = $button.data('course-description') || 'No description available';
        const originalText = $button.html();
        const $courseCard = $button.closest('.available-course-card');
        
        // Disable button and show loading state
        $button.prop('disabled', true);
        $button.html('<i class="bi bi-hourglass-split"></i> Enrolling...');
        
            // Send AJAX POST request using jQuery
            $.post('<?= base_url('course/enroll') ?>', {
                course_id: courseId
            })
        .done(function(data) {
            if (data.success) {
                // Show success Bootstrap alert
                showAlert(data.message || 'Successfully enrolled in the course!', 'success');
                
                // Hide or disable the Enroll button
                $button.html('<i class="bi bi-check-circle"></i> Enrolled').prop('disabled', true);
                $button.css({
                    'background': 'linear-gradient(135deg, #28a745 0%, #20c997 100%)',
                    'cursor': 'not-allowed'
                });
                
                // Add course to enrolled courses list dynamically using data from server
                if (data.course) {
                    addEnrolledCourse(data.course);
                } else {
                    // Fallback if course data not provided
                    addEnrolledCourse({
                        course_id: courseId,
                        title: courseTitle,
                        description: courseDescription,
                        instructor_name: 'N/A',
                        enrollment_date: new Date().toISOString(),
                        status: 'enrolled'
                    });
                }
                
                // Remove course card from available courses after a delay
                setTimeout(function() {
                    $courseCard.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if no more available courses
                        if ($('#available-courses-list .available-course-card').length === 0) {
                            $('#available-courses-container').html(`
                                <div class="alert alert-info" style="background: rgba(255, 102, 0, 0.1); border: 1px solid rgba(255, 102, 0, 0.3); color: #d0d0d0; padding: 20px; border-radius: 8px;">
                                    <i class="bi bi-info-circle"></i> No available courses at the moment.
                                </div>
                            `);
                        }
                    });
                }, 1000);
                
                // Update available courses count
                const currentCount = parseInt($('.stat-card:last .stat-number').text()) || 0;
                if (currentCount > 0) {
                    $('.stat-card:last .stat-number').text(currentCount - 1);
                }
            } else {
                // Show error Bootstrap alert
                showAlert(data.message || 'Failed to enroll in the course. Please try again.', 'danger');
                
                // Re-enable button
                $button.prop('disabled', false);
                $button.html(originalText);
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Error:', error);
            // Show error Bootstrap alert
            showAlert('An error occurred. Please try again.', 'danger');
            
            // Re-enable button
            $button.prop('disabled', false);
            $button.html(originalText);
        });
    });
});
</script>
<?= $this->endSection() ?>
<?php endif; ?>

