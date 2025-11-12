<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User;

class AuthController extends BaseController
{
    /**
     * Mengarahkan pengguna berdasarkan grup (role) mereka
     * setelah login berhasil.
     */
    public function redirect()
    {
        // auth()->user(), helper Shield untuk mendapatkan user yang sedang login
        // inGroup('...'), helper Shield untuk mengecek grup (role)

        /** @var \App\Entities\User $user */
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            return redirect()->to('/admin/dashboard');
        }

        if ($user->hasRole('TU')) {
            return redirect()->to('/tu/dashboard');
        }

        if ($user->hasRole('Peminjam')) {
            return redirect()->to('/peminjam/dashboard');
        }

        if ($user->hasRole('Pimpinan')) {
            return redirect()->to('/pimpinan/dashboard');
        }

        // logout paksa dan kembalikan ke login dengan pesan error jika tidak sesuai.
        auth()->logout();
        return redirect()->to('/login')->with('error', 'Anda tidak memiliki hak akses.');
    }
}
