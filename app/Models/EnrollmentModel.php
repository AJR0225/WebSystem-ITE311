<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table            = 'enrollments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['student_id', 'course_id', 'enrollment_date', 'status', 'decline_reason', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = true;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Enroll a user in a course
     * 
     * @param array $data Enrollment data (user_id, course_id, enrollment_date)
     * @return int|false Insert ID on success, false on failure
     */
    public function enrollUser($data)
    {
        // Convert user_id to student_id if needed (for database compatibility)
        if (isset($data['user_id']) && !isset($data['student_id'])) {
            $data['student_id'] = $data['user_id'];
            unset($data['user_id']);
        }
        
        // Set enrollment_date if not provided
        if (!isset($data['enrollment_date'])) {
            $data['enrollment_date'] = date('Y-m-d H:i:s');
        }

        // Set default status if not provided (pending for approval)
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        return $this->insert($data);
    }

    /**
     * Get all courses a user is enrolled in
     * 
     * @param int $user_id User ID
     * @return array Array of enrollment records with course details
     */
    public function getUserEnrollments($user_id)
    {
        // Use student_id as that's what exists in the database table
        // Show all enrollments (pending, approved, declined) - students can see their status
        return $this->select('enrollments.*, courses.title, courses.description, courses.instructor_id, courses.status as course_status, users.name as instructor_name')
                    ->join('courses', 'courses.id = enrollments.course_id', 'left')
                    ->join('users', 'users.id = courses.instructor_id', 'left')
                    ->where('enrollments.student_id', $user_id)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get pending enrollments for a course (for instructor approval)
     * 
     * @param int $course_id Course ID
     * @return array Array of pending enrollment records with student details
     */
    public function getPendingEnrollments($course_id)
    {
        return $this->select('enrollments.*, users.name as student_name, users.email as student_email, users.id as student_id, courses.title as course_title')
                    ->join('users', 'users.id = enrollments.student_id', 'left')
                    ->join('courses', 'courses.id = enrollments.course_id', 'left')
                    ->where('enrollments.course_id', $course_id)
                    ->where('enrollments.status', 'pending')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get all enrollments for a course (for instructor to see all students)
     * 
     * @param int $course_id Course ID
     * @return array Array of enrollment records with student details
     */
    public function getCourseEnrollments($course_id)
    {
        return $this->select('enrollments.*, users.name as student_name, users.email as student_email, users.id as student_id, courses.title as course_title')
                    ->join('users', 'users.id = enrollments.student_id', 'left')
                    ->join('courses', 'courses.id = enrollments.course_id', 'left')
                    ->where('enrollments.course_id', $course_id)
                    ->whereIn('enrollments.status', ['approved', 'enrolled'])
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Check if a user is already enrolled in a specific course (any status)
     * 
     * @param int $user_id User ID
     * @param int $course_id Course ID
     * @return bool True if enrolled (pending/approved/declined), false otherwise
     */
    public function isAlreadyEnrolled($user_id, $course_id)
    {
        // Use student_id as that's what exists in the database table
        // Check for any enrollment status (pending, approved, declined, enrolled)
        $enrollment = $this->where('student_id', $user_id)
                           ->where('course_id', $course_id)
                           ->first();

        return $enrollment !== null;
    }
}

