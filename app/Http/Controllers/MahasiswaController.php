<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\LogBimbingan;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;


class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Periksa apakah pengguna telah login dan memiliki role admin
        if (session('role') === 'mahasiswa') {
            // Ambil semua pengguna kecuali admin yang sedang login
            $users = User::where('id_users', '!=' ,session('user_id'))->get(); 
            $dosen = Dosen::with('user')->get();
            $log = LogBimbingan::with('dosen.user')->get();
            return view('mahasiswa.catatanMahasiswa', compact('users','dosen','log'));
        } else {
            // Jika tidak ada sesi atau pengguna bukan admin, redirect ke halaman login
            return redirect()->route('login');
        }
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validasi data yang diterima untuk catatan bimbingan
        try {
            $request->validate([
                'dosen' => 'required',
                'tanggal_bimbingan' => 'required|date',
                'catatan_bimbingan' => 'required',
                'action_plan' => 'required',
            ]);

            // Buat catatan bimbingan baru
            $catatan = new LogBimbingan();
            $catatan->user_id = session('user_id');
            $catatan->dosen_id = $request->input('dosen');
            $catatan->tanggal_bimbingan = $request->input('tanggal_bimbingan');
            $catatan->catatan_bimbingan = $request->input('catatan_bimbingan');
            $catatan->action_plan = $request->input('action_plan');
            $catatan->status = 'submitted';
            $catatan->save();

            // Kirim respon ke AJAX
            return response()->json(['message' => 'Catatan bimbingan berhasil disimpan.']);
        } catch (Exception $e) {
            // Tangani pengecualian
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan catatan bimbingan.'], 500);
        }
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     //
    // }
}
