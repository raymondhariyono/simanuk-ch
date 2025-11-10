<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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

        $user = auth('session')->user();

        if ($user->inGroup('Admin')) {
            return redirect()->to('/admin/dashboard');
        }

        if ($user->inGroup('TU')) {
            return redirect()->to('/tu/dashboard');
        }

        if ($user->inGroup('Peminjam')) {
            return redirect()->to('/peminjam/dashboard');
        }

        if ($user->inGroup('Pimpinan')) {
            return redirect()->to('/pimpinan/dashboard');
        }

        // logout paksa dan kembalikan ke login dengan pesan error jika tidak sesuai.
        auth()->logout();
        return redirect()->to('/login')->with('error', 'Anda tidak memiliki hak akses.');
    }
}
