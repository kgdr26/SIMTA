<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//middleware untuk mengecek role admin
class UserAkses
{
 /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // // Periksa apakah pengguna telah login
        // if (!session()->has('role')) {
        //     return redirect()->route('login');
        // }

        // return $next($request);

        if (Auth::check() && auth()->user()->role == $role) {
            return $next($request);
        }
        return response('Anda tidak memiliki akses', 403); // Menggunakan status kode 403 untuk akses ditolak
    }
}
