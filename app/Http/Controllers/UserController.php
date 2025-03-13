<?php

namespace App\Http\Controllers;

use App\Models\User; // Menggunakan model User yang benar
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
    
        $users = UserModel::firstOrCreate(
            [
                'username'=>'manager33',
                'nama'=> 'manager Tiga Tiga ',
                'password'=>Hash::make('12345'),
                'level_id'=> 2  
            ],
        );
        $users->save();

        return view('user', ['data' => $users]);
        

    }
}