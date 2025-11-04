<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        // Cek apakah superadmin sudah ada
        $existing = $userModel->where('username', 'superadmin')->first();

        if ($existing) {
            echo "⚠️ Superadmin sudah ada, tidak dibuat ulang.\n";
            return;
        }

        // Data superadmin default
        $data = [
            'username'      => 'superadmin',
            'password'      => 'admin123_rizqimauludin', // akan otomatis di-hash oleh UserModel
            'nama_lengkap'  => 'Administrator Utama',
            'role'          => 'superadmin',
        ];

        $userModel->insert($data);

        echo "✅ Superadmin berhasil dibuat!\n";
        echo "👉 Username: superadmin\n";
        echo "👉 Password: admin123\n";
    }
}