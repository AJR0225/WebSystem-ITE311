<?= $this->extend('template') ?>

<?= $this->section('head') ?>
<style>
    /* Manage User Page - Dark Theme */
    body.dashboard-page {
        background: #000000;
        min-height: 100vh;
        font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    
    /* Main Content Area - Full Width (No Sidebar) */
    .admin-main-content {
        width: 100%;
        padding: 40px;
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 25%, #0d0d0d 50%, #1a1a1a 75%, #000000 100%);
        min-height: calc(100vh - 80px);
    }
    
    .manage-user-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .page-title {
        color: #ffffff;
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.5px;
    }
    
    .btn-add-user {
        background: linear-gradient(135deg, #ff6600 0%, #e55a00 100%);
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 102, 0, 0.3);
        border: none;
        cursor: pointer;
    }
    
    .btn-add-user:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 102, 0, 0.5);
        color: #ffffff;
    }
    
    /* Search Container */
    .search-container {
        margin-bottom: 25px;
    }
    
    .search-box {
        position: relative;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 0;
        transition: all 0.3s ease;
    }
    
    .search-box:focus-within {
        border-color: rgba(255, 102, 0, 0.5);
        box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
    }
    
    .search-box i.bi-search {
        position: absolute;
        left: 15px;
        color: #888888;
        font-size: 1.1rem;
        z-index: 1;
    }
    
    .search-input {
        width: 100%;
        padding: 12px 45px 12px 45px;
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        font-family: 'Segoe UI', 'Roboto', sans-serif;
    }
    
    .search-input::placeholder {
        color: #888888;
    }
    
    .search-clear {
        position: absolute;
        right: 10px;
        background: transparent;
        border: none;
        color: #888888;
        cursor: pointer;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.3s ease;
    }
    
    .search-clear:hover {
        color: #ffffff;
    }
    
    .search-clear i {
        font-size: 1.2rem;
    }
    
    /* Users Table */
    .users-table-wrapper {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 20px;
        overflow-x: auto;
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .users-table thead {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .users-table th {
        color: #ffffff;
        font-weight: 600;
        padding: 15px;
        text-align: left;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .users-table td {
        color: #e0e0e0;
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 0.95rem;
    }
    
    .users-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }
    
    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .role-badge.admin {
        background: rgba(255, 102, 0, 0.2);
        color: #ff6600;
        border: 1px solid rgba(255, 102, 0, 0.3);
    }
    
    .role-badge.student {
        background: rgba(74, 158, 255, 0.2);
        color: #4a9eff;
        border: 1px solid rgba(74, 158, 255, 0.3);
    }
    
    .role-badge.teacher,
    .role-badge.instructor {
        background: rgba(76, 175, 80, 0.2);
        color: #4caf50;
        border: 1px solid rgba(76, 175, 80, 0.3);
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-edit,
    .btn-delete {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-edit {
        background: rgba(74, 158, 255, 0.2);
        color: #4a9eff;
        border: 1px solid rgba(74, 158, 255, 0.3);
    }
    
    .btn-edit:hover {
        background: rgba(74, 158, 255, 0.3);
        transform: translateY(-1px);
    }
    
    .btn-delete {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
        border: 1px solid rgba(244, 67, 54, 0.3);
    }
    
    .btn-delete:hover {
        background: rgba(244, 67, 54, 0.3);
        transform: translateY(-1px);
    }
    
    .btn-restore {
        padding: 8px 16px;
        border-radius: 6px;
        border: 1px solid rgba(76, 175, 80, 0.3);
        background: rgba(76, 175, 80, 0.2);
        color: #4caf50;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-restore:hover {
        background: rgba(76, 175, 80, 0.3);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.2);
    }
    
    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-content {
        background: linear-gradient(145deg, #0a0a0a 0%, #1a1a1a 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .modal-title {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }
    
    .btn-close-modal {
        background: none;
        border: none;
        color: #ffffff;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .btn-close-modal:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #ffffff;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #ff6600;
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
    }
    
    .form-control::placeholder {
        color: #888888;
    }
    
    .form-select {
        width: 100%;
        padding: 12px 15px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #ffffff;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .form-select:focus {
        outline: none;
        border-color: #ff6600;
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
    }
    
    .form-select option {
        background: #1a1a1a;
        color: #ffffff;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #ff6600 0%, #e55a00 100%);
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 102, 0, 0.3);
        width: 100%;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 102, 0, 0.5);
    }
    
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid;
    }
    
    .alert-success {
        background: rgba(30, 58, 46, 0.4);
        border-color: rgba(46, 90, 60, 0.5);
        color: #a8d5ba;
    }
    
    .alert-danger {
        background: rgba(58, 30, 30, 0.4);
        border-color: rgba(90, 45, 45, 0.5);
        color: #d5a8a8;
    }
    
    .invalid-feedback {
        color: #f44336;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #888888;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 
$userRole = $user_role ?? $user['role'] ?? 'student';
$userRole = strtolower($userRole);
if ($userRole === 'teacher') {
    $userRole = 'instructor';
}
?>

<!-- Main Content Area - Full Width (No Sidebar, Uses Header Navigation) -->
<div class="admin-main-content">
        <div class="manage-user-container">
            <div class="page-header">
                <h1 class="page-title">Manage Users</h1>
                <button class="btn-add-user" onclick="openAddModal()">
                    <i class="bi bi-person-plus"></i> Add User
                </button>
            </div>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <!-- Search Bar -->
            <div class="search-container">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search users by name, email, or role..." onkeyup="filterUsers()">
                    <button type="button" class="search-clear" id="clearSearch" onclick="clearSearch()" style="display: none;">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="users-table-wrapper">
                <?php if (!empty($users)): ?>
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <?php foreach ($users as $u): ?>
                                <tr class="user-row" data-name="<?= esc(strtolower($u['name'])) ?>" data-email="<?= esc(strtolower($u['email'])) ?>" data-role="<?= esc(strtolower($u['role'] === 'instructor' ? 'teacher' : $u['role'])) ?>">
                                    <td><?= esc($u['id']) ?></td>
                                    <td><?= esc($u['name']) ?></td>
                                    <td><?= esc($u['email']) ?></td>
                                    <td>
                                        <span class="role-badge <?= esc(strtolower($u['role'])) ?>">
                                            <?= esc(ucfirst($u['role'] === 'instructor' ? 'teacher' : $u['role'])) ?>
                                        </span>
                                    </td>
                                    <td><?= esc(date('M d, Y', strtotime($u['created_at']))) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($u['id'] != session('user_id')): ?>
                                                <button class="btn-edit" onclick="openEditModal(<?= esc($u['id']) ?>, '<?= esc($u['name']) ?>', '<?= esc($u['email']) ?>', '<?= esc($u['role'] === 'instructor' ? 'teacher' : $u['role']) ?>')">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button class="btn-delete" onclick="confirmDelete(<?= esc($u['id']) ?>, '<?= esc($u['name']) ?>')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            <?php else: ?>
                                                <span class="action-disabled" style="color: #888888; font-size: 0.9rem; font-style: italic;">Cannot edit yourself</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <p>No users found.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Deleted Users Section -->
            <?php if (!empty($deleted_users ?? [])): ?>
                <div style="margin-top: 40px;">
                    <h2 style="color: #ffffff; font-size: 1.5rem; font-weight: 600; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid rgba(255, 102, 0, 0.3);">
                        <i class="bi bi-archive"></i> Deleted Users
                    </h2>
                    <div class="users-table-wrapper">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="deletedUsersTableBody">
                                <?php foreach ($deleted_users as $u): ?>
                                    <tr class="deleted-user-row" style="opacity: 0.7;" data-name="<?= esc(strtolower($u['name'])) ?>" data-email="<?= esc(strtolower($u['email'])) ?>" data-role="<?= esc(strtolower($u['role'] === 'instructor' ? 'teacher' : $u['role'])) ?>">
                                        <td><?= esc($u['id']) ?></td>
                                        <td><?= esc($u['name']) ?></td>
                                        <td><?= esc($u['email']) ?></td>
                                        <td>
                                            <span class="role-badge <?= esc(strtolower($u['role'])) ?>">
                                                <?= esc(ucfirst($u['role'] === 'instructor' ? 'teacher' : $u['role'])) ?>
                                            </span>
                                        </td>
                                        <td><?= esc(date('M d, Y h:i A', strtotime($u['deleted_at']))) ?></td>
                                        <td>
                                            <form method="POST" action="<?= base_url('admin/manage-user') ?>" style="display: inline;">
                                                <input type="hidden" name="action" value="restore">
                                                <input type="hidden" name="id" value="<?= esc($u['id']) ?>">
                                                <button type="submit" class="btn-restore" onclick="return confirm('Are you sure you want to restore this user account?')">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New User</h2>
            <button class="btn-close-modal" onclick="closeModal('addModal')">&times;</button>
        </div>
        <form method="POST" action="<?= base_url('admin/manage-user') ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="add_name" class="form-control" required minlength="3" maxlength="100" placeholder="Enter full name" pattern="[a-zA-Z]+( [a-zA-Z]+)*" title="Only letters and single spaces are allowed">
                <?php if (isset($validation) && $validation->hasError('name')): ?>
                    <span class="invalid-feedback"><?= $validation->getError('name') ?></span>
                <?php endif; ?>
                <small style="color: #888888; font-size: 0.85rem; margin-top: 5px; display: block;">Only letters and single spaces are allowed. No numbers or special characters.</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="add_email" class="form-control" required placeholder="Enter email address" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Enter a valid email address">
                <?php if (isset($validation) && $validation->hasError('email')): ?>
                    <span class="invalid-feedback"><?= $validation->getError('email') ?></span>
                <?php endif; ?>
                <small style="color: #888888; font-size: 0.85rem; margin-top: 5px; display: block;">Enter a valid email address format.</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="add_password" class="form-control" required minlength="6" placeholder="Enter password (min 6 characters)">
                <?php if (isset($validation) && $validation->hasError('password')): ?>
                    <span class="invalid-feedback"><?= $validation->getError('password') ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label class="form-label">Verify Password</label>
                <input type="password" name="password_confirm" id="add_password_confirm" class="form-control" required placeholder="Re-enter password">
                <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
                    <span class="invalid-feedback"><?= $validation->getError('password_confirm') ?></span>
                <?php endif; ?>
                <span id="addPasswordMatchFeedback" class="invalid-feedback" style="display: none; color: #f44336;">Passwords do not match.</span>
            </div>
            
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
                <?php if (isset($validation) && $validation->hasError('role')): ?>
                    <span class="invalid-feedback"><?= $validation->getError('role') ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn-submit">Add User</button>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit User</h2>
            <button class="btn-close-modal" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form method="POST" action="<?= base_url('admin/manage-user') ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="edit_name" class="form-control" required minlength="3" maxlength="100" pattern="[a-zA-Z]+( [a-zA-Z]+)*" title="Only letters and single spaces are allowed">
                <?php if (isset($validation) && $validation->hasError('name')): ?>
                    <span class="invalid-feedback"><?= $validation->getError('name') ?></span>
                <?php endif; ?>
                <small style="color: #888888; font-size: 0.85rem; margin-top: 5px; display: block;">Only letters and single spaces are allowed. No numbers or special characters.</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="edit_email" class="form-control" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Enter a valid email address">
                <?php if (isset($validation) && $validation->hasError('email')): ?>
                    <span class="invalid-feedback"><?= $validation->getError('email') ?></span>
                <?php endif; ?>
                <small style="color: #888888; font-size: 0.85rem; margin-top: 5px; display: block;">Enter a valid email address format.</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password (Leave blank to keep current password)</label>
                <input type="password" name="password" id="edit_password" class="form-control" minlength="6" placeholder="Enter new password (optional)">
                <?php if (isset($validation) && $validation->hasError('password')): ?>
                    <span class="invalid-feedback"><?= $validation->getError('password') ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" id="edit_role" class="form-select" required>
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
                <?php if (isset($validation) && $validation->hasError('role')): ?>
                    <span class="invalid-feedback"><?= $validation->getError('role') ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn-submit">Update User</button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Confirm Delete</h2>
            <button class="btn-close-modal" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <p style="color: #e0e0e0; margin-bottom: 25px;">Are you sure you want to delete user <strong id="delete_user_name" style="color: #ffffff;"></strong>? This action cannot be undone.</p>
        <form method="POST" action="<?= base_url('admin/manage-user') ?>" style="display: flex; gap: 10px;">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" id="delete_id">
            <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); flex: 1;">Delete</button>
            <button type="button" class="btn-submit" onclick="closeModal('deleteModal')" style="background: rgba(255, 255, 255, 0.1); flex: 1;">Cancel</button>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.add('active');
}

function openEditModal(id, name, email, role) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_password').value = '';
    document.getElementById('editModal').classList.add('active');
}

