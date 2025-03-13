<?php

namespace App\Http\Controllers;

use App\Models\User; // Menggunakan model User yang benar
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Data yang akan disimpan
        $data = [
            'username' => 'customer-1',
            'nama' => 'Pelanggan_satu',
            'password' => Hash::make('12345'),
            'level_id' => 1
        ];

        // Simpan data ke database
        UserModel::create($data);

        // Ambil semua user
        $users = UserModel::all();

        return view('user', ['data' => $users]);
        

    }
}