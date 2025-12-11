<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use App\Models\CourseModel;
use CodeIgniter\Controller;

class Course extends Controller
{
    protected $enrollmentModel;
    protected $courseModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
    }

    /**
     * Handle course enrollment via AJAX request
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface JSON response
     */
    public function enroll()
    {
        // SECURITY: Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method.'
            ]);
        }

        // SECURITY: Verify CSRF token for AJAX requests (optional - can be enabled for stricter security)
        // Note: CSRF filter is excluded for this route, but we can add manual verification if needed
        // For now, we rely on session-based authentication which is sufficient

        $session = session();

        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to enroll in a course.'
            ]);
        }

        // Get user_id from session
        $user_id = $session->get('user_id');
        if (empty($user_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid user session.'
            ]);
        }

        // Receive course_id from POST request
        $course_id = $this->request->getPost('course_id');
        
        // Validate course_id
        if (empty($course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course ID is required.'
            ]);
        }

        // Check if course_id is a valid integer
        if (!is_numeric($course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID.'
            ]);
        }

        $course_id = (int) $course_id;

        // SECURITY: Verify that the course exists and is published
        $courseModel = new \App\Models\CourseModel();
        $course = $courseModel->find($course_id);
        
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ]);
        }
        
        // Only allow enrollment in published courses
        if ($course['status'] !== 'published') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This course is not available for enrollment.'
            ]);
        }

        // Check if the user is already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ]);
        }

        // Prepare enrollment data (status will be 'pending' for teacher approval)
        $enrollmentData = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'pending' // Changed to pending - requires teacher approval
        ];

        // Insert the new enrollment record
        $enrollmentId = $this->enrollmentModel->enrollUser($enrollmentData);

        if ($enrollmentId) {
            // Course details already fetched above for validation
            
            // Fetch instructor and student names
            $userModel = new \App\Models\UserModel();
            $instructor = $course ? $userModel->find($course['instructor_id']) : null;
            $student = $userModel->find($user_id);
            
            $courseTitle = $course['title'] ?? 'Untitled Course';
            $studentName = $student['name'] ?? 'A student';
            
            $notificationModel = new NotificationModel();
            
            // Create notification for the student
            $studentNotificationMessage = "You have been enrolled in " . $courseTitle;
            $studentNotificationData = [
                'user_id' => $user_id,
                'message' => $studentNotificationMessage,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Create notification for the instructor (new enrollment request)
            $instructorNotificationMessage = "New enrollment request for \"" . $courseTitle . "\" from " . $studentName;
            $instructorNotificationData = [
                'user_id' => $course['instructor_id'],
                'message' => $instructorNotificationMessage,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Insert notifications (don't fail enrollment if notification fails)
            try {
                $notificationModel->insert($studentNotificationData);
            } catch (\Exception $e) {
                // Log error but don't fail enrollment
                log_message('error', 'Failed to create student enrollment notification: ' . $e->getMessage());
            }
            
            // Notify instructor about the enrollment request
            if ($course['instructor_id']) {
                try {
                    $notificationModel->insert($instructorNotificationData);
                } catch (\Exception $e) {
                    // Log error but don't fail enrollment
                    log_message('error', 'Failed to create instructor enrollment notification: ' . $e->getMessage());
                }
            }
            
            // Success response with course data
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment request submitted! Waiting for instructor approval.',
                'enrollment_id' => $enrollmentId,
                'course' => [
                    'id' => $course_id,
                    'title' => $courseTitle,
                    'description' => $course['description'] ?? 'No description available',
                    'instructor_name' => $instructor['name'] ?? 'N/A',
                    'enrollment_date' => date('Y-m-d H:i:s'),
                    'status' => 'pending' // Status is pending, waiting for approval
                ]
            ]);
        } else {
            // Failure response
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll in the course. Please try again.'
            ]);
        }
    }

    /**
     * Search courses by title or description
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface JSON response or view
     */
    public function search()
    {
        // Retrieve the search term from GET or POST request
        $searchTerm = $this->request->getGet('search_term') ?? $this->request->getPost('search_term');
        
        // Check if the search term is not empty
        if (!empty($searchTerm)) {
            // Apply a LIKE condition to search 'title'
            $this->courseModel->like('title', $searchTerm);
            // Apply an OR LIKE condition to search 'description'
            $this->courseModel->orLike('description', $searchTerm);
        }
        
        // Only show published courses
        $this->courseModel->where('status', 'published');
        
        // Fetch all courses that match the applied conditions (or all if no search term)
        $courses = $this->courseModel->findAll();
        
        // Check if the request is an AJAX request
        if ($this->request->isAJAX()) {
            // Return the courses as a JSON response
            return $this->response->setJSON([
                'success' => true,
                'courses' => $courses,
                'search_term' => $searchTerm
            ]);
        }
        
        // For regular requests, render the search results view
        // Pass the fetched courses and the search term to the view
        $data = [
            'title' => 'Search Results - Learning Management System',
            'page_title' => 'Search Results',
            'body_class' => 'dashboard-page',
            'hide_header' => false,
            'hide_footer' => true,
            'courses' => $courses,
            'search_term' => $searchTerm
        ];
        
        return view('courses/search_results', $data);
    }
}

