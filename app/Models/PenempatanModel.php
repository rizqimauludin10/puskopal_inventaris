<?php

namespace App\Models;

use CodeIgniter\Model;

class PenempatanModel extends Model
{
    protected $table            = 'tabel_penempatan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_penempatan', 'keterangan'];
}