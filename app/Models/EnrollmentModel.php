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
    protected $allowedFields    = ['student_id', 'course_id', 'enrollment_date', 'status', 'created_at', 'updated_at'];

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

        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'enrolled';
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
        return $this->select('enrollments.*, courses.title, courses.description, courses.instructor_id, courses.status as course_status, users.name as instructor_name')
                    ->join('courses', 'courses.id = enrollments.course_id', 'left')
                    ->join('users', 'users.id = courses.instructor_id', 'left')
                    ->where('enrollments.student_id', $user_id)
                    ->where('enrollments.status', 'enrolled')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Check if a user is already enrolled in a specific course
     * 
     * @param int $user_id User ID
     * @param int $course_id Course ID
     * @return bool True if enrolled, false otherwise
     */
    public function isAlreadyEnrolled($user_id, $course_id)
    {
        // Use student_id as that's what exists in the database table
        $enrollment = $this->where('student_id', $user_id)
                           ->where('course_id', $course_id)
                           ->first();

        return $enrollment !== null;
    }
}

