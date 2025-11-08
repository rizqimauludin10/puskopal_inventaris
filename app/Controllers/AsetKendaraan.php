<?php

namespace App\Controllers;

use App\Models\AsetKendaraanModel;
use App\Models\JenisKendaraanModel;
use CodeIgniter\Controller;

class AsetKendaraan extends Controller
{
    protected $kendaraanModel;
    protected $jeniskendaraanModel;
    protected $session;
    protected $allowedRoles = ['admin', 'pengurus', 'superadmin']; // Role yang diizinkan untuk CRUD

    public function __construct()
    {
        $this->kendaraanModel = new AsetKendaraanModel();
        $this->jeniskendaraanModel = new JenisKendaraanModel();
        $this->session = session(); // Inisialisasi session
    }

    /**
     * Memeriksa apakah role pengguna diizinkan untuk melakukan aksi CRUD.
     * Jika tidak diizinkan, redirect ke dashboard.
     */
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

    // Menampilkan semua data kendaraan
    public function index()
    {
        $data['kendaraan'] = $this->kendaraanModel->findAllWithJenis();
        // Tambahkan role pengguna untuk logika tampilan tombol di view
        $data['userRole'] = $this->session->get('role');
        
        return view('aset_kendaraan/index', $data);
    }

    // Form tambah kendaraan
    public function create()
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        $data['jenis'] = $this->jeniskendaraanModel->findAll();
        return view('aset_kendaraan/create', $data);
    }
    
    // Proses simpan kendaraan baru
    public function store()
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        // Ambil file yang diupload
        $fileDokumen = $this->request->getFile('dokumen');
        $namaDokumen = ''; // Default jika tidak ada file diupload

        // Cek apakah ada file yang valid diupload
        if ($fileDokumen && $fileDokumen->isValid() && ! $fileDokumen->hasMoved())
        {
            // Pengecekan Validasi (Maks 5MB, hanya PDF)
            if ($fileDokumen->getMimeType() !== 'application/pdf') {
                session()->setFlashdata('error', 'Format file harus PDF!');
                return redirect()->back()->withInput();
            }
            $MAX_SIZE_BYTES = 10 * 1024 * 1024; 
            if ($fileDokumen->getSize() > $MAX_SIZE_BYTES) { 
                session()->setFlashdata('error', 'Ukuran file dokumen maksimal 5MB!');
                return redirect()->back()->withInput();
            }
            
            // 1. Generate nama file random yang unik
            $namaDokumen = $fileDokumen->getRandomName();

            $fileDokumen->move(ROOTPATH . 'public/uploads/kendaraan', $namaDokumen);
        } 
        
        $this->kendaraanModel->save([
            'jenis_kendaraan' => $this->request->getPost('jenis_kendaraan'),
            'merk'            => $this->request->getPost('merk'),
            'no_rangka'       => $this->request->getPost('no_rangka'),
            'no_mesin'        => $this->request->getPost('no_mesin'),
            'tahun'           => $this->request->getPost('tahun'),
            'nopol'           => $this->request->getPost('nopol'),
            'bpkb'            => $this->request->getPost('bpkb'),
            'kondisi'         => $this->request->getPost('kondisi'),
            'pajak'           => $this->request->getPost('pajak'),
            'pajak_setahun'   => $this->request->getPost('pajak_setahun'),
            'catatan'         => $this->request->getPost('catatan'),
            'dokumen'         => $namaDokumen,
        ]);

        session()->setFlashdata('success', 'Data aset kendaraan dengan Nopol ' . 
        $this->request->getPost('nopol') . ' Berhasil disimpan!');

        return redirect()->to('/asetkendaraan');
    }
    
    public function show($id = null)
    {
        // ... (Logika show/detail sama, hanya perlu menambahkan userRole untuk view)

        if ($id === null) {
            return redirect()->to(site_url('asetkendaraan'))->with('error', 'ID aset tidak valid.');
        }

        $aset = $this->kendaraanModel->findWithJenis($id);

        if (!$aset) {
            return redirect()->to(site_url('asetkendaraan'))->with('error', 'Aset tidak ditemukan.');
        }

        $data = [
            'aset' => $aset,
            'userRole' => $this->session->get('role'), // Tambahkan role untuk logika tampilan di detail
        ];

        return view('aset_kendaraan/detail', $data);
    }


    // Form edit kendaraan
    public function edit($id)
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        $data['kendaraan'] = $this->kendaraanModel->find($id);
        $data['jenis'] = $this->jeniskendaraanModel->findAll();
        return view('aset_kendaraan/edit', $data);
    }

    // Proses update kendaraan
    public function update($id)
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        $fileDokumen = $this->request->getFile('dokumen');
        $namaDokumen = $this->request->getPost('dokumen_lama');

        if ($fileDokumen && $fileDokumen->isValid() && ! $fileDokumen->hasMoved())
        {
            // --- VALIDASI FILE BARU ---
            if ($fileDokumen->getMimeType() !== 'application/pdf') {
                session()->setFlashdata('error', 'Format file harus PDF!');
                return redirect()->back()->withInput();
            }
            $MAX_SIZE_BYTES = 10 * 1024 * 1024; // 5MB
            if ($fileDokumen->getSize() > $MAX_SIZE_BYTES) { 
                session()->setFlashdata('error', 'Ukuran file dokumen maksimal 5MB!');
                return redirect()->back()->withInput();
            }
            
            // --- PROSES FILE BARU ---
            
            // 2. Jika ada file lama (dan bukan string kosong), hapus file lama dari server
            if ($namaDokumen && file_exists(ROOTPATH . 'public/uploads/kendaraan/' . $namaDokumen)) {
                unlink(ROOTPATH . 'public/uploads/kendaraan/' . $namaDokumen);
            }

            // 3. Generate nama file unik baru dan pindahkan
            $namaDokumen = $fileDokumen->getRandomName();
            $fileDokumen->move(ROOTPATH . 'public/uploads/kendaraan', $namaDokumen);
        } 
        
        $this->kendaraanModel->update($id, [
            'jenis_kendaraan' => $this->request->getPost('jenis_kendaraan'),
            'merk'            => $this->request->getPost('merk'),
            'no_rangka'       => $this->request->getPost('no_rangka'),
            'no_mesin'        => $this->request->getPost('no_mesin'),
            'tahun'           => $this->request->getPost('tahun'),
            'nopol'           => $this->request->getPost('nopol'),
            'bpkb'            => $this->request->getPost('bpkb'),
            'kondisi'         => $this->request->getPost('kondisi'),
            'pajak'           => $this->request->getPost('pajak'),
            'pajak_setahun'   => $this->request->getPost('pajak_setahun'),
            'catatan'         => $this->request->getPost('catatan'),
            'dokumen'         => $namaDokumen,
        ]);


        session()->setFlashdata('success', 'Data aset kendaraan dengan Nopol ' . 
        $this->request->getPost('nopol') . ' Berhasil diupdate!');
        return redirect()->to('/asetkendaraan');
    }

    // Hapus kendaraan
    public function delete($id)
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        // Ambil data kendaraan sebelum dihapus untuk mendapatkan nama dokumen
        $kendaraan = $this->kendaraanModel->find($id);

        if ($kendaraan) {
            // Hapus file dokumen terkait jika ada
            if ($kendaraan['dokumen'] && file_exists(ROOTPATH . 'public/uploads/kendaraan/' . $kendaraan['dokumen'])) {
                unlink(ROOTPATH . 'public/uploads/kendaraan/' . $kendaraan['dokumen']);
            }
            $this->kendaraanModel->delete($id);

            session()->setFlashdata('success', 'Data aset kendaraan dengan Nopol ' . 
            $kendaraan['nopol'] . ' Berhasil dihapus!');
        } else {
            session()->setFlashdata('error', 'Data aset kendaraan tidak ditemukan!');
        }
        
        return redirect()->to('/asetkendaraan');
    }
    
    /**
     * Export data Aset Kendaraan ke format CSV (Excel)
     */
    public function exportExcel()
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        // Ambil semua data aset kendaraan
        $data= $this->kendaraanModel->findAllWithJenis();

        if (empty($data)) {
            session()->setFlashdata('error', 'Tidak ada data untuk diekspor.');
            return redirect()->back();
        }

        $filename = 'Aset_Kendaraan_' . date('Ymd_His') . '.csv';
        $separator = ';'; // Menggunakan titik koma untuk kompatibilitas Excel regional

        // Set Headers untuk file download
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Output buffer untuk menampung data CSV
        $output = fopen('php://temp', 'r+');

        // 1. Header Kolom
        fputcsv($output, [
            'ID', 
            'Nomor Polisi', 
            'Jenis', 
            'Merk', 
            'Tahun', 
            'No. Rangka', 
            'No. Mesin', 
            'BPKB', 
            'Kondisi', 
            'Status Pajak', 
            'Pajak Tahunan',
            'Catatan', 
            'Nama Dokumen'
        ], $separator);

        // 2. Data Baris
        foreach ($data as $row) {
            // Format Kondisi agar lebih mudah dibaca di Excel
            $kondisiFormatted = str_replace('_', ' ', strtoupper($row['kondisi']));
            
            fputcsv($output, [
                $row['id'],
                $row['nopol'],
                $row['nama_jenis'],
                $row['merk'],
                $row['tahun'],
                $row['no_rangka'],
                $row['no_mesin'],
                $row['bpkb'],
                $kondisiFormatted,
                $row['pajak'],
                $row['pajak_setahun'],
                $row['catatan'],
                $row['dokumen'], // Hanya nama file
            ], $separator);
        }

        // Kembali ke awal buffer
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        // Kirim konten CSV ke browser
        return $this->response->setBody($csvContent);
    }   
}