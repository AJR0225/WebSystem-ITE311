<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="admin-main-content">
    <div class="manage-user-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Manage Courses</h1>
            <button type="button" class="btn-add-user" onclick="showAddCourseModal()">
                <i class="bi bi-plus-circle"></i> Add Course
            </button>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Courses Table -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Instructor</th>
                        <th>Semester</th>
                        <th>Academic Year</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($courses)): ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= esc($course['id']) ?></td>
                                <td>
                                    <strong><?= esc($course['title']) ?></strong>
                                </td>
                                <td>
                                    <div class="description-cell">
                                        <?= esc(substr($course['description'], 0, 100)) ?><?= strlen($course['description']) > 100 ? '...' : '' ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    // Check if instructor exists and is actually an instructor/teacher
                                    $isValidInstructor = !empty($course['instructor_name']) && 
                                                         in_array(strtolower($course['instructor_role'] ?? ''), ['instructor', 'teacher']);
                                    ?>
                                    <?php if ($isValidInstructor): ?>
                                        <div class="instructor-info">
                                            <i class="bi bi-person"></i> <?= esc($course['instructor_name']) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted" style="color: #ff6600;">
                                            <i class="bi bi-exclamation-triangle"></i> Not Assigned
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($course['semester'])): ?>
                                        <span class="status-badge" style="background: rgba(74, 158, 255, 0.2); color: #4a9eff; border: 1px solid rgba(74, 158, 255, 0.3);">
                                            <?= esc($course['semester']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($course['academic_year'])): ?>
                                        <strong style="color: #ffffff;"><?= esc($course['academic_year']) ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($course['schedule_days']) && !empty($course['start_time']) && !empty($course['end_time'])): ?>
                                        <div style="color: #e0e0e0; font-size: 0.9rem;">
                                            <div><i class="bi bi-calendar-week"></i> <?= esc($course['schedule_days']) ?></div>
                                            <div style="margin-top: 5px;">
                                                <i class="bi bi-clock"></i> <?= esc(date('g:i A', strtotime($course['start_time']))) ?> - <?= esc(date('g:i A', strtotime($course['end_time']))) ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">Not Set</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= esc($course['status']) ?>">
                                        <?= esc(ucfirst($course['status'])) ?>
                                    </span>
                                </td>
                                <td><?= esc(date('M d, Y', strtotime($course['created_at']))) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= base_url('admin/course/' . $course['id'] . '/upload') ?>" class="btn-edit" style="text-decoration: none; display: inline-block; margin-right: 8px;">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Materials
                                        </a>
                                        <button type="button" class="btn-edit" onclick="editCourse(<?= esc($course['id']) ?>, '<?= esc(addslashes($course['title'])) ?>', '<?= esc(addslashes($course['description'])) ?>', <?= esc($course['instructor_id']) ?>, '<?= esc($course['semester'] ?? '') ?>', '<?= esc($course['academic_year'] ?? '') ?>', '<?= esc(addslashes($course['schedule_days'] ?? '')) ?>', '<?= esc($course['start_time'] ?? '') ?>', '<?= esc($course['end_time'] ?? '') ?>', '<?= esc($course['status']) ?>')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button type="button" class="btn-delete" onclick="deleteCourse(<?= esc($course['id']) ?>, '<?= esc(addslashes($course['title'])) ?>')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="empty-state">No courses found. Click "Add Course" to create one.</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Course Modal -->
