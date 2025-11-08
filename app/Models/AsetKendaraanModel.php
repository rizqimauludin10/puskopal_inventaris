<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetKendaraanModel extends Model
{
    protected $table      = 'aset_kendaraan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'jenis_kendaraan',
        'merk',
        'no_rangka',
        'no_mesin',
        'tahun',
        'nopol',
        'bpkb',
        'kondisi',
        'pajak',
        'pajak_setahun',
        'catatan',
        'dokumen',
    ];

    public function findAllWithJenis()
    {
        return $this->select('aset_kendaraan.*, jenis_kendaraan.jenis AS nama_jenis')
                    ->join('jenis_kendaraan', 'jenis_kendaraan.id = aset_kendaraan.jenis_kendaraan', 'left')
                    ->findAll();
    }

    public function findWithJenis($id)
    {
        return $this->select('aset_kendaraan.*, jenis_kendaraan.jenis AS nama_jenis')
                    ->join('jenis_kendaraan', 'jenis_kendaraan.id = aset_kendaraan.jenis_kendaraan', 'left')
                    ->where('aset_kendaraan.id', $id)
                    ->first(); // Menggunakan first() karena hanya butuh satu baris
    }

    public function getJenisDistribution()
    {
        return $this->select('jenis_kendaraan, COUNT(id) AS count')
                    ->groupBy('jenis_kendaraan')
                    ->findAll();
    }
}