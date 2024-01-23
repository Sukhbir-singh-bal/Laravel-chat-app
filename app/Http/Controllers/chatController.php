<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\MessageModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class chatController extends Controller
{
    public function loadChatDashboard(){
        if (Auth::check()) {
            $loggedInUserId = Auth::id();
            $subquery = DB::table('messages')
                ->select(DB::raw('MAX(id) as max_id'))
                ->where(function ($query) use ($loggedInUserId) {
                    $query->where('user_id', $loggedInUserId)
                        ->orWhere('message_to', $loggedInUserId);
                })
                ->groupBy('user_id', 'message_to');

                $chats = MessageModel::select(
                    'messages.id',
                    'messages.user_id',
                    'messages.content',
                    'messages.message_to',
                    'messages.HasSeen',
                    'messages.Seen_at',
                    DB::raw('CASE WHEN messages.user_id = ? THEN users_receiver.name  ELSE users_sender.name END as Sender'),
                    'users_sender.name as SenderName',
                    'users_receiver.name as ReciverName'
                )
                ->addBinding($loggedInUserId)
                ->leftJoin('users as users_sender', 'messages.user_id', '=', 'users_sender.id')
                ->leftJoin('users as users_receiver', 'messages.message_to', '=', 'users_receiver.id')
                ->whereIn('messages.id', function ($query) use ($subquery) {
                    $query->fromSub($subquery, 'sub')
                        ->select('max_id');
                })
                ->orderBy('messages.Created_at', 'desc')
                ->get();
            return Inertia::render('Chat',["Chats"=> $chats]);
        }
    }

    public function loadprivatechats($id){
        $userId = Auth::id();
        $UserInfo = User::Select("name")->where("id",$id)->get();
        $Messages = MessageModel::select('user_id','content','HasSeen','created_at','message_to')
                    ->where(function ($query) use ($userId, $id) {
                        $query->where('user_id', $userId)->where("message_to", $id);
                    })
                    ->orWhere(function ($query) use ($id, $userId) {
                        $query->where('user_id', $id)->where("message_to", $userId);
                    })
                    ->get(); 
        return response()->json(["UserInfo"=>$UserInfo,"Messages"=>$Messages]);
    }
}
