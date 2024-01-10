<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function filterUsers($searchText){
        $users = User::select('id','name','email')->where('name','like',"%{$searchText}%")->get(); 
        return response()->json($users);
    }
}
