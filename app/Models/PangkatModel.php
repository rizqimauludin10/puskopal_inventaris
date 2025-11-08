<?php

namespace App\Models;

use CodeIgniter\Model;

class PangkatModel extends Model
{
    protected $table            = 'tabel_pangkat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_pangkat', 'keterangan', 'status'];
}