<?= $this->extend('template') ?>

<?= $this->section('head') ?>
<style>
    /* Announcement Page - Dark Theme */
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
    
    .announcement-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .page-title {
        color: #ffffff;
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.5px;
    }
    
    .btn-clear-all {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
        border: none;
        cursor: pointer;
        display: inline-block;
    }
    
    .btn-clear-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(244, 67, 54, 0.5);
        color: #ffffff;
    }
    
    /* Notifications List */
    .notifications-wrapper {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 20px;
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .notification-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        border-left: 4px solid;
    }
    
    .notification-item:hover {
        background: rgba(255, 255, 255, 0.06);
        transform: translateX(5px);
    }
    
    .notification-item.new_account {
        border-left-color: #4caf50;
    }
    
    .notification-item.password_changed {
        border-left-color: #ff9800;
    }
    
    .notification-item.name_changed {
        border-left-color: #2196f3;
    }
    
    .notification-item.role_changed {
        border-left-color: #9c27b0;
    }
    
    .notification-item.account_deleted {
        border-left-color: #f44336;
    }
    
    .notification-item.account_restored {
        border-left-color: #4caf50;
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }
    
    .notification-type {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .notification-type.new_account {
        background: rgba(76, 175, 80, 0.2);
        color: #4caf50;
        border: 1px solid rgba(76, 175, 80, 0.3);
    }
    
    .notification-type.password_changed {
        background: rgba(255, 152, 0, 0.2);
        color: #ff9800;
        border: 1px solid rgba(255, 152, 0, 0.3);
    }
    
    .notification-type.name_changed {
        background: rgba(33, 150, 243, 0.2);
        color: #2196f3;
        border: 1px solid rgba(33, 150, 243, 0.3);
    }
    
    .notification-type.role_changed {
        background: rgba(156, 39, 176, 0.2);
        color: #9c27b0;
        border: 1px solid rgba(156, 39, 176, 0.3);
    }
    
    .notification-type.account_deleted {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
        border: 1px solid rgba(244, 67, 54, 0.3);
    }
    
    .notification-type.account_restored {
        background: rgba(76, 175, 80, 0.2);
        color: #4caf50;
        border: 1px solid rgba(76, 175, 80, 0.3);
    }
    
    .notification-time {
        color: #888888;
        font-size: 0.85rem;
    }
    
    .notification-message {
        color: #e0e0e0;
        font-size: 1rem;
        line-height: 1.6;
        margin: 0;
    }
    
    .notification-user {
        color: #b0b0b0;
        font-size: 0.9rem;
        margin-top: 8px;
    }
    
    /* Notification Actions */
    .notification-actions {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: flex-end;
    }
    
    .btn-delete-notification {
        background: rgba(244, 67, 54, 0.2);
        color: #f44336;
        border: 1px solid rgba(244, 67, 54, 0.3);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-delete-notification:hover {
        background: rgba(244, 67, 54, 0.3);
        border-color: rgba(244, 67, 54, 0.5);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(244, 67, 54, 0.2);
    }
    
    .btn-delete-notification i {
        font-size: 0.9rem;
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
        <div class="announcement-container">
            <div class="page-header">
                <h1 class="page-title">System Notifications & Updates</h1>
                <?php if (!empty($notifications)): ?>
                    <form method="POST" action="<?= base_url('admin/announcement') ?>" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to clear all notifications? This action cannot be undone.');">
                        <input type="hidden" name="action" value="clear_all">
                        <button type="submit" class="btn-clear-all">
                            <i class="bi bi-trash"></i> Clear All Notifications
                        </button>
                    </form>
                <?php endif; ?>
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
            
            <div class="notifications-wrapper">
                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item <?= esc($notification['type']) ?>">
                            <div class="notification-header">
                                <span class="notification-type <?= esc($notification['type']) ?>">
                                    <?php
                                    $typeLabels = [
                                        'new_account' => 'New Account',
                                        'password_changed' => 'Password Changed',
                                        'name_changed' => 'Name Changed',
                                        'role_changed' => 'Role Changed',
                                        'account_deleted' => 'Account Deleted',
                                        'account_restored' => 'Account Restored'
                                    ];
                                    echo esc($typeLabels[$notification['type']] ?? ucfirst($notification['type']));
                                    ?>
                                </span>
                                <span class="notification-time">
                                    <?= esc(date('M d, Y h:i A', strtotime($notification['created_at']))) ?>
                                </span>
                            </div>
                            <p class="notification-message"><?= esc($notification['message']) ?></p>
                            <?php if (!empty($notification['user_name'])): ?>
                                <div class="notification-user">
                                    <i class="bi bi-person"></i> User: <?= esc($notification['user_name']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="notification-actions">
                                <form method="POST" action="<?= base_url('admin/announcement') ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="notification_id" value="<?= esc($notification['id']) ?>">
                                    <button type="submit" class="btn-delete-notification" title="Delete notification">
                                        <i class="bi bi-x-circle"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-bell-slash"></i>
                        <p>No notifications available.</p>
                        <p style="color: #666666; font-size: 0.9rem; margin-top: 10px;">System notifications and updates will appear here.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</div>

<?= $this->endSection() ?>

