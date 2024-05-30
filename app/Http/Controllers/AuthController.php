<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
        ]);

        // Siapkan data login
        $infologin = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Coba untuk login
        if (Auth::attempt($infologin)) {
            // Ambil data pengguna yang sudah login
            $user = Auth::user();

            // Simpan informasi pengguna ke dalam session
            session([
                'user_id' => $user->id_users,
                'username' => $user->username,
                'role' => $user->role,
                'name' => $user->name,
                'email' => $user->email,
            ]);

            // Redirect berdasarkan role pengguna
            if ($user->role == 'admin') {
                return redirect('/admin');
            } elseif ($user->role == 'dosen') {
                return redirect('/dosen');
            } elseif ($user->role == 'mahasiswa') {
                return redirect('/mahasiswa');
            }
        } else {
            return redirect('/')->withErrors('Username dan Password Tidak Cocok')->withInput();
        }
    }

    public function logout()
    {
        // Hapus semua data dalam session
        session()->flush();
        
        // Logout pengguna
        Auth::logout();

        // Redirect ke halaman utama
        return redirect('/');
    }

    function loginAdmin()
    {
        // Periksa apakah pengguna telah login dan memiliki role admin
        if (session('role') === 'admin') {
            // Ambil semua pengguna kecuali admin yang sedang login
            $users = User::where('id_users', '!=' ,session('user_id'))->get(); 
            return view('admin.kelolaAkun', compact('users'));
        } else {
            // Jika tidak ada sesi atau pengguna bukan admin, redirect ke halaman login
            return redirect()->route('login');
        }
    }

    public function loginDosen()
    {
        // Periksa apakah pengguna telah login dan memiliki role dosen
        if (session('role') === 'dosen') {
            // Dapatkan data diri dosen yang sedang login
            $dosen = User::find(session('user_id'));
            return view('dosen.logBimbingan', compact('dosen'));
        } else {
            // Jika tidak ada sesi atau pengguna bukan dosen, redirect ke halaman login
            return redirect()->route('login');
        }
    }

    // public function loginMahasiswa()
    // {
    //     // Periksa apakah pengguna telah login dan memiliki role mahasiswa
    //     if (session('role') === 'mahasiswa') {
    //         // Dapatkan data diri mahasiswa yang sedang login
    //         $mahasiswa = User::find(session('user_id'));
    //         return view('mahasiswa.catatanMahasiswa', compact('mahasiswa'));
    //     } else {
    //         // Jika tidak ada sesi atau pengguna bukan mahasiswa, redirect ke halaman login
    //         return redirect()->route('login');
    //     }
    // }
}
