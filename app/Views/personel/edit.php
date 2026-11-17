<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Edit Data Personel
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
/* Override agar teks di Select2 center vertikal */
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    display: inline-block;
    /* dari block → inline-block */
    line-height: 1.6;
    /* sesuaikan tinggi teks dengan height form-control */
    padding-left: 8px;
    /* padding kiri */
    padding-right: 20px;
    /* padding kanan untuk arrow */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: middle;
    /* pastikan vertikal center */

}

/* Tinggi container Select2 sesuai input form-control Bootstrap 5 */
.select2-container--bootstrap-5 .select2-selection--single {
    height: 38px;
    /* sama dengan form-control */
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
}

/* Arrow dropdown sejajar dengan teks */
.select2-container--bootstrap-5 .select2-selection__arrow {
    height: 36px;
}

/* Batasi tinggi dropdown dan tambahkan scroll */
.select2-container--bootstrap-5 .select2-results__options {
    max-height: 200px;
    /* tinggi maksimal dropdown */
    overflow-y: auto;
    /* aktifkan scroll vertikal */
}
</style>
<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-user-edit me-2 text-primary"></i> Edit Data Personel
    </h1>
    <p class="lead text-muted">
        Perbarui informasi personel di lingkungan Puskopal Koarmada II.
    </p>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-warning text-white">
        <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> Formulir Edit Data Personel</h5>
    </div>
    <div class="card-body">
        <form action="<?= site_url('personel/update/' . $personel['id']) ?>" method="post"
            enctype="multipart/form-data">

            <div class="row">
                <!-- ====================== KOLOM KIRI ====================== -->
                <div class="col-lg-6">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-user me-2"></i> Data Identitas Personel
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= esc($personel['nama']) ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" name="nik" class="form-control" maxlength="16"
                            value="<?= esc($personel['nik']) ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control"
                                value="<?= esc($personel['tempat_lahir']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control"
                                value="<?= esc($personel['tanggal_lahir']) ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status Perkawinan</label>
                            <select name="status_perkawinan" id="status_perkawinan" class="form-select">
                                <option value="">-- Pilih Status --</option>
                                <?php
                                $statusList = ['Tidak Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];
                                foreach ($statusList as $status) :
                                    $selected = ($personel['status_perkawinan'] == $status) ? 'selected' : '';
                                    echo "<option value='$status' $selected>$status</option>";
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jumlah Anak</label>
                            <input type="number" name="jumlah_anak" class="form-control" min="0"
                                value="<?= esc($personel['jumlah_anak']) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Agama</label>
                        <select name="agama" class="form-select">
                            <option value="">-- Pilih Agama --</option>
                            <?php
                            $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
                            foreach ($agamaList as $agama) :
                                $selected = ($personel['agama'] == $agama) ? 'selected' : '';
                                echo "<option value='$agama' $selected>$agama</option>";
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"><?= esc($personel['alamat']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Personel</label>
                        <?php if (!empty($personel['foto'])) : ?>
                        <div class="mb-2">
                            <img src="<?= base_url('uploads/personel/' . $personel['foto']) ?>" alt="Foto Personel"
                                class="img-thumbnail" width="120">
                        </div>
                        <?php endif; ?>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                    </div>
                </div>

                <!-- ====================== KOLOM KANAN ====================== -->
                <div class="col-lg-6">
                    <h6 class="text-success border-bottom pb-2 mb-3">
                        <i class="fas fa-briefcase me-2"></i> Data Kedinasan Personel
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-bold">NRP / NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nrp_nip" class="form-control" required
                            value="<?= esc($personel['nrp_nip']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pangkat <span class="text-danger">*</span></label>
                        <select name="pangkat_id" id="pangkat_id" class="form-select" required>
                            <option value="">-- Pilih Pangkat --</option>
                            <?php foreach ($pangkat as $p): ?>
                            <option value="<?= $p['id'] ?>"
                                <?= ($p['id'] == $personel['pangkat_id']) ? 'selected' : '' ?>>
                                <?= $p['keterangan'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Korps</label>
                        <input type="text" name="korps" class="form-control" value="<?= esc($personel['korps']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Penempatan <span class="text-danger">*</span></label>
                        <select name="penempatan_id" id="penempatan_id" class="form-select" required>
                            <option value="">-- Pilih Penempatan --</option>
                            <?php foreach ($penempatan as $pen): ?>
                            <option value="<?= $pen['id'] ?>"
                                <?= ($pen['id'] == $personel['penempatan_id']) ? 'selected' : '' ?>>
                                <?= $pen['nama_penempatan'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jabatan <span class="text-danger">*</span></label>
                        <select name="jabatan_id" id="jabatan_id" class="form-select" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <?php foreach ($jabatan as $j): ?>
                            <option value="<?= $j['id'] ?>"
                                <?= ($j['id'] == $personel['jabatan_id']) ? 'selected' : '' ?>>
                                <?= $j['nama_jabatan'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Dasar Penempatan</label>
                        <input type="text" name="dasar_penempatan" class="form-control"
                            value="<?= esc($personel['dasar_penempatan']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Kepegawaian <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">-- Pilih Status --</option>
                            <?php
                            $statusKepegawaian = ['Tetap', 'BP', 'Perjanjian Kerja'];
                            foreach ($statusKepegawaian as $s) :
                                $selected = ($personel['status'] == $s) ? 'selected' : '';
                                echo "<option value='$s' $selected>$s</option>";
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <div class="mb-3" id="berlaku-group"
                        style="display: <?= ($personel['status'] == 'BP' || $personel['status'] == 'Perjanjian Kerja') ? 'block' : 'none' ?>;">
                        <label class="form-label fw-bold">Tanggal Berlaku</label>
                        <input type="date" name="berlaku" id="berlaku" class="form-control"
                            value="<?= esc($personel['berlaku']) ?>">
                    </div>
                </div>
            </div>

            <hr class="mt-4">
            <button type="submit" class="btn btn-warning btn-lg shadow-sm me-2">
                <i class="fas fa-save me-1"></i> Update Data
            </button>
            <a href="<?= site_url('personel') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#pangkat_id').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Pangkat --',
        allowClear: true,
        width: '100%'
    });

    // Event untuk muat jabatan sesuai penempatan
    $('#penempatan_id').on('change', function() {
        const id = $(this).val();
        const jabatanSelect = $('#jabatan_id');
        jabatanSelect.html('<option>Memuat...</option>');
        if (id) {
            fetch(`<?= site_url('personel/getJabatanByPenempatan/') ?>${id}`)
                .then(res => res.json())
                .then(data => {
                    jabatanSelect.html('<option value="">-- Pilih Jabatan --</option>');
                    data.forEach(j => {
                        jabatanSelect.append(
                            `<option value="${j.id}">${j.nama_jabatan}</option>`);
                    });
                });
        }
    });

    // Toggle tanggal berlaku
    const statusSelect = document.getElementById('status');
    const berlakuGroup = document.getElementById('berlaku-group');
    const berlakuInput = document.getElementById('berlaku');

    function toggleBerlaku() {
        if (statusSelect.value === 'BP' || statusSelect.value === 'Perjanjian Kerja') {
            berlakuGroup.style.display = 'block';
            berlakuInput.removeAttribute('disabled');
        } else {
            berlakuGroup.style.display = 'none';
            berlakuInput.value = '';
            berlakuInput.setAttribute('disabled', true);
        }
    }

    // jalankan saat pertama kali load (misal edit form)
    toggleBerlaku();
    statusSelect.addEventListener('change', toggleBerlaku);
});
</script>
<?= $this->endSection() ?>