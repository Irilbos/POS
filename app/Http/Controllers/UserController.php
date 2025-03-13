<?php

namespace App\Http\Controllers;

use App\Models\User; // Menggunakan model User yang benar
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
    
        $users = UserModel::findOr(20,['username','nama'],function () {
            abort(404);
        });

        return view('user', ['data' => $users]);
        

    }
}