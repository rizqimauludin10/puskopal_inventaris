<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonelModel extends Model
{
    protected $table            = 'personel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nama',
        'pangkat_id',
        'nrp_nip',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'korps',
        'jabatan_id',
        'penempatan_id',
        'dasar_penempatan',
        'status',
        'status_perkawinan',
        'jumlah_anak',
        'agama',
        'alamat',
        'berlaku',
        'foto'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil data lengkap personel beserta nama pangkat, jabatan, dan penempatan
     */
    public function getFullData()
    {
        return $this->select('
                personel.*, 
                tabel_pangkat.nama_pangkat, 
                tabel_pangkat.keterangan AS singkatan_pangkat,
                tabel_jabatan.nama_jabatan,
                tabel_penempatan.nama_penempatan
            ')
            ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
            ->join('tabel_jabatan', 'tabel_jabatan.id = personel.jabatan_id', 'left')
            ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
            ->orderBy('personel.id', 'DESC')
            ->findAll();
    }

    public function getPersonelPerPenempatan()
    {
        return $this->db->table('personel')
            ->select("
                tabel_penempatan.nama_penempatan,
                SUM(CASE WHEN tabel_pangkat.kategori = 'Militer' THEN 1 ELSE 0 END) AS total_militer,
                SUM(CASE WHEN tabel_pangkat.kategori = 'PNS' THEN 1 ELSE 0 END) AS total_pns,
                SUM(CASE WHEN tabel_pangkat.kategori = 'PPPK' THEN 1 ELSE 0 END) AS total_pppk,
                SUM(CASE WHEN tabel_pangkat.kategori = 'Karyawan' THEN 1 ELSE 0 END) AS total_karyawan,
                COUNT(personel.id) AS total_personel
            ")
            ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
            ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
            ->groupBy('tabel_penempatan.nama_penempatan')
            ->orderBy('total_personel', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getExpiringBP(int $days = 60)
    {
        $today = date('Y-m-d');
        $expiryDate = date('Y-m-d', strtotime("+$days days"));

        return $this->db->table('personel')
        ->select('personel.id, personel.nama, personel.status, tabel_pangkat.keterangan, tabel_penempatan.nama_penempatan, personel.berlaku')
                    ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
                    ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
                    ->whereIn('personel.status', ['BP', 'Perjanjian Kerja'])
                    // ->where('personel.berlaku >=', $today)
                    ->where('personel.berlaku <=', $expiryDate)
                    ->orderBy('personel.berlaku', 'ASC')
                    ->get()
                    ->getResultArray();
    }

    public function getPersonelUltahBulanIni()
    {
        $bulanSekarang = date('m');

        return $this->select('personel.id, personel.nama, tabel_pangkat.keterangan, tabel_penempatan.nama_penempatan, personel.tanggal_lahir')
            ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
            ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
            ->where('MONTH(personel.tanggal_lahir)', $bulanSekarang)
            ->orderBy('DAY(personel.tanggal_lahir)', 'ASC')
            ->findAll();
    }

    // NEW METHODS FOR REPORTING

    public function getRekapPenempatan()
    {
        return $this->db->table('tabel_penempatan')
            ->select('tabel_penempatan.id, tabel_penempatan.nama_penempatan, COUNT(personel.id) AS total_personel')
            ->join('personel', 'personel.penempatan_id = tabel_penempatan.id', 'left')
            ->groupBy('tabel_penempatan.id')
            ->orderBy('tabel_penempatan.nama_penempatan', 'ASC')
            ->get()->getResultArray();
    }

    public function getPersonelByPenempatan($penempatanId)
    {
        return $this->select('personel.id, personel.nama, personel.nrp_nip, tabel_pangkat.nama_pangkat, tabel_jabatan.nama_jabatan, tabel_penempatan.nama_penempatan, personel.status, personel.berlaku')
            ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
            ->join('tabel_jabatan', 'tabel_jabatan.id = personel.jabatan_id', 'left')
            ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
            ->where('personel.penempatan_id', $penempatanId)
            ->orderBy('personel.nama', 'ASC')
            ->findAll();
    }


}