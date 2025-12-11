<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\Controller;

class Materials extends Controller
{
    protected $materialModel;
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    /**
     * Display the file upload form and handle file upload
     * 
     * @param int $course_id Course ID
     * @return mixed View or redirect
     */
    public function upload($course_id)
    {
        helper(['form']);
        $session = session();

        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to('/login');
        }

        $userRole = strtolower($session->get('user_role') ?? 'student');
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }

        // Only instructors and admins can upload materials
        if ($userRole !== 'instructor' && $userRole !== 'admin') {
            $session->setFlashdata('error', 'Access denied. Instructor or Admin only.');
            return redirect()->to('/dashboard');
        }

        $userId = $session->get('user_id');
        $course_id = (int) $course_id;

        // Verify course exists and belongs to instructor
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            $session->setFlashdata('error', 'Course not found.');
            return redirect()->to('/dashboard');
        }

        // Admin can access any course, instructor can only access their own courses
        if ($userRole !== 'admin' && $course['instructor_id'] != $userId) {
            $session->setFlashdata('error', 'Access denied. This course does not belong to you.');
            return redirect()->to('/dashboard');
        }

        // Handle file upload
        if ($this->request->getMethod() === 'POST') {
            // Load CodeIgniter's File Uploading Library
            $file = $this->request->getFile('material_file');

            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Load Validation Library
                $validation = \Config\Services::validation();

                // Configure validation rules
                $rules = [
                    'material_file' => [
                        'uploaded[material_file]',
                        'max_size[material_file,10240]', // 10MB max (10240 KB)
                        'mime_in[material_file,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,text/plain,image/jpeg,image/png,image/gif,application/zip,application/x-zip-compressed]', // Allowed file types
                    ]
                ];

                $messages = [
                    'material_file' => [
                        'uploaded' => 'Please select a file to upload.',
                        'max_size' => 'File size must not exceed 10MB.',
                        'mime_in' => 'Invalid file type. Allowed types: PDF, Word, Excel, PowerPoint, Images, ZIP, Text files.',
                    ]
                ];

                if ($this->validate($rules, $messages)) {
                    // Configure upload preferences
                    // Create upload directory for course if it doesn't exist
                    $uploadPath = WRITEPATH . 'uploads/materials/course_' . $course_id . '/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    // Generate unique filename to prevent conflicts
                    $newName = $file->getRandomName();
                    $filePath = 'materials/course_' . $course_id . '/' . $newName;

                    // Perform the file upload
                    if ($file->move($uploadPath, $newName)) {
                        // Prepare data for database
                        $materialData = [
                            'course_id' => $course_id,
                            'file_name' => $file->getClientName(), // Original filename
                            'file_path' => $filePath,
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        // Save to database using MaterialModel
                        if ($this->materialModel->insertMaterial($materialData)) {
                            // Set success flash message
                            $session->setFlashdata('success', 'Material uploaded successfully.');
                            // Redirect back to upload page
                            return redirect()->to('/admin/course/' . $course_id . '/upload');
                        } else {
                            // Delete uploaded file if database insert fails
                            @unlink($uploadPath . $newName);
                            // Set failure flash message
                            $session->setFlashdata('error', 'Failed to save material record. Please try again.');
                        }
                    } else {
                        // Set failure flash message
                        $session->setFlashdata('error', 'Failed to upload file. Please try again.');
                    }
                } else {
                    // Set validation error flash message
                    $errors = $validation->getErrors();
                    $errorMessage = !empty($errors) ? implode(' ', $errors) : 'File validation failed. Please check file size (max 10MB) and file type.';
                    $session->setFlashdata('error', $errorMessage);
                }
            } else {
                // Set error flash message
                if ($file && $file->hasError()) {
                    $errorCode = $file->getError();
                    $errorMessages = [
                        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini.',
                        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive in HTML form.',
                        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
                        UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
                        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                        UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.',
                    ];
                    $session->setFlashdata('error', $errorMessages[$errorCode] ?? 'File upload error occurred.');
                } else {
                    $session->setFlashdata('error', 'No valid file uploaded. Please select a file.');
                }
            }
        }

        // Get existing materials for this course
        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        // Prepare user data
        $userData = [
            'id' => $userId,
            'name' => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role' => $userRole
        ];

        $data = [
            'title' => 'Upload Materials - Learning Management System',
            'page_title' => 'Upload Materials',
            'body_class' => 'dashboard-page',
            'hide_header' => false,
            'hide_footer' => true,
            'user' => $userData,
            'user_role' => $userRole,
            'course' => $course,
            'materials' => $materials,
            'validation' => $this->validator
        ];

        echo view('instructor/upload-materials', $data);
    }

    /**
     * Delete a material record and the associated file
     * 
     * @param int $material_id Material ID
     * @return \CodeIgniter\HTTP\ResponseInterface JSON response
     */
    public function delete($material_id)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            // Support both GET and POST requests
            if ($this->request->isAJAX() || $this->request->getMethod() === 'POST') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login to access this page.'
                ]);
            } else {
                $session->setFlashdata('error', 'Please login to access this page.');
                return redirect()->to('/login');
            }
        }

        $userRole = strtolower($session->get('user_role') ?? 'student');
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }

        // Only instructors can delete materials
        if ($userRole !== 'instructor') {
            // Support both GET and POST requests
            if ($this->request->isAJAX() || $this->request->getMethod() === 'POST') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Access denied. Instructor only.'
                ]);
            } else {
                $session->setFlashdata('error', 'Access denied. Instructor only.');
                return redirect()->to('/dashboard');
            }
        }

        $userId = $session->get('user_id');
        $material_id = (int) $material_id;

        // Get material record
        $material = $this->materialModel->find($material_id);
        if (!$material) {
            // Support both GET and POST requests
            if ($this->request->isAJAX() || $this->request->getMethod() === 'POST') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Material not found.'
                ]);
            } else {
                $session->setFlashdata('error', 'Material not found.');
                return redirect()->to('/dashboard');
            }
        }

        // Verify course belongs to instructor
        $course = $this->courseModel->find($material['course_id']);
        if (!$course || $course['instructor_id'] != $userId) {
            // Support both GET and POST requests
            if ($this->request->isAJAX() || $this->request->getMethod() === 'POST') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Access denied. This material does not belong to your course.'
                ]);
            } else {
                $session->setFlashdata('error', 'Access denied. This material does not belong to your course.');
                return redirect()->to('/dashboard');
            }
        }

        // Delete file from filesystem
        $filePath = WRITEPATH . 'uploads/' . $material['file_path'];
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        // Delete record from database
        if ($this->materialModel->delete($material_id)) {
            // Support both GET and POST requests
            if ($this->request->isAJAX() || $this->request->getMethod() === 'POST') {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Material deleted successfully.'
                ]);
            } else {
                $session->setFlashdata('success', 'Material deleted successfully.');
                return redirect()->to('/instructor/course/' . $course['id']);
            }
        } else {
            // Support both GET and POST requests
            if ($this->request->isAJAX() || $this->request->getMethod() === 'POST') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete material record.'
                ]);
            } else {
                $session->setFlashdata('error', 'Failed to delete material record.');
                return redirect()->to('/instructor/course/' . $course['id']);
            }
        }
    }

    /**
     * Handle file download for enrolled students
     * 
     * Step 7: Implement the Download Method
     * - Check if the current user is logged in and enrolled in the course
     * - Retrieve the file path from the database using the material_id
     * - Use CodeIgniter's download() helper function or Response class to force the file download securely
     * 
     * @param int $material_id Material ID
     * @return mixed File download or error response
     */
    public function download($material_id)
    {
        $session = session();

        // Step 7.1: Check if the current user is logged in
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to download materials.');
            return redirect()->to('/login');
        }

        $userId = $session->get('user_id');
        if (empty($userId)) {
            $session->setFlashdata('error', 'Invalid user session.');
            return redirect()->to('/login');
        }

        $userRole = strtolower($session->get('user_role') ?? 'student');
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }

        $material_id = (int) $material_id;

        // Step 7.2: Retrieve the file path from the database using the material_id
        $material = $this->materialModel->find($material_id);
        if (!$material) {
            $session->setFlashdata('error', 'Material not found.');
            return redirect()->to('/dashboard');
        }

        // Get course associated with the material
        $course = $this->courseModel->find($material['course_id']);
        if (!$course) {
            $session->setFlashdata('error', 'Course not found.');
            return redirect()->to('/dashboard');
        }

        // Step 7.3: Check if the current user is enrolled in the course associated with the material
        $hasAccess = false;

        // Allow access if user is the instructor of the course
        if ($userRole === 'instructor' && $course['instructor_id'] == $userId) {
            $hasAccess = true;
        } elseif ($userRole === 'student') {
            // Check if student is enrolled in the course (approved or enrolled status)
            $enrollment = $this->enrollmentModel->where('student_id', $userId)
                ->where('course_id', $material['course_id'])
                ->whereIn('status', ['approved', 'enrolled'])
                ->first();

            if ($enrollment) {
                $hasAccess = true;
            }
        }

        if (!$hasAccess) {
            $session->setFlashdata('error', 'Access denied. You must be enrolled in this course to download materials.');
            return redirect()->to('/dashboard');
        }

        // Step 7.4: Retrieve the file path from the database
        // File path is already retrieved above: $material['file_path']
        $filePath = WRITEPATH . 'uploads/' . $material['file_path'];

        // Verify file exists on filesystem
        if (!file_exists($filePath)) {
            $session->setFlashdata('error', 'File not found on server.');
            return redirect()->to('/dashboard');
        }

        // Step 7.5: Use CodeIgniter's Response class download() method to force the file download securely
        // The download() method sets appropriate headers for secure file download
        return $this->response->download($filePath, null)->setFileName($material['file_name']);
    }
}

