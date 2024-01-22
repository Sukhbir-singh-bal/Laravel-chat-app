<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\MessageModel;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class chatController extends Controller
{
    public function loadChatDashboard(){
        if (Auth::check()) {
            $loggedInUserId = Auth::id();
            $chats = MessageModel::join('users', 'messages.message_to', '=', 'users.id')
            ->where(function ($query) use ($loggedInUserId) {
                $query->Where('messages.message_to', $loggedInUserId)
                ->orwhere('messages.user_id', $loggedInUserId);
            })
            ->select('users.name','messages.user_id','messages.message_to', 'messages.content', 'messages.HasSeen', 'messages.Seen_at')
            ->orderBy('messages.HasSeen', 'asc') 
            ->orderBy('messages.Seen_at', 'desc')
            ->get();
            return Inertia::render('Chat',["Chats"=> $chats]);
        }
    }

    public function loadprivatechats($id){
        $userId = Auth::id();
        $Messages = MessageModel::select('user_id','content','HasSeen','created_at','message_to')
                    ->where(function ($query) use ($userId, $id) {
                        $query->where('user_id', $userId)->where("message_to", $id);
                    })
                    ->orWhere(function ($query) use ($id, $userId) {
                        $query->where('user_id', $id)->where("message_to", $userId);
                    })
                    ->get(); 
        return response()->json($Messages);
    }
}
