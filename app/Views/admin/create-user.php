<?= $this->extend('template') ?>

<?= $this->section('head') ?>
<style>
    /* Create User Page - Dark Theme */
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
        min-height: 100vh;
    }
    
    .create-user-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .page-header {
        margin-bottom: 30px;
    }
    
    .page-title {
        color: #ffffff;
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        letter-spacing: -0.5px;
    }
    
    .page-subtitle {
        color: #c0c0c0;
        font-size: 1rem;
        margin: 0;
    }
    
    /* Form Card */
    .form-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 10px;
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
        padding: 14px 28px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 102, 0, 0.3);
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 102, 0, 0.5);
    }
    
    .btn-cancel {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 14px 28px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }
    
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
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
    
    .form-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 30px;
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
        <div class="create-user-container">
            <div class="page-header">
                <h1 class="page-title">Create New User</h1>
                <p class="page-subtitle">Add a new user to the system with email, password, and role assignment</p>
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
            
            <div class="form-card">
                <form method="POST" action="<?= base_url('admin/create-user') ?>" id="createUserForm">
                    <div class="form-group">
                        <label class="form-label">Username / Name</label>
                        <input type="text" name="name" id="name" class="form-control" required minlength="3" maxlength="100" placeholder="Enter username (letters and single spaces only)" value="<?= old('name') ?>" pattern="[a-zA-Z]+( [a-zA-Z]+)*" title="Only letters and single spaces are allowed">
                        <?php if (isset($validation) && $validation->hasError('name')): ?>
                            <span class="invalid-feedback"><?= $validation->getError('name') ?></span>
                        <?php endif; ?>
                        <small style="color: #888888; font-size: 0.85rem; margin-top: 5px; display: block;">Only letters and single spaces are allowed. No numbers or special characters.</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" required placeholder="Enter email address" value="<?= old('email') ?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Enter a valid email address">
                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                            <span class="invalid-feedback"><?= $validation->getError('email') ?></span>
                        <?php endif; ?>
                        <small style="color: #888888; font-size: 0.85rem; margin-top: 5px; display: block;">Enter a valid email address format.</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required minlength="6" placeholder="Enter password (minimum 6 characters)" value="<?= old('password') ?>">
                        <?php if (isset($validation) && $validation->hasError('password')): ?>
                            <span class="invalid-feedback"><?= $validation->getError('password') ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Verify Password</label>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" required minlength="6" placeholder="Re-enter password to confirm" value="<?= old('password_confirm') ?>">
                        <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
                            <span class="invalid-feedback"><?= $validation->getError('password_confirm') ?></span>
                        <?php endif; ?>
                        <span id="password-match" style="display: none; color: #f44336; font-size: 0.85rem; margin-top: 5px;">Passwords do not match</span>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('role')): ?>
                            <span class="invalid-feedback"><?= $validation->getError('role') ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Create User</button>
                        <a href="<?= base_url('admin/manage-user') ?>" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Prevent special characters in username/name field - only letters and single spaces
document.getElementById('name').addEventListener('input', function(e) {
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

// Prevent invalid characters in email field (only allow valid email characters)
document.getElementById('email').addEventListener('input', function(e) {
    // Allow only letters, numbers, and valid email characters: @ . _ - + %
    this.value = this.value.replace(/[^a-zA-Z0-9@._%+-]/g, '');
});

// Password match validation
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;
    const matchMessage = document.getElementById('password-match');
    
    if (passwordConfirm.length > 0 && password !== passwordConfirm) {
        matchMessage.style.display = 'block';
    } else {
        matchMessage.style.display = 'none';
    }
});

document.getElementById('password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;
    const matchMessage = document.getElementById('password-match');
    
    if (passwordConfirm.length > 0 && password !== passwordConfirm) {
        matchMessage.style.display = 'block';
    } else {
        matchMessage.style.display = 'none';
    }
});

// Form submission validation
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    
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
</script>

<?= $this->endSection() ?>