function confirmDelete(id, name) {
    document.getElementById('delete_id').value = id;
    document.getElementById('delete_user_name').textContent = name;
    document.getElementById('deleteModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Close modal when clicking outside
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
        }
    });
});

// Prevent special characters in add name field - only letters and single spaces
if (document.getElementById('add_name')) {
    document.getElementById('add_name').addEventListener('input', function(e) {
        // Remove any characters except letters
        let value = this.value.replace(/[^a-zA-Z\s]/g, '');
        // Replace multiple spaces with single space
        value = value.replace(/\s+/g, ' ');
        // Remove leading/trailing spaces
        value = value.trim();
        // Prevent space at the start
        if (value.startsWith(' ')) {
            value = value.substring(1);
        }
        this.value = value;
    });
}

// Prevent invalid characters in add email field
if (document.getElementById('add_email')) {
    document.getElementById('add_email').addEventListener('input', function(e) {
        // Allow only letters, numbers, and valid email characters: @ . _ - + %
        this.value = this.value.replace(/[^a-zA-Z0-9@._%+-]/g, '');
    });
}

// Prevent special characters in edit name field - only letters and single spaces
document.getElementById('edit_name').addEventListener('input', function(e) {
    // Remove any characters except letters
    let value = this.value.replace(/[^a-zA-Z\s]/g, '');
    // Replace multiple spaces with single space
    value = value.replace(/\s+/g, ' ');
    // Remove leading/trailing spaces
    value = value.trim();
    // Prevent space at the start
    if (value.startsWith(' ')) {
        value = value.substring(1);
    }
    this.value = value;
});

