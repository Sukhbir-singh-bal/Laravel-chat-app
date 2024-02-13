<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\MessageModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PhpParser\Node\Expr\Print_;

class chatController extends Controller
{
    public function loadChatDashboard(){
        if (Auth::check()) {
            $loggedInUser = Auth::user();
            $loggedInUserId = $loggedInUser->id;
            $UserDetails = User::where('id',$loggedInUser->id)->get();
            $chats = MessageModel::select([
                'id',
                'user_id',
                'message_to',
                'content',
                'seen_at',
                DB::raw("MAX(created_at) as latest_message_date"),
                DB::raw("CASE WHEN user_id = $loggedInUserId THEN message_to ELSE user_id END AS idtoshow")
            ])
            ->where(function ($query) use ($loggedInUserId) {
                $query->where('user_id', $loggedInUserId)
                    ->orWhere('message_to', $loggedInUserId);
            })
            ->groupBy('id', 'user_id', 'message_to', 'content', 'seen_at', 'idtoshow')
            ->orderBy('latest_message_date', 'desc')
            ->get();
                return response()->json($chats);
                // return Inertia::render('Chat',["Chats"=> $chats,"UserDetails"=>$UserDetails]);
            }
    }

    public function loadprivatechats($id){
        $userId = Auth::id();
        $user1 = User::find($id);
        $user2 = User::find($userId);
        $messagesSentByUser1 = $user1->sentMessages()->where('message_to', $user2->id)->get();
        $messagesReceivedByUser1 = $user1->receivedMessages()->where('user_id', $user2->id)->get();
        $allMessagesBetweenUsers = $messagesSentByUser1->merge($messagesReceivedByUser1)->sortBy('Created_at');
        return response()->json(["Messages"=>$allMessagesBetweenUsers]);
    }

    public function StoreNewMessage(Request $request){
        $userId = Auth::id();
        $this->validate($request, [
            'message' => 'required',
            'Receiver' => 'required',
        ]);
        $receiverID = $request["Receiver"];
        $message = $request["message"];
        $newMessage = MessageModel::create([
            'user_id' => $userId,
            'content' => $message,
            'message_to' => $receiverID,
        ]);

        return response()->json($newMessage);
    }
}
