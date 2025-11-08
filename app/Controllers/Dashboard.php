<?php

namespace App\Controllers;

use App\Models\AsetKendaraanModel;
use App\Models\AsetTanahBangunanModel;
use App\Models\PerjanjianKerjaSamaModel;
use App\Models\MitraKerjaModel;

class Dashboard extends BaseController
{
    protected $kendaraanModel;
    protected $tanahModel;
    protected $mitraModel;
    protected $pksModel;

    public function __construct()
    {
        // Memuat Model Aset, Mitra, dan PKS
        $this->kendaraanModel = new AsetKendaraanModel();
        $this->tanahModel = new AsetTanahBangunanModel();
        $this->mitraModel = new MitraKerjaModel();
        $this->pksModel = new PerjanjianKerjaSamaModel();
    }

    /**
     * Menampilkan halaman dashboard dengan ringkasan data.
     */
    public function index()
    {
        // --- 1. PENGAMBILAN DATA RINGKASAN UTAMA (KPIs) ---
        
        // Aset Kendaraan
        $totalKendaraan = $this->kendaraanModel->countAllResults();
        
        // Aset Tanah & Bangunan
        $totalProperti = $this->tanahModel->countAllResults();
        
        // Ambil total luas tanah untuk KPI
        $luasTanahResult = $this->tanahModel->selectSum('luas_tanah')->get()->getRow();
        $totalLuasTanah = $luasTanahResult ? $luasTanahResult->luas_tanah : 0;

        // Ambil total luas bangunan untuk KPI
        $luasBangunanResult = $this->tanahModel->selectSum('luas_bangunan')->get()->getRow();
        $totalLuasBangunan = $luasBangunanResult ? $luasBangunanResult->luas_bangunan : 0;

        // Mitra & PKS
        $totalMitra = $this->mitraModel->countAll();
        $totalPks = $this->pksModel->getTotalPks();
        
        // --- FITUR BARU PKS KADALUARSA (90 HARI) DENGAN DEDUPLIKASI ---
        $rawExpiringPksList = $this->pksModel->getExpiringPksList(90);
        $uniquePks = [];

        // Logika Deduplikasi: Gunakan ID PKS sebagai kunci untuk memastikan setiap PKS hanya muncul satu kali
        foreach ($rawExpiringPksList as $pks) {
            $uniquePks[$pks['id']] = $pks;
        }

        // Konversi kembali ke array berindeks numerik
        $expiringPksList = array_values($uniquePks);
        $expiringPksCount = count($expiringPksList); 
        
        // --- 2. KUMPULKAN DATA UNTUK CHART Kendaraan (Distribusi Kondisi) ---
        $kondisiData = $this->kendaraanModel
            ->select('kondisi, COUNT(id) as count')
            ->groupBy('kondisi')
            ->findAll();

        $chartKendaraan = [
            'labels' => array_column($kondisiData, 'kondisi'),
            'counts' => array_column($kondisiData, 'count'),
        ];
        
        // --- 3. KUMPULKAN DATA UNTUK CHART Properti (Distribusi Kepemilikan) ---
        $kepemilikanData = $this->tanahModel->getKepemilikanDistribution();

        $chartProperti = [
            'labels' => array_column($kepemilikanData, 'nama_kepemilikan'),
            'counts' => array_column($kepemilikanData, 'count'),
        ];

        // --- 4. KUMPULKAN PERINGATAN DOKUMEN KEDALUWARSA (Aset) ---
        $expiringDocs = $this->getExpiringDocuments();
        
        // --- 5. SATUKAN SEMUA DATA KE DALAM ARRAY $summary ---
        $summary = [
            // Aset KPIs
            'total_aset_kendaraan' => $totalKendaraan,
            'total_aset_tanah' => $totalProperti,
            'total_luas_tanah' => $totalLuasTanah,
            'total_luas_bangunan' => $totalLuasBangunan,
            
            // Chart Data
            'chart_kendaraan_kondisi' => $chartKendaraan,
            'chart_tanah_kepemilikan' => $chartProperti,
            
            // Peringatan Kadaluarsa
            // 'expiring_docs' => $expiringDocs,
            
            'expiring_5_tahunan' => $expiringDocs['pajak_5_tahunan'],
            'expiring_tahunan'   => $expiringDocs['pajak_tahunan'],
            'expiring_properti'  => $expiringDocs['properti'],
            // PKS & Mitra KPIs
            'totalMitra' => $totalMitra,
            'totalPks' => $totalPks,
            
            // PKS Kadaluarsa (Data yang sudah bersih)
            'expiringPks' => $expiringPksCount, // Total Count
            'expiringPksList' => $expiringPksList, // List Detail PKS
        ];

        $data['summary'] = $summary;
        $data['assets'] = $this->tanahModel->getAssetsForMap();
        return view('dashboard', $data); 
    }
    
