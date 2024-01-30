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
                $query->where(function ($query) use ($loggedInUserId) {
                        $query->where('user_id', $loggedInUserId)
                              ->orWhere('message_to', $loggedInUserId);
                    })
                    ->orWhere(function ($query) use ($loggedInUserId) {
                        $query->where('user_id', $loggedInUserId)
                              ->orWhere('message_to', $loggedInUserId);
                    });
            })
            ->groupBy(DB::raw('LEAST(user_id, message_to)'), DB::raw('GREATEST(user_id, message_to)'));
            
            $chats = MessageModel::select(
                    'messages.id',
                    'messages.user_id',
                    'messages.content',
                    'messages.message_to',
                    'messages.HasSeen',
                    'messages.Seen_at',
                    DB::raw('CASE WHEN messages.user_id = ? THEN users_receiver.name ELSE users_sender.name END as Sender'),
                    'users_sender.name as sender_name',
                    'users_receiver.name as receiver_name'
                )
                ->leftJoin('users as users_sender', 'messages.user_id', '=', 'users_sender.id')
                ->leftJoin('users as users_receiver', 'messages.message_to', '=', 'users_receiver.id')
                ->whereIn('messages.id', function ($query) use ($subquery) {
                    $query->fromSub($subquery, 'sub')
                        ->select('max_id');
                })
                ->addBinding($loggedInUserId)
                ->orderBy('messages.id', 'desc')
                ->get();
                // ->toSql();
                // return $chats;
                // return response()->json($chats);
                return Inertia::render('Chat',["Chats"=> $chats]);
            }
    }

    public function loadprivatechats($id){
        $userId = Auth::id();
        $Messages = MessageModel::select('messages.id','messages.user_id','messages.content','messages.HasSeen','messages.created_at','messages.message_to','users_sender.name as Sender','users_receiver.name as Reciver')
                    ->where(function ($query) use ($userId, $id) {
                        $query->where('user_id', $userId)->where("message_to", $id);
                    })
                    ->orWhere(function ($query) use ($id, $userId) {
                        $query->where('user_id', $id)->where("message_to", $userId);
                    })
                    ->leftJoin('users as users_sender', 'messages.user_id', '=', 'users_sender.id')
                     ->leftJoin('users as users_receiver', 'messages.message_to', '=', 'users_receiver.id')
                    ->orderBy('created_at','asc')
                    ->get(); 
        return response()->json(["Messages"=>$Messages]);
    }
}
