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
        'jabatan_id',
        'penempatan_id',
        'dasar_penempatan',
        'status',
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
}