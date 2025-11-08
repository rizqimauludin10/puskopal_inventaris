<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Detail Aset: <?= $aset['nopol'] ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 
    // Definisikan peran yang memiliki akses CRUD/aksi penuh
    $hasFullAccess = in_array($userRole, ['admin', 'pengurus', 'superadmin']);

    // --- Tambahan: Logika Status Masa Berlaku ---
    $statusLabel = '';
    $statusClass = '';

    if ($aset['pajak'] == '0000-00-00' || empty($aset['pajak'])) {
        $statusLabel = 'Tidak Ditentukan';
        $statusClass = 'secondary';
    } else {
        $today = new DateTime();
        $masaBerlaku = new DateTime($aset['pajak']);
        $interval = $today->diff($masaBerlaku)->days;

        if ($today > $masaBerlaku) {
            $statusLabel = 'Overdue';
            $statusClass = 'danger';
        } elseif ($interval <= 30) {
            $statusLabel = 'Mendekati Kadaluarsa';
            $statusClass = 'warning';
        } else {
            $statusLabel = 'Aktif';
            $statusClass = 'success';
        }
    }
?>
<div class="mb-5 p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h1 class="display-6 fw-bold mb-1 text-dark">
                Detail Aset: <?= $aset['merk'] ?>
            </h1>
            <p class="lead text-muted">
                Nomor Polisi: <span class="badge bg-secondary fs-6"><?= $aset['nopol'] ?></span>
            </p>
        </div>
        <div class="col-md-5 text-md-end">
            <a href="<?= site_url('asetkendaraan') ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            <?php if ($hasFullAccess): ?>
            <a href="<?= site_url('asetkendaraan/edit/' . $aset['id']) ?>" class="btn btn-warning shadow-sm">
                <i class="fas fa-edit me-1"></i> Edit Data
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Identitas dan Spesifikasi Teknis</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Jenis Kendaraan </strong>
                        <span class="fw-bold"><?= $aset['nama_jenis'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Merk </strong>
                        <span class="fw-bold text-success"><?= $aset['merk'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Tahun Pembuatan </strong>
                        <span><?= $aset['tahun'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Nomor Rangka </strong>
                        <code class="text-danger"><?= $aset['no_rangka'] ?></code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Nomor Mesin</strong>
                        <code class="text-danger"><?= $aset['no_mesin'] ?></code>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i> Status Administrasi dan Kondisi</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Nomor BPKB </strong>
                        <span class="text-secondary"><?= $aset['bpkb'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Kondisi Aset </strong>
                        <span>
                            <?php 
                                $kondisi = $aset['kondisi'];
                                $badge = 'bg-secondary';
                                if ($kondisi == 'Baik') $badge = 'bg-success';
                                elseif ($kondisi == 'Rusak Ringan') $badge = 'bg-warning text-dark';
                                elseif ($kondisi == 'Rusak Berat') $badge = 'bg-danger';
                            ?>
                            <span class="badge <?= $badge ?> text-uppercase">
                                <?= str_replace('_', ' ', $kondisi) ?>
                            </span>
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Masa Berlaku Pajak 5 Tahun </strong>
                        <span>
                            <?= ($aset['pajak'] == '0000-00-00' || empty($aset['pajak'])) 
                                ? '<span class="text-muted fst-italic">Tidak Ditentukan</span>' 
                                : date('d-m-Y', strtotime($aset['pajak'])) ?>
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Masa Berlaku Pajak 1 Tahun </strong>
                        <span>
                            <?= ($aset['pajak_setahun'] == '0000-00-00' || empty($aset['pajak_setahun'])) 
                                ? '<span class="text-muted fst-italic">Tidak Ditentukan</span>' 
                                : date('d-m-Y', strtotime($aset['pajak_setahun'])) ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Status Masa Berlaku</strong>
                        <span class="badge bg-<?= $statusClass ?>"><?= $statusLabel ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Berkas Kendaraan</strong>
                        <?php if ($aset['dokumen']): ?>
                        <span>
                            <a href="<?= base_url('uploads/kendaraan/' . $aset['dokumen']) ?>" target="_blank"
                                class="text-primary fw-bold">
                                <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen
                            </a>
                        </span>
                        <?php else: ?>
                        <span class="text-danger fst-italic">Dokumen Belum Diupload</span>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item">
                        <strong> Catatan Tambahan </strong>
                        <p class="mt-2 mb-0 border-top pt-2 text-dark fst-italic fw-bold fs-4">
                            <?= nl2br($aset['catatan']) ?: 'Tidak ada catatan khusus.' ?>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<?= $this->endSection() ?>