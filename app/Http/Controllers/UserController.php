<?php

namespace App\Http\Controllers;

use App\Models\User; // Menggunakan model User yang benar
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
    
        $users = UserModel::where('level_id', 2)->count();
        dd($users);

        return view('user', ['data' => $users]);
        

    }
}