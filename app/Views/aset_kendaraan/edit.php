<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Edit Aset Kendaraan: <?= $kendaraan['nopol'] ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-edit me-2 text-warning"></i> Edit Data Aset Kendaraan
    </h1>
    <p class="lead text-muted">
        Ubah data untuk **Nomor Polisi: <?= $kendaraan['nopol'] ?>** (ID: <?= $kendaraan['id'] ?>).
    </p>
</div>
<div class="card shadow mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0 p-2 ">Formulir Perubahan Data Kendaraan</h5>
    </div>
    <div class="card-body">

        <form action="<?= site_url('asetkendaraan/update/' . $kendaraan['id']) ?>" method="post"
            enctype="multipart/form-data">
            <div class="row">

                <div class="col-lg-6">
                    <h6 class="text-primary border-bottom pb-2 mb-3"><i class="fas fa-car me-2"></i> Data Identitas</h6>

                    <div class="mb-3">
                        <select name="jenis_kendaraan" id="jenis_kendaraan" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <?php foreach ($jenis as $jns): ?>
                            <option value="<?= $jns['id'] ?>"
                                <?= ($jns['id'] == $kendaraan['jenis_kendaraan']) ? 'selected' : '' ?>>
                                <?= $jns['jenis'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="merk" class="form-label fw-bold">Merk & Model</label>
                        <input type="text" name="merk" id="merk" class="form-control"
                            placeholder="Contoh: Toyota Avanza, Honda Vario" value="<?= $kendaraan['merk'] ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tahun" class="form-label fw-bold">Tahun Perolehan</label>
                            <input type="number" name="tahun" id="tahun" class="form-control" placeholder="Contoh: 2023"
                                value="<?= $kendaraan['tahun'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nopol" class="form-label fw-bold">Nomor Polisi (Nopol)</label>
                            <input type="text" name="nopol" id="nopol" class="form-control text-uppercase"
                                placeholder="Contoh: B 1234 XYZ" value="<?= $kendaraan['nopol'] ?>">
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-hashtag me-2"></i> Nomor
                        Identifikasi</h6>

                    <div class="mb-3">
                        <label for="no_rangka" class="form-label">Nomor Rangka</label>
                        <input type="text" name="no_rangka" id="no_rangka" class="form-control text-uppercase"
                            placeholder="Masukkan No. Rangka kendaraan" value="<?= $kendaraan['no_rangka'] ?>">
                    </div>

                    <div class="mb-3">
                        <label for="no_mesin" class="form-label">Nomor Mesin</label>
                        <input type="text" name="no_mesin" id="no_mesin" class="form-control text-uppercase"
                            placeholder="Masukkan No. Mesin kendaraan" value="<?= $kendaraan['no_mesin'] ?>">
                    </div>

                </div>

                <div class="col-lg-6">
                    <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-clipboard-list me-2"></i> Status
                        dan Administrasi</h6>

                    <div class="mb-3">
                        <label for="kondisi" class="form-label fw-bold">Kondisi Aset <span
                                class="text-danger">*</span></label>
                        <select name="kondisi" id="kondisi" class="form-select" required>
                            <option value="">-- Pilih Kondisi Saat Ini --</option>
                            <option value="Baik" <?= ($kendaraan['kondisi'] == 'Baik') ? 'selected' : '' ?>>Baik
                            </option>
                            <option value="Rusak Ringan"
                                <?= ($kendaraan['kondisi'] == 'Rusak Ringan') ? 'selected' : '' ?>>Rusak Ringan</option>
                            <option value="Rusak Berat"
                                <?= ($kendaraan['kondisi'] == 'Rusak Berat') ? 'selected' : '' ?>>Rusak Berat</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bpkb" class="form-label">Nomor BPKB</label>
                        <input type="text" name="bpkb" id="bpkb" class="form-control" placeholder="Masukkan No. BPKB"
                            value="<?= $kendaraan['bpkb'] ?>">
                    </div>

                    <div class="mb-3">
                        <label for="pajak" class="form-label">Tanggal Jatuh Tempo Pajak</label>
                        <input type="date" name="pajak" id="pajak" class="form-control"
                            value="<?= $kendaraan['pajak'] ?>">
                        <div class="form-text">Tanggal ini digunakan sebagai acuan untuk monitoring pajak kendaraan.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="dokumen" class="form-label fw-bold">Dokumen Kendaraan (PDF)</label>
                        <input type="file" name="dokumen" id="dokumen" class="form-control" accept=".pdf">
                        <div class="form-text">Maksimal 5MB. Kosongkan jika tidak ada perubahan dokumen.</div>

                        <?php if ($kendaraan['dokumen']): ?>
                        <p class="mt-2 mb-0">
                            **File Tersimpan:** <a href="<?= base_url('uploads/kendaraan/' . $kendaraan['dokumen']) ?>"
                                target="_blank" class="text-primary fw-bold">
                                <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen
                            </a>
                            <input type="hidden" name="dokumen_lama" value="<?= $kendaraan['dokumen'] ?>">
                        </p>
                        <?php endif; ?>
                    </div>

                    <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-book me-2"></i> Catatan
                    </h6>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan Tambahan</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="5"
                            placeholder="Tuliskan keterangan tambahan atau riwayat kerusakan..."><?= $kendaraan['catatan'] ?></textarea>
                    </div>
                </div>

            </div>
            <hr class="mt-4">
            <button type="submit" class="btn btn-warning btn-lg shadow-sm me-2">
                <i class="fas fa-sync-alt me-1"></i> Update Data
            </button>
            <a href="<?= site_url('asetkendaraan')?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Batalkan
            </a>

        </form>
    </div>
</div> <?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<?= $this->endSection() ?>