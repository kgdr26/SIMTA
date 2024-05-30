<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Models\DosenPembimbing;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller //ini konroller untuk admin setelah login dalam menampilkan halaman kelola akun pengguna dan melakukan CRUD disatuin
{

    // ini buat menu kelola akun
    function fetchUsers()
    {
        // Fetch users excluding the user with id 1
        $users = User::where('id_users', '!=', 1)->get();
        return response()->json($users);
    }

    function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required', // Menambahkan validasi 'username
            'email' => 'required',
            'role' => 'required',
            'password' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username, // Menambahkan 'username'
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password)
            ]);

            if ($user->role == 'admin') {
                Admin::create([
                    'nip' => $user->username,
                    'id_users' => $user->id_users,
                ]);
            } else if ($user->role == 'dosen') {
                Dosen::create([
                    'nip' => $user->username,
                    'id_users' => $user->id_users,
                ]);
            } else if ($user->role == 'mahasiswa') {
                Mahasiswa::create([
                    'nim' => $user->username,
                    'id_users' => $user->id_users,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menambahkan data pengguna',
                'error' => $e->getMessage()
            ], 500);
        }

        //lebih rentan kena injeksi
        // $user = new User;
        // $user->name = $request->name;
        // $user->username = $request->username; // Menambahkan 'username'
        // $user->email = $request->email;
        // $user->role = $request->role;
        // $user->password = Hash::make($request->password);

        //simpan data user ke dalam database
        // $user->save();

        return response()->json([
            'message' => 'Data pengguna berhasil ditambahkan',
        ], 200);
    }

    function EditUser($id)
    {
        $user = User::where('id_users', $id)->first();
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    function updateUser(Request $request, $id)
    {
        // Ambil data pengguna yang akan diperbarui
        $user = User::where('id_users', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validasi input
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id . ',id_users',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'nullable|min:6',
            'nim' => 'sometimes|nullable|unique:mahasiswa,nim,' . $id . ',id_users',
            'nip' => 'sometimes|nullable|unique:dosen,nip,' . $id . ',id_users',
            'nip' => 'sometimes|nullable|unique:admin,nip,' . $id . ',id_users',
        ]);

        try {
            DB::beginTransaction();

            // Jika password diisi, maka lakukan hash dan masukkan ke dalam data pembaruan
            if ($request->filled('password')) {
                $request['password'] = Hash::make($request->password);
            } else {
                unset($request['password']);
            }

            // Perbarui data pengguna
            $user->update(array_merge($request->all()));

            if ($user->role == 'admin') {
                if ($request->filled('nip')) {
                    Admin::where('id_users', $id)->update([
                        'nip' => $request->nip
                    ]);
                }
            } else if ($user->role == 'dosen') {
                if ($request->filled('nip')) {
                    Dosen::where('id_users', $id)->update([
                        'nip' => $request->nip
                    ]);
                }
            } else if ($user->role == 'mahasiswa') {
                if ($request->filled('nim')) {
                    Mahasiswa::where('id_users', $id)->update([
                        'nim' => $request->nim
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data pengguna berhasil diperbarui'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memperbarui data pengguna',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    function deleteUser($id)
    {

        try {
            DB::beginTransaction();
            $user = User::where('id_users', $id)->first();
            Dosen::where('id_users', $id)->delete();
            Mahasiswa::where('id_users', $id)->delete();
            Admin::where('id_users', $id)->delete();
            $user->delete();
            DB::commit();
            return response()->json([
                'message' => 'Data pengguna berhasil dihapus'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus data pengguna',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //kelola role dosen
    public function viewKelolaDosen()
    {
        $admin = Admin::with('user')->get();
        $dosens = Dosen::with('user')->get();
        $mahasiswaNotAssigned = Mahasiswa::doesntHave('dosenPembimbings')->with('user')->get();
        $mahasiswas = Mahasiswa::with('dosenPembimbings')->get();
        
        return view('admin.kelolaDosen', compact('admin', 'dosens', 'mahasiswas', 'mahasiswaNotAssigned'));
    }

    public function storeRoleDosen(Request $request)
    {
        try {

            $request->validate([
                'dosen' => 'required|array',
                'dosen.*.tipe' => 'required|in:pembimbing1,pembimbing2,penguji1,penguji2,penguji3',
            ]);

            // Find the Mahasiswa to update the dosen roles
            $mahasiswa = Mahasiswa::findOrFail($request->mahasiswa_id);

            // Delete existing dosen roles for the mahasiswa
            $mahasiswa->dosenPembimbings()->delete();

            foreach ($request->dosen as $dosen) {
                DosenPembimbing::create([
                    'id_mahasiswa' => $request->mahasiswa_id,
                    'id_dosen' => $dosen['dosen_id'],
                    'tipe' => $dosen['tipe'],
                ]);
            }

            return response()->json(['message' => 'Roles stored successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function editRoleDosen($id)
    {
        try {
            $mahasiswa = Mahasiswa::with('dosenPembimbings','user')->findOrFail($id);
            $dosens = Dosen::with('user')->get();

            return response()->json(['mahasiswa' => $mahasiswa, 'dosens' => $dosens]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    
}
