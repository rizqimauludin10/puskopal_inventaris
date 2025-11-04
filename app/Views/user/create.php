<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-user-plus me-2 text-primary"></i> Tambah Pengguna Baru
    </h1>
    <p class="lead text-muted">
        Isi detail akun untuk pengguna sistem yang baru.
    </p>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 p-2">Formulir Pendaftaran Akun</h5>
    </div>
    <div class="card-body">

        <form action="<?= site_url('users/store') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap <span
                        class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                    placeholder="Nama lengkap pengguna" value="<?= old('nama_lengkap') ?>" required>
                <div class="text-danger"><?= $validation->getError('nama_lengkap') ?></div>
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" id="username" class="form-control"
                    placeholder="Username unik untuk login" value="<?= old('username') ?>" required>
                <div class="text-danger"><?= $validation->getError('username') ?></div>
            </div>

            <div class="row">
                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Minimal 6 karakter" required>
                    <div class="text-danger"><?= $validation->getError('password') ?></div>
                </div>

                <!-- Konfirmasi Password -->
                <div class="col-md-6 mb-3">
                    <label for="pass_confirm" class="form-label fw-bold">Konfirmasi Password <span
                            class="text-danger">*</span></label>
                    <input type="password" name="pass_confirm" id="pass_confirm" class="form-control"
                        placeholder="Ketik ulang password" required>
                    <div class="text-danger"><?= $validation->getError('pass_confirm') ?></div>
                </div>
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="form-label fw-bold">Hak Akses (Role) <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select" required>
                    <option value="">-- Pilih Hak Akses --</option>
                    <?php foreach ($roles as $role): ?>
                    <option value="<?= esc($role) ?>" <?= (old('role') == $role) ? 'selected' : '' ?>>
                        <?= ucwords(esc($role)) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="text-danger"><?= $validation->getError('role') ?></div>
            </div>


            <hr class="mt-4">
            <button type="submit" class="btn btn-primary btn-lg shadow-sm me-2">
                <i class="fas fa-save me-1"></i> Simpan Pengguna
            </button>
            <a href="<?= site_url('users') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Batalkan
            </a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>