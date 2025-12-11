<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="admin-main-content">
    <div class="manage-user-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Upload Materials</h1>
                <p style="color: #c0c0c0; margin: 5px 0 0 0;">
                    <a href="<?= base_url('instructor/course/' . $course['id']) ?>" style="color: #4a9eff; text-decoration: none;">
                        <i class="bi bi-arrow-left"></i> Back to Course
                    </a>
                </p>
            </div>
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

        <!-- Course Information Card -->
        <div class="data-section" style="margin-bottom: 30px;">
            <div class="data-item" style="background: linear-gradient(145deg, #0f0f0f 0%, #1a1a1a 100%); border: 1px solid rgba(255, 102, 0, 0.2);">
                <div class="data-item-title">
                    <i class="bi bi-book"></i> <?= esc($course['title']) ?>
                </div>
                <div class="data-item-detail"><?= esc($course['description']) ?></div>
            </div>
        </div>

        <!-- File Upload Form -->
        <div class="data-section" style="margin-bottom: 30px;">
            <h3>Upload New Material</h3>
            <form method="POST" action="<?= base_url('admin/course/' . $course['id'] . '/upload') ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="material_file" class="form-label" style="color: #ffffff; font-weight: 500; margin-bottom: 8px; display: block;">
                        Select File <span class="required">*</span>
                    </label>
                    <input 
                        type="file" 
                        class="form-control form-control-lg" 
                        id="material_file" 
                        name="material_file" 
                        required
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip"
                        style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 8px; color: #ffffff; padding: 12px 15px;"
                    >
                    <small class="form-text text-muted" style="color: #888888; display: block; margin-top: 8px;">
                        <i class="bi bi-info-circle"></i> Allowed file types: PDF, Word, Excel, PowerPoint, Images, ZIP, Text files. Maximum file size: 10MB.
                    </small>
                    <?php if ($validation && $validation->hasError('material_file')): ?>
                        <div class="error-message" style="color: #ff4444; font-size: 0.85rem; margin-top: 5px; display: block;">
                            <?= $validation->getError('material_file') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer" style="padding: 0; margin-top: 20px; border: none;">
                    <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #ff6600 0%, #e55a00 100%); color: #ffffff; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                        <i class="bi bi-upload"></i> Upload Material
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Materials List -->
        <div class="data-section">
            <h3>Uploaded Materials (<?= count($materials) ?>)</h3>
            
            <?php if (!empty($materials)): ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>File Name</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $material): ?>
                                <tr>
                                    <td><?= esc($material['id']) ?></td>
                                    <td>
                                        <strong style="color: #ffffff;">
                                            <i class="bi bi-file-earmark"></i> <?= esc($material['file_name']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span style="color: #d0d0d0;">
                                            <i class="bi bi-calendar-check"></i> <?= esc(date('M d, Y H:i', strtotime($material['created_at'] ?? 'now'))) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= base_url('materials/download/' . $material['id']) ?>" class="btn-edit" style="text-decoration: none;">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                            <button type="button" class="btn-delete" onclick="deleteMaterial(<?= esc($material['id']) ?>, '<?= esc(addslashes($material['file_name'])) ?>')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
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
                    <p>No materials uploaded yet. Upload your first material using the form above.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Delete Material
function deleteMaterial(materialId, fileName) {
    if (!confirm('Are you sure you want to delete "' + fileName + '"? This action cannot be undone.')) {
        return;
    }
    
    $.get('<?= base_url('materials/delete/') ?>' + materialId)
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

// Form validation
(function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
<?= $this->endSection() ?>

