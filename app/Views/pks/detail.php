<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Detail PKS: <?= $pks['nomor_pks'] ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
    // Definisikan peran yang memiliki akses CRUD/aksi penuh
    $hasFullAccess = in_array($userRole, ['admin', 'pengurus', 'superadmin']);
?>

<!-- BLOK HEADER KONTEN -->
<div class="mb-5 p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h1 class="display-6 fw-bold mb-1 text-dark">
                Detail Perjanjian Kerja Sama
            </h1>
            <p class="lead text-muted">
                Nomor: <span class="badge bg-primary fs-6"><?= esc($pks['nomor_pks']) ?></span>
            </p>
        </div>
        <div class="col-md-5 text-md-end">
            <!-- Tombol Kembali dan Edit -->
            <a href="<?= site_url('mitra/detail/' . $pks['mitra_id']) ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Mitra
            </a>
            <?php if ($hasFullAccess): ?>
            <a href="<?= site_url('pks/edit/' . $pks['id']) ?>" class="btn btn-warning shadow-sm">
                <i class="fas fa-edit me-1"></i> Edit PKS
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">

    <!-- Kolom Kiri: INFORMASI UTAMA PKS -->
    <div class="col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i> Data Kontrak</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        **Nama Mitra**
                        <span class="fw-bold text-primary">
                            <a
                                href="<?= site_url('mitra/detail/' . $pks['mitra_id']) ?>"><?= esc($pks['nama_mitra']) ?></a>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        **Nomor PKS**
                        <span class="fw-bold"><?= esc($pks['nomor_pks']) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        **Nama Proyek**
                        <span class="fw-bold text-success"><?= esc($pks['nama_proyek']) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        **Status PKS**
                        <?php 
                            $status = esc($pks['status_pks']);
                            $badge = 'bg-secondary'; // default value
                            switch ($status) {
                                case 'Aktif':
                                    $badge = 'bg-success';
                                    break;
                                case 'Selesai':
                                    $badge = 'bg-danger';
                                    break;
                                case 'Perpanjangan':
                                    $badge = 'bg-warning text-dark';
                                    break;
                            };
                        ?>
                        <span class="badge <?= $badge ?> text-uppercase fs-6"><?= $status ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        **Nilai Kontrak**
                        <span class="fw-bold text-success">
                            Rp <?= number_format($pks['nilai_kontrak'], 0, ',', '.') ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: JANGKA WAKTU & DOKUMEN -->
    <div class="col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i> Jangka Waktu & Dokumen</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        **Tanggal Mulai**
                        <span class="fw-bold"><?= date('d M Y', strtotime($pks['tanggal_mulai'])) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        **Tanggal Berakhir**
                        <span class="fw-bold text-danger">
                            <?= date('d M Y', strtotime($pks['tanggal_berakhir'])) ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        **Jangka Waktu**
                        <?php 
                            $start = new \DateTime($pks['tanggal_mulai']);
                            $end = new \DateTime($pks['tanggal_berakhir']);
                            $interval = $start->diff($end);
                            $days = $interval->days;
                        ?>
                        <span class="text-secondary"><?= $days ?> Hari</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        **Dokumen PKS (PDF)**
                        <?php if ($pks['file_dokumen']): ?>
                        <span>
                            <a href="<?= base_url($uploadPath . $pks['file_dokumen']) ?>" target="_blank"
                                class="text-primary fw-bold btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen
                            </a>
                        </span>
                        <?php else: ?>
                        <span class="text-danger fst-italic">Dokumen Belum Diupload</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Kolom Bawah: KETERANGAN -->
    <div class="col-12 mb-4">
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Keterangan Tambahan</h5>
            </div>
            <div class="card-body">
                <p class="mt-2 mb-0 text-muted fst-italic">
                    <?= nl2br(esc($pks['keterangan'])) ?: 'Tidak ada keterangan khusus untuk PKS ini.' ?>
                </p>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Memuat Font Awesome jika belum ada di layout -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<?= $this->endSection() ?>