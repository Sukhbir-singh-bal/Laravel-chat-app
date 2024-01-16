<?php

namespace App\Http\Controllers;

use App\Models\MessageModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class chatController extends Controller
{
    public function loadChats(){
        if (Auth::check()) {
            // User is authenticated
            $userId = auth('api')->user()->id;
            $SendMessages = MessageModel::select('user_id','content','HasSeen','created_at','message_to')->where('user_id',$userId)->get(); 
            $RecvedMessages = MessageModel::select('user_id','content','HasSeen','created_at','message_to')->where('message_to',$userId)->get(); 
            $Chats = ["SendMessages"=>$SendMessages,"RecvedMessages"=>$RecvedMessages];
            return response()->json($Chats);
        }else{
            return response()->json(["Please Login to load chats"]);
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
