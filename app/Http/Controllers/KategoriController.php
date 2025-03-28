<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        //DB::table('m_kategori')->insert([
        //    'kategori_kode' => 'SNK',
        //    'kategori_nama' => 'Snack/Makanan Ringan',
        //    'created_at' => now(),
        //]);

        //return 'Insert data baru berhasil';

        //$row = DB::table('m_kategori')
        //    ->where('kategori_kode', 'SNK')
        //    ->update(['kategori_nama' => 'Camilan']);
//
        //return 'Update data berhasil. Jumlah data yang diupdate: ' . $row . ' baris';
        $data = DB::table('m_kategori')->get();
        return view('kategori', ['data' => $data]);
    }
}