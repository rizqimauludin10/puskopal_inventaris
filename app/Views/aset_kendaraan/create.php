<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Tambah Aset Kendaraan Baru
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-plus-circle me-2 text-primary"></i> Tambah Aset Kendaraan Baru
    </h1>
    <p class="lead text-muted">
        Isi detail spesifikasi dan status administrasi kendaraan operasional.
    </p>
</div>
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Formulir Data Kendaraan</h5>
    </div>
    <div class="card-body">

        <form action="<?= site_url('asetkendaraan/store') ?>" method="post" enctype="multipart/form-data">
            <div class="row">

                <div class="col-lg-6">
                    <h6 class="text-primary border-bottom pb-2 mb-3"><i class="fas fa-car me-2"></i> Data Identitas</h6>

                    <div class="mb-3">
                        <label for="jenis_kendaraan" class="form-label fw-bold">Jenis Kendaraan <span
                                class="text-danger">*</span></label>
                        <select name="jenis_kendaraan" id="jenis_kendaraan" class="form-select" required>
                            <option value="">-- Pilih Jenis Kendaraan --</option>
                            <?php 
                                // Perulangan data jenis kendaraan yang dikirim dari Controller
                                if (isset($jenis) && is_array($jenis)):
                                    foreach ($jenis as $jns): 
                                ?>
                            <option value="<?= $jns['id'] ?>">
                                <?= $jns['jenis'] ?>
                            </option>
                            <?php 
                                    endforeach;
                                endif; 
                                ?>
                        </select>
                        <div class="form-text">Data diambil dari tabel jenis_kendaraan.</div>
                    </div>

                    <div class="mb-3">
                        <label for="merk" class="form-label fw-bold">Merk & Model</label>
                        <input type="text" name="merk" id="merk" class="form-control"
                            placeholder="Contoh: Toyota Avanza, Honda Vario">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tahun" class="form-label fw-bold">Tahun Perolehan</label>
                            <input type="number" name="tahun" id="tahun" class="form-control" placeholder="Contoh: 2023"
                                min="1900" max="<?= date('Y') + 1 ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nopol" class="form-label fw-bold">Nomor Polisi (Nopol)</label>
                            <input type="text" name="nopol" id="nopol" class="form-control text-uppercase"
                                placeholder="Contoh: B 1234 XYZ">
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-hashtag me-2"></i> Nomor
                        Identifikasi</h6>

                    <div class="mb-3">
                        <label for="no_rangka" class="form-label">Nomor Rangka</label>
                        <input type="text" name="no_rangka" id="no_rangka" class="form-control text-uppercase"
                            placeholder="Masukkan No. Rangka kendaraan">
                    </div>

                    <div class="mb-3">
                        <label for="no_mesin" class="form-label">Nomor Mesin</label>
                        <input type="text" name="no_mesin" id="no_mesin" class="form-control text-uppercase"
                            placeholder="Masukkan No. Mesin kendaraan">
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
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bpkb" class="form-label">Nomor BPKB</label>
                        <input type="text" name="bpkb" id="bpkb" class="form-control" placeholder="Masukkan No. BPKB">
                    </div>

                    <div class="mb-3">
                        <label for="pajak" class="form-label">Tanggal Jatuh Tempo Pajak 5 Tahun</label>
                        <input type="date" name="pajak" id="pajak" class="form-control">
                        <div class="form-text">Tanggal ini digunakan untuk pajak 5 tahunan.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="pajak_setahun" class="form-label">Tanggal Jatuh Tempo Pajak Tahunan</label>
                        <input type="date" name="pajak_setahun" id="pajak_setahun" class="form-control">
                        <div class="form-text">Tanggal ini digunakan untuk pajak tahunan.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="dokumen" class="form-label fw-bold">Dokumen Pendukung (PDF)</label>
                        <input type="file" name="dokumen" id="dokumen" class="form-control" accept=".pdf">
                        <div class="form-text">Maksimal ukuran file 5MB. Hanya format PDF yang diizinkan.</div>
                    </div>

                    <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-book me-2"></i> Catatan
                    </h6>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan Tambahan</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="5"
                            placeholder="Tuliskan keterangan tambahan atau riwayat kerusakan..."></textarea>
                    </div>
                </div>

            </div>
            <hr class="mt-4">
            <button type="submit" class="btn btn-success btn-lg shadow-sm me-2">
                <i class="fas fa-save me-1"></i> Simpan Aset
            </button>
            <a href="<?= site_url('asetkendaraan') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Batalkan
            </a>

        </form>
    </div>
</div> <?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<?= $this->endSection() ?>