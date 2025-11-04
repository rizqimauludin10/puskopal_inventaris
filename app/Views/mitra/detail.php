<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Detail Mitra: <?= esc($mitra['nama_mitra']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
    // Definisikan peran yang memiliki akses CRUD/aksi penuh
    $hasFullAccess = in_array($userRole, ['admin', 'pengurus' , 'superadmin']);
?>

<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-handshake me-2 text-primary"></i> Detail Mitra Kerja
    </h1>
    <p class="lead text-muted">
        Informasi detail perusahaan dan daftar Perjanjian Kerja Sama (PKS) yang terkait.
    </p>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-info text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-building me-2"></i> Data Perusahaan Mitra</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th width="40%">Nama</th>
                        <td>: <?= esc($mitra['nama_mitra']) ?></td>
                    </tr>
                    <tr>
                        <th width="40%">Nama PIC</th>
                        <td>: <?= esc($mitra['pic']) ?></td>
                    </tr>

                    <tr>
                        <th>Telepon</th>
                        <td>: <?= esc($mitra['no_telepon'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>: <?= esc($mitra['email'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: <?= nl2br(esc($mitra['alamat'])) ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:
                            <?php 
                                $status = esc($mitra['status']);
                                $badgeClass = ($status == 'aktif') ? 'bg-success' : 'bg-secondary';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>: <?= date('d M Y', strtotime($mitra['created_at'])) ?></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="<?= site_url('mitra') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <?php if ($hasFullAccess): ?>
                <a href="<?= site_url('mitra/edit/' . $mitra['id']) ?>" class="btn btn-warning btn-sm text-dark">
                    <i class="fas fa-edit me-1"></i> Edit Mitra
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold"><i class="fas fa-file-contract me-2"></i> Daftar Perjanjian Kerja Sama (PKS)
                </h6>
                <?php if ($hasFullAccess): ?>
                <a href="<?= site_url('pks/create/' . $mitra['id']) ?>" class="btn btn-sm btn-success fw-bold">
                    <i class="fas fa-plus me-1"></i> Tambah PKS Baru
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="pksTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th>Nomor & Proyek</th>
                                <th width="15%">Mulai</th>
                                <th width="15%">Berakhir</th>
                                <th width="10%">Status</th>
                                <th width='20%' class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pksList)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada Perjanjian Kerja Sama yang
                                    dicatat untuk mitra ini.</td>
                            </tr>
                            <?php else: ?>
                            <?php $no = 1; ?>
                            <?php foreach ($pksList as $pks): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td>
                                    <div class="fw-bold"><?= esc($pks['nama_proyek']) ?></div>
                                    <div class="small text-muted"><?= esc($pks['nomor_pks']) ?></div>
                                </td>
                                <td><?= date('d M Y', strtotime($pks['tanggal_mulai'])) ?></td>
                                <td>
                                    <?php 
                                            $tglBerakhir = strtotime($pks['tanggal_berakhir']);
                                            $isExpired = $tglBerakhir < time();
                                            $isNear = $tglBerakhir < strtotime('+3 months') && $tglBerakhir >= time();
                                            
                                            echo date('d M Y', $tglBerakhir);
                                            
                                            if ($isExpired): 
                                        ?>
                                    <span class="badge bg-danger ms-1">Kadaluarsa</span>
                                    <?php elseif ($isNear): ?>
                                    <span class="badge bg-warning text-dark ms-1">Hampir Berakhir</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                            $status = esc($pks['status_pks']);
                                            $badgeClass = ($status == 'berlaku') ? 'bg-success' : (($status == 'perpanjangan') ? 'bg-info' : 'bg-secondary');
                                        ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('pks/detail/' . $pks['id']) ?>"
                                        class="btn btn-sm btn-info me-1" title="Detail PKS">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($hasFullAccess): ?>
                                    <a href="<?= site_url('pks/edit/' . $pks['id']) ?>"
                                        class="btn btn-sm btn-warning me-1" title="Edit PKS">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-pks"
                                        data-id="<?= $pks['id'] ?>" data-name="<?= esc($pks['nomor_pks']) ?>"
                                        title="Hapus PKS">
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
    </div>
</div>

<?php if ($hasFullAccess): ?>
<div class="modal fade" id="deletePksModal" tabindex="-1" aria-labelledby="deletePksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePksModalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Hapus
                    PKS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus PKS Nomor <strong id="pksNumberDelete"></strong>?</p>
                <p class="text-danger small fw-bold">Aksi ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <form id="deletePksForm" action="" method="get">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {

    // 🎯 PERBAIKAN UTAMA: Hanya inisialisasi DataTables jika ada data
    <?php if (!empty($pksList)): ?>

    // Inisialisasi DataTables PKS
    $('#pksTable').DataTable({
        "order": [
            [0, 'asc']
        ], // Urutkan berdasarkan tanggal mulai
        "paging": true,
        "searching": true,
        "info": true,
        "responsive": true,
        "columnDefs": [{
            "orderable": false,
            "targets": [0, 5]
        }]
    });

    <?php else: ?>
    // Jika data kosong, pastikan tabel tetap terlihat, tapi tanpa DataTables
    console.log('Tidak ada data PKS, DataTables tidak diinisialisasi.');
    <?php endif; ?>

    <?php if ($hasFullAccess): ?>
    // Logika untuk Modal Hapus PKS
    $('#pksTable').on('click', '.btn-delete-pks', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        var actionUrl = '<?= site_url('pks/delete/') ?>' + id;

        $('#pksNumberDelete').text(name);
        $('#deletePksForm').attr('action', actionUrl);

        var deletePksModal = new bootstrap.Modal(document.getElementById('deletePksModal'));
        deletePksModal.show();
    });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>