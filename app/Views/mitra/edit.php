<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Edit Mitra Kerja: <?= esc($mitra['nama_mitra']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-edit me-2 text-warning"></i> Edit Mitra Kerja
    </h1>
    <p class="lead text-muted">
        Perbarui detail perusahaan mitra **<?= esc($mitra['nama_mitra']) ?>**.
    </p>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">Formulir Edit Data Perusahaan Mitra</h5>
    </div>
    <div class="card-body">

        <form action="<?= site_url('mitra/update/' . $mitra['id']) ?>" method="post">
            <?= csrf_field() ?>
            <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading small fw-bold">Periksa Kembali Data Anda:</h4>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="row">

                <div class="col-lg-6">
                    <h6 class="text-primary border-bottom pb-2 mb-3"><i class="fas fa-building me-2"></i> Detail Utama
                        Perusahaan</h6>

                    <div class="mb-3">
                        <label for="nama_mitra" class="form-label fw-bold">Nama Perusahaan Mitra <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nama_mitra" id="nama_mitra"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nama_mitra'])) ? 'is-invalid' : '' ?>"
                            value="<?= old('nama_mitra', $mitra['nama_mitra']) ?>"
                            placeholder="Contoh: PT. Sinar Abadi Jaya" required>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nama_mitra'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['nama_mitra'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-bold">Alamat Lengkap <span
                                class="text-danger">*</span></label>
                        <textarea name="alamat" id="alamat" rows="4"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['alamat'])) ? 'is-invalid' : '' ?>"
                            placeholder="Masukkan alamat kantor pusat atau lokasi kerja sama utama"
                            required><?= old('alamat', $mitra['alamat']) ?></textarea>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['alamat'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['alamat'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-book me-2"></i> Keterangan
                    </h6>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Catatan Tambahan</label>
                        <textarea name="keterangan" id="keterangan" rows="4" class="form-control"
                            placeholder="Catatan mengenai riwayat kerjasama, preferensi, atau info penting lainnya."><?= old('keterangan', $mitra['keterangan'] ?? '') ?></textarea>
                    </div>

                </div>

                <div class="col-lg-6">
                    <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-id-card me-2"></i> Detail Kontak &
                        Administrasi</h6>

                    <div class="mb-3">
                        <label for="pic" class="form-label fw-bold">Nama Contact Person (PIC)</label>
                        <input type="text" name="pic" id="pic"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['pic'])) ? 'is-invalid' : '' ?>"
                            value="<?= old('pic', $mitra['pic'] ?? '') ?>"
                            placeholder="Nama penanggung jawab di perusahaan mitra">
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['pic'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['pic'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Alamat Email <span
                                class="text-danger">*</span></label>
                        <input type="email" name="email" id="email"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])) ? 'is-invalid' : '' ?>"
                            value="<?= old('email', $mitra['email']) ?>"
                            placeholder="Contoh: kontak@perusahaanmitra.com" required>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['email'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="no_telepon" class="form-label fw-bold">Nomor Telepon/HP <span
                                class="text-danger">*</span></label>
                        <input type="tel" name="no_telepon" id="no_telepon"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['no_telepon'])) ? 'is-invalid' : '' ?>"
                            value="<?= old('no_telepon', $mitra['no_telepon']) ?>" placeholder="Contoh: 081234567890"
                            required>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['no_telepon'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['no_telepon'] ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-text">Masukkan nomor kontak aktif untuk kemudahan komunikasi.</div>
                    </div>

                    <h6 class="text-success border-bottom pb-2 mb-3 mt-4"><i class="fas fa-check-circle me-2"></i>
                        Status</h6>
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status Kemitraan <span
                                class="text-danger">*</span></label>
                        <select name="status" id="status"
                            class="form-select <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['status'])) ? 'is-invalid' : '' ?>"
                            required>
                            <option value="">-- Pilih Status --</option>
                            <?php $selectedStatus = old('status', $mitra['status']); ?>
                            <option value="aktif" <?= ($selectedStatus == 'aktif') ? 'selected' : '' ?>>Aktif Bekerja
                                Sama</option>
                            <option value="nonaktif" <?= ($selectedStatus == 'nonaktif') ? 'selected' : '' ?>>Nonaktif /
                                Potensial</option>
                        </select>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['status'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['status'] ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-text">Tentukan apakah mitra ini sedang aktif dalam PKS atau tidak.</div>
                    </div>

                </div>
            </div>

            <hr class="mt-4">
            <button type="submit" class="btn btn-success btn-lg shadow-sm me-2">
                <i class="fas fa-save me-1"></i> Simpan Perubahan
            </button>
            <a href="<?= site_url('mitra/detail/' . $mitra['id']) ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Batalkan
            </a>
        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// LOGIKA AUTO-HIDE ALERT (5 DETIK)
document.addEventListener('DOMContentLoaded', function() {
    const alertElement = document.querySelector('.alert-dismissible');
    if (alertElement) {
        setTimeout(function() {
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                const bootstrapAlert = bootstrap.Alert.getOrCreateInstance(alertElement);
                bootstrapAlert.close();
            } else {
                alertElement.style.display = 'none';
            }
        }, 5000);
    }
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<?= $this->endSection() ?>