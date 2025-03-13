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
        // Tambah data user dengan Eloquent Model (sebenarnya ini update data)
        $data = [
            'nama' => 'Pelanggan Pertama',
        ];

        UserModel::where('username', 'customer-1')->update($data); // Update data user

        // Coba akses model User
        $users = User::all(); // Ambil semua data dari tabel m_user

        return view('user', ['data' => $users]);
    }
}