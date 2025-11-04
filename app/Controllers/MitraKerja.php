<?php

namespace App\Controllers;

use App\Models\MitraKerjaModel;
use CodeIgniter\Controller;

class MitraKerja extends Controller
{
    protected $mitraModel;
    protected $helpers = ['form', 'url'];
    // Roles yang diizinkan untuk CRUD (Tambah, Edit, Hapus)
    protected $allowedCrudRoles = ['superadmin', 'admin', 'pengurus']; 

    public function __construct()
    {
        $this->mitraModel = new MitraKerjaModel();

        // Proteksi login: Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            // Menggunakan send() untuk memastikan redirect terjadi
            session()->setFlashdata('error', 'Sesi Anda telah berakhir, silakan login kembali.');
            return redirect()->to(site_url('auth'))->send();
        }
        
        // Catatan: Role protection untuk read-only methods (index, detail) dihapus 
        // dari construct agar semua user terautentikasi bisa melihat data.
    }
    
    /**
     * Helper function untuk membatasi akses CRUD (superadmin dan admin saja).
     */
    private function checkCrudAccess()
    {
        $userRole = session()->get('role');
        
        // Cek apakah role user ada di array allowedCrudRoles
        if (!in_array($userRole, $this->allowedCrudRoles)) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk melakukan aksi ini.');
            // Redirect kembali ke halaman index mitra
            return redirect()->to(site_url('mitra')); 
        }
        return true; // Lanjutkan eksekusi
    }


    /** ========================
     * TAMPILKAN SEMUA MITRA
     * ======================== */
    public function index()
    {
        $mitraList = $this->mitraModel->getAllMitraWithPksCount();
        $data = [
            'title'      => 'Manajemen Mitra Kerja',
            'mitraList'  => $mitraList,
            'userRole'   => session()->get('role'), // Kirim role untuk kontrol view
        ];

        return view('mitra/index', $data);
    }

    /** ========================
     * FORM TAMBAH MITRA
     * ======================== */
    public function create()
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        $data = [
            'title'       => 'Tambah Mitra Kerja Baru',
            'validation'  => service('validation'),
        ];

        return view('mitra/create', $data);
    }

    /** ========================
     * SIMPAN DATA BARU
     * ======================== */
    public function store()
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        if (!$this->validate($this->mitraModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_mitra'        => $this->request->getPost('nama_mitra'),
            'alamat'            => $this->request->getPost('alamat'),
            'pic'               => $this->request->getPost('pic'),
            'no_telepon'        => $this->request->getPost('no_telepon'),
            'email'             => $this->request->getPost('email'),
            'status'            => $this->request->getPost('status'),
            'keterangan'        => $this->request->getPost('keterangan'),
        ];

        try {
            $this->mitraModel->insert($data);
            session()->setFlashdata('success', 'Mitra Kerja <b>' . esc($data['nama_mitra']) . '</b> berhasil ditambahkan.');
            return redirect()->to(site_url('mitra'));
        } catch (\Exception $e) {
            log_message('error', 'Error insert mitra: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menyimpan data Mitra.');
            return redirect()->back()->withInput();
        }
    }

    /** ========================
     * EDIT MITRA
     * ======================== */
    public function edit($id)
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        $mitra = $this->mitraModel->find($id);

        if (!$mitra) {
            session()->setFlashdata('error', 'Mitra tidak ditemukan.');
            return redirect()->to(site_url('mitra'));
        }

        $data = [
            'title'       => 'Edit Mitra Kerja: ' . esc($mitra['nama_mitra']),
            'mitra'       => $mitra,
            'validation'  => service('validation'),
        ];

        return view('mitra/edit', $data);
    }

    /** ========================
     * UPDATE DATA MITRA
     * ======================== */
    public function update($id)
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        if (!$this->validate($this->mitraModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_mitra'        => $this->request->getPost('nama_mitra'),
            'alamat'            => $this->request->getPost('alamat'),
            'pic'               => $this->request->getPost('pic'),
            'no_telepon'        => $this->request->getPost('no_telepon'),
            'email'             => $this->request->getPost('email'),
            'status'            => $this->request->getPost('status'),
            'keterangan'        => $this->request->getPost('keterangan'),
        ];

        try {
            $this->mitraModel->update($id, $data);
            session()->setFlashdata('success', 'Data Mitra <b>' . esc($data['nama_mitra']) . '</b> berhasil diperbarui.');
            return redirect()->to(site_url('mitra'));
        } catch (\Exception $e) {
            log_message('error', 'Error update mitra: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal memperbarui data Mitra.');
            return redirect()->back()->withInput();
        }
    }

    /** ========================
     * HAPUS MITRA
     * ======================== */
    public function delete($id)
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        $mitra = $this->mitraModel->find($id);

        if (!$mitra) {
            session()->setFlashdata('error', 'Mitra tidak ditemukan.');
            return redirect()->to(site_url('mitra'));
        }

        try {
            $this->mitraModel->delete($id);
            session()->setFlashdata('success', 'Mitra Kerja <b>' . esc($mitra['nama_mitra']) . '</b> berhasil dihapus.');
            return redirect()->to(site_url('mitra'));
        } catch (\Exception $e) {
            log_message('error', 'Error delete mitra: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus data Mitra.');
            return redirect()->to(site_url('mitra'));
        }
    }

    /** ========================
     * DETAIL MITRA + LIST PKS
     * ======================== */
    public function detail($mitraId)
    {
        $mitra = $this->mitraModel->find($mitraId);

        if (!$mitra) {
            session()->setFlashdata('error', 'Mitra Kerja tidak ditemukan.');
            return redirect()->to(site_url('mitra'));
        }

        // Ambil daftar PKS terkait mitra
        $pksModel = new \App\Models\PerjanjianKerjasamaModel();
        $pksList  = $pksModel->where('mitra_id', $mitraId)->orderBy('tanggal_mulai', 'ASC')->findAll();

        $data = [
            'title'    => 'Detail Mitra & PKS: ' . esc($mitra['nama_mitra']),
            'mitra'    => $mitra,
            'pksList'  => $pksList,
            'userRole' => session()->get('role'), // Kirim role untuk kontrol view
        ];

        return view('mitra/detail', $data);
    }
}