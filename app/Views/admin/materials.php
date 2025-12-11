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
                                <tr>
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

