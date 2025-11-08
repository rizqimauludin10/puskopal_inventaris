<?php

namespace App\Controllers;

use App\Models\PersonelModel;
use App\Models\PangkatModel;
use App\Models\JabatanModel;
use App\Models\PenempatanModel;
use CodeIgniter\Controller;

class Personel extends Controller
{
    protected $personelModel;
    protected $pangkatModel;
    protected $jabatanModel;
    protected $penempatanModel;
    protected $session;
    protected $allowedRoles = ['admin', 'pengurus', 'superadmin']; // Role yang diizinkan

    public function __construct()
    {
        $this->personelModel   = new PersonelModel();
        $this->pangkatModel    = new PangkatModel();
        $this->jabatanModel    = new JabatanModel();
        $this->penempatanModel = new PenempatanModel();
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
                ->select('personel.*, tabel_pangkat.nama_pangkat, tabel_pangkat.keterangan, tabel_pangkat.status, tabel_jabatan.nama_jabatan, tabel_penempatan.nama_penempatan')
                ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
                ->join('tabel_jabatan', 'tabel_jabatan.id = personel.jabatan_id', 'left')
                ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
                ->orderBy('tabel_jabatan.id', 'ASC')
                ->findAll(),
            'userRole' => $this->session->get('role'),
            'total' => [
                'semua'   => $this->personelModel->countAll(),
                'tetap'   => $this->personelModel->where('status', 'Tetap')->countAllResults(),
                'bp'      => $this->personelModel->where('status', 'BP')->countAllResults(),
                'kontrak' => $this->personelModel->where('status', 'Perjanjian Kontrak')->countAllResults(),
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

            if ($fileFoto->getSize() > (2 * 1024 * 1024)) { // 2MB
                session()->setFlashdata('error', 'Ukuran foto maksimal 2MB!');
                return redirect()->back()->withInput();
            }

            $fotoName = $fileFoto->getRandomName();
            $fileFoto->move(ROOTPATH . 'public/uploads/personel', $fotoName);
        }

        $this->personelModel->save([
            'nama'             => $this->request->getPost('nama'),
            'pangkat_id'       => $this->request->getPost('pangkat_id'),
            'nrp_nip'          => $this->request->getPost('nrp_nip'),
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

            if ($fileFoto->getSize() > (2 * 1024 * 1024)) {
                session()->setFlashdata('error', 'Ukuran foto maksimal 2MB!');
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

    public function getJabatanByPenempatan($penempatan_id)
    {
        $jabatan = $this->jabatanModel
            ->where('id_penempatan', $penempatan_id)
            ->findAll();

        return $this->response->setJSON($jabatan);
    }

}