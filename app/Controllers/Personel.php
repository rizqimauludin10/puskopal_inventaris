<?php

namespace App\Controllers;

use App\Models\PersonelModel;
use App\Models\PangkatModel;
use App\Models\JabatanModel;
use App\Models\PenempatanModel;
use App\Models\AgamaModel;
use CodeIgniter\Controller;

class Personel extends Controller
{
    protected $personelModel;
    protected $pangkatModel;
    protected $jabatanModel;
    protected $penempatanModel;
    protected $agamaModel;
    protected $session;
    protected $allowedRoles = ['admin', 'pengurus', 'superadmin']; // Role yang diizinkan

    public function __construct()
    {
        $this->personelModel   = new PersonelModel();
        $this->pangkatModel    = new PangkatModel();
        $this->jabatanModel    = new JabatanModel();
        $this->penempatanModel = new PenempatanModel();
        $this->agamaModel      = new AgamaModel();
        $this->session         = session();
    }

    /**
     * Validasi hak akses CRUD
     */
    private function checkCrudAccess()
    {
        $userRole = $this->session->get('role');
        if (!in_array($userRole, $this->allowedRoles)) {
            $this->session->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk melakukan aksi ini.');
            return redirect()->to(site_url('dashboard'));
        }
        return true;
    }

    // ===========================
    // INDEX / LIST PERSONEL
    // ===========================
    public function index()
    {
        $data = [
            'personel' => $this->personelModel
                ->select('personel.*, 
                        tabel_pangkat.id AS pangkat_id, tabel_pangkat.nama_pangkat, tabel_pangkat.keterangan, tabel_pangkat.kategori, 
                        tabel_jabatan.id AS jabatan_id, tabel_jabatan.id_penempatan AS jabatan_penempatan_id, tabel_jabatan.nama_jabatan, 
                        tabel_penempatan.id AS penempatan_id, tabel_penempatan.nama_penempatan')
                ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
                ->join('tabel_jabatan', 'tabel_jabatan.id = personel.jabatan_id', 'left')
                ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
                // Urutkan berdasarkan id_penempatan dari tabel_jabatan terlebih dahulu,
                // lalu berdasarkan pangkat, lalu sebagai fallback urut berdasarkan id jabatan
                ->orderBy('tabel_jabatan.id_penempatan', 'ASC')
                ->orderBy('tabel_pangkat.id', 'ASC')
                ->orderBy('tabel_jabatan.id', 'ASC')
                ->findAll(),
            'userRole' => $this->session->get('role'),
            'total' => [
                'semua'     => $this->personelModel->countAll(),
                'tetap'     => $this->personelModel->where('status', 'Tetap')->countAllResults(),
                'bp'        => $this->personelModel->where('status', 'BP')->countAllResults(),
                'kerja'     => $this->personelModel->where('status', 'Perjanjian Kerja')->countAllResults(),
            ],
        ];



        return view('personel/index', $data);
    }

    // ===========================
    // FORM TAMBAH PERSONEL
    // ===========================
    public function create()
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) return $guard;

        $data = [
            'pangkat'    => $this->pangkatModel->findAll(),
            'jabatan'    => $this->jabatanModel->findAll(),
            'penempatan' => $this->penempatanModel->findAll(),
            'agama'      => $this->agamaModel->findAll(),
        ];

