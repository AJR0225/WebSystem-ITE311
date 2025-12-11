<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================
// PUBLIC ROUTES
// ============================================
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// ============================================
// AUTHENTICATION ROUTES
// ============================================
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// ============================================
// DASHBOARD ROUTE (Unified for all roles)
// ============================================
$routes->get('/dashboard', 'Auth::dashboard');

// ============================================
// ROLE-SPECIFIC ROUTES (Placeholder routes)
// These can be implemented later with dedicated controllers
// ============================================

// Admin Routes (Auth check is done in controller)
$routes->group('admin', function($routes) {
    $routes->get('announcement', 'Auth::announcement'); // Announcement management - GET
    $routes->post('announcement', 'Auth::announcement'); // Announcement management - POST (clear all)
    $routes->get('manage-user', 'Auth::manageUser'); // Manage users - GET
    $routes->post('manage-user', 'Auth::manageUser'); // Manage users - POST (add, edit, delete)
    $routes->get('create-user', 'Auth::createUser'); // Create new user - GET
    $routes->post('create-user', 'Auth::createUser'); // Create new user - POST
    $routes->get('users', 'Auth::dashboard'); // Placeholder - redirects to dashboard
    $routes->get('courses', 'Auth::dashboard'); // Placeholder - redirects to dashboard
    $routes->get('reports', 'Auth::dashboard'); // Placeholder - redirects to dashboard
    $routes->get('settings', 'Auth::dashboard'); // Placeholder - redirects to dashboard
});

// Instructor Routes (Auth check is done in controller)
$routes->group('instructor', function($routes) {
    $routes->get('courses', 'Auth::dashboard'); // Placeholder - redirects to dashboard
    $routes->get('students', 'Auth::dashboard'); // Placeholder - redirects to dashboard
    $routes->get('grades', 'Auth::dashboard'); // Placeholder - redirects to dashboard
    $routes->get('quizzes', 'Auth::dashboard'); // Placeholder - redirects to dashboard
});

// Student Routes (Auth check is done in controller)
$routes->group('student', function($routes) {
    $routes->get('courses', 'Auth::dashboard'); // Placeholder - redirects to dashboard
    $routes->get('enrollments', 'Auth::dashboard'); // Placeholder - redirects to dashboard
    $routes->get('grades', 'Auth::dashboard'); // Placeholder - redirects to dashboard
    $routes->get('assignments', 'Auth::dashboard'); // Placeholder - redirects to dashboard
});

// Profile Route
$routes->get('profile', 'Auth::dashboard'); // Placeholder - redirects to dashboard

// ============================================
// AUTO ROUTING (Enable for development)
// ============================================
$routes->setAutoRoute(true);