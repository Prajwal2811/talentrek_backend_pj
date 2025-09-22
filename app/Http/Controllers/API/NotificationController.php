<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Services\MobileAppNotificationService;

class NotificationController extends Controller
{
    protected $notifications;

    public function __construct(MobileAppNotificationService $notifications)
    {
        $this->notifications = $notifications;
    }

    public function notificationListByMCAJT($userType,$userId)
    {        
        try {
            $NotificationList  = $this->notifications->getNotifications($userId,$userType);        

            return response()->json([
                    'status' => true,
                    'message' => 'Notification List Fetch Successfully.',
                    'data' => $NotificationList
                ]);   

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch translation.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function notificationMarkReadByMCAJT($notificationId)
    {        
        try {
            $NotificationList  = $this->notifications->markAsRead($notificationId);        

            return response()->json([
                    'status' => true,
                    'message' => 'Notification mark as read Successfully.',
                    'data' => $NotificationList
                ]);   

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch translation.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }


}