<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Get instructor IDs from users table
        $instructors = $this->db->table('users')
            ->where('role', 'instructor')
            ->get()
            ->getResultArray();
        
        // If no instructors found, create courses with default instructor_id = 2 (assuming first instructor)
        $instructorIds = [];
        if (!empty($instructors)) {
            foreach ($instructors as $instructor) {
                $instructorIds[] = $instructor['id'];
            }
        } else {
            // Fallback: use ID 2 if no instructors found (assuming UserSeeder was run)
            $instructorIds = [2, 3];
        }
        
        // Course data - at least 5 courses
        $data = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. This course covers everything from basic markup to interactive web applications. Perfect for beginners who want to start their journey in web development.',
                'instructor_id' => $instructorIds[0] ?? 2,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Database Management Systems',
                'description' => 'Master the concepts of database design, SQL queries, and database administration. Learn how to create efficient database schemas, write complex queries, and manage database performance. Essential for any software developer.',
                'instructor_id' => $instructorIds[0] ?? 2,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Object-Oriented Programming',
                'description' => 'Deep dive into OOP concepts including classes, objects, inheritance, polymorphism, and encapsulation. Learn best practices for designing maintainable and scalable software using object-oriented principles.',
                'instructor_id' => $instructorIds[1] ?? 3,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Build native and cross-platform mobile applications. Learn to develop apps for iOS and Android using modern frameworks and tools. Includes hands-on projects to create fully functional mobile applications.',
                'instructor_id' => $instructorIds[1] ?? 3,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Software Engineering Principles',
                'description' => 'Learn software engineering best practices including version control, testing, code review, and project management. Understand the software development lifecycle and how to work effectively in teams.',
                'instructor_id' => $instructorIds[0] ?? 2,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data into courses table
        $this->db->table('courses')->insertBatch($data);
        
        echo "Successfully seeded " . count($data) . " courses!\n";
    }
}