// Prevent invalid characters in edit email field (only allow valid email characters)
document.getElementById('edit_email').addEventListener('input', function(e) {
    // Allow only letters, numbers, and valid email characters: @ . _ - + %
    this.value = this.value.replace(/[^a-zA-Z0-9@._%+-]/g, '');
});

// Password match validation for add form
if (document.getElementById('add_password') && document.getElementById('add_password_confirm')) {
    const addPassword = document.getElementById('add_password');
    const addPasswordConfirm = document.getElementById('add_password_confirm');
    const addPasswordMatchFeedback = document.getElementById('addPasswordMatchFeedback');
    
    function checkAddPasswordMatch() {
        if (addPasswordConfirm.value.length > 0) {
            if (addPassword.value !== addPasswordConfirm.value) {
                addPasswordMatchFeedback.style.display = 'block';
                addPasswordConfirm.setCustomValidity('Passwords do not match');
            } else {
                addPasswordMatchFeedback.style.display = 'none';
                addPasswordConfirm.setCustomValidity('');
            }
        } else {
            addPasswordMatchFeedback.style.display = 'none';
            addPasswordConfirm.setCustomValidity('');
        }
    }
    
    addPassword.addEventListener('input', checkAddPasswordMatch);
    addPasswordConfirm.addEventListener('input', checkAddPasswordMatch);
}