<div id="courseModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add Course</h2>
            <span class="close" onclick="closeCourseModal()">&times;</span>
        </div>
        <form id="courseForm" method="POST" action="<?= base_url('admin/courses') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="course_id" id="courseId" value="">
            
            <div class="form-group">
                <label for="title">Course Title <span class="required">*</span></label>
                <input type="text" id="title" name="title" class="form-control" required>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['title'])): ?>
                    <span class="error-message"><?= session()->getFlashdata('errors')['title'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="description">Description <span class="required">*</span></label>
                <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['description'])): ?>
                    <span class="error-message"><?= session()->getFlashdata('errors')['description'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="instructor_id">Assign Instructor <span class="required">*</span></label>
                <select id="instructor_id" name="instructor_id" class="form-control" required>
                    <option value="">Select Instructor</option>
                    <?php if (!empty($instructors)): ?>
                        <?php foreach ($instructors as $instructor): ?>
                            <?php 
                            // Only show users with instructor or teacher role
                            $instructorRole = strtolower($instructor['role'] ?? '');
                            if (in_array($instructorRole, ['instructor', 'teacher'])): 
                            ?>
                                <option value="<?= esc($instructor['id']) ?>">
                                    <?= esc($instructor['name']) ?> (<?= esc($instructor['email']) ?>) - <?= esc(ucfirst($instructorRole)) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?php if (empty($instructors) || count(array_filter($instructors, function($i) { return in_array(strtolower($i['role'] ?? ''), ['instructor', 'teacher']); })) === 0): ?>
                    <small class="text-muted" style="color: #ff6600; display: block; margin-top: 5px;">
                        <i class="bi bi-exclamation-triangle"></i> No instructors available. Please create instructor accounts first.
                    </small>
                <?php endif; ?>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['instructor_id'])): ?>
                    <span class="error-message"><?= session()->getFlashdata('errors')['instructor_id'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="semester">Semester <span class="required">*</span></label>
                <select id="semester" name="semester" class="form-control" required>
                    <option value="">Select Semester</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                </select>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['semester'])): ?>
                    <span class="error-message"><?= session()->getFlashdata('errors')['semester'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="academic_year">Academic Year <span class="required">*</span></label>
                <select id="academic_year" name="academic_year" class="form-control" required>
                    <option value="">Select Academic Year</option>
                    <?php 
                    // Generate academic years from 2025-2026 to 2030-2031 (6 years ahead)
                    $currentYear = (int) date('Y');
                    $startYear = max(2025, $currentYear); // Start from 2025 or current year, whichever is later
                    for ($i = 0; $i < 6; $i++): 
                        $year1 = $startYear + $i;
                        $year2 = $year1 + 1;
                        $academicYear = $year1 . '-' . $year2;
                    ?>
                        <option value="<?= esc($academicYear) ?>"><?= esc($academicYear) ?></option>
                    <?php endfor; ?>
                </select>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['academic_year'])): ?>
                    <span class="error-message"><?= session()->getFlashdata('errors')['academic_year'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="schedule_days">Schedule Days <span class="required">*</span></label>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px;">
                    <?php 
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    foreach ($days as $day): 
                    ?>
                        <label style="display: flex; align-items: center; gap: 5px; color: #e0e0e0; cursor: pointer;">
                            <input type="checkbox" name="schedule_days[]" value="<?= esc($day) ?>" class="schedule-day-checkbox" style="width: 18px; height: 18px; cursor: pointer;">
                            <span><?= esc($day) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="schedule_days_hidden" name="schedule_days" value="">
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['schedule_days'])): ?>
                    <span class="error-message"><?= session()->getFlashdata('errors')['schedule_days'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="start_time">Class Start Time <span class="required">*</span></label>
                <input type="time" id="start_time" name="start_time" class="form-control" required>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['start_time'])): ?>
                    <span class="error-message"><?= session()->getFlashdata('errors')['start_time'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="end_time">Class End Time <span class="required">*</span></label>
                <input type="time" id="end_time" name="end_time" class="form-control" required>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['end_time'])): ?>
                    <span class="error-message"><?= session()->getFlashdata('errors')['end_time'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="status">Status <span class="required">*</span></label>
                <select id="status" name="status" class="form-control" required>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['status'])): ?>
                    <span class="error-message"><?= session()->getFlashdata('errors')['status'] ?></span>
                <?php endif; ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeCourseModal()">Cancel</button>
                <button type="submit" class="btn-submit">Save Course</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content modal-small">
        <div class="modal-header">
            <h2>Delete Course</h2>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete the course "<strong id="deleteCourseName"></strong>"?</p>
            <p class="text-warning"><i class="bi bi-exclamation-triangle"></i> This action cannot be undone.</p>
        </div>
        <form id="deleteForm" method="POST" action="<?= base_url('admin/courses') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="course_id" id="deleteCourseId" value="">
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn-delete">Delete Course</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Show Add Course Modal
function showAddCourseModal() {
    document.getElementById('modalTitle').textContent = 'Add Course';
    document.getElementById('formAction').value = 'add';
    document.getElementById('courseId').value = '';
    document.getElementById('courseForm').reset();
    document.getElementById('courseModal').style.display = 'block';
}

