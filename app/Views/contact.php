<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow border-0 mb-4">
        <div class="card-body p-5">
            <h1 class="display-4 fw-bold text-dark mb-3"><?= $page_title ?? 'Contact Us' ?></h1>
            <p class="lead text-muted mb-4"><?= $description ?? 'Get in touch with our support team' ?></p>
            
            <div class="row g-4 mt-3">
                <!-- Contact Information -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body p-4">
                            <h3 class="h4 fw-bold mb-3"><i class="bi bi-envelope-fill me-2"></i>Get in Touch</h3>
                            <p class="text-muted mb-4">We'd love to hear from you! Whether you have questions about our platform, need technical support, or want to discuss partnership opportunities, we're here to help.</p>
                            
                            <h4 class="h5 fw-bold mt-4 mb-3">Office Information</h4>
                            <div class="mb-3">
                                <p class="mb-1"><strong><i class="bi bi-geo-alt-fill me-2"></i>Address:</strong></p>
                                <p class="text-muted ms-4">123 Education Street<br>Learning City, LC 12345</p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-1"><strong><i class="bi bi-telephone-fill me-2"></i>Phone:</strong></p>
                                <p class="text-muted ms-4">+1 (555) 123-4567</p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-1"><strong><i class="bi bi-envelope me-2"></i>Email:</strong></p>
                                <p class="text-muted ms-4">support@lms.com</p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-1"><strong><i class="bi bi-clock-fill me-2"></i>Business Hours:</strong></p>
                                <p class="text-muted ms-4">
                                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                                    Saturday: 10:00 AM - 4:00 PM<br>
                                    Sunday: Closed
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body p-4">
                            <h3 class="h4 fw-bold mb-4"><i class="bi bi-send-fill me-2"></i>Send us a Message</h3>
                            <form action="#" method="POST">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label fw-semibold">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label fw-semibold">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send me-2"></i>Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Methods -->
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="mb-3" style="font-size: 3rem;">📧</div>
                            <h3 class="h5 fw-bold">Email Support</h3>
                            <p class="text-muted mb-1">support@lms.com</p>
                            <p class="text-muted small mb-0">24/7 response within 24 hours</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="mb-3" style="font-size: 3rem;">📞</div>
                            <h3 class="h5 fw-bold">Phone Support</h3>
                            <p class="text-muted mb-1">+1 (555) 123-4567</p>
                            <p class="text-muted small mb-0">Monday - Friday, 9 AM - 6 PM</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="mb-3" style="font-size: 3rem;">💬</div>
                            <h3 class="h5 fw-bold">Live Chat</h3>
                            <p class="text-muted mb-1">Available on our website</p>
                            <p class="text-muted small mb-0">Monday - Friday, 9 AM - 5 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
