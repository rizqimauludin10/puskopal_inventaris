<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Manajemen Pengguna
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
    // Definisikan peran yang memiliki akses CRUD/aksi penuh
    $hasFullAccess = in_array($userRole, ['admin', 'superadmin']);
?>

<div class="mb-5 p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold mb-1 text-dark">
                <i class="fas fa-users-cog me-2 text-primary"></i> Manajemen Pengguna
            </h1>
            <p class="lead text-muted">
                Daftar lengkap pengguna sistem dan hak akses mereka.
            </p>
        </div>
        <?php if ($hasFullAccess): ?>
        <div class="col-md-4 text-md-end">
            <a href="<?= site_url('users/create') ?>" class="btn btn-lg btn-primary shadow-sm">
                <i class="fas fa-user-plus me-2"></i> Tambah Pengguna
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna Aktif</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="userTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Dibuat Pada</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= esc($user['nama_lengkap']) ?></td>
                        <td><?= esc($user['username']) ?></td>
                        <td>
                            <?php 
                                $role = strtolower($user['role']);
                                $badgeClass = 'bg-secondary';
                                if ($role === 'superadmin') $badgeClass = 'bg-danger';
                                else if ($role === 'admin') $badgeClass = 'bg-warning text-dark';
                                else if ($role === 'pengurus') $badgeClass = 'bg-info';
                                else if ($role === 'anggota') $badgeClass = 'bg-success';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= ucfirst($role) ?></span>
                        </td>
                        <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>

                        <td class="text-center">
                            <?php if ($hasFullAccess): ?>
                            <a href="<?= site_url('users/edit/'.$user['id']) ?>" class="btn btn-sm btn-warning me-1"
                                title="Edit Pengguna">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal" data-id="<?= $user['id'] ?>"
                                data-name="<?= $user['nama_lengkap'] ?>" title="Hapus Pengguna">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if ($hasFullAccess): ?>
<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi
                    Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pengguna **<span id="userName" class="fw-bold"></span>** secara
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
    $('#userTable').DataTable({
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
            emptyTable: "<i class='fas fa-info-circle me-1'></i> Belum ada data pengguna.",
        }
    });

    <?php if ($hasFullAccess): ?>
    // LOGIKA MODAL HAPUS
    $('.delete-btn').on('click', function() {
        var userId = $(this).data('id');
        var userName = $(this).data('name');

        $('#userName').text(userName);

        // Anda harus membuat route delete di UserController
        var deleteUrl = '<?= site_url('users/delete/') ?>' + userId;

        $('#confirmDeleteLink').attr('href', deleteUrl);
    });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>