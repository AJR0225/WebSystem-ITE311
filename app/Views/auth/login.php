<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body p-4">
        <h2 class="text-center mb-4 fw-bold">Login</h2>
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if(isset($validation)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $validation->listErrors() ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email') ?>" placeholder="Enter your email" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-login btn-lg">Login</button>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <p style="color: #d0d0d0; margin: 0; font-size: 0.95rem;">Don't have an account? <a href="<?= site_url('register') ?>" style="color: #ffffff; text-decoration: none; font-weight: 600;" onmouseover="this.style.color='#4a9eff'" onmouseout="this.style.color='#ffffff'">Register</a></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
