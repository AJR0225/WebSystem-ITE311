<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Notifications extends Controller
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Get notifications for the current user
     * Returns JSON response with unread count and list of notifications
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface JSON response
     */
    public function get()
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in.',
                'unread_count' => 0,
                'notifications' => []
            ])->setStatusCode(401);
        }
        
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID not found.',
                'unread_count' => 0,
                'notifications' => []
            ])->setStatusCode(400);
        }
        
        // Check if user is admin - admins see all notifications
        $userRole = strtolower($session->get('user_role') ?? 'student');
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }
        
        if ($userRole === 'admin') {
            // Admin sees all notifications
            $unreadCount = $this->notificationModel->where('is_read', 0)->countAllResults();
            $notifications = $this->notificationModel->orderBy('created_at', 'DESC')->limit(10)->findAll();
        } else {
            // Regular users see only their own notifications
            $unreadCount = $this->notificationModel->getUnreadCount($userId);
            $notifications = $this->notificationModel->getNotificationsForUser($userId, 5);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read
     * Accepts notification ID via POST and marks it as read
     * 
     * @param int $id Notification ID
     * @return \CodeIgniter\HTTP\ResponseInterface JSON response
     */
    public function mark_as_read($id)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in.'
            ])->setStatusCode(401);
        }
        
        $userId = $session->get('user_id');
        $notificationId = (int) $id;
        
        if (!$notificationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid notification ID.'
            ])->setStatusCode(400);
        }
        
        // Verify the notification exists
        $notification = $this->notificationModel->find($notificationId);
        
        if (!$notification) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found.'
            ])->setStatusCode(404);
        }
        
        // Check if user is admin - admins can mark any notification as read
        $userRole = strtolower($session->get('user_role') ?? 'student');
        if ($userRole === 'teacher') {
            $userRole = 'instructor';
        }
        
        // Only allow if user owns the notification OR user is admin
        if ($userRole !== 'admin' && $notification['user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. This notification does not belong to you.'
            ])->setStatusCode(403);
        }
        
        // Mark as read
        if ($this->notificationModel->markAsRead($notificationId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark notification as read.'
            ])->setStatusCode(500);
        }
    }
}

