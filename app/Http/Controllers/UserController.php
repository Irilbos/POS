<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUser($id, $name)
    {
        return view('user.profile', compact('id', 'name'));
    }
}