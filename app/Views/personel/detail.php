<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Detail Data Personel
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-user-tie me-2 text-primary"></i> Detail Data Personel
    </h1>
    <p class="lead text-muted mb-0">
        Informasi lengkap personel sesuai data kepegawaian dan penempatan.
    </p>
</div>

<div class="card shadow-lg mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-id-card me-2"></i> Identitas: <?= esc($personel['nama']) ?>
        </h5>
        <a href="<?= site_url('personel') ?>" id="btnKembali" class="btn btn-light btn-sm text-primary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <div class="row">

            <div class="col-lg-4 col-xl-3 text-center border-end">

                <div class="p-3 bg-light rounded-3 shadow-sm mb-3">
                    <?php 
                    $foto_path = 'uploads/personel/' . $personel['foto'];
                    $foto_url = base_url($foto_path);
                    $file_exists = !empty($personel['foto']) && file_exists(FCPATH . $foto_path);
                    
                    if ($file_exists): ?>
                    <img src="<?= $foto_url ?>" alt="Foto Personel" class="img-fluid rounded-3 mb-2 border border-2"
                        style="max-height: 350px; width: 100%; object-fit: cover;">
                    <?php else: ?>
                    <img src="<?= base_url('assets/img/image-default.jpg') ?>" alt="No Photo"
                        class="img-fluid rounded-3 mb-2 border border-2 opacity-75"
                        style="max-height: 350px; width: 100%; object-fit: cover;">
                    <?php endif; ?>

                    <?php if ($file_exists): ?>
                    <a href="<?= $foto_url ?>"
                        download="<?= 'Foto_Personel_' . $personel['nrp_nip'] . '_' . $personel['nama'] ?>"
                        class="btn btn-success btn-sm mt-2 w-100">
                        <i class="fas fa-download me-1"></i> Download Foto
                    </a>
                    <?php else: ?>
                    <button class="btn btn-secondary btn-sm mt-2 w-100" disabled>
                        <i class="fas fa-download me-1"></i> Foto Tidak Tersedia
                    </button>
                    <?php endif; ?>
                </div>

                <div class="alert alert-info py-2 px-3 mb-3 d-flex justify-content-between align-items-center small">
                    <strong>Status:</strong>
                    <span class="badge bg-primary fs-6"><?= esc($personel['status_kepegawaian']) ?></span>
                </div>

                <?php if (in_array($personel['status_kepegawaian'], ['BP', 'Perjanjian Kerja']) && !empty($personel['tanggal_berlaku'])): ?>
                <div class="alert alert-warning py-2 px-3 small">
                    **Berlaku Hingga** <span class="fw-bold d-block">
                        <?= format_tanggal_full($personel['tanggal_berlaku']) ?></span>
                </div>
                <?php endif; ?>

            </div>
            <div class="col-lg-8 col-xl-9">

                <div class="row">
                    <div class="col-md-6">

                        <h6 class="text-info border-bottom pb-2 mb-3"><i class="fas fa-address-card me-2"></i> Informasi
                            Identitas</h6>
                        <dl class="row mb-4">
                            <dt class="col-sm-4 text-truncate">Nama Lengkap</dt>
                            <dd class="col-sm-8 fw-bold text-dark mb-2"><?= esc($personel['nama']) ?></dd>

                            <dt class="col-sm-4 text-truncate">NIK</dt>
                            <dd class="col-sm-8 fw-bold text-dark mb-2"><?= esc($personel['nik']) ?></dd>

                            <dt class="col-sm-4 text-truncate">Tgl Lahir</dt>
                            <dd class="col-sm-8 mb-2">
                                <?= esc($personel['tempat_lahir']) ?>,
                                <?= format_tanggal_full($personel['tanggal_lahir']) ?>

                                <!-- <?= date('d F Y', strtotime($personel['tanggal_lahir'])) ?> -->
                            </dd>

                            <dt class="col-sm-4 text-truncate">Agama</dt>
                            <dd class="col-sm-8 mb-2"><?= esc($personel['agama']) ?></dd>
                            <dt class="col-sm-4 text-truncate">Status </dt>
                            <dd class="col-sm-8 mb-2"><?= esc($personel['status_perkawinan']) ?> -
                                <?= esc($personel['jumlah_anak'])?></dd>

                            <dt class="col-sm-4 text-truncate">Alamat</dt>
                            <dd class="col-sm-8 mb-2"><?= nl2br(esc($personel['alamat'])) ?></dd>
                        </dl>

                        <h6 class="text-primary border-bottom pb-2 mb-3"><i class="fas fa-user me-2"></i> Informasi
                            Kedinasan</h6>
                        <dl class="row mb-4">
                            <dt class="col-sm-4 text-truncate">NRP / NIP</dt>
                            <dd class="col-sm-8 mb-2"><?= esc($personel['nrp_nip']) ?></dd>

                            <dt class="col-sm-4 text-truncate">Pangkat / Korps</dt>
                            <dd class="col-sm-8 mb-2"><?= esc($personel['pangkat_ket'])?> /
                                <?=  esc($personel['korps'])  ?></dd>

                            <dt class="col-sm-4 text-truncate">Kategori</dt>
                            <dd class="col-sm-8 mb-2"><?= esc($personel['pangkat_kategori']) ?></dd>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-map-marker-alt me-2"></i>
                            Posisi Saat Ini</h6>
                        <dl class="row mb-4">
                            <dt class="col-sm-4 text-truncate">Penempatan</dt>
                            <dd class="col-sm-8 fw-bold text-dark mb-2"><?= esc($personel['penempatan_nama']) ?></dd>

                            <dt class="col-sm-4 text-truncate">Jabatan</dt>
                            <dd class="col-sm-8 mb-2"><?= esc($personel['jabatan_nama']) ?></dd>
                        </dl>

                        <h6 class="text-dark border-bottom pb-2 mb-3"><i class="fas fa-certificate me-2"></i> Dasar
                            Penempatan</h6>
                        <div class="bg-light p-3 rounded-3 shadow-sm small mb-4">
                            <?= !empty($personel['dasar_penempatan']) ? nl2br(esc($personel['dasar_penempatan'])) : '<em>Tidak ada catatan dasar penempatan.</em>' ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('btnKembali').addEventListener('click', function(e) {
    e.preventDefault();
    // cukup lakukan redirect ke index — DataTable akan restore page otomatis dari sessionStorage
    window.location.href = "<?= base_url('personel') ?>";
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<?= $this->endSection() ?>