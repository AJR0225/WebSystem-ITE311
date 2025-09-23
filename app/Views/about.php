<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'About Us - Learning Management System' ?></title>
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
        .team {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .team-member {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
        }
        .team-member h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .team-member p {
            color: #7f8c8d;
            font-style: italic;
        }
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
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
                    <li><a href="<?= base_url('about') ?>" class="active">About</a></li>
                    <li><a href="<?= base_url('contact') ?>">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <h1><?= $page_title ?? 'About LMS' ?></h1>
            <p><?= $description ?? 'Learn more about our learning management system' ?></p>
            
            <h2>Our Mission</h2>
            <p>We are dedicated to providing a comprehensive learning management system that empowers educators and students to achieve their educational goals. Our platform combines cutting-edge technology with user-friendly design to create an optimal learning experience.</p>
            
            <h2>What We Offer</h2>
            <p>Our Learning Management System provides a complete solution for educational institutions, including:</p>
            <ul>
                <li>Course creation and management tools</li>
                <li>Student enrollment and progress tracking</li>
                <li>Interactive lessons and multimedia content</li>
                <li>Automated quiz and assessment systems</li>
                <li>Real-time analytics and reporting</li>
                <li>Mobile-responsive design for learning anywhere</li>
            </ul>
            
            <h2>Our Team</h2>
            <div class="team">
                <div class="team-member">
                    <h3>John Smith</h3>
                    <p>Lead Developer</p>
                </div>
                <div class="team-member">
                    <h3>Sarah Johnson</h3>
                    <p>Educational Technology Specialist</p>
                </div>
                <div class="team-member">
                    <h3>Mike Chen</h3>
                    <p>UI/UX Designer</p>
                </div>
            </div>
            
            <h2>Why Choose Us?</h2>
            <p>With years of experience in educational technology, we understand the unique challenges faced by educators and students. Our platform is designed to be intuitive, scalable, and reliable, ensuring that your learning environment runs smoothly and effectively.</p>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 Learning Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
