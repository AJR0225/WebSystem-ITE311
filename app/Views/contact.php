<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Contact Us - Learning Management System' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-links li {
            margin-left: 2rem;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .nav-links a:hover {
            background-color: #34495e;
        }
        .nav-links a.active {
            background-color: #3498db;
        }
        .content {
            background-color: white;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .content h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .content h2 {
            color: #34495e;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .content p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-top: 2rem;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
        }
        .contact-info h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .contact-info p {
            margin-bottom: 0.5rem;
        }
        .contact-form {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-group textarea {
            height: 120px;
            resize: vertical;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .contact-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .contact-method {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
        }
        .contact-method h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .contact-method p {
            color: #7f8c8d;
            margin-bottom: 0.5rem;
        }
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">LMS</div>
                <ul class="nav-links">
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li><a href="<?= base_url('about') ?>">About</a></li>
                    <li><a href="<?= base_url('contact') ?>" class="active">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <h1><?= $page_title ?? 'Contact Us' ?></h1>
            <p><?= $description ?? 'Get in touch with our support team' ?></p>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Get in Touch</h3>
                    <p>We'd love to hear from you! Whether you have questions about our platform, need technical support, or want to discuss partnership opportunities, we're here to help.</p>
                    
                    <h3>Office Information</h3>
                    <p><strong>Address:</strong><br>
                    123 Education Street<br>
                    Learning City, LC 12345</p>
                    
                    <p><strong>Phone:</strong><br>
                    +1 (555) 123-4567</p>
                    
                    <p><strong>Email:</strong><br>
                    support@lms.com</p>
                    
                    <p><strong>Business Hours:</strong><br>
                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                    Saturday: 10:00 AM - 4:00 PM<br>
                    Sunday: Closed</p>
                </div>
                
                <div class="contact-form">
                    <h3>Send us a Message</h3>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
            
            <div class="contact-methods">
                <div class="contact-method">
                    <h3>📧 Email Support</h3>
                    <p>support@lms.com</p>
                    <p>24/7 response within 24 hours</p>
                </div>
                <div class="contact-method">
                    <h3>📞 Phone Support</h3>
                    <p>+1 (555) 123-4567</p>
                    <p>Monday - Friday, 9 AM - 6 PM</p>
                </div>
                <div class="contact-method">
                    <h3>💬 Live Chat</h3>
                    <p>Available on our website</p>
                    <p>Monday - Friday, 9 AM - 5 PM</p>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 Learning Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
