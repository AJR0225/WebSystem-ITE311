<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <!-- Hero Section -->
    <div class="hero bg-white rounded shadow p-5 mb-5 text-center">
        <h1 class="display-4 fw-bold text-dark mb-3"><?= $page_title ?? 'Welcome to LMS' ?></h1>
        <p class="lead text-muted mb-4"><?= $description ?? 'Your comprehensive learning management platform' ?></p>
        <a href="<?= base_url('about') ?>" class="btn btn-primary btn-lg">Learn More</a>
    </div>

    <!-- Features Section -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 3rem;">📚</div>
                    <h3 class="card-title fw-bold">Course Management</h3>
                    <p class="card-text text-muted">Create and manage courses with ease. Organize lessons, quizzes, and assignments in one place.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 3rem;">👥</div>
                    <h3 class="card-title fw-bold">Student Tracking</h3>
                    <p class="card-text text-muted">Monitor student progress, track enrollments, and manage submissions efficiently.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 3rem;">📊</div>
                    <h3 class="card-title fw-bold">Analytics</h3>
                    <p class="card-text text-muted">Get insights into student performance and course effectiveness with detailed analytics.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center p-5">
                    <h2 class="card-title mb-3">Ready to Get Started?</h2>
                    <p class="card-text mb-4">Join thousands of educators and students using our platform</p>
                    <a href="<?= base_url('register') ?>" class="btn btn-light btn-lg me-2">Sign Up Now</a>
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-lg">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
