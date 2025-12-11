<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'description', 'instructor_id', 'status', 'semester', 'academic_year', 'schedule_days', 'start_time', 'end_time', 'created_at', 'updated_at'];

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
     * Check if there's a schedule conflict for an instructor
     * 
     * @param int $instructorId The instructor ID
     * @param string $scheduleDays Comma-separated days (e.g., "Monday, Tuesday")
     * @param string $startTime Start time (HH:MM:SS format)
     * @param string $endTime End time (HH:MM:SS format)
     * @param int|null $excludeCourseId Course ID to exclude from check (for editing)
     * @return array|false Returns conflict course data if conflict exists, false otherwise
     */
    public function checkScheduleConflict($instructorId, $scheduleDays, $startTime, $endTime, $excludeCourseId = null)
    {
        // Get all courses for this instructor
        $builder = $this->where('instructor_id', $instructorId);
        
        // Exclude the current course if editing
        if ($excludeCourseId !== null) {
            $builder->where('id !=', $excludeCourseId);
        }
        
        // Only check courses that have schedule information
        $builder->where('schedule_days IS NOT NULL')
                ->where('schedule_days !=', '')
                ->where('start_time IS NOT NULL')
                ->where('end_time IS NOT NULL');
        
        $existingCourses = $builder->findAll();
        
        if (empty($existingCourses)) {
            return false;
        }
        
        // Parse the new schedule days
        $newDays = array_map('trim', explode(',', $scheduleDays));
        
        // Convert times to timestamps for comparison
        $newStartTime = strtotime($startTime);
        $newEndTime = strtotime($endTime);
        
        // Check each existing course for conflicts
        foreach ($existingCourses as $course) {
            // Parse existing course days
            $existingDays = array_map('trim', explode(',', $course['schedule_days']));
            
            // Check if there's any day overlap
            $dayOverlap = array_intersect($newDays, $existingDays);
            
            if (!empty($dayOverlap)) {
                // There's a day overlap, now check time overlap
                $existingStartTime = strtotime($course['start_time']);
                $existingEndTime = strtotime($course['end_time']);
                
                // Check if time ranges overlap
                // Two time ranges overlap if: (newStart < existingEnd) && (existingStart < newEnd)
                if (($newStartTime < $existingEndTime) && ($existingStartTime < $newEndTime)) {
                    // Conflict found!
                    return [
                        'course_id' => $course['id'],
                        'course_title' => $course['title'],
                        'conflict_days' => implode(', ', $dayOverlap),
                        'conflict_time' => date('g:i A', $existingStartTime) . ' - ' . date('g:i A', $existingEndTime)
                    ];
                }
            }
        }
        
        return false;
    }
}

