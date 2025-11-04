<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Edit PKS: <?= esc($pks['nomor_pks']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BLOK HEADER KONTEN -->
<div class="mb-4 p-3 border-bottom">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="display-6 fw-bold mb-1 text-dark">
                <i class="fas fa-edit me-2 text-warning"></i> Edit PKS
            </h1>
            <p class="lead text-muted">
                Mitra: <span class="fw-bold text-primary"><?= esc($mitra['nama_mitra']) ?></span> | PKS: <span
                    class="fw-bold text-secondary"><?= esc($pks['nomor_pks']) ?></span>
            </p>
        </div>
        <a href="<?= site_url('mitra/detail/' . $mitra['id']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail Mitra
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">Formulir Edit Data Perjanjian Kerja Sama (PKS)</h5>
    </div>
    <div class="card-body">

        <!-- Formulir untuk menyimpan perubahan data PKS -->
        <form action="<?= site_url('pks/update/' . $pks['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <!-- Field tersembunyi untuk MITRA ID -->
            <input type="hidden" name="mitra_id" value="<?= esc($mitra['id']) ?>">
            <!-- Field tersembunyi untuk nama file dokumen lama -->
            <input type="hidden" name="old_file_dokumen" value="<?= esc($pks['file_dokumen'] ?? '') ?>">

            <!-- Notifikasi Error Validasi Global -->
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
                <!-- KOLOM KIRI: DETAIL PKS -->
                <div class="col-lg-6">
                    <h6 class="text-info border-bottom pb-2 mb-3"><i class="fas fa-clipboard-list me-2"></i> Detail
                        Kontrak</h6>

                    <!-- Nomor PKS -->
                    <div class="mb-3">
                        <label for="nomor_pks" class="form-label fw-bold">Nomor PKS <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nomor_pks" id="nomor_pks"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nomor_pks'])) ? 'is-invalid' : '' ?>"
                            value="<?= old('nomor_pks', $pks['nomor_pks']) ?>" placeholder="Contoh: PKS/001/PT-ABJ/2024"
                            required>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nomor_pks'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['nomor_pks'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Nama Proyek/Kegiatan -->
                    <div class="mb-3">
                        <label for="nama_proyek" class="form-label fw-bold">Nama Proyek/Kegiatan <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nama_proyek" id="nama_proyek"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nama_proyek'])) ? 'is-invalid' : '' ?>"
                            value="<?= old('nama_proyek', $pks['nama_proyek']) ?>"
                            placeholder="Contoh: Pengadaan Sistem Informasi Logistik" required>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nama_proyek'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['nama_proyek'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Nilai Kontrak -->
                    <div class="mb-3">
                        <label for="nilai_kontrak" class="form-label fw-bold">Nilai Kontrak (Rp)</label>
                        <?php 
                            // Ambil nilai lama atau dari database. Pastikan selalu berupa angka (float)
                            $nilaiKontrakRaw = old('nilai_kontrak', $pks['nilai_kontrak'] ?? 0);
                            
                            // 🎯 PERBAIKAN: Konversi eksplisit ke float. Ini mengatasi kasus string kosong ('').
                            $nilaiKontrakClean = (float) str_replace(['.', ','], ['', '.'], $nilaiKontrakRaw);

                            // Format nilai kontrak yang sudah bersih untuk ditampilkan di input
                            $nilaiKontrakFormatted = number_format($nilaiKontrakClean, 0, ',', '.');
                        ?>
                        <input type="text" name="nilai_kontrak" id="nilai_kontrak"
                            class="form-control text-end <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nilai_kontrak'])) ? 'is-invalid' : '' ?>"
                            value="<?= $nilaiKontrakFormatted ?>">
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nilai_kontrak'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['nilai_kontrak'] ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-text">Opsional. Masukkan angka saja, pemisah ribuan akan ditambahkan otomatis.
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Catatan Tambahan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control"
                            placeholder="Detail singkat mengenai lingkup kerja sama atau ketentuan khusus."><?= old('keterangan', $pks['keterangan'] ?? '') ?></textarea>
                    </div>

                </div>

                <!-- KOLOM KANAN: JANGKA WAKTU & DOKUMEN -->
                <div class="col-lg-6">
                    <h6 class="text-danger border-bottom pb-2 mb-3"><i class="fas fa-calendar-alt me-2"></i> Jangka
                        Waktu & Status</h6>

                    <!-- Tanggal Mulai -->
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label fw-bold">Tanggal Mulai Berlaku <span
                                class="text-danger">*</span></label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['tanggal_mulai'])) ? 'is-invalid' : '' ?>"
                            value="<?= old('tanggal_mulai', $pks['tanggal_mulai']) ?>" required>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['tanggal_mulai'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['tanggal_mulai'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tanggal Berakhir -->
                    <div class="mb-3">
                        <label for="tanggal_berakhir" class="form-label fw-bold">Tanggal Berakhir <span
                                class="text-danger">*</span></label>
                        <input type="date" name="tanggal_berakhir" id="tanggal_berakhir"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['tanggal_berakhir'])) ? 'is-invalid' : '' ?>"
                            value="<?= old('tanggal_berakhir', $pks['tanggal_berakhir']) ?>" required>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['tanggal_berakhir'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['tanggal_berakhir'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Status PKS -->
                    <div class="mb-3">
                        <label for="status_pks" class="form-label fw-bold">Status PKS <span
                                class="text-danger">*</span></label>
                        <select name="status_pks" id="status_pks"
                            class="form-select <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['status_pks'])) ? 'is-invalid' : '' ?>"
                            required>
                            <option value="">-- Pilih Status --</option>
                            <?php $selectedStatus = old('status_pks', $pks['status_pks']); ?>
                            <option value="berlaku" <?= ($selectedStatus == 'berlaku') ? 'selected' : '' ?>>Berlaku
                            </option>
                            <option value="perpanjangan" <?= ($selectedStatus == 'perpanjangan') ? 'selected' : '' ?>>
                                Proses Perpanjangan</option>
                            <option value="selesai" <?= ($selectedStatus == 'selesai') ? 'selected' : '' ?>>Selesai
                            </option>
                        </select>
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['status_pks'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['status_pks'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <h6 class="text-success border-bottom pb-2 mb-3 mt-4"><i class="fas fa-file-pdf me-2"></i> Dokumen
                        Fisik</h6>

                    <!-- File Dokumen PKS -->
                    <div class="mb-3">
                        <label for="file_dokumen" class="form-label fw-bold">Upload Dokumen PKS (PDF)</label>
                        <?php if (!empty($pks['file_dokumen'])): ?>
                        <div class="alert alert-info py-2 small">
                            Dokumen saat ini:
                            <a href="<?= base_url('uploads/pks/' . $pks['file_dokumen']) ?>" target="_blank"
                                class="fw-bold text-primary">
                                <i class="fas fa-file-pdf me-1"></i> <?= esc($pks['file_dokumen']) ?>
                            </a>. (Pilih file baru jika ingin mengganti)
                        </div>
                        <?php else: ?>
                        <div class="alert alert-warning py-2 small">Dokumen PKS belum diunggah.</div>
                        <?php endif; ?>

                        <input type="file" name="file_dokumen" id="file_dokumen" accept=".pdf"
                            class="form-control <?= (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['file_dokumen'])) ? 'is-invalid' : '' ?>">
                        <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['file_dokumen'])): ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('errors')['file_dokumen'] ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-text">Maksimal ukuran file 5 MB, format wajib PDF. Jika tidak memilih file
                            baru, dokumen lama akan dipertahankan.</div>
                    </div>
                </div>
            </div>

            <hr class="mt-4">
            <!-- Tombol Submit dan Batal -->
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
// Fungsi untuk memformat angka menjadi format Rupiah (dengan titik sebagai pemisah ribuan)
function formatRupiah(angka) {
    // Hapus semua karakter non-digit dan non-koma
    let number_string = angka.replace(/[^,\d]/g, '').toString();
    let split = number_string.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}

document.addEventListener('DOMContentLoaded', function() {
    const nilaiKontrakInput = document.getElementById('nilai_kontrak');
    const tanggalMulaiInput = document.getElementById('tanggal_mulai');
    const tanggalBerakhirInput = document.getElementById('tanggal_berakhir');

    // 1. Logika format Rupiah
    nilaiKontrakInput.addEventListener('keyup', function() {
        nilaiKontrakInput.value = formatRupiah(this.value);
    });

    // 2. Logika validasi tanggal sisi klien (UX Improvement)
    function updateMinDate() {
        // Ketika tanggal mulai diubah, tanggal berakhir tidak boleh lebih kecil dari tanggal mulai
        if (tanggalMulaiInput.value) {
            tanggalBerakhirInput.min = tanggalMulaiInput.value;
        } else {
            tanggalBerakhirInput.removeAttribute('min');
        }

        // Opsional: Jika tanggal berakhir sudah ada dan lebih kecil dari tanggal mulai baru, kosongkan tanggal berakhir
        // Di form edit, kita tidak mengosongkan langsung, tapi membiarkan validasi server bekerja.
    }

    // Panggil fungsi updateMinDate ketika tanggal mulai berubah
    tanggalMulaiInput.addEventListener('change', updateMinDate);

    // Inisialisasi awal (jika data lama sudah ada)
    updateMinDate();


    // 3. Logika auto-hide alert (5 detik)
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