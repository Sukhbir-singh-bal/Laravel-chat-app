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
                        'messages.content',
                         DB::raw("CASE WHEN messages.user_id = $loggedInUserId THEN message_to ELSE user_id END AS idtoshow"),
                         DB::raw("CASE WHEN messages.user_id = $loggedInUserId THEN users_receiver.name ELSE users_sender.name END AS ChatWith"),
                    )
                    ->leftJoin('users as users_sender', 'messages.user_id', '=', 'users_sender.id')
                    ->leftJoin('users as users_receiver', 'messages.message_to', '=', 'users_receiver.id')
                    ->whereIn('messages.id', function ($query) use ($subquery) {
                        $query->fromSub($subquery, 'sub')
                            ->select('max_id');
                    })
                    ->orderBy('messages.id', 'desc')
                    ->get();
                    // ->toSql();
                    // return $chats;
                    // return response()->json($chats);
                return Inertia::render('Chat',["Chats"=> $chats,"UserDetails"=>$UserDetails]);
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