// Edit Course
function editCourse(id, title, description, instructorId, semester, academicYear, scheduleDays, startTime, endTime, status) {
    document.getElementById('modalTitle').textContent = 'Edit Course';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('courseId').value = id;
    document.getElementById('title').value = title;
    document.getElementById('description').value = description;
    
    // Only set instructor_id if it's a valid option in the dropdown
    const instructorSelect = document.getElementById('instructor_id');
    const instructorOption = instructorSelect.querySelector(`option[value="${instructorId}"]`);
    if (instructorOption) {
        instructorSelect.value = instructorId;
    } else {
        // If current instructor is not valid, set to empty (Not Assigned)
        instructorSelect.value = '';
    }
    
    document.getElementById('semester').value = semester || '';
    document.getElementById('academic_year').value = academicYear || '';
    
    // Set schedule days (checkboxes)
    if (scheduleDays) {
        const days = scheduleDays.split(', '); // Split comma-separated days
        const checkboxes = document.querySelectorAll('.schedule-day-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = days.includes(checkbox.value);
        });
        document.getElementById('schedule_days_hidden').value = scheduleDays;
    } else {
        const checkboxes = document.querySelectorAll('.schedule-day-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        document.getElementById('schedule_days_hidden').value = '';
    }
    
    if (startTime) {
        document.getElementById('start_time').value = startTime;
    } else {
        document.getElementById('start_time').value = '';
    }
    if (endTime) {
        document.getElementById('end_time').value = endTime;
    } else {
        document.getElementById('end_time').value = '';
    }
    
    document.getElementById('status').value = status;
    document.getElementById('courseModal').style.display = 'block';
}

// Close Course Modal
function closeCourseModal() {
    document.getElementById('courseModal').style.display = 'none';
    document.getElementById('courseForm').reset();
    // Uncheck all schedule day checkboxes
    document.querySelectorAll('.schedule-day-checkbox').forEach(checkbox => checkbox.checked = false);
    document.getElementById('schedule_days_hidden').value = '';
}

// Handle schedule days checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.schedule-day-checkbox');
    const hiddenInput = document.getElementById('schedule_days_hidden');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const selectedDays = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            hiddenInput.value = selectedDays.join(', ');
        });
    });
    
    // Handle form submission - ensure schedule_days is set
    const courseForm = document.getElementById('courseForm');
    if (courseForm) {
        courseForm.addEventListener('submit', function(e) {
            const selectedDays = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            if (selectedDays.length === 0) {
                e.preventDefault();
                alert('Please select at least one schedule day.');
                return false;
            }
            hiddenInput.value = selectedDays.join(', ');
        });
    }
});

// Delete Course
function deleteCourse(id, title) {
    document.getElementById('deleteCourseId').value = id;
    document.getElementById('deleteCourseName').textContent = title;
    document.getElementById('deleteModal').style.display = 'block';
}

// Close Delete Modal
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const courseModal = document.getElementById('courseModal');
    const deleteModal = document.getElementById('deleteModal');
    if (event.target == courseModal) {
        courseModal.style.display = 'none';
    }
    if (event.target == deleteModal) {
        deleteModal.style.display = 'none';
    }
}
</script>
<?= $this->endSection() ?>

