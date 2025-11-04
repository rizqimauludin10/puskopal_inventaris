<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraKerjaModel extends Model
{
    protected $table      = 'mitra_kerja';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Jika tidak menggunakan fitur soft delete

    protected $allowedFields = [
        'nama_mitra', 
        'alamat',
        'pic',
        'no_telepon', 
        'email', 
        'status',
        'keterangan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules    = [
        'nama_mitra' => 'required|min_length[3]|max_length[255]',
        'email'      => 'permit_empty|valid_email', // Email tidak wajib, tapi jika diisi harus valid
    ];

    protected $validationMessages = [
        'nama_mitra' => [
            'required' => 'Nama Mitra wajib diisi.',
            'min_length' => 'Nama Mitra minimal 3 karakter.'
        ],
    ];

    protected $skipValidation = false;

    public function getAllMitraWithPksCount()
    {
        return $this->select('mitra_kerja.*, COUNT(pk.id) as pks_count')
                    ->join('perjanjian_kerjasama pk', 'pk.mitra_id = mitra_kerja.id', 'left')
                    ->groupBy('mitra_kerja.id')
                    ->orderBy('mitra_kerja.nama_mitra', 'ASC')
                    ->findAll();
    }
}