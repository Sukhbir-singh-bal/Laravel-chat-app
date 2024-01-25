<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\MessageModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
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
