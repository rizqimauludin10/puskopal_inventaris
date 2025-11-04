<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Manajemen Mitra Kerja
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
    // Definisikan peran yang memiliki akses CRUD/aksi penuh
    $hasFullAccess = in_array($userRole, ['admin', 'pengurus', 'superadmin']);
?>

<div class="mb-5 p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold mb-1 text-dark"><i class="fas fa-handshake me-2"></i> Daftar Mitra Kerja</h1>
            <p class="lead text-muted">
                Ringkasan dan detail seluruh Perusahaan Mitra dan Perjanjian Kerja Sama (PKS) Puskopal.
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if ($hasFullAccess): ?>
            <a href="<?= site_url('mitra/create') ?>" class="btn btn-lg btn-success shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Tambah Mitra Baru
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Flashdata -->
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

<?php 
$totalMitra = count($mitraList);
$mitraAktif = count(array_filter($mitraList, fn($m) => $m['status'] === 'aktif'));
$mitraNonAktif = $totalMitra - $mitraAktif;
?>

<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-start border-4 border-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-primary text-uppercase mb-1">Total Perusahaan Mitra</div>
                        <div class="h5 mb-0 fw-bold"><?= $totalMitra ?> Perusahaan</div>
                    </div>
                    <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-start border-4 border-success shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-success text-uppercase mb-1">Mitra Aktif</div>
                        <div class="h5 mb-0 fw-bold"><?= $mitraAktif ?> Perusahaan</div>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-start border-4 border-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-warning text-uppercase mb-1">Mitra Nonaktif</div>
                        <div class="h5 mb-0 fw-bold"><?= $mitraNonAktif ?> Perusahaan</div>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-primary">Daftar Perusahaan Mitra</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="mitraTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th>Nama Mitra</th>
                        <th width="25%">Kontak</th>
                        <th>Alamat</th>
                        <th width="10%">Status</th>
                        <th width='15%' class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($mitraList)): ?>
                    <?php $no = 1; foreach ($mitraList as $mitra): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="fw-bold text-primary"><?= esc($mitra['nama_mitra']) ?></td>
                        <td>
                            <small class="text-muted"><i
                                    class="fas fa-envelope me-1"></i><?= esc($mitra['email'] ?: 'N/A') ?></small><br>
                            <small class="text-muted"><i
                                    class="fas fa-phone me-1"></i><?= esc($mitra['no_telepon'] ?: 'N/A') ?></small>
                        </td>
                        <td><?= esc(substr($mitra['alamat'] ?: 'N/A', 0, 70)) . (strlen($mitra['alamat']) > 70 ? '...' : '') ?>
                        </td>
                        <td>
                            <span class="badge <?= ($mitra['status'] === 'aktif') ? 'bg-success' : 'bg-secondary' ?>">
                                <?= ucfirst($mitra['status']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <!-- <a href="<?= site_url('mitra/detail/' . $mitra['id']) ?>"
                                class="btn btn-sm btn-info text-white me-1">
                                <i class="fas fa-list-alt me-1"></i> PKS
                            </a> -->
                            <a href="<?= site_url('mitra/detail/' . $mitra['id']) ?>"
                                class="btn btn-sm btn-primary text-white me-1" title="Lihat Daftar PKS">
                                <i class="fas fa-file-contract me-1"></i> PKS (<?= esc($mitra['pks_count']) ?>)
                            </a>
                            <?php if ($hasFullAccess): ?>
                            <a href="<?= site_url('mitra/edit/' . $mitra['id']) ?>" class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal" data-id="<?= $mitra['id'] ?>"
                                data-name="<?= esc($mitra['nama_mitra']) ?>">
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
<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi
                    Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus Mitra <b><span id="mitraNameDelete" class="text-danger"></span></b>?
                <p class="text-danger mt-2 fw-bold">Tindakan ini akan menghapus semua PKS terkait dan tidak dapat
                    dibatalkan.</p>
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
    $('#mitraTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "›",
                previous: "‹"
            },
            emptyTable: "<i class='fas fa-info-circle me-1'></i> Belum ada data Mitra Kerja.",
        },
        columnDefs: [{
            orderable: false,
            targets: 5
        }]
    });


    var alertElement = $('.alert-dismissible');
    if (alertElement.length) {
        setTimeout(function() {
            alertElement.fadeOut('slow', function() {
                $(this).alert('close');
            });
        }, 3000);
    }

    <?php if ($hasFullAccess): ?>
    $('.delete-btn').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#mitraNameDelete').text(name);
        $('#confirmDeleteLink').attr('href', '<?= site_url('mitra/delete/') ?>' + id);
    });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>