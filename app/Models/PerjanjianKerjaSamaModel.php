<?php

namespace App\Models;

use CodeIgniter\Model;

class PerjanjianKerjasamaModel extends Model
{
    protected $table      = 'perjanjian_kerjasama';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'mitra_id', 
        'nomor_pks', 
        'nama_proyek', 
        'tanggal_mulai', 
        'tanggal_berakhir', 
        'nilai_kontrak', 
        'status_pks',
        'file_dokumen',
        'keterangan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules    = [
        'mitra_id'           => 'required|integer',
        'nomor_pks'          => 'required|max_length[100]',
        'nama_proyek'        => 'required|max_length[255]',
        // 'tanggal_mulai'      => 'required|valid_date',
        // 'tanggal_berakhir'   => 'required|valid_date|greater_than_equal_to[tanggal_mulai]',
    ];

    protected $validationMessages = [
        'mitra_id' => ['required' => 'Mitra ID wajib diisi.'],
        'nomor_pks' => ['required' => 'Nomor PKS wajib diisi.'],
        // 'tanggal_berakhir' => ['greater_than_equal_to' => 'Tanggal Berakhir harus setelah atau sama dengan Tanggal Mulai.'],
    ];

    protected $skipValidation = false;
    
    // Fungsi untuk mendapatkan PKS beserta data Mitra (JOIN)
    public function getPksWithMitra($pksId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('perjanjian_kerjasama.*, mitra_kerja.nama_mitra');
        $builder->join('mitra_kerja', 'mitra_kerja.id = perjanjian_kerjasama.mitra_id');
        
        if ($pksId) {
            return $builder->where('perjanjian_kerjasama.id', $pksId)->get()->getRowArray();
        }

        return $builder->findAll();
    }

    public function getTotalPks()
    {
        return $this->countAll();
    }

    public function countExpiringPks(int $days = 60)
    {
        // Tanggal batas: Hari ini + 60 hari
        $expiryDate = date('Y-m-d', strtotime("+$days days"));
        $today = date('Y-m-d');

        return $this->where('tanggal_berakhir >=', $today)
                    ->where('tanggal_berakhir <=', $expiryDate)
                    ->whereIn('status_pks', ['berlaku', 'perpanjangan'])
                    ->countAllResults();
    }

    public function getExpiringPksList(int $days = 90)
    {
        $expiryDate = date('Y-m-d', strtotime("+$days days"));
        $today = date('Y-m-d');

        return $this->select('pk.id, pk.nama_proyek, pk.tanggal_berakhir, pk.status_pks, pk.mitra_id, mk.nama_mitra')
                    ->from('perjanjian_kerjasama pk')
                    ->join('mitra_kerja mk', 'mk.id = pk.mitra_id')
                    ->where('pk.tanggal_berakhir >=', $today)
                    ->where('pk.tanggal_berakhir <=', $expiryDate)
                    ->whereIn('pk.status_pks', ['berlaku', 'perpanjangan'])
                    ->orderBy('pk.tanggal_berakhir', 'ASC')
                    ->findAll();
    }
}