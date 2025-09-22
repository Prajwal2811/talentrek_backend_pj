<?php

namespace App\Services;

use App\Models\Notification;

class MobileAppNotificationService
{
    /**
     * Add new notification
     */
    public function addNotification(array $data)
    {
        return Notification::create([
            'sender_id'     => $data['sender_id'] ?? null,
            'sender_type'   => $data['sender_type'] ?? null,
            'receiver_id'   => $data['receiver_id'] ?? null,
            'message'       => $data['message'] ?? '',
            'is_read'       => null,
            'is_read_users'  => 0 ,
            'user_type'     => $data['user_type'] ?? null,
        ]);
    }

    /**
     * Add Admin new notification
     */
    public function addAdminNotification(array $data)
    {
        return Notification::create([
            'sender_id'     => $data['sender_id'] ?? null,
            'sender_type'   => $data['sender_type'] ?? null,
            'receiver_id'   => $data['receiver_id'] ?? null,
            'message'       => $data['message'] ?? '',
            'is_read'       => 0,
            'is_read_user'  => '' ,
            'user_type'     => $data['user_type'] ?? null,
        ]);
    }

    /**
     * Fetch notifications for a user
     */
    public function getNotifications($UserId, $userType = null, $limit = 20)
    {
        $query = Notification::where('sender_id', $UserId)
                             ->orderBy('created_at', 'desc');

        if ($userType) {
            $query->where('user_type', $userType);
        }

        return $query->take($limit)->get();
    }

    /**
     * Mark notifications as read
     */
    public function markAsRead($NotificationId)
    {
        $query = Notification::where('id', $NotificationId);
        return $query->update(['is_read_user' => 1]);
    }
}