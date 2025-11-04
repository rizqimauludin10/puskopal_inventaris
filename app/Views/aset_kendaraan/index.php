<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Daftar Aset Kendaraan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Variabel $userRole dikirim dari Controller AsetKendaraan di fungsi index() -->
<?php 
    // Definisikan peran yang memiliki akses CRUD/aksi penuh
    $hasFullAccess = in_array($userRole, ['admin', 'pengurus', 'superadmin']);
?>

<div class="mb-4 p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="display-6 fw-bold mb-1 text-dark">Daftar Aset Kendaraan</h2>
            <p class="lead text-muted">
                Ringkasan seluruh inventaris kendaraan operasional Puskopal Koarmada II.
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if ($hasFullAccess): ?>
            <a href="<?= site_url('asetkendaraan/create') ?>" class="btn btn-lg btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Tambah Aset Baru
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row mb-3">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Kendaraan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count($kendaraan) ?> Unit
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-car fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Kondisi Baik
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                                $baik = array_filter($kendaraan, function($v) {
                                    return $v['kondisi'] == 'Baik';
                                });
                                echo count($baik);
                            ?> Unit
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Kondisi Rusak Ringan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                                $rusak = array_filter($kendaraan, function($v) {
                                    return $v['kondisi'] == 'Rusak Ringan';
                                });
                                echo count($rusak);
                            ?> Unit
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Kondisi Rusak Berat
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                                $rusak = array_filter($kendaraan, function($v) {
                                    return $v['kondisi'] == 'Rusak Berat';
                                });
                                echo count($rusak);
                            ?> Unit
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD: Pajak Mendekati Jatuh Tempo -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card border-start border-4 border-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pajak Hampir Jatuh Tempo
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                                $currentDate = date('Y-m-d');
                                $limitDate = date('Y-m-d', strtotime('+90 days'));
                                $expiringSoon = array_filter($kendaraan, function($v) use ($currentDate, $limitDate) {
                                    return !empty($v['pajak']) 
                                        && $v['pajak'] != '0000-00-00'
                                        && $v['pajak'] >= $currentDate 
                                        && $v['pajak'] <= $limitDate;
                                });
                                echo count($expiringSoon);
                            ?> Unit
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD: Pajak Sudah Kadaluarsa -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card border-start border-4 border-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Pajak Sudah Kadaluarsa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                                $overdue = array_filter($kendaraan, function($v) use ($currentDate) {
                                    return !empty($v['pajak']) 
                                        && $v['pajak'] != '0000-00-00'
                                        && $v['pajak'] < $currentDate;
                                });
                                echo count($overdue);
                            ?> Unit
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Aset Kendaraan</h6>

        <!-- Kolom Kanan: Grup Tombol Aksi -->
        <div class="btn-group">
            <?php if ($hasFullAccess): ?>
            <!-- Tombol Export Excel -->
            <a href="<?= site_url('asetkendaraan/exportExcel') ?>" class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="kendaraanTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Jenis</th>
                        <th>Merk</th>
                        <th>No Polisi</th>
                        <th>Kondisi</th>
                        <th>Masa Berlaku</th>
                        <th>Status Masa Berlaku</th> <!-- Kolom baru -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php if ($kendaraan): ?>
                    <?php foreach ($kendaraan as $row): ?>
                    <?php
                    // Logika masa berlaku
                    $statusLabel = '';
                    $statusClass = '';

                    if ($row['pajak'] == '0000-00-00' || empty($row['pajak'])) {
                        $statusLabel = 'Tidak Ditentukan';
                        $statusClass = 'secondary';
                    } else {
                        $today = new DateTime();
                        $masaBerlaku = new DateTime($row['pajak']);
                        $interval = $today->diff($masaBerlaku)->days;

                        if ($today > $masaBerlaku) {
                            $statusLabel = 'Overdue';
                            $statusClass = 'danger';
                        } elseif ($interval <= 90) {
                            $statusLabel = 'Mendekati Kadaluarsa';
                            $statusClass = 'warning';
                        } else {
                            $statusLabel = 'Aktif';
                            $statusClass = 'success';
                        }
                    }
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= esc($row['nama_jenis']) ?></td>
                        <td><?= esc($row['merk']) ?></td>
                        <td><?= esc($row['nopol']) ?></td>
                        <td>
                            <?php if ($row['kondisi'] == 'Baik'): ?>
                            <span class="badge bg-success">Baik</span>
                            <?php elseif ($row['kondisi'] == 'Rusak Ringan'): ?>
                            <span class="badge bg-warning">Rusak Ringan</span>
                            <?php else : ?>
                            <span class="badge bg-danger">Rusak Berat</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['pajak'] == '0000-00-00' ? '-' : date('d-m-Y', strtotime($row['pajak'])) ?>
                        </td>
                        <td><span class="badge bg-<?= $statusClass ?>"><?= $statusLabel ?></span></td>
                        <td class="text-center">
                            <a href="<?= site_url('asetkendaraan/detail/' . $row['id']) ?>"
                                class="btn btn-sm btn-info me-1" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>

                            <?php if ($hasFullAccess): ?>
                            <a href="<?= site_url('asetkendaraan/edit/' . $row['id']) ?>"
                                class="btn btn-sm btn-warning me-1" title="Edit Aset">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal" data-id="<?= $row['id'] ?>"
                                data-nopol="<?= $row['nopol'] ?>" title="Hapus Aset">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php if ($hasFullAccess): ?>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi
                    Penghapusan Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data aset kendaraan **<span id="asetNopol" class="fw-bold"></span>**
                secara permanen?
                <p class="text-danger mt-2">Aksi ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batalkan
                </button>
                <a href="#" id="confirmDeleteLink" class="btn btn-danger">
                    <i class="fas fa-trash-alt me-1"></i> Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#kendaraanTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "›",
                "previous": "‹"
            },
            emptyTable: "<i class='fas fa-info-circle me-1'></i> Belum ada data Aset Kendaraan.",
        }
    });

    // Cari semua elemen alert dengan class 'alert-dismissible'
    var alertElement = $('.alert-dismissible');

    if (alertElement.length) {
        // Jika alert ditemukan, atur timer
        setTimeout(function() {
            // Ini akan memicu event 'close.bs.alert'
            alertElement.alert('close');
        }, 5000); // 3000 
        // milidetik = 3 detik
    }

    <?php if ($hasFullAccess): ?>
    // KODE BARU UNTUK MODAL HAPUS
    $('.delete-btn').on('click', function() {
        // Ambil data dari tombol yang diklik
        var asetId = $(this).data('id');
        var asetNopol = $(this).data('nopol');

        // 1. Isi Nopol ke dalam body modal
        $('#asetNopol').text(asetNopol);

        // 2. Buat URL hapus dinamis
        var deleteUrl = '<?= site_url('asetkendaraan/delete/') ?>' + asetId;

        // 3. Masukkan URL ke tombol "Ya, Hapus" di dalam modal
        $('#confirmDeleteLink').attr('href', deleteUrl);
    });
    <?php endif; ?>
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<?= $this->endSection() ?>