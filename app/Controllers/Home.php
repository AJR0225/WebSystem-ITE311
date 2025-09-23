<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Learning Management System',
            'page_title' => 'Welcome to LMS',
            'description' => 'Your comprehensive learning management platform'
        ];
        
        return view('index', $data);
    }
    
    public function about()
    {
        $data = [
            'title' => 'About Us - Learning Management System',
            'page_title' => 'About LMS',
            'description' => 'Learn more about our learning management system'
        ];
        
        return view('about', $data);
    }
    
    public function contact()
    {
        $data = [
            'title' => 'Contact Us - Learning Management System',
            'page_title' => 'Contact Us',
            'description' => 'Get in touch with our support team'
        ];
        
        return view('contact', $data);
    }
}