// Form submission validation for add form
if (document.querySelector('#addModal form')) {
    document.querySelector('#addModal form').addEventListener('submit', function(e) {
        const name = document.getElementById('add_name').value;
        const email = document.getElementById('add_email').value;
        const password = document.getElementById('add_password').value;
        const passwordConfirm = document.getElementById('add_password_confirm').value;
        
        // Check if name contains only letters and single spaces
        if (!/^[a-zA-Z]+( [a-zA-Z]+)*$/.test(name)) {
            e.preventDefault();
            alert('Name can only contain letters and single spaces. No numbers or special characters allowed.');
            return false;
        }
        
        // Check if email contains only valid email characters
        if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address format.');
            return false;
        }
        
        // Check if passwords match
        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('Passwords do not match. Please make sure both password fields are identical.');
            return false;
        }
    });
}

// Form submission validation for edit form
document.querySelector('#editModal form').addEventListener('submit', function(e) {
    const name = document.getElementById('edit_name').value;
    const email = document.getElementById('edit_email').value;
    
    // Check if name contains only letters and single spaces
    if (!/^[a-zA-Z]+( [a-zA-Z]+)*$/.test(name)) {
        e.preventDefault();
        alert('Name can only contain letters and single spaces. No numbers or special characters allowed.');
        return false;
    }
    
    // Check if email contains only valid email characters
    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address format.');
        return false;
    }
});

