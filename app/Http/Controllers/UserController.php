<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'user'],
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem',
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all(); // ambil data level untuk filter level

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu,
        ]);
    }
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        if ($request->has('level_id') && $request->filled('level_id')) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            // Menambahkan indeks nomor otomatis (default: DT_RowIndex)
            ->addIndexColumn()
            // Menambahkan kolom aksi (edit, detail, hapus)
            ->addColumn('aksi', function ($user) {
                $btn = '<a href="' . url('/user/' . $user->user_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/user/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" aksi="' . url('/user/' . $user->user_id) . '">'
                    . csrf_field()
                    . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\');">
                    Delete
                </button>
            </form>';

                return $btn;
            })
            // Memberitahu DataTables bahwa kolom 'action' berisi HTML
            ->rawColumns(['aksi'])
            ->make(true);
    }
    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // Ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // Set menu yang sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username', // Username harus unik, minimal 3 karakter
            'nama' => 'required|string|max:100', // Nama harus diisi, maksimal 100 karakter
            'password' => 'required|min:5', // Password minimal 5 karakter
            'level_id' => 'required|integer' // Level ID harus berupa angka
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), // Enkripsi password sebelum disimpan
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    

    //menampilkan detail user
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user'; // Set menu yang sedang aktif

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store_ajax(Request $request)
{
    // Check if the request is an AJAX or JSON request
    if ($request->ajax() || $request->wantsJson()) {
        // Define validation rules
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:6',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return a JSON error response
        if ($validator->fails()) {
            return response()->json([
                'status' => false, // Indicate failure
                'message' => 'Validasi Gagal', // Validation Failed message
                'msgField' => $validator->errors(), // Validation error messages
            ], 422); // Use HTTP status code 422 for Unprocessable Entity (validation errors)
        }

        // If validation passes, create a new user
        try {
            UserModel::create($request->all());

            // Return a JSON success response
            return response()->json([
                'status' => true, // Indicate success
                'message' => 'Data user berhasil disimpan', // User data successfully saved message
            ]);
        } catch (\Exception $e) {
            // Handle potential database errors
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data user: ' . $e->getMessage(),
            ], 500); // Use HTTP status code 500 for Internal Server Error
        }
    }

    // If the request is not AJAX or JSON, redirect to a specific route
    return redirect('/'); // You might want to adjust the redirection route
}
    // Menampilkan halaman form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user'; // Set menu yang sedang aktif

        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    public function create_ajax()
    {
        $level = LevelModel::select('level_id','level_nama')->get();

        return view('user.create_ajax')
                    ->with('level',$level);
    }

    

    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        $request->validate([
            // Username harus diisi, minimal 3 karakter, dan unik kecuali untuk user yang sedang diedit
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            // Nama harus diisi, berupa string, dan maksimal 100 karakter
            'nama' => 'required|string|max:100',
            // Password harus diisi minimal 5 karakter, bisa tidak diisi
            'password' => 'nullable|min:5',
            // Level ID harus diisi dan berupa angka
            'level_id' => 'required|integer'
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    // Menghapus data user
    public function destroy(string $id)
    {
        $check = UserModel::find($id);

        // Mengecek apakah data user dengan ID yang dimaksud ada atau tidak
        if (!$check) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            UserModel::destroy($id); // Hapus data user

            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan pesan error
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
