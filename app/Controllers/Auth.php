<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->userModel = new UserModel();
        $this->session = session();
    }

    /**
     * Halaman Login
     */
    public function index()
    {
        // Jika sudah login, langsung arahkan ke dashboard
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to(site_url('/dashboard'));
        }

        return view('auth/login');
    }

    /**
     * Proses Login
     */
    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Ambil data user berdasarkan username
        $user = $this->userModel->where('username', $username)->first();

        if (!$user) {
            $this->session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->back()->withInput();
        }

        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            $this->session->setFlashdata('error', 'Kata sandi salah.');
            return redirect()->back()->withInput();
        }

        // Buat data session user
        $sessionData = [
            'id'           => $user['id'],
            'username'     => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'role'         => $user['role'],
            'isLoggedIn'   => true,
        ];

        $this->session->set($sessionData);
        $this->session->setFlashdata('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');

        // Redirect sesuai role (opsional)
        switch ($user['role']) {
            case 'superadmin':
            case 'admin':
                return redirect()->to(site_url('/dashboard'));
            case 'pengurus':
                return redirect()->to(site_url('/dashboard'));
            case 'anggota':
            default:
                return redirect()->to(site_url('/'));
        }
    }

    /**
     * Logout User
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()
            ->to(site_url('/login'))
            ->with('success', 'Anda telah berhasil keluar.');
    }
}