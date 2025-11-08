<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Daftar Personel
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
    // Periksa role user
    $hasFullAccess = in_array($userRole, ['admin', 'superadmin']);
?>

<div class="mb-4 p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="display-6 fw-bold mb-1 text-dark">Daftar Personel</h2>
            <p class="lead text-muted">
                Ringkasan seluruh personel yang terdaftar di lingkungan Puskopal Koarmada II.
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if ($hasFullAccess): ?>
            <a href="<?= site_url('personel/create') ?>" class="btn btn-lg btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Tambah Personel Baru
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

<!-- Statistik Ringkas -->
<div class="row mb-3">
    <?php
        $total = count($personel);
        $tetap = count(array_filter($personel, fn($p) => $p['status'] === 'Tetap'));
        $bp = count(array_filter($personel, fn($p) => $p['status'] === 'BP'));
        $kontrak = count(array_filter($personel, fn($p) => $p['status'] === 'Perjanjian Kontrak'));
    ?>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Personel</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= $total ?> Orang</div>
                    </div>
                    <i class="fas fa-users fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-success shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Personel Tetap</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= $tetap ?> Orang</div>
                    </div>
                    <i class="fas fa-user-check fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Personel BP</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= $bp ?> Orang</div>
                    </div>
                    <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">Personel Kontrak</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= $kontrak ?> Orang</div>
                    </div>
                    <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Personel -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-primary">Data Personel</h6>
        <?php if ($hasFullAccess): ?>
        <a href="<?= site_url('personel/exportExcel') ?>" class="btn btn-success btn-sm shadow-sm">
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </a>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="personelTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Pangkat</th>
                        <th>Jabatan</th>
                        <th>Penempatan</th>
                        <th>Status</th>
                        <th>Berlaku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($personel as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="text-center">
                            <?php if (!empty($row['foto'])): ?>
                            <img src="<?= base_url('uploads/personel/' . $row['foto']) ?>" alt="Foto" class="rounded"
                                width="70" height="100">
                            <?php else: ?>
                            <img src="<?= base_url('assets/img/default-user.png') ?>" alt="Default" width="50"
                                height="50" class="rounded-circle">
                            <?php endif; ?>
                        </td>
                        <td><?= esc($row['nama']) ?></td>
                        <td><?= esc($row['keterangan']) ?> <br>
                            <span class="badge bg-secondary"><?= $row['status'] ?></span>
                        </td>
                        <td><?= esc($row['nama_jabatan']) ?></td>
                        <td><?= esc($row['nama_penempatan']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'Tetap'): ?>
                            <span class="badge bg-success"><?= $row['status'] ?></span>
                            <?php elseif ($row['status'] == 'BP'): ?>
                            <span class="badge bg-warning text-dark"><?= $row['status'] ?></span>
                            <?php else: ?>
                            <span class="badge bg-danger"><?= $row['status'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['berlaku'] ? date('d-m-Y', strtotime($row['berlaku'])) : '-' ?></td>
                        <td class="text-center">
                            <a href="<?= site_url('personel/detail/' . $row['id']) ?>"
                                class="btn btn-sm btn-info me-1"><i class="fas fa-eye"></i></a>
                            <?php if ($hasFullAccess): ?>
                            <a href="<?= site_url('personel/edit/' . $row['id']) ?>"
                                class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal" data-id="<?= $row['id'] ?>"
                                data-nama="<?= $row['nama'] ?>"><i class="fas fa-trash-alt"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<?php if ($hasFullAccess): ?>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi
                    Hapus Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data personel <strong><span id="personelNama"></span></strong>?
                <p class="text-danger mt-2">Tindakan ini tidak dapat dibatalkan.</p>
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
    $('#personelTable').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "›",
                previous: "‹"
            },
            emptyTable: "<i class='fas fa-info-circle me-1'></i> Belum ada data Personel."
        }
    });

    // Auto close alert
    const alertElement = $('.alert-dismissible');
    if (alertElement.length) {
        setTimeout(() => alertElement.alert('close'), 5000);
    }

    // Event delete modal
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        $('#personelNama').text(nama);
        $('#confirmDeleteLink').attr('href', '<?= site_url('personel/delete/') ?>' + id);
        $('#confirmDeleteModal').modal('show');
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<?= $this->endSection() ?>