<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="admin-main-content">
    <div class="manage-user-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Course Students</h1>
                <p style="color: #c0c0c0; margin: 5px 0 0 0;">
                    <a href="<?= base_url('dashboard') ?>" style="color: #4a9eff; text-decoration: none;">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </p>
            </div>
            <button type="button" class="btn-add-course" onclick="showEnrollStudentModal()">
                <i class="bi bi-person-plus"></i> Enroll Student
            </button>
        </div>

        <!-- Course Information Card -->
        <div class="data-section" style="margin-bottom: 30px;">
            <div class="data-item" style="background: linear-gradient(145deg, #0f0f0f 0%, #1a1a1a 100%); border: 1px solid rgba(255, 102, 0, 0.2);">
                <div class="data-item-title">
                    <i class="bi bi-book"></i> <?= esc($course['title']) ?>
                </div>
                <div class="data-item-detail"><?= esc($course['description']) ?></div>
                <div class="data-item-meta">
                    <?php if (!empty($course['semester'])): ?>
                        <i class="bi bi-calendar-event"></i> <?= esc($course['semester']) ?> | 
                    <?php endif; ?>
                    <?php if (!empty($course['academic_year'])): ?>
                        <i class="bi bi-calendar"></i> <?= esc($course['academic_year']) ?> | 
                    <?php endif; ?>
                    <?php if (!empty($course['schedule_days']) && !empty($course['start_time']) && !empty($course['end_time'])): ?>
                        <br>
                        <i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?> | 
                        <i class="bi bi-clock"></i> <?= esc(date('g:i A', strtotime($course['start_time']))) ?> - <?= esc(date('g:i A', strtotime($course['end_time']))) ?> | 
                    <?php endif; ?>
                    Status: <?= esc(ucfirst($course['status'] ?? 'draft')) ?> | 
                    Created: <?= esc(date('M d, Y', strtotime($course['created_at'] ?? 'now'))) ?>
                </div>
            </div>
        </div>

        <!-- Pending Enrollments Section -->
        <?php if (!empty($pending_enrollments)): ?>
            <div class="data-section" style="margin-bottom: 30px;">
                <h3>Pending Enrollments (<?= count($pending_enrollments) ?>)</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Request Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_enrollments as $enrollment): ?>
                                <tr>
                                    <td><?= esc($enrollment['student_id']) ?></td>
                                    <td>
                                        <strong style="color: #ffffff;">
                                            <i class="bi bi-person"></i> <?= esc($enrollment['student_name']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span style="color: #d0d0d0;"><?= esc($enrollment['student_email']) ?></span>
                                    </td>
                                    <td>
                                        <span style="color: #d0d0d0;">
                                            <i class="bi bi-calendar-check"></i> <?= esc(date('M d, Y', strtotime($enrollment['enrollment_date'] ?? 'now'))) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn-edit" onclick="approveEnrollment(<?= esc($enrollment['id']) ?>)">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>
                                            <button type="button" class="btn-delete" onclick="showDeclineModal(<?= esc($enrollment['id']) ?>, '<?= esc(addslashes($enrollment['student_name'])) ?>')">
                                                <i class="bi bi-x-circle"></i> Decline
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- Enrolled Students Section -->
        <div class="data-section">
            <h3>Enrolled Students (<?= esc($total_students) ?>)</h3>
            
            <?php if (!empty($enrolled_students)): ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Enrollment Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrolled_students as $student): ?>
                                <tr>
                                    <td><?= esc($student['student_id']) ?></td>
                                    <td>
                                        <strong style="color: #ffffff;">
                                            <i class="bi bi-person"></i> <?= esc($student['student_name']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span style="color: #d0d0d0;"><?= esc($student['student_email']) ?></span>
                                    </td>
                                    <td>
                                        <span style="color: #d0d0d0;">
                                            <i class="bi bi-calendar-check"></i> <?= esc(date('M d, Y', strtotime($student['enrollment_date'] ?? 'now'))) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-published">
                                            <?= esc(ucfirst($student['status'] ?? 'enrolled')) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-delete" onclick="unenrollStudent(<?= esc($student['id']) ?>, '<?= esc(addslashes($student['student_name'])) ?>')">
                                            <i class="bi bi-person-dash"></i> Unenroll
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-info-circle" style="font-size: 3rem; color: #888888; margin-bottom: 15px;"></i>
                    <p>No students enrolled in this course yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Decline Enrollment Modal -->
<div id="declineModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Decline Enrollment</h2>
            <span class="close" onclick="closeDeclineModal()">&times;</span>
        </div>
        <form id="declineForm">
            <input type="hidden" id="declineEnrollmentId" name="enrollment_id" value="">
            <div class="form-group">
                <label>Student: <strong id="declineStudentName"></strong></label>
            </div>
            <div class="form-group">
                <label for="decline_reason">Reason for Declining <span class="required">*</span></label>
                <textarea id="decline_reason" name="decline_reason" class="form-control" rows="4" required placeholder="Please provide a reason for declining this enrollment request..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeDeclineModal()">Cancel</button>
                <button type="submit" class="btn-delete">Decline Enrollment</button>
            </div>
        </form>
    </div>
</div>

<!-- Enroll Student Modal -->
<div id="enrollStudentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Enroll Student</h2>
            <span class="close" onclick="closeEnrollStudentModal()">&times;</span>
        </div>
        <form id="enrollStudentForm">
            <input type="hidden" name="course_id" value="<?= esc($course['id']) ?>">
            <div class="form-group">
                <label for="student_id">Select Student <span class="required">*</span></label>
                <select id="student_id" name="student_id" class="form-control" required>
                    <option value="">Select Student</option>
                    <?php if (!empty($all_students)): ?>
                        <?php foreach ($all_students as $student): ?>
                            <option value="<?= esc($student['id']) ?>">
                                <?= esc($student['name']) ?> (<?= esc($student['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEnrollStudentModal()">Cancel</button>
                <button type="submit" class="btn-submit">Enroll Student</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Approve Enrollment
function approveEnrollment(enrollmentId) {
    if (!confirm('Are you sure you want to approve this enrollment?')) {
        return;
    }
    
    $.post('<?= base_url('instructor/enrollment/approve') ?>', {
        enrollment_id: enrollmentId
    })
    .done(function(data) {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .fail(function() {
        alert('An error occurred. Please try again.');
    });
}

// Show Decline Modal
function showDeclineModal(enrollmentId, studentName) {
    document.getElementById('declineEnrollmentId').value = enrollmentId;
    document.getElementById('declineStudentName').textContent = studentName;
    document.getElementById('declineModal').style.display = 'block';
}

// Close Decline Modal
function closeDeclineModal() {
    document.getElementById('declineModal').style.display = 'none';
    document.getElementById('declineForm').reset();
}

// Handle Decline Form Submission
$('#declineForm').on('submit', function(e) {
    e.preventDefault();
    
    const enrollmentId = $('#declineEnrollmentId').val();
    const declineReason = $('#decline_reason').val();
    
    if (!declineReason.trim()) {
        alert('Please provide a reason for declining.');
        return;
    }
    
    $.post('<?= base_url('instructor/enrollment/decline') ?>', {
        enrollment_id: enrollmentId,
        decline_reason: declineReason
    })
    .done(function(data) {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .fail(function() {
        alert('An error occurred. Please try again.');
    });
});

// Unenroll Student
function unenrollStudent(enrollmentId, studentName) {
    if (!confirm('Are you sure you want to unenroll ' + studentName + ' from this course?')) {
        return;
    }
    
    $.post('<?= base_url('instructor/enrollment/unenroll') ?>', {
        enrollment_id: enrollmentId
    })
    .done(function(data) {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .fail(function() {
        alert('An error occurred. Please try again.');
    });
}

// Show Enroll Student Modal
function showEnrollStudentModal() {
    document.getElementById('enrollStudentModal').style.display = 'block';
}

// Close Enroll Student Modal
function closeEnrollStudentModal() {
    document.getElementById('enrollStudentModal').style.display = 'none';
    document.getElementById('enrollStudentForm').reset();
}

// Handle Enroll Student Form Submission
$('#enrollStudentForm').on('submit', function(e) {
    e.preventDefault();
    
    $.post('<?= base_url('instructor/enrollment/enroll') ?>', {
        course_id: $('input[name="course_id"]').val(),
        student_id: $('#student_id').val()
    })
    .done(function(data) {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .fail(function() {
        alert('An error occurred. Please try again.');
    });
});

// Close modals when clicking outside
window.onclick = function(event) {
    const declineModal = document.getElementById('declineModal');
    const enrollStudentModal = document.getElementById('enrollStudentModal');
    if (event.target == declineModal) {
        declineModal.style.display = 'none';
    }
    if (event.target == enrollStudentModal) {
        enrollStudentModal.style.display = 'none';
    }
}
</script>
<?= $this->endSection() ?>
