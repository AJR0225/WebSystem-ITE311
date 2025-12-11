<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        helper(['form']);
        $session = session();
        $model = new UserModel();
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'matches[password]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role' => 'student' // All users are students in this LMS
                ];
                
                // Save user to database
                if ($model->insert($data)) {
                    $session->setFlashdata('success', 'Registration successful. Please login.');
                    return redirect()->to('/login');
                } else {
                    // Get the last error for debugging
                    $errors = $model->errors();
                    $errorMessage = 'Registration failed. ';
                    if (!empty($errors)) {
                        $errorMessage .= implode(', ', $errors);
                    } else {
                        $errorMessage .= 'Please try again.';
                    }
                    $session->setFlashdata('error', $errorMessage);
                }
            }
        }
        
        $data = [
            'title' => 'Register - Learning Management System',
            'body_class' => 'auth-page',
            'hide_header' => true, // No header on login/register pages
            'hide_footer' => true,
            'validation' => $this->validator
        ];
        echo view('auth/register', $data);
    }

    public function login()
    {
        helper(['form']);
        $session = session();
        $model = new UserModel();
        
        // Handle POST request for login form submission
        if ($this->request->getMethod() === 'POST') {
            // Validation rules
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];
            
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                
                // Verify user credentials
                $user = $model->where('email', $email)->first();
                
                if ($user && password_verify($password, $user['password'])) {
                    // Get user name (using 'name' field from database)
                    $userName = $user['name'] ?? $user['email'];
                    
                    // Store user data in session for unified dashboard access
                    $session->set([
                        'user_id' => $user['id'],
                        'user_name' => $userName,
                        'user_email' => $user['email'],
                        'user_role' => $user['role'] ?? 'student', // Default to student if role is not set
                        'is_logged_in' => true
                    ]);
                    
                    // UNIFIED DASHBOARD: Redirect everyone (admin, instructor, student) to the same generic dashboard
                    // Role-based conditional checks will be handled in the dashboard() method
                    $session->setFlashdata('success', 'Welcome, ' . $userName . '!');
                    return redirect()->to('/dashboard');
                } else {
                    $session->setFlashdata('error', 'Invalid login credentials.');
                }
            }
        }
        
        // Display login form
        $data = [
            'title' => 'Login - Learning Management System',
            'body_class' => 'auth-page',
            'hide_header' => true, // No header on login/register pages
            'hide_footer' => true,
            'validation' => $this->validator
        ];
        echo view('auth/login', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function dashboard()
    {
        $session = session();
        
        // ============================================
        // AUTHORIZATION CHECK: Ensure user is logged in
        // ============================================
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('/login');
        }
        
        // Additional authorization: Verify user ID exists in session
        $userId = $session->get('user_id');
        if (empty($userId)) {
            $session->setFlashdata('error', 'Invalid session. Please login again.');
            return redirect()->to('/login');
        }
        
        // ============================================
        // GET USER ROLE FROM SESSION
        // ============================================
        $userRole = $session->get('user_role') ?? 'student'; // Default to student if role is not set
        $userRole = strtolower($userRole); // Normalize role to lowercase
        
        // Normalize "teacher" to "instructor" for compatibility
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }
        
        // ============================================
        // PREPARE USER DATA
        // ============================================
        $userData = [
            'id' => $userId,
            'name' => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role' => $userRole
        ];
        
        // ============================================
        // FETCH ROLE-SPECIFIC DATA FROM DATABASE
        // ============================================
        $userModel = new UserModel();
        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();
        
        $roleSpecificData = [];
        $databaseData = [];
        
        switch ($userRole) {
            case 'admin':
                // Admin: Fetch all system data
                $roleSpecificData['permissions'] = ['manage_users', 'manage_courses', 'view_reports', 'system_settings'];
                $roleSpecificData['dashboard_title'] = 'Admin Dashboard';
                $roleSpecificData['can_manage_users'] = true;
                $roleSpecificData['can_manage_courses'] = true;
                $roleSpecificData['can_view_reports'] = true;
                
                // Fetch all users from database
                $databaseData['total_users'] = $userModel->countAllResults();
                $databaseData['users_by_role'] = $userModel->select('role, COUNT(*) as count')
                    ->groupBy('role')
                    ->findAll();
                
                // Fetch all courses from database
                $databaseData['total_courses'] = $courseModel->countAllResults();
                $databaseData['all_courses'] = $courseModel->orderBy('created_at', 'DESC')
                    ->limit(10)
                    ->findAll();
                
                // Fetch enrollment statistics
                $databaseData['total_enrollments'] = $enrollmentModel->countAllResults();
                $databaseData['enrollments_by_status'] = $enrollmentModel->select('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->findAll();
                
                break;
                
            case 'instructor':
                // Instructor: Fetch courses they teach and their students
                $roleSpecificData['permissions'] = ['manage_courses', 'view_students', 'grade_assignments', 'create_quizzes'];
                $roleSpecificData['dashboard_title'] = 'Instructor Dashboard';
                $roleSpecificData['can_manage_courses'] = true;
                $roleSpecificData['can_view_students'] = true;
                $roleSpecificData['can_grade_assignments'] = true;
                
                // Fetch courses taught by this instructor
                $databaseData['my_courses'] = $courseModel->where('instructor_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
                $databaseData['total_my_courses'] = count($databaseData['my_courses']);
                
                // Fetch students enrolled in instructor's courses
                if (!empty($databaseData['my_courses'])) {
                    $courseIds = array_column($databaseData['my_courses'], 'id');
                    $databaseData['my_students'] = $enrollmentModel->select('enrollments.*, users.name as student_name, users.email as student_email, courses.title as course_title')
                        ->join('users', 'users.id = enrollments.student_id')
                        ->join('courses', 'courses.id = enrollments.course_id')
                        ->whereIn('enrollments.course_id', $courseIds)
                        ->where('enrollments.status', 'enrolled')
                        ->findAll();
                    $databaseData['total_my_students'] = count($databaseData['my_students']);
                } else {
                    $databaseData['my_students'] = [];
                    $databaseData['total_my_students'] = 0;
                }
                
                break;
                
            case 'student':
            default:
                // Student: Fetch enrolled courses and progress
                $roleSpecificData['permissions'] = ['view_courses', 'submit_assignments', 'view_grades', 'enroll_courses'];
                $roleSpecificData['dashboard_title'] = 'Student Dashboard';
                $roleSpecificData['can_view_courses'] = true;
                $roleSpecificData['can_submit_assignments'] = true;
                $roleSpecificData['can_view_grades'] = true;
                
                // Fetch enrolled courses for this student using EnrollmentModel::getUserEnrollments()
                try {
                    $databaseData['enrolled_courses'] = $enrollmentModel->getUserEnrollments($userId);
                    if (!is_array($databaseData['enrolled_courses'])) {
                        $databaseData['enrolled_courses'] = [];
                    }
                    $databaseData['total_enrolled_courses'] = count($databaseData['enrolled_courses']);
                    
                    // Fetch available courses (not enrolled)
                    $enrolledCourseIds = [];
                    if (!empty($databaseData['enrolled_courses'])) {
                        foreach ($databaseData['enrolled_courses'] as $enrollment) {
                            if (isset($enrollment['course_id']) && !empty($enrollment['course_id'])) {
                                $enrolledCourseIds[] = (int) $enrollment['course_id'];
                            }
                        }
                    }
                    
                    // Fetch available courses (not enrolled)
                    // Use direct database query to ensure we get the data
                    $db = \Config\Database::connect();
                    $builder = $db->table('courses');
                    $builder->where('status', 'published');
                    if (!empty($enrolledCourseIds)) {
                        $builder->whereNotIn('id', $enrolledCourseIds);
                    }
                    $builder->orderBy('created_at', 'DESC');
                    $builder->limit(10);
                    $query = $builder->get();
                    $databaseData['available_courses'] = $query->getResultArray();
                    
                    if (!is_array($databaseData['available_courses'])) {
                        $databaseData['available_courses'] = [];
                    }
                    $databaseData['total_available_courses'] = count($databaseData['available_courses']);
                } catch (\Exception $e) {
                    // Log the error for debugging
                    log_message('error', 'Error fetching student courses: ' . $e->getMessage());
                    // If there's an error, set empty arrays
                    $databaseData['enrolled_courses'] = [];
                    $databaseData['total_enrolled_courses'] = 0;
                    $databaseData['available_courses'] = [];
                    $databaseData['total_available_courses'] = 0;
                }
                
                break;
        }
        
        // ============================================
        // PASS DATA TO VIEW
        // ============================================
        $data = [
            'title' => 'Dashboard - Learning Management System',
            'page_title' => 'Dashboard',
            'body_class' => 'dashboard-page',
            'hide_header' => false, // Show header to display navigation
            'hide_footer' => true,
            'user' => $userData,
            'user_role' => $userRole, // Pass role explicitly for view conditional checks
            'role_data' => $roleSpecificData,
            'db_data' => $databaseData // Pass fetched database data to view
        ];
        
        echo view('auth/dashboard', $data);
    }

    public function manageUser()
    {
        helper(['form']);
        $session = session();
        
        // Authorization: Only admin can manage users
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to('/login');
        }
        
        $userRole = $session->get('user_role') ?? 'student';
        $userRole = strtolower($userRole);
        
        if ($userRole !== 'admin') {
            $session->setFlashdata('error', 'Access denied. Admin only.');
            return redirect()->to('/dashboard');
        }
        
        $userModel = new UserModel();
        $userId = $session->get('user_id');
        
        // Handle POST requests (add, edit, delete)
        if ($this->request->getMethod() === 'POST') {
            $action = $this->request->getPost('action');
            
            if ($action === 'add') {
                // Add new user
                $rules = [
                    'name' => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z]+( [a-zA-Z]+)*$/]',
                    'email' => 'required|valid_email|is_unique[users.email]|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]',
                    'password' => 'required|min_length[6]',
                    'password_confirm' => 'required|matches[password]',
                    'role' => 'required|in_list[admin,student,teacher,instructor]'
                ];
                
                if ($this->validate($rules)) {
                    $data = [
                        'name' => $this->request->getPost('name'),
                        'email' => $this->request->getPost('email'),
                        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                        'role' => $this->request->getPost('role')
                    ];
                    
                    // Normalize teacher to instructor
                    if ($data['role'] === 'teacher') {
                        $data['role'] = 'instructor';
                    }
                    
                    if ($userModel->insert($data)) {
                        // Create notification for new account
                        $notificationModel = new NotificationModel();
                        $notificationModel->insert([
                            'type' => 'new_account',
                            'message' => 'New account created: ' . $data['email'] . ' (' . ucfirst($data['role'] === 'instructor' ? 'teacher' : $data['role']) . ')',
                            'user_id' => $userModel->getInsertID(),
                            'user_name' => $data['name']
                        ]);
                        
                        $session->setFlashdata('success', 'User added successfully.');
                    } else {
                        $session->setFlashdata('error', 'Failed to add user.');
                    }
                } else {
                    $session->setFlashdata('error', 'Validation failed. Please check your input.');
                }
            } elseif ($action === 'edit') {
                // Edit existing user
                $id = $this->request->getPost('id');
                
                // STRICT: Prevent admin from editing themselves
                if ($id == $userId) {
                    $session->setFlashdata('error', 'You cannot edit your own account. Please use profile settings to update your information.');
                    return redirect()->to('/admin/manage-user');
                }
                
                $rules = [
                    'id' => 'required|integer',
                    'name' => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z]+( [a-zA-Z]+)*$/]',
                    'email' => 'required|valid_email|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]',
                    'role' => 'required|in_list[admin,student,teacher,instructor]'
                ];
                
                // Check if email is unique (excluding current user)
                $existingUser = $userModel->find($id);
                if ($existingUser && $existingUser['email'] !== $this->request->getPost('email')) {
                    $rules['email'] = 'required|valid_email|is_unique[users.email]|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]';
                }
                
                if ($this->validate($rules)) {
                    $data = [
                        'name' => $this->request->getPost('name'),
                        'email' => $this->request->getPost('email'),
                        'role' => $this->request->getPost('role')
                    ];
                    
                    // Normalize teacher to instructor
                    if ($data['role'] === 'teacher') {
                        $data['role'] = 'instructor';
                    }
                    
                    // Check if name was changed
                    $nameChanged = false;
                    $oldName = '';
                    if ($existingUser && $existingUser['name'] !== $data['name']) {
                        $nameChanged = true;
                        $oldName = $existingUser['name'];
                    }
                    
                    // Check if role was changed
                    $roleChanged = false;
                    $oldRole = '';
                    $newRole = $data['role'];
                    if ($existingUser) {
                        $existingRole = $existingUser['role'] === 'instructor' ? 'teacher' : $existingUser['role'];
                        if ($existingRole !== $newRole) {
                            $roleChanged = true;
                            $oldRole = ucfirst($existingRole);
                        }
                    }
                    
                    // Update password only if provided
                    $passwordChanged = false;
                    if ($this->request->getPost('password') && strlen($this->request->getPost('password')) >= 6) {
                        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                        $passwordChanged = true;
                    }
                    
                    if ($userModel->update($id, $data)) {
                        $notificationModel = new NotificationModel();
                        
                        // Create notification for name change if name was updated
                        if ($nameChanged) {
                            $notificationModel->insert([
                                'type' => 'name_changed',
                                'message' => 'Name changed for user: ' . $data['email'] . ' (from "' . $oldName . '" to "' . $data['name'] . '")',
                                'user_id' => $id,
                                'user_name' => $data['name']
                            ]);
                        }
                        
                        // Create notification for role change if role was updated
                        if ($roleChanged) {
                            $notificationModel->insert([
                                'type' => 'role_changed',
                                'message' => 'Role changed for user: ' . $data['email'] . ' (from "' . $oldRole . '" to "' . ucfirst($newRole) . '")',
                                'user_id' => $id,
                                'user_name' => $data['name']
                            ]);
                        }
                        
                        // Create notification for password change if password was updated
                        if ($passwordChanged) {
                            $notificationModel->insert([
                                'type' => 'password_changed',
                                'message' => 'Password changed for user: ' . $data['email'],
                                'user_id' => $id,
                                'user_name' => $data['name']
                            ]);
                        }
                        
                        $session->setFlashdata('success', 'User updated successfully.');
                    } else {
                        $session->setFlashdata('error', 'Failed to update user.');
                    }
                } else {
                    $session->setFlashdata('error', 'Validation failed. Please check your input.');
                }
            } elseif ($action === 'delete') {
                // Soft delete user (does not remove from database)
                $id = $this->request->getPost('id');
                
                // Prevent deleting yourself
                if ($id == $userId) {
                    $session->setFlashdata('error', 'You cannot delete your own account.');
                } else {
                    // Get user info before deletion (with deletedAt to check if already deleted)
                    $userToDelete = $userModel->withDeleted()->find($id);
                    
                    // Check if user is already deleted
                    if ($userToDelete && isset($userToDelete['deleted_at']) && $userToDelete['deleted_at'] !== null) {
                        $session->setFlashdata('error', 'User is already deleted.');
                    } else {
                        // Soft delete (sets deleted_at timestamp, does not remove from database)
                        if ($userModel->delete($id)) {
                            // Create notification for account deletion
                            if ($userToDelete) {
                                $notificationModel = new NotificationModel();
                                $notificationModel->insert([
                                    'type' => 'account_deleted',
                                    'message' => 'User account deleted: ' . $userToDelete['email'] . ' (' . ucfirst($userToDelete['role'] === 'instructor' ? 'teacher' : $userToDelete['role']) . ')',
                                    'user_id' => $id,
                                    'user_name' => $userToDelete['name']
                                ]);
                            }
                            
                            $session->setFlashdata('success', 'User deleted successfully. You can restore it anytime.');
                        } else {
                            $session->setFlashdata('error', 'Failed to delete user.');
                        }
                    }
                }
            } elseif ($action === 'restore') {
                // Restore deleted user
                $id = $this->request->getPost('id');
                
                // Get user info (including deleted users)
                $userToRestore = $userModel->withDeleted()->find($id);
                
                if ($userToRestore && isset($userToRestore['deleted_at']) && $userToRestore['deleted_at'] !== null) {
                    // Restore the user by setting deleted_at to null
                    if ($userModel->update($id, ['deleted_at' => null])) {
                        // Create notification for account restoration
                        $notificationModel = new NotificationModel();
                        $notificationModel->insert([
                            'type' => 'account_restored',
                            'message' => 'User account restored: ' . $userToRestore['email'] . ' (' . ucfirst($userToRestore['role'] === 'instructor' ? 'teacher' : $userToRestore['role']) . ')',
                            'user_id' => $id,
                            'user_name' => $userToRestore['name']
                        ]);
                        
                        $session->setFlashdata('success', 'User account restored successfully.');
                    } else {
                        $session->setFlashdata('error', 'Failed to restore user.');
                    }
                } else {
                    $session->setFlashdata('error', 'User is not deleted or does not exist.');
                }
            }
            
            return redirect()->to('/admin/manage-user');
        }
        
        // Fetch all non-deleted users (soft delete automatically excludes deleted users)
        $users = $userModel->orderBy('created_at', 'DESC')->findAll();
        
        // Fetch deleted users for restore option (using withDeleted() to include soft-deleted records)
        $deletedUsers = [];
        try {
            // Get all users including deleted ones, then filter for deleted
            $allUsers = $userModel->withDeleted()->orderBy('deleted_at', 'DESC')->findAll();
            $deletedUsers = array_filter($allUsers, function($user) {
                return isset($user['deleted_at']) && $user['deleted_at'] !== null;
            });
            // Re-index array
            $deletedUsers = array_values($deletedUsers);
        } catch (\Exception $e) {
            // If query fails, set empty array
            $deletedUsers = [];
        }
        
        // Prepare user data
        $userData = [
            'id' => $userId,
            'name' => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role' => $userRole
        ];
        
        $data = [
            'title' => 'Manage Users - Learning Management System',
            'page_title' => 'Manage Users',
            'body_class' => 'dashboard-page',
            'hide_header' => false,
            'hide_footer' => true,
            'user' => $userData,
            'user_role' => $userRole,
            'users' => $users,
            'deleted_users' => $deletedUsers ?? [],
            'validation' => $this->validator
        ];
        
        echo view('admin/manage-user', $data);
    }

    public function createUser()
    {
        helper(['form']);
        $session = session();
        
        // Authorization: Only admin can create users
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to('/login');
        }
        
        $userRole = $session->get('user_role') ?? 'student';
        $userRole = strtolower($userRole);
        
        if ($userRole !== 'admin') {
            $session->setFlashdata('error', 'Access denied. Admin only.');
            return redirect()->to('/dashboard');
        }
        
        $userModel = new UserModel();
        $userId = $session->get('user_id');
        
        // Handle POST request
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z]+( [a-zA-Z]+)*$/]',
                'email' => 'required|valid_email|is_unique[users.email]|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
                'role' => 'required|in_list[admin,student,teacher]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role' => $this->request->getPost('role')
                ];
                
                // Normalize teacher to instructor
                if ($data['role'] === 'teacher') {
                    $data['role'] = 'instructor';
                }
                
                if ($userModel->insert($data)) {
                    // Create notification for new account
                    $notificationModel = new NotificationModel();
                    $notificationModel->insert([
                        'type' => 'new_account',
                        'message' => 'New account created: ' . $data['email'] . ' (' . ucfirst($data['role'] === 'instructor' ? 'teacher' : $data['role']) . ')',
                        'user_id' => $userModel->getInsertID(),
                        'user_name' => $data['name']
                    ]);
                    
                    $session->setFlashdata('success', 'User created successfully.');
                    return redirect()->to('/admin/manage-user');
                } else {
                    $session->setFlashdata('error', 'Failed to create user.');
                }
            } else {
                $session->setFlashdata('error', 'Validation failed. Please check your input.');
            }
        }
        
        // Prepare user data
        $userData = [
            'id' => $userId,
            'name' => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role' => $userRole
        ];
        
        $data = [
            'title' => 'Create User - Learning Management System',
            'page_title' => 'Create User',
            'body_class' => 'dashboard-page',
            'hide_header' => false,
            'hide_footer' => true,
            'user' => $userData,
            'user_role' => $userRole,
            'validation' => $this->validator
        ];
        
        echo view('admin/create-user', $data);
    }

    public function announcement()
    {
        helper(['form']);
        $session = session();
        
        // Authorization: Only admin can view announcements
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to('/login');
        }
        
        $userRole = $session->get('user_role') ?? 'student';
        $userRole = strtolower($userRole);
        
        if ($userRole !== 'admin') {
            $session->setFlashdata('error', 'Access denied. Admin only.');
            return redirect()->to('/dashboard');
        }
        
        $notificationModel = new NotificationModel();
        $userId = $session->get('user_id');
        
        // Handle POST request (clear all notifications or delete individual)
        if ($this->request->getMethod() === 'POST') {
            $action = $this->request->getPost('action');
            
            if ($action === 'clear_all') {
                // Delete all notifications
                $db = \Config\Database::connect();
                if ($db->table('notifications')->truncate()) {
                    $session->setFlashdata('success', 'All notifications cleared successfully.');
                } else {
                    $session->setFlashdata('error', 'Failed to clear notifications.');
                }
                return redirect()->to('/admin/announcement');
            } elseif ($action === 'delete') {
                // Delete individual notification
                $notificationId = $this->request->getPost('notification_id');
                
                if ($notificationId && is_numeric($notificationId)) {
                    if ($notificationModel->delete($notificationId)) {
                        $session->setFlashdata('success', 'Notification deleted successfully.');
                    } else {
                        $session->setFlashdata('error', 'Failed to delete notification.');
                    }
                } else {
                    $session->setFlashdata('error', 'Invalid notification ID.');
                }
                
                return redirect()->to('/admin/announcement');
            }
        }
        
        // Fetch all notifications
        $notifications = $notificationModel->orderBy('created_at', 'DESC')->findAll();
        
        // Prepare user data
        $userData = [
            'id' => $userId,
            'name' => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role' => $userRole
        ];
        
        $data = [
            'title' => 'Announcements & Notifications - Learning Management System',
            'page_title' => 'Announcements',
            'body_class' => 'dashboard-page',
            'hide_header' => false,
            'hide_footer' => true,
            'user' => $userData,
            'user_role' => $userRole,
            'notifications' => $notifications
        ];
        
        echo view('admin/announcement', $data);
    }

    public function adminCourses()
    {
        helper(['form']);
        $session = session();
        
        // Authorization: Only admin can manage courses
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to('/login');
        }
        
        $userRole = $session->get('user_role') ?? 'student';
        $userRole = strtolower($userRole);
        
        if ($userRole !== 'admin') {
            $session->setFlashdata('error', 'Access denied. Admin only.');
            return redirect()->to('/dashboard');
        }
        
        $courseModel = new CourseModel();
        $userModel = new UserModel();
        $userId = $session->get('user_id');
        
        // Handle POST requests (add, edit, delete)
        if ($this->request->getMethod() === 'POST') {
            $action = $this->request->getPost('action');
            
            if ($action === 'add') {
                // Add new course
                $rules = [
                    'title' => 'required|min_length[3]|max_length[255]',
                    'description' => 'required|min_length[10]',
                    'instructor_id' => 'required|integer',
                    'status' => 'required|in_list[draft,published,archived]',
                    'semester' => 'required|in_list[1st Semester,2nd Semester]',
                    'academic_year' => 'required|regex_match[/^\d{4}-\d{4}$/]',
                    'schedule_days' => 'required',
                    'start_time' => 'required',
                    'end_time' => 'required'
                ];
                
                if ($this->validate($rules)) {
                    $instructorId = (int) $this->request->getPost('instructor_id');
                    
                    // Validate that the instructor is actually an instructor/teacher
                    $instructor = $userModel->find($instructorId);
                    if (!$instructor) {
                        $session->setFlashdata('error', 'Selected instructor not found.');
                        return redirect()->to('/admin/courses');
                    }
                    
                    $instructorRole = strtolower($instructor['role'] ?? '');
                    if (!in_array($instructorRole, ['instructor', 'teacher'])) {
                        $session->setFlashdata('error', 'Selected user is not an instructor. Please select a valid instructor.');
                        return redirect()->to('/admin/courses');
                    }
                    
                    // Process schedule days (can be string from hidden input or array from checkboxes)
                    $scheduleDays = $this->request->getPost('schedule_days');
                    if (empty($scheduleDays)) {
                        // Try to get from checkboxes array if hidden input is empty
                        $scheduleDaysArray = $this->request->getPost('schedule_days[]');
                        if (is_array($scheduleDaysArray) && !empty($scheduleDaysArray)) {
                            $scheduleDays = implode(', ', $scheduleDaysArray);
                        }
                    }
                    
                    $startTime = $this->request->getPost('start_time');
                    $endTime = $this->request->getPost('end_time');
                    
                    // Check for schedule conflicts
                    $conflict = $courseModel->checkScheduleConflict($instructorId, $scheduleDays, $startTime, $endTime);
                    if ($conflict !== false) {
                        $session->setFlashdata('error', 'Schedule conflict detected! The instructor already has a class on ' . $conflict['conflict_days'] . ' at ' . $conflict['conflict_time'] . ' (Course: ' . $conflict['course_title'] . '). Please choose a different day or time.');
                        return redirect()->to('/admin/courses');
                    }
                    
                    $data = [
                        'title' => $this->request->getPost('title'),
                        'description' => $this->request->getPost('description'),
                        'instructor_id' => $instructorId,
                        'status' => $this->request->getPost('status'),
                        'semester' => $this->request->getPost('semester'),
                        'academic_year' => $this->request->getPost('academic_year'),
                        'schedule_days' => $scheduleDays,
                        'start_time' => $startTime,
                        'end_time' => $endTime
                    ];
                    
                    if ($courseModel->insert($data)) {
                        $session->setFlashdata('success', 'Course created successfully.');
                        return redirect()->to('/admin/courses');
                    } else {
                        $session->setFlashdata('error', 'Failed to create course.');
                    }
                } else {
                    $session->setFlashdata('error', 'Validation failed. Please check your input.');
                }
            } elseif ($action === 'edit') {
                // Edit existing course
                $courseId = (int) $this->request->getPost('course_id');
                $rules = [
                    'title' => 'required|min_length[3]|max_length[255]',
                    'description' => 'required|min_length[10]',
                    'instructor_id' => 'required|integer',
                    'status' => 'required|in_list[draft,published,archived]',
                    'semester' => 'required|in_list[1st Semester,2nd Semester]',
                    'academic_year' => 'required|regex_match[/^\d{4}-\d{4}$/]',
                    'schedule_days' => 'required',
                    'start_time' => 'required',
                    'end_time' => 'required'
                ];
                
                if ($this->validate($rules)) {
                    $instructorId = (int) $this->request->getPost('instructor_id');
                    
                    // Validate that the instructor is actually an instructor/teacher
                    $instructor = $userModel->find($instructorId);
                    if (!$instructor) {
                        $session->setFlashdata('error', 'Selected instructor not found.');
                        return redirect()->to('/admin/courses');
                    }
                    
                    $instructorRole = strtolower($instructor['role'] ?? '');
                    if (!in_array($instructorRole, ['instructor', 'teacher'])) {
                        $session->setFlashdata('error', 'Selected user is not an instructor. Please select a valid instructor.');
                        return redirect()->to('/admin/courses');
                    }
                    
                    // Process schedule days (can be string from hidden input or array from checkboxes)
                    $scheduleDays = $this->request->getPost('schedule_days');
                    if (empty($scheduleDays)) {
                        // Try to get from checkboxes array if hidden input is empty
                        $scheduleDaysArray = $this->request->getPost('schedule_days[]');
                        if (is_array($scheduleDaysArray) && !empty($scheduleDaysArray)) {
                            $scheduleDays = implode(', ', $scheduleDaysArray);
                        }
                    }
                    
                    $startTime = $this->request->getPost('start_time');
                    $endTime = $this->request->getPost('end_time');
                    
                    // Check for schedule conflicts (exclude current course being edited)
                    $conflict = $courseModel->checkScheduleConflict($instructorId, $scheduleDays, $startTime, $endTime, $courseId);
                    if ($conflict !== false) {
                        $session->setFlashdata('error', 'Schedule conflict detected! The instructor already has a class on ' . $conflict['conflict_days'] . ' at ' . $conflict['conflict_time'] . ' (Course: ' . $conflict['course_title'] . '). Please choose a different day or time.');
                        return redirect()->to('/admin/courses');
                    }
                    
                    $data = [
                        'title' => $this->request->getPost('title'),
                        'description' => $this->request->getPost('description'),
                        'instructor_id' => $instructorId,
                        'status' => $this->request->getPost('status'),
                        'semester' => $this->request->getPost('semester'),
                        'academic_year' => $this->request->getPost('academic_year'),
                        'schedule_days' => $scheduleDays,
                        'start_time' => $startTime,
                        'end_time' => $endTime
                    ];
                    
                    if ($courseModel->update($courseId, $data)) {
                        $session->setFlashdata('success', 'Course updated successfully.');
                        return redirect()->to('/admin/courses');
                    } else {
                        $session->setFlashdata('error', 'Failed to update course.');
                    }
                } else {
                    $session->setFlashdata('error', 'Validation failed. Please check your input.');
                }
            } elseif ($action === 'delete') {
                // Delete course
                $courseId = (int) $this->request->getPost('course_id');
                if ($courseModel->delete($courseId)) {
                    $session->setFlashdata('success', 'Course deleted successfully.');
                    return redirect()->to('/admin/courses');
                } else {
                    $session->setFlashdata('error', 'Failed to delete course.');
                }
            }
        }
        
        // Fetch all courses with instructor names
        $courses = $courseModel->select('courses.*, users.name as instructor_name, users.email as instructor_email, users.role as instructor_role')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->orderBy('courses.created_at', 'DESC')
            ->findAll();
        
        // Fetch only real instructors/teachers for dropdown (exclude admin and students)
        $allUsers = $userModel->orderBy('name', 'ASC')->findAll();
        $instructors = [];
        foreach ($allUsers as $user) {
            $userRole = strtolower($user['role'] ?? '');
            // Only include users with instructor or teacher role (exclude admin, student, etc.)
            if (in_array($userRole, ['instructor', 'teacher'])) {
                $instructors[] = $user;
            }
        }
        
        // Prepare user data
        $userData = [
            'id' => $userId,
            'name' => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role' => $userRole
        ];
        
        $data = [
            'title' => 'Manage Courses - Learning Management System',
            'page_title' => 'Manage Courses',
            'body_class' => 'dashboard-page',
            'hide_header' => false,
            'hide_footer' => true,
            'user' => $userData,
            'user_role' => $userRole,
            'courses' => $courses,
            'instructors' => $instructors,
            'validation' => $this->validator
        ];
        
        echo view('admin/courses', $data);
    }

    public function viewCourseStudents($courseId)
    {
        helper(['form']);
        $session = session();
        
        // Authorization: Only instructor can view course students
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to('/login');
        }
        
        $userRole = $session->get('user_role') ?? 'student';
        $userRole = strtolower($userRole);
        
        // Normalize "teacher" to "instructor"
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }
        
        if ($userRole !== 'instructor') {
            $session->setFlashdata('error', 'Access denied. Instructor only.');
            return redirect()->to('/dashboard');
        }
        
        $userId = $session->get('user_id');
        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();
        
        // Fetch course details
        $course = $courseModel->find($courseId);
        
        if (!$course) {
            $session->setFlashdata('error', 'Course not found.');
            return redirect()->to('/dashboard');
        }
        
        // Verify that the course belongs to this instructor
        if ($course['instructor_id'] != $userId) {
            $session->setFlashdata('error', 'Access denied. This course does not belong to you.');
            return redirect()->to('/dashboard');
        }
        
        // Fetch pending enrollments (for approval)
        $pendingEnrollments = $enrollmentModel->getPendingEnrollments($courseId);
        
        // Fetch approved/enrolled students for this course
        $enrolledStudents = $enrollmentModel->getCourseEnrollments($courseId);
        
        // Get all students for manual enrollment dropdown
        $userModel = new UserModel();
        $allStudents = $userModel->where('role', 'student')
            ->orderBy('name', 'ASC')
            ->findAll();
        
        // Prepare user data
        $userData = [
            'id' => $userId,
            'name' => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role' => $userRole
        ];
        
        $data = [
            'title' => 'Course Students - Learning Management System',
            'page_title' => 'Course Students',
            'body_class' => 'dashboard-page',
            'hide_header' => false,
            'hide_footer' => true,
            'user' => $userData,
            'user_role' => $userRole,
            'course' => $course,
            'pending_enrollments' => $pendingEnrollments,
            'enrolled_students' => $enrolledStudents,
            'total_students' => count($enrolledStudents),
            'all_students' => $allStudents
        ];
        
        echo view('instructor/course-students', $data);
    }
    
    public function approveEnrollment()
    {
        $session = session();
        
        if (!$session->get('is_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to access this page.'
            ]);
        }
        
        $userRole = strtolower($session->get('user_role') ?? 'student');
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }
        
        if ($userRole !== 'instructor') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. Instructor only.'
            ]);
        }
        
        $enrollmentId = (int) $this->request->getPost('enrollment_id');
        $enrollmentModel = new EnrollmentModel();
        
        // Get enrollment details
        $enrollment = $enrollmentModel->find($enrollmentId);
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ]);
        }
        
        // Verify course belongs to instructor
        $courseModel = new CourseModel();
        $course = $courseModel->find($enrollment['course_id']);
        if (!$course || $course['instructor_id'] != $session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. This course does not belong to you.'
            ]);
        }
        
        // Update enrollment status to approved
        if ($enrollmentModel->update($enrollmentId, ['status' => 'approved'])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment approved successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to approve enrollment.'
            ]);
        }
    }
    
    public function declineEnrollment()
    {
        $session = session();
        
        if (!$session->get('is_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to access this page.'
            ]);
        }
        
        $userRole = strtolower($session->get('user_role') ?? 'student');
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }
        
        if ($userRole !== 'instructor') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. Instructor only.'
            ]);
        }
        
        $enrollmentId = (int) $this->request->getPost('enrollment_id');
        $declineReason = $this->request->getPost('decline_reason');
        
        if (empty($declineReason)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please provide a reason for declining the enrollment.'
            ]);
        }
        
        $enrollmentModel = new EnrollmentModel();
        
        // Get enrollment details
        $enrollment = $enrollmentModel->find($enrollmentId);
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ]);
        }
        
        // Verify course belongs to instructor
        $courseModel = new CourseModel();
        $course = $courseModel->find($enrollment['course_id']);
        if (!$course || $course['instructor_id'] != $session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. This course does not belong to you.'
            ]);
        }
        
        // Update enrollment status to declined with reason
        if ($enrollmentModel->update($enrollmentId, [
            'status' => 'declined',
            'decline_reason' => $declineReason
        ])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment declined successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to decline enrollment.'
            ]);
        }
    }
    
    public function unenrollStudent()
    {
        $session = session();
        
        if (!$session->get('is_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to access this page.'
            ]);
        }
        
        $userRole = strtolower($session->get('user_role') ?? 'student');
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }
        
        if ($userRole !== 'instructor') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. Instructor only.'
            ]);
        }
        
        $enrollmentId = (int) $this->request->getPost('enrollment_id');
        $enrollmentModel = new EnrollmentModel();
        
        // Get enrollment details
        $enrollment = $enrollmentModel->find($enrollmentId);
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ]);
        }
        
        // Verify course belongs to instructor
        $courseModel = new CourseModel();
        $course = $courseModel->find($enrollment['course_id']);
        if (!$course || $course['instructor_id'] != $session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. This course does not belong to you.'
            ]);
        }
        
        // Delete enrollment
        if ($enrollmentModel->delete($enrollmentId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student unenrolled successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to unenroll student.'
            ]);
        }
    }
    
    public function enrollStudent()
    {
        $session = session();
        
        if (!$session->get('is_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to access this page.'
            ]);
        }
        
        $userRole = strtolower($session->get('user_role') ?? 'student');
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }
        
        if ($userRole !== 'instructor') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. Instructor only.'
            ]);
        }
        
        $courseId = (int) $this->request->getPost('course_id');
        $studentId = (int) $this->request->getPost('student_id');
        
        if (empty($courseId) || empty($studentId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course ID and Student ID are required.'
            ]);
        }
        
        // Verify course belongs to instructor
        $courseModel = new CourseModel();
        $course = $courseModel->find($courseId);
        if (!$course || $course['instructor_id'] != $session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. This course does not belong to you.'
            ]);
        }
        
        // Verify student exists
        $userModel = new UserModel();
        $student = $userModel->find($studentId);
        if (!$student || strtolower($student['role']) !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid student.'
            ]);
        }
        
        // Check if already enrolled
        $enrollmentModel = new EnrollmentModel();
        if ($enrollmentModel->isAlreadyEnrolled($studentId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student is already enrolled in this course.'
            ]);
        }
        
        // Enroll student (approved directly by instructor)
        $enrollmentData = [
            'student_id' => $studentId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'approved' // Directly approved when enrolled by instructor
        ];
        
        $enrollmentId = $enrollmentModel->enrollUser($enrollmentData);
        
        if ($enrollmentId) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student enrolled successfully.',
                'enrollment_id' => $enrollmentId
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll student.'
            ]);
        }
    }
}
