<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="admin-main-content">
    <div class="manage-user-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Materials Management</h1>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Search Form -->
        <div class="row mb-4" style="margin-bottom: 20px; margin-top: 20px;">
            <div class="col-md-6">
                <div class="d-flex">
                    <div class="input-group">
                        <input type="text" id="searchInputMaterials" class="form-control" placeholder="Search courses..." style="background: #1a1a1a; border: 1px solid rgba(255, 102, 0, 0.3); color: #ffffff;">
                        <button class="btn btn-outline-primary" type="button" id="searchBtnMaterials" onclick="triggerMaterialsSearch()" style="border-color: rgba(255, 102, 0, 0.5); color: #ff6600; cursor: pointer; pointer-events: auto; z-index: 10; position: relative;">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses with Materials -->
        <div class="data-section">
            <h3>Courses</h3>
            
            <?php if (!empty($courses)): ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Course Title</th>
                                <th>Instructor</th>
                                <th>Materials Count</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr class="course-row-materials" data-course-title="<?= esc(strtolower($course['title'])) ?>" data-instructor="<?= esc(strtolower($course['instructor_name'] ?? '')) ?>">
                                    <td><?= esc($course['id']) ?></td>
                                    <td>
                                        <strong style="color: #ffffff;">
                                            <i class="bi bi-book"></i> <?= esc($course['title']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?php if (!empty($course['instructor_name'])): ?>
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
                                        <span class="status-badge" style="background: rgba(74, 158, 255, 0.2); color: #4a9eff; border: 1px solid rgba(74, 158, 255, 0.3);">
                                            <i class="bi bi-file-earmark"></i> <?= esc($course['materials_count'] ?? 0) ?> file(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= esc($course['status']) ?>">
                                            <?= esc(ucfirst($course['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= base_url('admin/course/' . $course['id'] . '/upload') ?>" class="btn-edit" style="text-decoration: none;">
                                                <i class="bi bi-file-earmark-arrow-up"></i> Manage Materials
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-info-circle" style="font-size: 3rem; color: #888888; margin-bottom: 15px;"></i>
                    <p>No courses found. <?= $user_role === 'admin' ? 'Create courses first to upload materials.' : 'You don\'t have any courses yet.' ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Materials search handler - Instant filtering as user types
    $('#searchInputMaterials').on('keyup input', function() {
        const value = $(this).val().toLowerCase().trim();
        
        // Filter course table rows
        $('.course-row-materials').each(function() {
            const $row = $(this);
            const title = ($row.data('course-title') || '').toLowerCase();
            const instructor = ($row.data('instructor') || '').toLowerCase();
            const visibleText = $row.text().toLowerCase();
            
            // Combine all searchable text
            const searchableText = (title + ' ' + instructor + ' ' + visibleText).replace(/\s+/g, ' ').trim();
            
            // Show if matches, hide if not
            if (value === '' || searchableText.indexOf(value) > -1) {
                $row.show();
            } else {
                $row.hide();
            }
        });
        
        // Show "no results" message if all rows are hidden
        if (value.length > 0) {
            const visibleRows = $('.course-row-materials:visible').length;
            
            if (visibleRows === 0) {
                if ($('#no-results-message-materials').length === 0) {
                    $('.table-container').after(`
                        <div id="no-results-message-materials" class="alert alert-info mt-3" style="background: rgba(255, 102, 0, 0.1); border: 1px solid rgba(255, 102, 0, 0.3); color: #d0d0d0; padding: 20px; border-radius: 8px;">
                            <i class="bi bi-info-circle"></i> No courses found matching "${value}".
                        </div>
                    `);
                }
            } else {
                $('#no-results-message-materials').remove();
            }
        } else {
            // Clear search - show all rows
            $('.course-row-materials').show();
            $('#no-results-message-materials').remove();
        }
    });
    
    // Materials search button click handler
    function triggerMaterialsSearch() {
        $('#searchInputMaterials').trigger('keyup');
        return false;
    }
    
    $(document).on('click', '#searchBtnMaterials', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#searchInputMaterials').trigger('keyup');
        return false;
    });
    
    // Also allow Enter key in search input
    $('#searchInputMaterials').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $(this).trigger('keyup');
        }
    });
});
</script>
<?= $this->endSection() ?>

