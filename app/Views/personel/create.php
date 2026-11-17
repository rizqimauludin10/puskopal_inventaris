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
                <!-- ===================================
                     KOLOM KIRI : DATA IDENTITAS
                ===================================== -->
                <div class="col-lg-6">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-user me-2"></i> Data Identitas Personel
                    </h6>

                    <div class="mb-3">
                        <label for="nama" class="form-label fw-bold">Nama Lengkap <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" required
                            placeholder="Masukkan nama lengkap personel">
                    </div>

                    <div class="mb-3">
                        <label for="nik" class="form-label fw-bold">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" name="nik" id="nik" class="form-control" maxlength="16"
                            placeholder="Masukkan 16 digit NIK">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label fw-bold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                placeholder="Contoh: Surabaya">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status_perkawinan" class="form-label fw-bold">Status Perkawinan</label>
                            <select name="status_perkawinan" id="status_perkawinan" class="form-select">
                                <option value="">-- Pilih Status --</option>
                                <option value="Tidak Kawin">Tidak Kawin</option>
                                <option value="Kawin">Kawin</option>
                                <option value="Cerai Hidup">Cerai Hidup</option>
                                <option value="Cerai Mati">Cerai Mati</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3" id="jumlahAnakWrapper">
                            <label for="jumlah_anak" class="form-label fw-bold">Jumlah Anak</label>
                            <input type="number" name="jumlah_anak" id="jumlah_anak" class="form-control" min="0"
                                placeholder="Masukkan jumlah anak">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="agama" class="form-label fw-bold">Agama</label>
                        <select name="agama" id="agama" class="form-select">
                            <option value="">-- Pilih Agama --</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-bold">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3"
                            placeholder="Masukkan alamat lengkap personel"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label fw-bold">Foto Personel</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, maksimal 2MB.</div>
                    </div>
                </div>

                <!-- ===================================
                    KOLOM KANAN : DATA KEPEGAWAIAN
                ===================================== -->
                <div class="col-lg-6">
                    <h6 class="text-success border-bottom pb-2 mb-3">
                        <i class="fas fa-briefcase me-2"></i> Data Kedinasan Personel
                    </h6>

                    <div class="mb-3">
                        <label for="nrp_nip" class="form-label fw-bold">NRP / NIP <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nrp_nip" id="nrp_nip" class="form-control" required
                            placeholder="Masukkan NRP atau NIP personel">
                    </div>

                    <div class="mb-3">
                        <label for="pangkat_id" class="form-label fw-bold">Pangkat <span
                                class="text-danger">*</span></label>
                        <select name="pangkat_id" id="pangkat_id" class="form-select" required>
                            <option value="">-- Pilih Pangkat --</option>
                            <?php foreach ($pangkat as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= $p['keterangan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Gunakan kolom pencarian untuk mempermudah memilih pangkat.</div>
                    </div>

                    <div class="mb-3">
                        <label for="korps" class="form-label fw-bold">Korps</label>
                        <input type="text" name="korps" id="korps" class="form-control"
                            placeholder="Masukkan Korps personel">
                    </div>

                    <div class="mb-3">
                        <label for="penempatan_id" class="form-label fw-bold">Penempatan <span
                                class="text-danger">*</span></label>
                        <select name="penempatan_id" id="penempatan_id" class="form-select" required>
                            <option value="">-- Pilih Penempatan --</option>
                            <?php foreach ($penempatan as $pen): ?>
                            <option value="<?= $pen['id'] ?>"><?= $pen['nama_penempatan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jabatan_id" class="form-label fw-bold">Jabatan <span
                                class="text-danger">*</span></label>
                        <select name="jabatan_id" id="jabatan_id" class="form-select" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <!-- Opsi akan dimuat otomatis via AJAX -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dasar_penempatan" class="form-label fw-bold">Dasar Penempatan</label>
                        <input type="text" name="dasar_penempatan" id="dasar_penempatan" class="form-control"
                            placeholder="Contoh: SK Kadis No. 123/II/2024">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status Kepegawaian <span
                                class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Tetap">Tetap</option>
                            <option value="BP">BP</option>
                            <option value="Perjanjian Kerja">Perjanjian Kerja</option>
                        </select>
                    </div>

                    <div class="mb-3" id="berlaku-group"" style=" display: none;">
                        <label for="berlaku" class="form-label fw-bold">Tanggal Berlaku Kerja</label>
                        <input type="date" name="berlaku" id="berlaku" class="form-control">
                        <div class="form-text">Hanya diisi untuk status BP atau Perjanjian Kerja.</div>
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
        if (val === 'BP' || val === 'Perjanjian Kerja') {
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