        return view('personel/create', $data);
    }

    // ===========================
    // SIMPAN PERSONEL BARU
    // ===========================
    public function store()
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) return $guard;

        $fileFoto = $this->request->getFile('foto');
        $fotoName = '';

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Validasi file
            if (!in_array($fileFoto->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                session()->setFlashdata('error', 'Format foto harus JPG atau PNG!');
                return redirect()->back()->withInput();
            }

            if ($fileFoto->getSize() > (5 * 1024 * 1024)) { // 5MB
                session()->setFlashdata('error', 'Ukuran foto maksimal 5MB!');
                return redirect()->back()->withInput();
            }

            $fotoName = $fileFoto->getRandomName();
            $fileFoto->move(ROOTPATH . 'public/uploads/personel', $fotoName);
        }

        $this->personelModel->save([
            'nama'             => $this->request->getPost('nama'),
            'pangkat_id'       => $this->request->getPost('pangkat_id'),
            'nrp_nip'          => $this->request->getPost('nrp_nip'),
            'nik'              => $this->request->getPost('nik'),
            'tempat_lahir'     => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'    => $this->request->getPost('tanggal_lahir'),
            'alamat'           => $this->request->getPost('alamat'),
            'agama'            => $this->request->getPost('agama'),
            'status_perkawinan'=> $this->request->getPost('status_perkawinan'),
            'jumlah_anak'      => $this->request->getPost('jumlah_anak'),
            'korps'            => $this->request->getPost('korps'),
            'jabatan_id'       => $this->request->getPost('jabatan_id'),
            'penempatan_id'    => $this->request->getPost('penempatan_id'),
            'dasar_penempatan' => $this->request->getPost('dasar_penempatan'),
            'status'           => $this->request->getPost('status'),
            'berlaku'          => $this->request->getPost('berlaku'),
            'foto'             => $fotoName,
        ]);

        session()->setFlashdata('success', 'Data personel ' . $this->request->getPost('nama') . ' berhasil disimpan!');
        return redirect()->to('/personel');
    }

    // ===========================
    // FORM EDIT PERSONEL
    // ===========================
    public function edit($id)
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) return $guard;

        $personel = $this->personelModel->find($id);
        if (!$personel) {
            session()->setFlashdata('error', 'Data personel tidak ditemukan.');
            return redirect()->to('/personel');
        }

        $data = [
            'personel'   => $personel,
            'pangkat'    => $this->pangkatModel->findAll(),
            'jabatan'    => $this->jabatanModel->findAll(),
            'penempatan' => $this->penempatanModel->findAll(),
        ];

        return view('personel/edit', $data);
    }

    // ===========================
    // UPDATE PERSONEL
    // ===========================
    public function update($id)
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) return $guard;

        $personel = $this->personelModel->find($id);
        if (!$personel) {
            session()->setFlashdata('error', 'Data personel tidak ditemukan.');
            return redirect()->to('/personel');
        }

        $fileFoto = $this->request->getFile('foto');
        $fotoName = $personel['foto'];

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Validasi file
            if (!in_array($fileFoto->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                session()->setFlashdata('error', 'Format foto harus JPG atau PNG!');
                return redirect()->back()->withInput();
            }

            if ($fileFoto->getSize() > (5 * 1024 * 1024)) {
                session()->setFlashdata('error', 'Ukuran foto maksimal 5MB!');
                return redirect()->back()->withInput();
            }

            // Hapus foto lama
            if ($fotoName && file_exists(ROOTPATH . 'public/uploads/personel/' . $fotoName)) {
                unlink(ROOTPATH . 'public/uploads/personel/' . $fotoName);
            }

            $fotoName = $fileFoto->getRandomName();
            $fileFoto->move(ROOTPATH . 'public/uploads/personel', $fotoName);
        }

        $this->personelModel->update($id, [
            'nama'             => $this->request->getPost('nama'),
            'pangkat_id'       => $this->request->getPost('pangkat_id'),
            'nrp_nip'          => $this->request->getPost('nrp_nip'),
            'nik'              => $this->request->getPost('nik'),
            'tempat_lahir'     => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'    => $this->request->getPost('tanggal_lahir'),
            'alamat'           => $this->request->getPost('alamat'),
            'agama'            => $this->request->getPost('agama'),
            'status_perkawinan'=> $this->request->getPost('status_perkawinan'),
            'jumlah_anak'      => $this->request->getPost('jumlah_anak'),
            'korps'            => $this->request->getPost('korps'),
            'jabatan_id'       => $this->request->getPost('jabatan_id'),
            'penempatan_id'    => $this->request->getPost('penempatan_id'),
            'dasar_penempatan' => $this->request->getPost('dasar_penempatan'),
            'status'           => $this->request->getPost('status'),
            'berlaku'          => $this->request->getPost('berlaku'),
            'foto'             => $fotoName,
        ]);

        session()->setFlashdata('success', 'Data personel ' . $this->request->getPost('nama') . ' berhasil diperbarui!');
        return redirect()->to('/personel');
    }

    // ===========================
    // HAPUS PERSONEL
    // ===========================
    public function delete($id)
    {
        $guard = $this->checkCrudAccess();
        if ($guard !== true) return $guard;

        $personel = $this->personelModel->find($id);

        if ($personel) {
            if ($personel['foto'] && file_exists(ROOTPATH . 'public/uploads/personel/' . $personel['foto'])) {
                unlink(ROOTPATH . 'public/uploads/personel/' . $personel['foto']);
            }

            $this->personelModel->delete($id);
            session()->setFlashdata('success', 'Data personel ' . $personel['nama'] . ' berhasil dihapus!');
        } else {
            session()->setFlashdata('error', 'Data personel tidak ditemukan!');
        }

        return redirect()->to('/personel');
    }

    public function show($id = null)
    {
        if (!$id) {
            return redirect()->to(site_url('personel'))->with('error', 'Data personel tidak ditemukan.');
        }

        $personelModel = new \App\Models\PersonelModel();

        $personel = $personelModel
            ->select('personel.*, 
                    tabel_pangkat.keterangan AS pangkat_ket, 
                    tabel_pangkat.nama_pangkat AS pangkat_nama,
                    tabel_pangkat.kategori AS pangkat_kategori,
                    tabel_jabatan.nama_jabatan AS jabatan_nama, 
                    tabel_penempatan.nama_penempatan AS penempatan_nama')
            ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
            ->join('tabel_jabatan', 'tabel_jabatan.id = personel.jabatan_id', 'left')
            ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
            ->where('personel.id', $id)
            ->first();

        if (!$personel) {
            return redirect()->to(site_url('personel'))->with('error', 'Data personel tidak ditemukan.');
        }

        // Konversi nama field agar konsisten dengan view
        $data = [
            'personel' => [
                'id'                    => $personel['id'],
                'nama'                  => $personel['nama'],
                'nik'                   => $personel['nik'],
                'tempat_lahir'          => $personel['tempat_lahir'],
                'tanggal_lahir'         => $personel['tanggal_lahir'],
                'alamat'                => $personel['alamat'],
                'agama'                 => $personel['agama'],
                'status_perkawinan'     => $personel['status_perkawinan'],
                'jumlah_anak'           => $personel['jumlah_anak'],
                'pangkat_ket'           => $personel['pangkat_ket'],
                'pangkat_nama'          => $personel['pangkat_nama'],
                'pangkat_kategori'      => $personel['pangkat_kategori'],
                'korps'                 => $personel['korps'],
                'nrp_nip'               => $personel['nrp_nip'],
                'status_kepegawaian'    => $personel['status'],
                'tanggal_berlaku'       => $personel['berlaku'],
                'jabatan_nama'          => $personel['jabatan_nama'],
                'penempatan_nama'       => $personel['penempatan_nama'],
                'dasar_penempatan'      => $personel['dasar_penempatan'],
                'foto'                  => $personel['foto'],
                'created_at'            => $personel['created_at'],
                'updated_at'            => $personel['updated_at']
            ]
        ];

        return view('personel/detail', $data);
    }


    public function getJabatanByPenempatan($penempatan_id)
    {
        $jabatan = $this->jabatanModel
            ->where('id_penempatan', $penempatan_id)
            ->findAll();

        return $this->response->setJSON($jabatan);
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

        // Ambil data lengkap personel
        $data = $this->personelModel
            ->select('personel.*, tabel_pangkat.keterangan AS nama_pangkat, tabel_pangkat.kategori, tabel_jabatan.nama_jabatan, tabel_penempatan.nama_penempatan')
            ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
            ->join('tabel_jabatan', 'tabel_jabatan.id = personel.jabatan_id', 'left')
            ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
            ->orderBy('personel.id', 'ASC')
            ->findAll();

        if (empty($data)) {
            session()->setFlashdata('error', 'Tidak ada data personel untuk diekspor.');
            return redirect()->back();
        }

        // Nama file export
        $filename = 'Data_Personel_' . date('Ymd_His') . '.csv';
        $separator = ';'; // pakai titik koma biar Excel Indonesia bisa baca dengan benar

        // Set header CSV
        $this->response->setHeader('Content-Type', 'text/csv; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Siapkan buffer output
        $output = fopen('php://temp', 'r+');

        // Header kolom CSV
        fputcsv($output, [
            'ID',
            'Nama Lengkap',
            'NRP / NIP',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Status Perkawinan',
            'Jumlah Anak',
            'Agama',
            'Alamat',
            'Pangkat',
            'Korps',
            'Jabatan',
            'Penempatan',
            'Dasar Penempatan',
            'Status Kepegawaian',
            'Tanggal Berlaku Perja',
        ], $separator);

        // Baris data CSV
        foreach ($data as $row) {
            fputcsv($output, [
                $row['id'],
                $row['nama'],
                $row['nrp_nip'],
                $row['nik'],
                $row['tempat_lahir'],
                $row['tanggal_lahir'],
                $row['status_perkawinan'],
                $row['jumlah_anak'],
                $row['agama'],
                $row['alamat'],
                $row['nama_pangkat'],
                $row['korps'],
                $row['nama_jabatan'],
                $row['nama_penempatan'],
                $row['dasar_penempatan'],
                $row['status'],
                $row['berlaku'],
            ], $separator);
        }

        // Selesaikan buffer
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        // Kirim file ke browser
        return $this->response->setBody($csvContent);
    }

    public function rekap()
    {
        $model = new PersonelModel();
        $data['penempatan'] = $model->getRekapPenempatan();
        return view('personel/rekap_personel', $data);
    }

    public function getPersonelByPenempatan($id)
    {
        $model = new PersonelModel();
        $result = $model->getPersonelByPenempatan($id);
        return $this->response->setJSON($result);
    }

    


}