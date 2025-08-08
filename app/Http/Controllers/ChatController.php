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
use Illuminate\Support\Facades\Broadcast;


class ChatController extends Controller
{

    // ✅ Send a message
    public function sendMessage(Request $request)
    {
        $sender = $this->getSender();

        $message = Message::create([
            'sender_id' => $sender['id'],
            'sender_type' => $sender['type'],
            'receiver_id' => $request->receiver_id,
            'receiver_type' => $request->receiver_type,
            'message' => $request->message,
            'type' => 1
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'sender_type' => $message->sender_type,
            'message' => $message->message,
            'created_at' => $message->created_at->toDateTimeString()
        ]);
    }

    private function getSender()
    {
        if (auth()->guard('jobseeker')->check()) {
            return ['id' => auth()->guard('jobseeker')->id(), 'type' => 'jobseeker'];
        } elseif (auth()->guard('trainer')->check()) {
            return ['id' => auth()->guard('trainer')->id(), 'type' => 'trainer'];
        } elseif (auth()->guard('coach')->check()) {
            return ['id' => auth()->guard('coach')->id(), 'type' => 'coach'];
        }

        return ['id' => null, 'type' => null];
    }


    // ✅ Get chat messages between any 2 parties
    public function getMessages(Request $request)
    {
        $sender = $this->getSender();

        if (!$sender['id'] || !$request->receiver_id || !$request->receiver_type) {
            return response()->json(['error' => 'Invalid data'], 422);
        }

        $messages = Message::where(function ($q) use ($sender, $request) {
            $q->where('sender_id', $sender['id'])
                ->where('sender_type', $sender['type'])
                ->where('receiver_id', $request->receiver_id)
                ->where('receiver_type', $request->receiver_type);
        })->orWhere(function ($q) use ($sender, $request) {
            $q->where('sender_id', $request->receiver_id)
                ->where('sender_type', $request->receiver_type)
                ->where('receiver_id', $sender['id'])
                ->where('receiver_type', $sender['type']);
        })->orderBy('created_at')->get();

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

}
