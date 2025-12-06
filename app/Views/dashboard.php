<?= $this->extend('template') ?>

<?= $this->section('head') ?>
<style>
    /* Dashboard Dark Minimalist Design */
    body.dashboard-page {
        background: linear-gradient(135deg, #0a0e27 0%, #1a1f35 50%, #0f1419 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
    }
    
    .dashboard-container {
        background: linear-gradient(145deg, #1a1f35 0%, #0f1419 100%);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6), 
                    0 0 0 1px rgba(255, 255, 255, 0.05);
        padding: 60px 50px;
        max-width: 700px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    }
    
    .dashboard-title {
        color: #ffffff;
        font-size: 3.5rem;
        font-weight: 700;
        margin: 0 0 40px 0;
        letter-spacing: -1px;
        text-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
    }
    
    .welcome-banner {
        background: #1e3a2e;
        border-radius: 12px;
        padding: 18px 30px;
        margin: 0 auto 35px auto;
        max-width: 500px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(46, 90, 60, 0.3);
    }
    
    .welcome-banner-text {
        color: #ffffff;
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
        letter-spacing: 0.5px;
    }
    
    .user-details {
        margin: 40px 0;
        color: #c0c0c0;
    }
    
    .user-details h3 {
        color: #e0e0e0;
        font-size: 1.8rem;
        font-weight: 500;
        margin: 0 0 15px 0;
        letter-spacing: 0.3px;
    }
    
    .user-details p {
        color: #b0b0b0;
        font-size: 1.1rem;
        margin: 10px 0;
        letter-spacing: 0.2px;
    }
    
    .user-email {
        color: #a8a8a8;
        font-size: 1rem;
        margin-top: 8px;
    }
    
    .logout-btn {
        display: inline-block;
        background: linear-gradient(135deg, #4a9eff 0%, #5bb0ff 100%);
        color: #ffffff;
        padding: 16px 40px;
        text-decoration: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(74, 158, 255, 0.3),
                    0 0 0 2px rgba(255, 20, 147, 0.4),
                    0 0 30px rgba(255, 20, 147, 0.2);
        border: none;
        cursor: pointer;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }
    
    .logout-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .logout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 30px rgba(74, 158, 255, 0.5),
                    0 0 0 3px rgba(255, 20, 147, 0.6),
                    0 0 40px rgba(255, 20, 147, 0.4);
        background: linear-gradient(135deg, #5bb0ff 0%, #6bc0ff 100%);
    }
    
    .logout-btn:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .logout-btn:active {
        transform: translateY(0);
    }
    
    .alert-success {
        background: rgba(30, 58, 46, 0.4);
        border: 1px solid rgba(46, 90, 60, 0.5);
        color: #a8d5ba;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 25px;
        font-size: 0.95rem;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 40px 30px;
            border-radius: 20px;
        }
        
        .dashboard-title {
            font-size: 2.5rem;
            margin-bottom: 30px;
        }
        
        .welcome-banner {
            padding: 15px 20px;
        }
        
        .welcome-banner-text {
            font-size: 1.1rem;
        }
        
        .user-details h3 {
            font-size: 1.5rem;
        }
        
        .user-details p {
            font-size: 1rem;
        }
        
        .logout-btn {
            padding: 14px 32px;
            font-size: 1rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-container">
    <h1 class="dashboard-title">Dashboard</h1>
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <div class="welcome-banner">
        <p class="welcome-banner-text">Welcome, <?= strtolower(session('user_name')) ?>!</p>
    </div>
    
    <div class="user-details">
        <h3>Hello, <?= strtolower(session('user_name')) ?>!</h3>
        <p class="user-email">Email: <?= session('user_email') ?></p>
    </div>
    
    <a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a>
</div>
<?= $this->endSection() ?>
