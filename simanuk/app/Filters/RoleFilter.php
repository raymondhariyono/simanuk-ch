<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika tidak login (meskipun harusnya sudah ditangani filter 'session')
        if (!auth()->loggedIn()) {
            return redirect()->to(site_url('login'));
        }

        // Jika tidak ada argumen (role), tolak akses
        if (empty($arguments)) {
            return redirect()->to(site_url('/auth/redirect'))->with('error', 'Akses ditolak.');
        }

        // Dapatkan user yang sedang login (ini adalah Entitas kustom kita)
        $user = auth()->user();
        $allowedRoles = $arguments ?? [];

        // Periksa apakah role pengguna ada di dalam daftar $arguments
        // Kita gunakan helper hasRole() yang baru saja kita buat di Entitas
        foreach ($arguments as $role) {
            if ($user->hasRole($role)) {
                // Jika cocok, izinkan request (return)
                return;
            }
        }

        // Jika loop selesai dan tidak ada role yang cocok, lempar keluar
        return redirect()->to(site_url('/auth/redirect'))
            ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
