# Testing Guide for Unified Dashboard System

## Overview
This guide will help you test the unified dashboard system with role-based access control.

## Prerequisites
1. Database migrations have been run
2. Test users have been seeded (or manually created)

## Step 1: Create Test Users

### Option A: Using Seeder (Recommended)
Run the seeder to create test users:
```bash
php spark db:seed UserSeeder
```

### Option B: Manual SQL Insert
You can also manually insert test users using SQL:
```sql
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin User', 'admin@lms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('John Smith', 'john.smith@lms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', NOW(), NOW()),
('Alice Brown', 'alice.brown@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW());
```

**Note:** The password hash above is for 'password123'. For the seeder, passwords are:
- Admin: `admin123`
- Instructor: `instructor123`
- Student: `student123`

## Step 2: Test User Login and Dashboard Access

### Test Case 1: Admin User Login
1. Navigate to `/login`
2. Enter credentials:
   - Email: `admin@lms.com`
   - Password: `admin123`
3. Click "Login"
4. **Expected Result:**
   - Redirected to `/dashboard`
   - Dashboard shows "Welcome, admin user!"
   - Role badge displays "Admin"
   - Admin-specific content visible:
     - Total Users statistic
     - Total Courses statistic
     - Total Enrollments statistic
     - Users by Role section
     - Recent Courses section
     - Enrollments by Status section
   - Navigation bar shows:
     - Dashboard
     - Users (admin only)
     - Courses (admin only)
     - Reports (admin only)
     - Settings (admin only)

### Test Case 2: Instructor User Login
1. Navigate to `/login`
2. Enter credentials:
   - Email: `john.smith@lms.com`
   - Password: `instructor123`
3. Click "Login"
4. **Expected Result:**
   - Redirected to `/dashboard`
   - Dashboard shows "Welcome, john smith!"
   - Role badge displays "Instructor"
   - Instructor-specific content visible:
     - My Courses count
     - My Students count
     - My Courses list
     - My Students list
   - Navigation bar shows:
     - Dashboard
     - My Courses (instructor only)
     - Students (instructor only)
     - Grades (instructor only)
     - Quizzes (instructor only)

### Test Case 3: Student User Login
1. Navigate to `/login`
2. Enter credentials:
   - Email: `alice.brown@student.com`
   - Password: `student123`
3. Click "Login"
4. **Expected Result:**
   - Redirected to `/dashboard`
   - Dashboard shows "Welcome, alice brown!"
   - Role badge displays "Student"
   - Student-specific content visible:
     - Enrolled Courses count
     - Available Courses count
     - My Enrolled Courses list
     - Available Courses list
   - Navigation bar shows:
     - Dashboard
     - My Courses (student only)
     - Enrollments (student only)
     - Grades (student only)
     - Assignments (student only)

## Step 3: Test Access Control

### Test Case 4: Unauthorized Dashboard Access
1. Clear browser cookies/session
2. Navigate directly to `/dashboard` (without logging in)
3. **Expected Result:**
   - Redirected to `/login`
   - Error message: "Please login to access the dashboard."

### Test Case 5: Role-Based Navigation
1. Log in as Admin
2. Check navigation bar - should show admin-specific items
3. Log out
4. Log in as Student
5. Check navigation bar - should NOT show admin items
6. **Expected Result:**
   - Each role sees only their appropriate navigation items
   - No cross-role access to menu items

## Step 4: Test Logout Functionality

### Test Case 6: Logout and Session Clear
1. Log in as any user
2. Click "Logout" button
3. **Expected Result:**
   - Redirected to `/login`
   - Session is cleared
   - Cannot access `/dashboard` without logging in again

## Step 5: Test Dashboard Content Display

### Test Case 7: Role-Specific Data Display
1. Log in as Admin
2. Verify admin statistics are displayed
3. Log out and log in as Instructor
4. Verify instructor-specific data is displayed
5. Log out and log in as Student
6. Verify student-specific data is displayed
7. **Expected Result:**
   - Each role sees only their relevant data
   - No data leakage between roles
   - Statistics match the user's role

## Step 6: Test Database Queries

### Test Case 8: Verify Database Data Fetching
1. Log in as Admin
2. Check if statistics match actual database counts
3. Log in as Instructor
4. Verify "My Courses" shows only courses where instructor_id matches
5. Log in as Student
6. Verify "Enrolled Courses" shows only courses where student is enrolled
7. **Expected Result:**
   - All database queries return correct role-specific data
   - No unauthorized data access

## Step 7: Test Responsive Design

### Test Case 9: Dashboard Layout
1. Log in as any user
2. Verify dashboard is in landscape layout
3. Check dark gradient black theme
4. Verify user info panel on left side
5. Verify main content on right side
6. **Expected Result:**
   - Landscape-oriented layout
   - Dark gradient black background
   - Proper two-column layout
   - All content visible and accessible

## Troubleshooting

### Issue: Users not redirecting to dashboard
**Solution:** Check Routes.php has `$routes->get('/dashboard', 'Auth::dashboard');`

### Issue: Wrong role content displayed
**Solution:** Verify session stores correct role: `$session->get('user_role')`

### Issue: Database queries failing
**Solution:** Ensure migrations are run and models are properly configured

### Issue: Navigation items not showing
**Solution:** Check template.php navigation conditional logic

## Test Checklist

- [ ] Admin can login and see admin dashboard
- [ ] Instructor can login and see instructor dashboard
- [ ] Student can login and see student dashboard
- [ ] All users redirect to same `/dashboard` URL
- [ ] Dashboard shows role-specific content
- [ ] Navigation bar shows role-specific items
- [ ] Unauthorized access redirects to login
- [ ] Logout clears session properly
- [ ] Database queries return correct data
- [ ] Landscape layout displays correctly
- [ ] Dark gradient theme is applied

## Test Users Summary

| Role | Email | Password | Name |
|------|-------|----------|------|
| Admin | admin@lms.com | admin123 | Admin User |
| Instructor | john.smith@lms.com | instructor123 | John Smith |
| Instructor | sarah.johnson@lms.com | instructor123 | Sarah Johnson |
| Student | alice.brown@student.com | student123 | Alice Brown |
| Student | bob.wilson@student.com | student123 | Bob Wilson |
| Student | carol.davis@student.com | student123 | Carol Davis |

## Notes

- All passwords are case-sensitive
- Session data persists until logout or timeout
- Dashboard uses unified URL for all roles
- Role-based content is determined server-side for security

