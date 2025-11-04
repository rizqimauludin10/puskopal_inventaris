<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;
    protected $session;
    protected $allowedRoles = ['admin', 'superadmin'];

    public function __construct()
    {
        // Inisialisasi model di constructor
        $this->userModel = new UserModel();
        $this->session = session();
    }

    private function checkCrudAccess()
    {
        $userRole = $this->session->get('role');
        
        // Cek apakah role user ada di array allowedRoles
        if (!in_array($userRole, $this->allowedRoles)) {
            $this->session->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk melakukan aksi ini.');
            // Menggunakan redirect ke rute dashboard
            return redirect()->to(site_url('dashboard')); 
        }
        return true; // Lanjutkan eksekusi
    }

    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Fetch semua data pengguna dari database
        $data = [
            'users' => $this->userModel->findAll(),
            'title' => 'Manajemen Pengguna',
            'userRole' => $this->session->get('role')
        ];

        return view('user/index', $data);
    }

    /**
     * Menampilkan form untuk menambah pengguna baru.
     */
    public function create()
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        // Daftar Role yang digunakan di dropdown form
        $validRoles = ['superadmin', 'admin', 'pengurus', 'anggota'];

        $data = [
            'title' => 'Tambah Pengguna Baru',
            'roles' => $validRoles,
            'validation' => \Config\Services::validation(),
        ];

        return view('user/create', $data);
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store()
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        // 1. Tentukan aturan validasi
        if (!$this->validate([
            'nama_lengkap' => 'required|min_length[3]',
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[5]',
            'pass_confirm' => 'required_with[password]|matches[password]',
            'role' => 'required',
        ])) {
            return redirect()->back()->withInput();
        }

        // 2. Ambil data dan simpan (password akan di-hash oleh UserModel::beforeInsert)
        $this->userModel->save([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
        ]);

        return redirect()->to(site_url('users'))->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function edit($id)
    {

        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to(site_url('users'))->with('error', 'Pengguna tidak ditemukan.');
        }

        $validRoles = ['superadmin', 'admin', 'pengurus', 'anggota'];

        $data = [
            'title' => 'Edit Pengguna: ' . $user['nama_lengkap'],
            'user' => $user,
            'roles' => $validRoles,
            'validation' => \Config\Services::validation(),
        ];

        return view('user/edit', $data);
    }

    public function update($id)
    {

        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        $oldUser = $this->userModel->find($id);
        $password = $this->request->getPost('password');

        // 1. Tentukan aturan validasi
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'role' => 'required',
        ];

        // Aturan untuk Username: Cek unik, kecuali jika username sama dengan yang lama
        if ($oldUser['username'] != $this->request->getPost('username')) {
            $rules['username'] = 'required|is_unique[users.username]';
        } else {
            $rules['username'] = 'required';
        }

        // Aturan untuk Password: Hanya wajib jika diisi
        if (!empty($password)) {
            $rules['password'] = 'required|min_length[6]';
            $rules['pass_confirm'] = 'required_with[password]|matches[password]';
        }

        // 2. Lakukan validasi
        if (!$this->validate($rules)) {
            return redirect()->to(site_url('users/edit/' . $id))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // 3. Siapkan data untuk update
        $data = [
            'id' => $id,
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role'),
        ];

        // 4. Tambahkan password hanya jika diisi
        if (!empty($password)) {
            $data['password'] = $password; 
            // Model::save() akan otomatis melakukan hashing via beforeInsert
        }

        // 5. Simpan (Update)
        try {
            $this->userModel->save($data);
            return redirect()->to(site_url('users'))->with('success', 'Data pengguna ' . $data['nama_lengkap'] . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->to(site_url('users/edit/' . $id))
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {

        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to(site_url('users'))->with('error', 'Pengguna tidak ditemukan.');
        }

        // Pencegahan: Jangan biarkan admin menghapus dirinya sendiri jika fitur Auth sudah diimplementasikan.
        // Asumsi saat ini: Boleh hapus.

        try {
            $this->userModel->delete($id);
            return redirect()->to(site_url('users'))->with('success', 'Pengguna ' . $user['nama_lengkap'] . ' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(site_url('users'))->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

}