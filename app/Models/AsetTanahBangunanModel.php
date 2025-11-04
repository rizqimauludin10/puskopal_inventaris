<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetTanahBangunanModel extends Model
{
    protected $table      = 'aset_tanah_bangunan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'lokasi',
        'luas_tanah',
        'luas_bangunan',
        'kepemilikan',
        'detail_kepemilikan',
        'berlaku',
        'keterangan',
        'dokumen_legalitas',
        'latitude',
        'longitude'
    ];

    public function findAllWithKepemilikan()
    {
        return $this->select('aset_tanah_bangunan.*, kepemilikan_properti.jenis AS nama_kepemilikan, kepemilikan_properti.deskripsi AS deskripsi_kepemilikan')
                    ->join('kepemilikan_properti', 'kepemilikan_properti.id = aset_tanah_bangunan.kepemilikan', 'left')
                    ->findAll();
    }

    public function findWithKepemilikan($id)
    {
        return $this->select('aset_tanah_bangunan.*, kepemilikan_properti.jenis AS nama_kepemilikan, kepemilikan_properti.deskripsi AS deskripsi_kepemilikan')
                    ->join('kepemilikan_properti', 'kepemilikan_properti.id = aset_tanah_bangunan.kepemilikan', 'left')
                    ->where('aset_tanah_bangunan.id', $id)
                    ->first();
    }

    public function getKepemilikanDistribution()
    {
        return $this->select('kepemilikan_properti.jenis AS nama_kepemilikan, COUNT(aset_tanah_bangunan.id) AS count')
                    ->join('kepemilikan_properti', 'kepemilikan_properti.id = aset_tanah_bangunan.kepemilikan', 'left')
                    ->groupBy('kepemilikan_properti.jenis')
                    ->findAll();
    }

    public function getAssetsForMap(): array
    {
        return $this->select('id, lokasi, latitude, longitude')
                    ->where('latitude IS NOT NULL')
                    ->where('longitude IS NOT NULL')
                    ->where('latitude !=', '') // Pastikan bukan string kosong
                    ->where('longitude !=', '') // Pastikan bukan string kosong
                    ->findAll();
    }
}