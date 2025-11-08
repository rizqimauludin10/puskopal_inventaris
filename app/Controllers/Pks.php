<?php namespace App\Controllers;

use App\Models\PerjanjianKerjaSamaModel;
use App\Models\MitraKerjaModel;
use CodeIgniter\Controller; // Menggunakan CodeIgniter\Controller

class Pks extends Controller
{
    protected $pksModel;
    protected $mitraModel;
    protected $uploadPath = 'uploads/pks/'; // Path relatif dari public/
    protected $allowedCrudRoles = ['superadmin', 'admin' , 'pengurus']; // Roles yang diizinkan untuk CRUD

    public function __construct()
    {
        $this->pksModel = new PerjanjianKerjaSamaModel();
        $this->mitraModel = new MitraKerjaModel();
        
        // Proteksi login: Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Sesi Anda telah berakhir, silakan login kembali.');
            return redirect()->to(site_url('auth'))->send();
        }
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
            // Redirect ke dashboard atau halaman mitra
            return redirect()->to(site_url('mitra')); 
        }
        return true; // Lanjutkan eksekusi
    }

    /**
     * Menampilkan form untuk menambah PKS baru untuk Mitra tertentu.
     * URL: /pks/new/{mitra_id}
     */
    public function create($mitraId)
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        // 1. Pastikan Mitra ID valid
        $mitra = $this->mitraModel->find($mitraId);

        if (!$mitra) {
            session()->setFlashdata('error', 'Mitra Kerja tidak ditemukan.');
            return redirect()->to(site_url('mitra'));
        }

        $data = [
            'title' => 'Tambah PKS Baru',
            'mitra' => $mitra,
            'validation' => \Config\Services::validation(),
        ];

        return view('pks/create', $data);
    }

    /**
     * Menyimpan data PKS baru.
     * URL: /pks/store
     */
    public function store()
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        $mitraId = $this->request->getPost('mitra_id');

        // 1. Ambil Rules Validasi dari Model
        $rules = $this->pksModel->getValidationRules();

        // Di method store, file kita asumsikan required (wajib diupload pertama kali)
        $rules['file_dokumen'] = 'uploaded[file_dokumen]|max_size[file_dokumen,5120]|ext_in[file_dokumen,pdf]';

        // 2. Validasi
        if (!$this->validate($rules, $this->pksModel->getValidationMessages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Upload Dokumen
        $dokumenPks = $this->request->getFile('file_dokumen');
        $newName = null;

        if ($dokumenPks->isValid() && !$dokumenPks->hasMoved()) {
            $newName = $dokumenPks->getRandomName();
            // Pindahkan ke folder public/uploads/pks
            $dokumenPks->move(ROOTPATH . 'public/' . $this->uploadPath, $newName);
        }

        // 4. Persiapkan Data
        $data = [
            'mitra_id'           => $mitraId,
            'nomor_pks'          => $this->request->getPost('nomor_pks'),
            'nama_proyek'        => $this->request->getPost('nama_proyek'),
            'tanggal_mulai'      => $this->request->getPost('tanggal_mulai'),
            'tanggal_berakhir'   => $this->request->getPost('tanggal_berakhir'),
            // Hapus titik ribuan sebelum disimpan
            'nilai_kontrak'      => str_replace(['.', ','], ['', '.'], $this->request->getPost('nilai_kontrak')), 
            'status_pks'         => $this->request->getPost('status_pks'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'file_dokumen'       => $newName, // Simpan hanya nama file (atau null jika tidak ada)
        ];

        // 5. Simpan ke Database
        if ($this->pksModel->insert($data)) {
            session()->setFlashdata('success', 'Perjanjian Kerja Sama baru berhasil ditambahkan.');
            return redirect()->to(site_url('mitra/detail/' . $mitraId));
        } else {
            session()->setFlashdata('error', 'Gagal menyimpan data PKS. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit PKS.
     * URL: /pks/edit/{id}
     */
    public function edit($pksId)
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        $pks = $this->pksModel->getPksWithMitra($pksId);

        if (!$pks) {
            session()->setFlashdata('error', 'Data PKS tidak ditemukan.');
            return redirect()->to(site_url('mitra')); // Arahkan ke daftar mitra utama
        }

        $data = [
            'title' => 'Edit PKS: ' . esc($pks['nomor_pks']),
            'pks' => $pks,
            'mitra' => $this->mitraModel->find($pks['mitra_id']),
            'validation' => \Config\Services::validation(),
        ];
        
        return view('pks/edit', $data);
    }

    /**
     * Menyimpan perubahan data PKS.
     * URL: /pks/update/{id}
     */
    public function update($pksId)
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        $oldPks = $this->pksModel->find($pksId);

        if (!$oldPks) {
            session()->setFlashdata('error', 'Data PKS tidak ditemukan.');
            return redirect()->to(site_url('mitra')); 
        }

        $mitraId = $oldPks['mitra_id'];

        // 1. Ambil Rules Validasi dari Model
        $rules = $this->pksModel->getValidationRules();
        
        // Ubah rules nomor_pks agar mengabaikan PKS yang sedang diedit
        $rules['nomor_pks'] = 'required|max_length[100]|is_unique[perjanjian_kerjasama.nomor_pks,id,' . $pksId . ']';
        
        // Tangani Validasi File Secara Kondisional 
        $newDokumen = $this->request->getFile('file_dokumen'); // Ambil file dari input form

        // Error code 4 (UPLOAD_ERR_NO_FILE) artinya pengguna TIDAK mengunggah file.
        if ($newDokumen && $newDokumen->getError() == 4) { 
             // Jika tidak ada file diupload, hapus rules file_dokumen dari array validasi
            unset($rules['file_dokumen']);
        } else {
            // Jika ada file yang diupload, pastikan validasi size dan ekstensi berjalan.
            $rules['file_dokumen'] = 'max_size[file_dokumen,5120]|ext_in[file_dokumen,pdf]';
        }


        // 2. Validasi
        if (!$this->validate($rules, $this->pksModel->getValidationMessages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Upload Dokumen Baru (Jika ada)
        $dokumenFilename = $oldPks['file_dokumen']; // Pertahankan dokumen lama by default

        // Lakukan upload hanya jika file VALID dan bukan error 4/NO_FILE
        if ($newDokumen->isValid() && !$newDokumen->hasMoved() && $newDokumen->getError() != 4) {
            
            // Hapus dokumen lama jika ada
            if (!empty($oldPks['file_dokumen'])) {
                $oldFilePath = ROOTPATH . 'public/' . $this->uploadPath . $oldPks['file_dokumen'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            // Pindahkan dokumen baru
            $newName = $newDokumen->getRandomName();
            $newDokumen->move(ROOTPATH . 'public/' . $this->uploadPath, $newName);
            $dokumenFilename = $newName; // Simpan hanya nama file baru
        }

        // 4. Persiapkan Data
        $data = [
            'id'                 => $pksId,
            'nomor_pks'          => $this->request->getPost('nomor_pks'),
            'nama_proyek'        => $this->request->getPost('nama_proyek'),
            'tanggal_mulai'      => $this->request->getPost('tanggal_mulai'),
            'tanggal_berakhir'   => $this->request->getPost('tanggal_berakhir'),
            // Pastikan nilai kontrak diproses dengan benar
            'nilai_kontrak'      => str_replace(['.', ','], ['', '.'], $this->request->getPost('nilai_kontrak')),
            'status_pks'         => $this->request->getPost('status_pks'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'file_dokumen'       => $dokumenFilename, // Bisa nama file baru, nama file lama, atau null
        ];

        // 5. Simpan ke Database
        if ($this->pksModel->save($data)) {
            session()->setFlashdata('success', 'Perubahan PKS berhasil disimpan.');
            return redirect()->to(site_url('mitra/detail/' . $mitraId));
        } else {
            session()->setFlashdata('error', 'Gagal menyimpan perubahan data PKS. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function detail($pksId)
    {
        // Fungsi detail tidak perlu role guard, bisa diakses semua user yang login.
        
        // Pastikan Anda memiliki method getPksWithMitra() di PerjanjianKerjasamaModel
        $pks = $this->pksModel->getPksWithMitra($pksId);

        if (!$pks) {
            session()->setFlashdata('error', 'Data PKS tidak ditemukan.');
            return redirect()->to(site_url('mitra'));
        }

        $data = [
            'title' => 'Detail PKS: ' . esc($pks['nomor_pks']),
            'pks' => $pks,
            'uploadPath' => $this->uploadPath,
            'userRole' => session()->get('role'), // Kirim role untuk kontrol tampilan tombol
        ];
        
        return view('pks/detail', $data);
    }

    /**
     * Menghapus PKS.
     * URL: /pks/delete/{id}
     */
    public function delete($pksId)
    {
        // GUARD: Hanya superadmin dan admin
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        $pks = $this->pksModel->find($pksId);

        if (!$pks) {
            session()->setFlashdata('error', 'Data PKS tidak ditemukan.');
            return redirect()->to(site_url('mitra'));
        }
        
        $mitraId = $pks['mitra_id'];

        // 1. Hapus Dokumen Fisik (Jika ada)
        if (!empty($pks['file_dokumen'])) {
            // Gunakan $this->uploadPath untuk path yang konsisten
            $filePath = ROOTPATH . 'public/' . $this->uploadPath . $pks['file_dokumen'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // 2. Hapus dari Database
        if ($this->pksModel->delete($pksId)) {
            session()->setFlashdata('success', 'PKS Nomor ' . esc($pks['nomor_pks']) . ' berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus data PKS.');
        }

        // Kembali ke halaman detail Mitra
        return redirect()->to(site_url('mitra/detail/' . $mitraId));
    }
}