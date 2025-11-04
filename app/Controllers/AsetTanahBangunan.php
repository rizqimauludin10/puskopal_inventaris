<?php

namespace App\Controllers;

use App\Models\AsetTanahBangunanModel;
use App\Models\JenisKepemilikanModel;
use CodeIgniter\Controller; // Menggunakan CodeIgniter\Controller, bukan BaseController jika BaseController tidak didefinisikan di namespace ini

class AsetTanahBangunan extends Controller
{
    protected $tanahModel;
    protected $kepemilikanModel;
    protected $session;
    protected $allowedRoles = ['admin', 'pengurus', 'superadmin']; // Role yang diizinkan untuk CRUD

    public function __construct()
    {
        $this->tanahModel = new AsetTanahBangunanModel();
        $this->kepemilikanModel = new JenisKepemilikanModel();
        $this->session = session(); // Inisialisasi session
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

    // READ: tampilkan semua data
    public function index()
    {
        $data['tanah'] = $this->tanahModel->findAllWithKepemilikan();
        $data['userRole'] = $this->session->get('role');
        return view('aset_tanahbangunan/index', $data);
    }

    public function exportExcel()
    {
        // GUARD: Hanya admin dan pengurus yang boleh export
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        // Ambil semua data aset kendaraan
        $data = $this->tanahModel->findAllWithKepemilikan();

        if (empty($data)) {
            session()->setFlashdata('error', 'Tidak ada data untuk diekspor.');
            return redirect()->back();
        }

        $filename = 'Aset_Tanah_Bangunan' . date('Ymd_His') . '.csv';
        $separator = ';'; // Menggunakan titik koma untuk kompatibilitas Excel regional

        // Set Headers untuk file download
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Output buffer untuk menampung data CSV
        $output = fopen('php://temp', 'r+');

        // 1. Header Kolom
        fputcsv($output, [
            'ID', 
            'Lokasi', 
            'Luas Tanah', 
            'Luas Bangunan', 
            'Kepemilikan', 
            'Berlaku', 
            'Keterangan', 
            'Dokumen Legalitas',
            'Latitude',
            'Longitude'
        ], $separator);

        // 2. Data Baris
        foreach ($data as $row) {
            
            fputcsv($output, [
                $row['id'],
                $row['lokasi'],
                $row['luas_tanah'],
                $row['luas_bangunan'],
                $row['nama_kepemilikan'], // Nama kepemilikan dari relasi
                $row['berlaku'],
                $row['keterangan'],
                $row['dokumen_legalitas'], // Hanya nama file
                $row['latitude'],
                $row['longitude']
            ], $separator);
        }

        // Kembali ke awal buffer
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        // Kirim konten CSV ke browser
        return $this->response->setBody($csvContent);
    }

    // CREATE: form tambah data
    public function create()
    {
        // GUARD: Hanya admin dan pengurus yang boleh akses form create
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        $data['kepemilikan'] = $this->kepemilikanModel->findAll();
        return view('aset_tanahbangunan/create', $data);
    }

    // READ: tampilkan detail data
    public function show($id)
    {
        $data['tanah'] = $this->tanahModel->findWithKepemilikan($id);

        if (empty($data['tanah'])) {
            session()->setFlashdata('error', 'Aset properti tidak ditemukan.');
            return redirect()->to('/asettanahbangunan');
        }
        
        // Tambahkan role pengguna untuk logika tampilan tombol di view
        $data['userRole'] = $this->session->get('role');

        return view('aset_tanahbangunan/detail', $data);
    }

    // CREATE: simpan data baru
    public function store()
    {
        // GUARD: Hanya admin dan pengurus yang boleh store data
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }

        // 1. Ambil data file
        $fileDokumen = $this->request->getFile('dokumen_legalitas');
        $namaDokumen = ''; 
        
        // 2. Proses Upload File
        if ($fileDokumen && $fileDokumen->isValid() && ! $fileDokumen->hasMoved())
        {
            // --- VALIDASI FILE BARU ---
            if ($fileDokumen->getMimeType() !== 'application/pdf') {
                session()->setFlashdata('error', 'Format file harus PDF!');
                return redirect()->back()->withInput();
            }
            $MAX_SIZE_BYTES = 5 * 1024 * 1024; // 5MB
            if ($fileDokumen->getSize() > $MAX_SIZE_BYTES) { 
                session()->setFlashdata('error', 'Ukuran file dokumen maksimal 5MB!');
                return redirect()->back()->withInput();
            }
            
            // Generate nama file unik dan pindahkan
            $namaDokumen = $fileDokumen->getRandomName();
            // Pastikan folder 'public/uploads/tanahbangunan' sudah dibuat!
            $fileDokumen->move(ROOTPATH . 'public/uploads/tanahbangunan', $namaDokumen);
        }

        $latitude  = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');

        $luas_tanah = $this->request->getPost('luas_tanah');
        $luas_bangunan = $this->request->getPost('luas_bangunan');

        $luas_tanah = floatval($luas_tanah);
        $luas_bangunan = floatval($luas_bangunan);

        // =======================
        // 4. Simpan ke model
        // =======================
        $this->tanahModel->save([
            'lokasi'            => $this->request->getPost('lokasi'),
            'luas_tanah'        => $luas_tanah,
            'luas_bangunan'     => $luas_bangunan,
            'kepemilikan'       => $this->request->getPost('kepemilikan'),
            'berlaku'           => $this->request->getPost('berlaku'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'dokumen_legalitas' => $namaDokumen,
            'detail_kepemilikan'=> $this->request->getPost('detail_kepemilikan'),
            'latitude'          => $latitude,
            'longitude'         => $longitude,
        ]);

        session()->setFlashdata('success', 'Data aset properti di lokasi ' . $this->request->getPost('lokasi') . ' berhasil disimpan!');
        return redirect()->to('/asettanahbangunan');
    }


    // UPDATE: form edit
    public function edit($id)
    {
        // GUARD: Hanya admin dan pengurus yang boleh akses form edit
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        $data['tanah'] = $this->tanahModel->find($id);
        $data['kepemilikan'] = $this->kepemilikanModel->findAll();
        return view('aset_tanahbangunan/edit', $data);
    }

    // UPDATE: simpan perubahan
    public function update($id)
    {
        // GUARD: Hanya admin dan pengurus yang boleh update data
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        // 1. Ambil data file lama dan file baru
        $fileDokumen = $this->request->getFile('dokumen_legalitas');
        $namaDokumen = $this->request->getPost('dokumen_legalitas_lama'); // Ambil nama file lama
        // Cek apakah ada file baru yang diupload
        if ($fileDokumen && $fileDokumen->isValid() && ! $fileDokumen->hasMoved())
        {
            // --- VALIDASI FILE BARU ---
            if ($fileDokumen->getMimeType() !== 'application/pdf') {
                session()->setFlashdata('error', 'Format file harus PDF!');
                return redirect()->back()->withInput();
            }
            $MAX_SIZE_BYTES = 5 * 1024 * 1024; // 5MB
            if ($fileDokumen->getSize() > $MAX_SIZE_BYTES) { 
                session()->setFlashdata('error', 'Ukuran file dokumen maksimal 5MB!');
                return redirect()->back()->withInput();
            }
            
            // --- PROSES FILE BARU ---
            
            // 2. Jika ada file lama (dan bukan string kosong), hapus file lama dari server
            if ($namaDokumen && file_exists(ROOTPATH . 'public/uploads/tanahbangunan/' . $namaDokumen)) {
                unlink(ROOTPATH . 'public/uploads/tanahbangunan/' . $namaDokumen);
            }

            // 3. Generate nama file unik baru dan pindahkan
            $namaDokumen = $fileDokumen->getRandomName();
            $fileDokumen->move(ROOTPATH . 'public/uploads/tanahbangunan', $namaDokumen);
        } 

        // --- KONVERSI LUAS TAHAN & BANGUNAN ---
        $luasTanahRaw    = $this->request->getPost('luas_tanah');
        $luasBangunanRaw = $this->request->getPost('luas_bangunan');

        $luasTanah = floatval($luasTanahRaw);
        $luasBangunan = floatval($luasBangunanRaw);
        
        $this->tanahModel->update($id, [
            'lokasi'       => $this->request->getPost('lokasi'),
            'luas_tanah'   => $luasTanah,
            'luas_bangunan'=> $luasBangunan,
            'kepemilikan'  => $this->request->getPost('kepemilikan'),
            'berlaku'      => $this->request->getPost('berlaku'),
            'keterangan'   => $this->request->getPost('keterangan'),
            'dokumen_legalitas' => $namaDokumen,
            'detail_kepemilikan' => $this->request->getPost('detail_kepemilikan'),
            'latitude'           => $this->request->getPost('latitude'),
            'longitude'          => $this->request->getPost('longitude'),
        ]);
        session()->setFlashdata('success', 'Data aset properti di lokasi ' . $this->request->getPost('lokasi') . ' Berhasil diupdate!');

        return redirect()->to('/asettanahbangunan');
    }

    // DELETE: hapus data
    public function delete($id)
    {
        // GUARD: Hanya admin dan pengurus yang boleh delete data
        $guard = $this->checkCrudAccess();
        if ($guard !== true) {
            return $guard;
        }
        
        // Ambil data sebelum dihapus untuk menghapus file
        $aset = $this->tanahModel->find($id);

        if ($aset) {
            // Hapus file dokumen terkait jika ada
            if ($aset['dokumen_legalitas'] && file_exists(ROOTPATH . 'public/uploads/tanahbangunan/' . $aset['dokumen_legalitas'])) {
                unlink(ROOTPATH . 'public/uploads/tanahbangunan/' . $aset['dokumen_legalitas']);
            }
            
            $this->tanahModel->delete($id);
            session()->setFlashdata('success', 'Data aset tanah dan bangunan dengan Lokasi ' . 
            $aset['lokasi'] . ' Berhasil dihapus!');
        } else {
            session()->setFlashdata('error', 'Data aset properti tidak ditemukan!');
        }
        
        return redirect()->to('/asettanahbangunan');
    }
}