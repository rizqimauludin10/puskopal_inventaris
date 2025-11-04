<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Sebelum request dijalankan
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Cek apakah user sudah login
        if (!$session->has('isLoggedIn') || $session->get('isLoggedIn') !== true) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika filter dipanggil dengan argumen role tertentu
        if ($arguments && isset($arguments[0])) {
            $requiredRole = strtolower($arguments[0]);
            $userRole = strtolower($session->get('role'));

            // Superadmin bisa akses semua
            if ($userRole === 'superadmin') {
                return;
            }

            // Jika role tidak sesuai, tolak akses
            if ($userRole !== $requiredRole) {
                return redirect()->to('/unauthorized')->with('error', 'Anda tidak memiliki hak akses.');
            }
        }

        // Jika lolos, lanjut ke controller
    }

    /**
     * Setelah request dijalankan
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu tindakan setelah request
    }
}