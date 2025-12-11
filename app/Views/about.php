<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow border-0 mb-4">
        <div class="card-body p-5">
            <h1 class="display-4 fw-bold text-dark mb-3"><?= $page_title ?? 'About LMS' ?></h1>
            <p class="lead text-muted mb-4"><?= $description ?? 'Learn more about our learning management system' ?></p>
            
            <hr class="my-4">
            
            <h2 class="h3 fw-bold text-dark mt-4 mb-3">Our Mission</h2>
            <p class="text-muted">We are dedicated to providing a comprehensive learning management system that empowers educators and students to achieve their educational goals. Our platform combines cutting-edge technology with user-friendly design to create an optimal learning experience.</p>
            
            <h2 class="h3 fw-bold text-dark mt-5 mb-3">What We Offer</h2>
            <p class="text-muted mb-3">Our Learning Management System provides a complete solution for educational institutions, including:</p>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item border-0 px-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Course creation and management tools</li>
                <li class="list-group-item border-0 px-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Student enrollment and progress tracking</li>
                <li class="list-group-item border-0 px-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Interactive lessons and multimedia content</li>
                <li class="list-group-item border-0 px-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Automated quiz and assessment systems</li>
                <li class="list-group-item border-0 px-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Real-time analytics and reporting</li>
                <li class="list-group-item border-0 px-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Mobile-responsive design for learning anywhere</li>
            </ul>
            
            <h2 class="h3 fw-bold text-dark mt-5 mb-4">Our Team</h2>
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-person-circle" style="font-size: 4rem; color: #4a9eff;"></i>
                            </div>
                            <h3 class="h5 fw-bold">John Smith</h3>
                            <p class="text-muted mb-0">Lead Developer</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-person-circle" style="font-size: 4rem; color: #4a9eff;"></i>
                            </div>
                            <h3 class="h5 fw-bold">Sarah Johnson</h3>
                            <p class="text-muted mb-0">Educational Technology Specialist</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-person-circle" style="font-size: 4rem; color: #4a9eff;"></i>
                            </div>
                            <h3 class="h5 fw-bold">Mike Chen</h3>
                            <p class="text-muted mb-0">UI/UX Designer</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <h2 class="h3 fw-bold text-dark mt-5 mb-3">Why Choose Us?</h2>
            <p class="text-muted">With years of experience in educational technology, we understand the unique challenges faced by educators and students. Our platform is designed to be intuitive, scalable, and reliable, ensuring that your learning environment runs smoothly and effectively.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
