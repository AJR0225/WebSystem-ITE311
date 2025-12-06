<?php
namespace App\Controllers;

use App\Models\UserModel;
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
            'hide_header' => true,
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
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $user = $model->where('email', $email)->first();
                if ($user && password_verify($password, $user['password'])) {
                    // Get user name (using 'name' field from database)
                    $userName = $user['name'] ?? $user['email'];
                    
                    $session->set([
                        'user_id' => $user['id'],
                        'user_name' => $userName,
                        'user_email' => $user['email'],
                        'user_role' => $user['role'],
                        'is_logged_in' => true
                    ]);
                    $session->setFlashdata('success', 'Welcome, ' . $userName . '!');
                    return redirect()->to('/dashboard');
                } else {
                    $session->setFlashdata('error', 'Invalid login credentials.');
                }
            }
        }
        $data = [
            'title' => 'Login - Learning Management System',
            'body_class' => 'auth-page',
            'hide_header' => true,
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
        if (!$session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Dashboard - Learning Management System',
            'page_title' => 'Dashboard',
            'body_class' => 'dashboard-page',
            'hide_header' => true,
            'hide_footer' => true,
            'user' => [
                'id' => $session->get('user_id'),
                'name' => $session->get('user_name'),
                'email' => $session->get('user_email'),
                'role' => $session->get('user_role')
            ]
        ];
        
        echo view('dashboard', $data);
    }
}
