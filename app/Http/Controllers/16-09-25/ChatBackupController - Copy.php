<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\Coach;
use DB;
use Auth;
use App\Models\Mentors;
use App\Models\Trainers;
use App\Models\Jobseekers;
use App\Models\Recruiters;
use App\Models\TrainingExperience;
use App\Models\EducationDetails;
use App\Models\WorkExperience;
use App\Models\AdditionalInfo;
use App\Models\Review;
use App\Models\BookingSlotUnavailableDate;
use App\Models\BookingSession;
use App\Models\TrainingCategory;
use App\Models\BookingSlot;
use App\Models\Message;
use Carbon\Carbon;
use App\Events\MessageDeleted;
use App\Events\MessageSent;
use App\Events\MessageSeen;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\URL;

class ChatController extends Controller
{

    // ✅ Send a message
    // public function sendMessage(Request $request)
    // {
    //     $sender = $this->getSender();

    //     $message = Message::create([
    //         'sender_id' => $sender['id'],
    //         'sender_type' => $sender['type'],
    //         'receiver_id' => $request->receiver_id,
    //         'receiver_type' => $request->receiver_type,
    //         'message' => $request->message,
    //         'type' => 1
    //     ]);

    //     broadcast(new MessageSent($message))->toOthers();

    //     return response()->json([
    //         'id' => $message->id,
    //         'sender_id' => $message->sender_id,
    //         'sender_type' => $message->sender_type,
    //         'message' => $message->message,
    //         'created_at' => $message->created_at->toDateTimeString()
    //     ]);
    // }

      
    public function sendMessage(Request $request)
    {
        $sender = $this->getSender();

        $data = [
            'sender_id'    => $sender['id'],
            'sender_type'  => $sender['type'],
            'receiver_id'  => $request->receiver_id,
            'receiver_type'=> $request->receiver_type,
            'type'         => 1, // default text
            'message'      => $request->message
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Custom uploads folder
            $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            $data['message'] = url('uploads/' . $filename);
            $data['type'] = 2;
        }

        $message = Message::create($data);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id'          => $message->id,
            'sender_id'   => $message->sender_id,
            'sender_type' => $message->sender_type,
            'message'     => $message->message,
            'type'        => $message->type,
            'created_at'  => $message->created_at->toDateTimeString()
        ]);
    }



    private function getSender()
    {
        if (auth()->guard('jobseeker')->check()) 
        {
            return ['id' => auth()->guard('jobseeker')->id(), 'type' => 'jobseeker'];
        } 
        elseif (auth()->guard('trainer')->check()) 
        {
            return ['id' => auth()->guard('trainer')->id(), 'type' => 'trainer'];
        } 
        elseif (auth()->guard('mentor')->check()) 
        {
            return ['id' => auth()->guard('mentor')->id(), 'type' => 'mentor'];
        } 
        elseif (auth()->guard('coach')->check()) 
        {
            return ['id' => auth()->guard('coach')->id(), 'type' => 'coach'];
        }
        elseif (auth()->guard('assessor')->check()) 
        {
            return ['id' => auth()->guard('assessor')->id(), 'type' => 'assessor'];
        }
        elseif (auth()->guard('admin')->check()) 
        {
            return ['id' => auth()->guard('admin')->id(), 'type' => 'admin'];
        }

        return ['id' => null, 'type' => null];
    }


    // ✅ Get chat messages between any 2 parties
    // public function getMessages(Request $request)
    // {
    //     $sender = $this->getSender();

    //     if (!$sender['id'] || !$request->receiver_id || !$request->receiver_type) {
    //         return response()->json(['error' => 'Invalid data'], 422);
    //     }

    //     $messages = Message::where(function ($q) use ($sender, $request) {
    //         $q->where('sender_id', $sender['id'])
    //             ->where('sender_type', $sender['type'])
    //             ->where('receiver_id', $request->receiver_id)
    //             ->where('receiver_type', $request->receiver_type);
    //     })->orWhere(function ($q) use ($sender, $request) {
    //         $q->where('sender_id', $request->receiver_id)
    //             ->where('sender_type', $request->receiver_type)
    //             ->where('receiver_id', $sender['id'])
    //             ->where('receiver_type', $sender['type']);
    //     })->orderBy('created_at')->get();

    //     return response()->json($messages);
    // }
    public function getMessages(Request $request)
    {
        $sender = $this->getSender();

        if (!$sender['id'] || !$request->receiver_id || !$request->receiver_type) {
            return response()->json(['error' => 'Invalid data'], 422);
        }

        // ✅ Step 1: Mark as read (unread → read)
        Message::where('sender_id', $request->receiver_id)
            ->where('sender_type', $request->receiver_type)
            ->where('receiver_id', $sender['id'])
            ->where('receiver_type', $sender['type'])
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        // ✅ Step 2: Fetch conversation messages
        $messages = Message::where(function ($q) use ($sender, $request) {
                $q->where('sender_id', $sender['id'])
                    ->where('sender_type', $sender['type'])
                    ->where('receiver_id', $request->receiver_id)
                    ->where('receiver_type', $request->receiver_type);
            })
            ->orWhere(function ($q) use ($sender, $request) {
                $q->where('sender_id', $request->receiver_id)
                    ->where('sender_type', $request->receiver_type)
                    ->where('receiver_id', $sender['id'])
                    ->where('receiver_type', $sender['type']);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }



    public function delete(Request $request)
    {
        $message = Message::find($request->id);

        $sender = $this->getSender();

        if ($message && $message->sender_id == $sender['id'] && $message->sender_type == $sender['type']) {
            $receiverId = $message->receiver_id;
            $message->delete();

            broadcast(new MessageDeleted($request->id, $receiverId))->toOthers();

            return response()->json(['status' => 'deleted']);
        }

    }



    public function sendGroupMessage(Request $request)
    {
        $sender = $this->getSender(); // returns ['id' => ..., 'type' => ...]

        if (!$sender['id'] || (!$request->message && !$request->hasFile('file'))) {
            return response()->json(['error' => 'Message or file required'], 422);
        }

        $receiverId = 0;
        $receiverType = 'group';

        if ($sender['type'] === 'admin') {
            if (!$request->receiver_id || !$request->receiver_type) {
                return response()->json(['error' => 'Receiver ID and type required for admin'], 422);
            }
            $receiverId = $request->receiver_id;
            $receiverType = $request->receiver_type;
        }

        $data = [
            'sender_id' => $sender['id'],
            'sender_type' => $sender['type'],
            'receiver_id' => $receiverId,
            'receiver_type' => $receiverType,
            'type' => 1,
            'message' => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'chat_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            $data['message'] = URL::to('uploads/' . $filename);
            $data['type'] = 2;
        }

        $messageId = DB::table('admin_group_chats')->insertGetId($data);

        // Fetch the inserted message to return
        $message = DB::table('admin_group_chats')->where('id', $messageId)->first();

        broadcast(new MessageSent((object)$message))->toOthers();

        return response()->json($message);
    }


    public function fetchGroupMessages(Request $request)
    {
        $user = $this->getSender(); 
        $selectedUserId = $request->receiver_id ?? 0;
        $selectedUserType = $request->receiver_type ?? null;

        $query = DB::table('admin_group_chats');

        if ($user['type'] == 'admin') {
            $query->where(function($q) use ($selectedUserId, $selectedUserType) {
                $q->where(function($q2) use ($selectedUserId, $selectedUserType) {
                    $q2->where('sender_id', $selectedUserId)
                    ->where('sender_type', $selectedUserType);
                })->orWhere(function($q3) use ($selectedUserId, $selectedUserType) {
                    $q3->where('receiver_id', $selectedUserId)
                    ->where('receiver_type', $selectedUserType);
                });
            });
        } else {
            // Mentor ya other users ke liye
            $query->where(function($q) use ($user) {
                $q->where('receiver_id', $user['id'])
                ->where('receiver_type', $user['type'])
                ->orWhere('sender_id', $user['id'])
                ->where('sender_type', $user['type']);
            });
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }


    public function markMessagesAsRead(Request $request)
    {
        $user = $this->getSender(); // admin

        // Update messages where receiver is admin and sender is selected jobseeker
        DB::table('admin_group_chats')
            ->where('sender_id', $request->receiver_id)      
            ->where('sender_type', $request->receiver_type)   
            ->where('receiver_type', 'group')   
            ->where('is_read', 0)
            ->update(['is_read' => 1]);


        // Fetch updated messages to check
        $updated = DB::table('admin_group_chats')
            ->where('sender_id', $request->receiver_id)
            ->where('sender_type', $request->receiver_type)
            ->where('receiver_id', $user['id'])
            ->where('receiver_type', $user['type'])
            ->get();

        // Broadcast real-time "seen"
        broadcast(new MessageSeen(
            $request->receiver_id, 
            $request->receiver_type, 
            $user['id'], 
            $user['type']
        ))->toOthers();

        return response()->json([
            'status' => 'seen updated',
            'updated_messages' => $updated
        ]);
    }


    public function getJobseekersList()
    {
        $jobseekers = DB::table('jobseekers as j')
            ->select(
                'j.id as user_id',
                'j.name as jobseeker_name',
                DB::raw('COUNT(talentrek_m.id) as unread_count')
            )
            ->leftJoin('admin_group_chats as m', function ($join) {
                $join->on('j.id', '=', 'm.sender_id')
                    ->where('m.sender_type', '=', 'jobseeker')   
                    ->where('m.receiver_type', '=', 'group')    
                    ->where('m.is_read', '=', 0);               
            })
            ->where('j.status', '=', 'active')
            ->groupBy('j.id', 'j.name')
            ->get();

        return response()->json($jobseekers);
    }

    public function getMentorsList()
    {
        $mentors = DB::table('mentors as j')
            ->select(
                'j.id as user_id',
                'j.name as mentor_name',
                DB::raw('COUNT(talentrek_m.id) as unread_count')
            )
            ->leftJoin('admin_group_chats as m', function ($join) {
                $join->on('j.id', '=', 'm.sender_id')
                    ->where('m.sender_type', '=', 'mentor')   
                    ->where('m.receiver_type', '=', 'group')    
                    ->where('m.is_read', '=', 0);               
            })
            ->where('j.status', '=', 'active')
            ->groupBy('j.id', 'j.name')
            ->get();
         
        return response()->json($mentors);
    }

    public function getAssessorsList()
    {
        $assessors = DB::table('assessors as j')
            ->select(
                'j.id as user_id',
                'j.name as assessor_name',
                DB::raw('COUNT(talentrek_m.id) as unread_count')
            )
            ->leftJoin('admin_group_chats as m', function ($join) {
                $join->on('j.id', '=', 'm.sender_id')
                    ->where('m.sender_type', '=', 'assessor')   
                    ->where('m.receiver_type', '=', 'group')    
                    ->where('m.is_read', '=', 0);            
                                  
            })
            ->where('j.status', '=', 'active')
            ->groupBy('j.id', 'j.name')
            ->get();
         
        return response()->json($assessors);
    }

    public function getCoachesList()
    {
        $coaches = DB::table('coaches as j')
            ->select(
                'j.id as user_id',
                'j.name as coach_name',
                DB::raw('COUNT(talentrek_m.id) as unread_count')
            )
            ->leftJoin('admin_group_chats as m', function ($join) {
                $join->on('j.id', '=', 'm.sender_id')
                    ->where('m.sender_type', '=', 'coach')   
                    ->where('m.receiver_type', '=', 'group')    
                    ->where('m.is_read', '=', 0);               
            })
            ->where('j.status', '=', 'active')
            ->groupBy('j.id', 'j.name')
            ->get();
         
        return response()->json($coaches);
    }

 
    // public function getUnreadCounts(){
    //     $userId = auth()->guard('jobseeker')->id();
    //     $userType = 'jobseeker';
    //     $counts = DB::table('messages')
    //         ->select('sender_id','sender_type', DB::raw('COUNT(*) as unread_count'))
    //         ->where('receiver_id',$userId)
    //         ->where('receiver_type',$userType)
    //         ->where('is_read',0)
    //         ->groupBy('sender_id','sender_type')->get();
    //     return response()->json($counts);

    // }

    // public function markAsRead(Request $request){
    //     DB::table('messages')
    //         ->where('sender_id',$request->receiver_id)
    //         ->where('sender_type',$request->receiver_type)
    //         ->where('receiver_id',auth()->guard('jobseeker')->id())
    //         ->where('receiver_type','jobseeker')
    //         ->where('is_read',0)
    //         ->update(['is_read'=>1]);
    //     return response()->json(['status'=>'success']);
    // }


  public function getUnreadCounts()
    {
        $userId = auth()->guard('jobseeker')->id();
        $userType = 'jobseeker';

        // Normal chat
        $normalCounts = DB::table('messages')
            ->select('sender_type', DB::raw('COUNT(*) as unread_count'))
            ->where('receiver_id', $userId)
            ->where('receiver_type', $userType)
            ->where('is_read', 0)
            ->groupBy('sender_type')
            ->pluck('unread_count', 'sender_type');
        
        // Admin chat
        $adminCount = DB::table('admin_group_chats')
            ->where('receiver_id', $userId)
            ->where('receiver_type', $userType)
            ->where('is_read', 0)
            ->count();

        return response()->json([
            'trainer'   => $normalCounts['trainer']   ?? 0,
            'mentor'    => $normalCounts['mentor']    ?? 0,
            'coach'     => $normalCounts['coach']     ?? 0,
            'assessor'  => $normalCounts['assessor']  ?? 0,
            'admin'     => $adminCount,
        ]);
    }

    // ---------------- Mark messages as read
    public function markAsRead(Request $request)
    {
        DB::table('messages')
            ->where('sender_id', $request->receiver_id)
            ->where('sender_type', $request->receiver_type)
            ->where('receiver_id', auth()->guard('jobseeker')->id())
            ->where('receiver_type', 'jobseeker')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        DB::table('admin_group_chats')
            ->where('receiver_id', auth()->guard('jobseeker')->id())
            ->where('receiver_type', 'jobseeker')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json(['status' => 'success']);
    }
}
