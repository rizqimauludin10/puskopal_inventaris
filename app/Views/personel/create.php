<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Tambah Data Personel
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
        <i class="fas fa-user-plus me-2 text-primary"></i> Tambah Data Personel
    </h1>
    <p class="lead text-muted">
        Lengkapi formulir berikut untuk menambahkan data personel baru di lingkungan Puskopal Koarmada II.
    </p>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> Formulir Data Personel</h5>
    </div>
    <div class="card-body">

        <form action="<?= site_url('personel/store') ?>" method="post" enctype="multipart/form-data">
            <div class="row">

                <!-- Kolom Kiri -->
                <div class="col-lg-6">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-user me-2"></i> Identitas Personel
                    </h6>

                    <div class="mb-3">
                        <label for="nama" class="form-label fw-bold">Nama Lengkap <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" required
                            placeholder="Masukkan nama lengkap personel">
                    </div>

                    <div class="mb-3">
                        <label for="pangkat_id" class="form-label fw-bold">Pangkat <span
                                class="text-danger">*</span></label>
                        <select name="pangkat_id" id="pangkat_id" class="form-select" required>
                            <option value="">-- Pilih Pangkat --</option>
                            <?php foreach ($pangkat as $p): ?>
                            <option value="<?= $p['id'] ?>"> <?= $p['keterangan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="nrp_nip" class="form-label fw-bold">NRP / NIP <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nrp_nip" id="nrp_nip" class="form-control" required
                            placeholder="Masukkan NRP atau NIP personel">
                    </div>

                    <div class="mb-3">
                        <label for="penempatan_id" class="form-label fw-bold">Penempatan <span
                                class="text-danger">*</span></label>
                        <select name="penempatan_id" id="penempatan_id" class="form-select" required>
                            <option value="">-- Pilih Penempatan --</option>
                            <?php foreach ($penempatan as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= $p['nama_penempatan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jabatan_id" class="form-label fw-bold">Jabatan <span
                                class="text-danger">*</span></label>
                        <select name="jabatan_id" id="jabatan_id" class="form-select" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <!-- Akan diisi dinamis lewat AJAX -->
                        </select>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-lg-6">
                    <h6 class="text-success border-bottom pb-2 mb-3">
                        <i class="fas fa-clipboard-check me-2"></i> Status dan Administrasi
                    </h6>

                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status Kepegawaian <span
                                class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Tetap">Tetap</option>
                            <option value="BP">BP</option>
                            <option value="Perjanjian Kontrak">Perjanjian Kontrak</option>
                        </select>
                    </div>

                    <div class="mb-3" id="berlaku-group" style="display: none;">
                        <label for="berlaku" class="form-label fw-bold">Tanggal Berlaku</label>
                        <input type="date" name="berlaku" id="berlaku" class="form-control">
                        <div class="form-text">Wajib diisi jika status BP atau Perjanjian Kontrak.</div>
                    </div>

                    <div class="mb-3">
                        <label for="dasar_penempatan" class="form-label fw-bold">Dasar Penempatan</label>
                        <input type="text" name="dasar_penempatan" id="dasar_penempatan" class="form-control"
                            placeholder="Contoh: SK Penempatan No. 123/II/2024">
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label fw-bold">Upload Foto Personel</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                        <div class="form-text">Maksimal ukuran file 2MB. Format: JPG, JPEG, PNG.</div>
                    </div>

                </div>
            </div>

            <hr class="mt-4">

            <button type="submit" class="btn btn-success btn-lg shadow-sm me-2">
                <i class="fas fa-save me-1"></i> Simpan Data
            </button>
            <a href="<?= site_url('personel') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Batalkan
            </a>

        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const penempatanSelect = document.getElementById('penempatan_id');
    const jabatanSelect = document.getElementById('jabatan_id');

    penempatanSelect.addEventListener('change', function() {
        const penempatanId = this.value;
        jabatanSelect.innerHTML = '<option value="">-- Memuat data jabatan... --</option>';

        if (penempatanId) {
            fetch(`<?= site_url('personel/getJabatanByPenempatan/') ?>${penempatanId}`)
                .then(response => response.json())
                .then(data => {
                    jabatanSelect.innerHTML = '<option value="">-- Pilih Jabatan --</option>';
                    data.forEach(jabatan => {
                        const option = document.createElement('option');
                        option.value = jabatan.id;
                        option.textContent = jabatan.nama_jabatan;
                        jabatanSelect.appendChild(option);
                    });
                })
                .catch(err => {
                    console.error('Gagal memuat data jabatan:', err);
                    jabatanSelect.innerHTML =
                        '<option value="">-- Gagal memuat data jabatan --</option>';
                });
        } else {
            jabatanSelect.innerHTML = '<option value="">-- Pilih Jabatan --</option>';
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const berlakuGroup = document.getElementById('berlaku-group');
    const berlakuInput = document.getElementById('berlaku');

    function toggleBerlaku() {
        const val = statusSelect.value;
        if (val === 'BP' || val === 'Perjanjian Kontrak') {
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

    // jalankan setiap kali user ganti status
    statusSelect.addEventListener('change', toggleBerlaku);
});
</script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk dropdown Pangkat
    $('#pangkat_id').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Pangkat --',
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true,
        dropdownCssClass: 'select2-scrollable-dropdown'
    });

    // Supaya tampilan gak aneh di modal (kalau nanti pakai modal)
    $.fn.modal.Constructor.prototype._enforceFocus = function() {};
});
</script>



<?= $this->endSection() ?>