<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-edit me-2 text-warning"></i> Edit Pengguna
    </h1>
    <p class="lead text-muted">
        Ubah detail akun untuk pengguna **<?= esc($user['nama_lengkap']) ?>** (ID: <?= $user['id'] ?>).
    </p>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0 p-2">Formulir Perubahan Data Akun</h5>
    </div>
    <div class="card-body">

        <form action="<?= site_url('users/update/' . $user['id']) ?>" method="post">
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap <span
                        class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                    placeholder="Nama lengkap pengguna" value="<?= old('nama_lengkap') ?? esc($user['nama_lengkap']) ?>"
                    required>
                <div class="text-danger"><?= $validation->getError('nama_lengkap') ?></div>
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" id="username" class="form-control"
                    placeholder="Username unik untuk login" value="<?= old('username') ?? esc($user['username']) ?>"
                    required>
                <div class="form-text">Ubah hanya jika perlu mengganti nama pengguna.</div>
                <div class="text-danger"><?= $validation->getError('username') ?></div>
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="form-label fw-bold">Hak Akses (Role) <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select" required>
                    <option value="">-- Pilih Hak Akses --</option>
                    <?php 
                    $currentRole = old('role') ?? $user['role'];
                    foreach ($roles as $role): 
                    ?>
                    <option value="<?= esc($role) ?>" <?= ($currentRole == $role) ? 'selected' : '' ?>>
                        <?= ucwords(esc($role)) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="text-danger"><?= $validation->getError('role') ?></div>
            </div>

            <h6 class="text-danger border-bottom pb-2 mb-3 mt-4">
                <i class="fas fa-lock me-2"></i> Perubahan Password
            </h6>

            <div class="row">
                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label fw-bold">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Kosongkan jika tidak ingin diubah">
                    <div class="form-text">Minimal 6 karakter.</div>
                    <div class="text-danger"><?= $validation->getError('password') ?></div>
                </div>

                <!-- Konfirmasi Password -->
                <div class="col-md-6 mb-3">
                    <label for="pass_confirm" class="form-label fw-bold">Konfirmasi Password Baru</label>
                    <input type="password" name="pass_confirm" id="pass_confirm" class="form-control"
                        placeholder="Ketik ulang password baru">
                    <div class="text-danger"><?= $validation->getError('pass_confirm') ?></div>
                </div>
            </div>

            <hr class="mt-4">
            <button type="submit" class="btn btn-warning btn-lg shadow-sm me-2 text-dark">
                <i class="fas fa-sync-alt me-1"></i> Update Data
            </button>
            <a href="<?= site_url('users') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Batalkan
            </a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>