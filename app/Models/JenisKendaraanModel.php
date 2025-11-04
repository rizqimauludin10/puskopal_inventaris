<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisKendaraanModel extends Model
{
    protected $table      = 'jenis_kendaraan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'jenis',
        'keterangan'
    ];
}