<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'role', 'nama_lengkap'];
    protected $useTimestamps = true;

    protected $beforeInsert = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }

    // Validasi role (opsional tapi bagus)
    public function isValidRole($role)
    {
        $validRoles = ['superadmin', 'admin', 'pengurus', 'anggota'];
        return in_array($role, $validRoles);
    }
}