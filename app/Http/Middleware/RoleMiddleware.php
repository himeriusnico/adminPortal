<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles (e.g., 'admin', 'super_admin')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil user dan role-nya
        // Kita gunakan pengecekan aman untuk menghindari error jika role-nya null
        $userRoleName = Auth::user()->role->name ?? null;


        // 3. ========= DEBUGGING =========
        // Tampilkan variabelnya dan hentikan program
        // dd([
        //     'role_user_saat_ini' => $userRoleName,
        //     'role_yang_dibutuhkan_route_ini' => $roles,
        //     'apakah_cocok' => in_array($userRoleName, $roles)
        // ]);
        // ===============================


        // 4. Cek apakah role user ada di dalam daftar role yang diizinkan ($roles)
        foreach ($roles as $role) {
            if ($userRoleName === $role) {
                // 5. Jika diizinkan, lanjutkan request
                return $next($request);
            }
        }

        // 6. Jika tidak diizinkan, lempar error 403 (Forbidden)
        abort(403, 'UNAUTHORIZED ACCESS.');
    }
}
