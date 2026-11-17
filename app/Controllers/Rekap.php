<?php

namespace App\Controllers;

use App\Models\PersonelModel;
use App\Models\PenempatanModel;
use CodeIgniter\Controller;

class Rekap extends Controller
{
    protected $personelModel;
    protected $penempatanModel;

    public function __construct()
    {
        $this->personelModel = new PersonelModel();
        $this->penempatanModel = new PenempatanModel();
    }

    // Halaman utama rekap
    public function index()
    {
        // Ambil daftar penempatan dan jumlah personelnya
        $penempatanList = $this->penempatanModel
            ->select('tabel_penempatan.id, tabel_penempatan.nama_penempatan, COUNT(personel.id) as jumlah_personel')
            ->join('personel', 'personel.penempatan_id = tabel_penempatan.id', 'left')
            ->groupBy('tabel_penempatan.id')
            ->orderBy('tabel_penempatan.id', 'ASC')
            ->findAll();

        return view('personel/rekap_personel', [
            'penempatanList' => $penempatanList
        ]);
    }

    // Endpoint AJAX untuk ambil data personel berdasarkan penempatan
    public function getPersonelByPenempatan($id)
    {
        $personelList = $this->personelModel
            ->select('personel.*, tabel_pangkat.nama_pangkat, tabel_pangkat.keterangan, tabel_pangkat.kategori, tabel_penempatan.nama_penempatan, tabel_jabatan.nama_jabatan')
            ->join('tabel_pangkat', 'tabel_pangkat.id = personel.pangkat_id', 'left')
            ->join('tabel_penempatan', 'tabel_penempatan.id = personel.penempatan_id', 'left')
            ->join('tabel_jabatan', 'tabel_jabatan.id = personel.jabatan_id', 'left')
            ->where('personel.penempatan_id', $id)
            ->orderBy('personel.pangkat_id', 'ASC')
            ->findAll();

        // Hitung total kategori
        $totalKategori = [
            'Militer'  => 0,
            'PNS'     => 0,
            'PPPK'    => 0,
            'Karyawan'=> 0,
        ];

        foreach ($personelList as $p) {
        $kat = $p['kategori']; // ambil kategori dari tabel_pangkat
        if (isset($totalKategori[$kat])) {
            $totalKategori[$kat]++;
        }
    }

        return view('personel/partials/personel_table', [
            'personelList' => $personelList,
            'totalKategori' => $totalKategori
        ]);
    }

}