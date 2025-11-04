<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Daftar Aset Tanah & Bangunan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
    // Definisikan peran yang memiliki akses CRUD/aksi penuh
    $hasFullAccess = in_array($userRole, ['admin', 'pengurus', 'superadmin']);
?>


<div class="mb-5 p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold mb-1 text-dark">Daftar Aset Tanah & Bangunan</h1>
            <p class="lead text-muted">
                Ringkasan dan detail seluruh inventaris aset properti Puskopal Koarmada II.
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if ($hasFullAccess): ?>
            <a href="<?= site_url('asettanahbangunan/create') ?>" class="btn btn-lg btn-success shadow-sm">
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

<div class="row mb-4">

    <?php 
        // Hitung data untuk Cards (Asumsi: Anda akan mengganti ini dengan data agregasi dari Controller)
        $totalAset = count($tanah);
        $totalLuasTanah = array_sum(array_column($tanah, 'luas_tanah'));
        $totalLuasBangunan = array_sum(array_column($tanah, 'luas_bangunan'));
        // Hitungan ini bisa dilakukan lebih baik di controller untuk efisiensi
    ?>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-start border-4 border-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Unit Properti
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalAset ?> Unit</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-start border-4 border-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Luas Tanah (M²)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($totalLuasTanah, 2, ',', '.') ?> M²</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-start border-4 border-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Luas Bangunan (M²)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($totalLuasBangunan, 2, ',', '.') ?> M²</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Daftar Aset Tanah dan Bangunan</h6>

        <!-- Kolom Kanan: Grup Tombol Aksi -->
        <div class="btn-group">
            <?php if ($hasFullAccess): ?>
            <!-- Tombol Export Excel -->
            <a href="<?= site_url('asettanahbangunan/exportExcel') ?>" class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tanahTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lokasi</th>
                        <th>Luas Tanah (M²)</th>
                        <th>Luas Bangunan (M²)</th>
                        <th>Kepemilikan</th>
                        <th>Berlaku s/d</th>
                        <th>Keterangan</th>
                        <th width='13%'>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php if (!empty($tanah)): ?>
                    <?php foreach ($tanah as $row): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['lokasi'] ?></td>
                        <td><?= number_format($row['luas_tanah'], 2, ',', '.') ?></td>
                        <td><?= number_format($row['luas_bangunan'], 2, ',', '.') ?></td>
                        <td>
                            <?php 
                                $jenisKepemilikan = strtoupper($row['nama_kepemilikan']);
                                $badgeClass = (strpos($jenisKepemilikan, 'SHM') !== false) ? 'bg-success' : 'bg-info';
                                ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= $jenisKepemilikan ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                                // Cek jika nilainya adalah '0000-00-00' atau kosong/null
                                if (empty($row['berlaku']) || $row['berlaku'] === '0000-00-00'): ?>
                            <span class="text-muted fst-italic">Tidak memiliki masa berlaku</span>
                            <?php else: ?>
                            <?= $row['berlaku'] ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['keterangan'] ?></td>
                        <td class="text-center">
                            <a href="<?= site_url('asettanahbangunan/detail/'.$row['id']) ?>"
                                class="btn btn-sm btn-info me-1" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if ($hasFullAccess): ?>
                            <a href="<?= site_url('asettanahbangunan/edit/'.$row['id']) ?>"
                                class="btn btn-sm btn-warning me-1" title="Edit Aset">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal" data-id="<?= $row['id'] ?>"
                                data-lokasi="<?= $row['lokasi'] ?>" title="Hapus Aset">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
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
                Apakah Anda yakin ingin menghapus data aset **<span id="asetNama" class="fw-bold"></span>** secara
                permanen?
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
    // INISIALISASI DATATABLES
    $('#tanahTable').DataTable({
        "pageLength": 10, // Ubah ke 10 agar seragam dengan kendaraan
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
            emptyTable: "<i class='fas fa-info-circle me-1'></i> Belum ada data Aset Properti.",
        },
        // Pastikan kolom Aksi tidak bisa diurutkan
        "columnDefs": [{
            "orderable": false,
            "targets": 7
        }]
    });

    // LOGIKA AUTO-HIDE ALERT (3 DETIK)
    var alertElement = $('.alert-dismissible');
    if (alertElement.length) {
        setTimeout(function() {
            alertElement.alert('close');
        }, 3000);
    }

    <?php if ($hasFullAccess): ?>
    // LOGIKA MODAL HAPUS
    $('.delete-btn').on('click', function() {
        var asetId = $(this).data('id');
        var asetNama = $(this).data('lokasi'); // Mengambil lokasi sebagai nama aset

        $('#asetNama').text(asetNama);

        var deleteUrl = '<?= site_url('asettanahbangunan/delete/') ?>' + asetId;

        $('#confirmDeleteLink').attr('href', deleteUrl);
    });
    <?php endif; ?>
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<?= $this->endSection() ?>