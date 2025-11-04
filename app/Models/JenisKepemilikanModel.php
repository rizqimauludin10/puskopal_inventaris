<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisKepemilikanModel extends Model
{
    protected $table      = 'kepemilikan_properti';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'jenis',
        'deskripsi'
    ];

}