// Search functionality
function filterUsers() {
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.toLowerCase().trim();
    const clearButton = document.getElementById('clearSearch');
    
    // Show/hide clear button
    if (searchTerm.length > 0) {
        clearButton.style.display = 'block';
    } else {
        clearButton.style.display = 'none';
    }
    
    // Filter active users
    const userRows = document.querySelectorAll('.user-row');
    let visibleCount = 0;
    
    userRows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const email = row.getAttribute('data-email') || '';
        const role = row.getAttribute('data-role') || '';
        
        if (name.includes(searchTerm) || email.includes(searchTerm) || role.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Filter deleted users
    const deletedUserRows = document.querySelectorAll('.deleted-user-row');
    
    deletedUserRows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const email = row.getAttribute('data-email') || '';
        const role = row.getAttribute('data-role') || '';
        
        if (name.includes(searchTerm) || email.includes(searchTerm) || role.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show "No results" message if no users match
    const usersTableBody = document.getElementById('usersTableBody');
    const deletedUsersTableBody = document.getElementById('deletedUsersTableBody');
    
    if (usersTableBody && visibleCount === 0 && searchTerm.length > 0) {
        // Check if no results message already exists
        if (!document.getElementById('noResultsMessage')) {
            const noResults = document.createElement('tr');
            noResults.id = 'noResultsMessage';
            noResults.innerHTML = '<td colspan="6" style="text-align: center; padding: 40px; color: #888888;"><i class="bi bi-search"></i> No users found matching "' + searchTerm + '"</td>';
            usersTableBody.appendChild(noResults);
        }
    } else {
        // Remove no results message if it exists
        const noResultsMsg = document.getElementById('noResultsMessage');
        if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }
}

function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    searchInput.value = '';
    document.getElementById('clearSearch').style.display = 'none';
    filterUsers();
}

// Add event listener for search input
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterUsers);
        searchInput.addEventListener('keyup', filterUsers);
    }
});
</script>

<?= $this->endSection() ?>