    /**
     * Metode pembantu untuk mengambil dokumen yang akan kedaluwarsa.
     * Menggabungkan data kendaraan (pajak) dan properti (berlaku).
     */
    private function getExpiringDocuments_old()
    {
        $limitDate = date('Y-m-d', strtotime('+90 days'));
        $currentDate = date('Y-m-d');
        $expiring = [];

        // Ambil data Kendaraan yang pajaknya akan habis dalam 90 hari
        $expKendaraan = $this->kendaraanModel
            ->where('pajak >=', $currentDate)
            ->where('pajak <=', $limitDate)
            ->findAll();

        foreach ($expKendaraan as $item) {
            $expiring[] = [
                'jenis' => 'Kendaraan',
                'id' => $item['id'],
                'lokasi_id' => $item['nopol'],
                'dokumen' => 'Pajak STNK',
                'berlaku_sd' => $item['pajak'],
                'url' => site_url('asetkendaraan/edit/' . $item['id']),
            ];
        }

        // Ambil data Properti yang masa berlakunya akan habis dalam 90 hari
        $expProperti = $this->tanahModel
            ->where('berlaku IS NOT NULL') // Pastikan field berlaku tidak null
            ->where('berlaku >=', $currentDate)
            ->where('berlaku <=', $limitDate)
            ->findAll();

        foreach ($expProperti as $item) {
            $expiring[] = [
                'jenis' => 'Properti',
                'id' => $item['id'],
                'lokasi_id' => $item['lokasi'],
                'dokumen' => 'Masa Berlaku Dokumen (' . $item['detail_kepemilikan'] . ')',
                'berlaku_sd' => $item['berlaku'],
                'url' => site_url('asettanahbangunan/edit/' . $item['id']),
            ];
        }

        // Urutkan berdasarkan tanggal kedaluwarsa terdekat
        usort($expiring, function($a, $b) {
            return strtotime($a['berlaku_sd']) - strtotime($b['berlaku_sd']);
        });

        return $expiring;
    }

    private function getExpiringDocuments()
    {
        $limitDate5Th   = date('Y-m-d', strtotime('+90 days')); // Batas 90 hari utk pajak 5 tahunan
        $limitDate1Th   = date('Y-m-d', strtotime('+60 days')); // Batas 60 hari utk pajak tahunan
        $currentDate    = date('Y-m-d');

        // Siapkan array terpisah
        $expiring5Th    = [];
        $expiring1Th    = [];
        $expiringProperti = [];

        // ============================
        // Data Kendaraan 5 Tahunan
        // ============================
        $expKendaraan = $this->kendaraanModel
            ->where('pajak IS NOT NULL')
            ->findAll();

        foreach ($expKendaraan as $item) {
            $pajakDate = $item['pajak'];

            if (empty($pajakDate) || $pajakDate === '0000-00-00') continue;

            if ($pajakDate < $currentDate) {
                $status = 'Overdue';
            } elseif ($pajakDate <= $limitDate5Th) {
                $status = 'Expiring Soon';
            } else {
                continue;
            }

            $expiring5Th[] = [
                'jenis'       => 'Kendaraan',
                'id'          => $item['id'],
                'lokasi_id'   => $item['nopol'],
                'merk'        => $item['merk'],
                'dokumen'     => 'Ganti Plat Nomor (5 Tahunan)',
                'berlaku_sd'  => $pajakDate,
                'status'      => $status,
                'catatan'     => $item['catatan'],
                'url'         => site_url('asetkendaraan/edit/' . $item['id']),
            ];
        }

        // ============================
        // Pajak Tahunan
        // ============================
        $expPajakSetahun = $this->kendaraanModel
            ->where('pajak_setahun IS NOT NULL')
            ->findAll();

        foreach ($expPajakSetahun as $item) {
            $pajakDate2 = $item['pajak_setahun'];

            if (empty($pajakDate2) || $pajakDate2 === '0000-00-00') continue;

            if ($pajakDate2 < $currentDate) {
                $status = 'Overdue';
            } elseif ($pajakDate2 <= $limitDate1Th) {
                $status = 'Expiring Soon';
            } else {
                continue;
            }

            $expiring1Th[] = [
                'jenis'       => 'Kendaraan',
                'id'          => $item['id'],
                'lokasi_id'   => $item['nopol'],
                'merk'        => $item['merk'],
                'dokumen'     => 'Pajak Tahunan',
                'berlaku_sd'  => $pajakDate2,
                'status'      => $status,
                'catatan'     => $item['catatan'],
                'url'         => site_url('asetkendaraan/edit/' . $item['id']),
            ];
        }

        // ============================
        // Data Properti
        // ============================
        $expProperti = $this->tanahModel
            ->where('berlaku IS NOT NULL')
            ->findAll();

        foreach ($expProperti as $item) {
            $berlakuDate = $item['berlaku'];

            if (empty($berlakuDate) || $berlakuDate === '0000-00-00') continue;

            if ($berlakuDate < $currentDate) {
                $status = 'Overdue';
            } elseif ($berlakuDate <= $limitDate5Th) {
                $status = 'Expiring Soon';
            } else {
                continue;
            }

            $expiringProperti[] = [
                'jenis'       => 'Properti',
                'id'          => $item['id'],
                'lokasi_id'   => $item['lokasi'],
                'dokumen'     => 'Masa Berlaku Dokumen (' . $item['detail_kepemilikan'] . ')',
                'berlaku_sd'  => $berlakuDate,
                'status'      => $status,
                'url'         => site_url('asettanahbangunan/edit/' . $item['id']),
            ];
        }

        // ============================
        // Urutkan berdasarkan tanggal
        // ============================
        $sortByDate = function (&$array) {
            usort($array, function ($a, $b) {
                return strtotime($a['berlaku_sd']) - strtotime($b['berlaku_sd']);
            });
        };

        $sortByDate($expiring5Th);
        $sortByDate($expiring1Th);
        $sortByDate($expiringProperti);

        // ============================
        // Return hasil terpisah
        // ============================
        return [
            'pajak_5_tahunan' => $expiring5Th,
            'pajak_tahunan'   => $expiring1Th,
            'properti'        => $expiringProperti,
        ];
    }



}