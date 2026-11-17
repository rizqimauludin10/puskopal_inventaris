<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Rekap Personel per Penempatan
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
.personel-table {
    opacity: 0;
    transform: translateY(10px);
    transition: opacity 0.4s ease, transform 0.4s ease;
}

.personel-table.show {
    opacity: 1;
    transform: translateY(0);
}
</style>
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div class="me-3">
            <h4 class="fw-bold mb-1">📋 Rekap Personel per Penempatan</h4>
            <p class="text-muted mb-0">Klik salah satu penempatan di bawah ini untuk melihat daftar personel di
                dalamnya.</p>
        </div>
        <a href="<?= base_url('personel') ?>" class="btn btn-outline-secondary btn-lg">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="accordion" id="accordionRekap">
        <?php foreach ($penempatanList as $index => $penempatan): ?>
        <div class="accordion-item shadow-sm mb-2">
            <h2 class="accordion-header" id="heading<?= $index ?>">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>"
                    data-penempatan-id="<?= $penempatan['id'] ?>">

                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <span><?= esc($penempatan['nama_penempatan']) ?></span>
                        <span class="badge bg-success" style="min-width: 100px; text-align: center;">
                            <?= esc($penempatan['jumlah_personel']) ?> Personel
                        </span>
                    </div>
                </button>
            </h2>

            <div id="collapse<?= $index ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index ?>"
                data-bs-parent="#accordionRekap">
                <div class="accordion-body">
                    <div class="text-center py-3" id="loading-<?= $penempatan['id'] ?>" style="display:none;">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted small mt-2">Memuat data personel...</p>
                    </div>
                    <div id="personel-container-<?= $penempatan['id'] ?>"></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accordionButtons = document.querySelectorAll('.accordion-button');

    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const penempatanId = this.getAttribute('data-penempatan-id');
            const container = document.getElementById(`personel-container-${penempatanId}`);
            const loading = document.getElementById(`loading-${penempatanId}`);

            // Cegah load ulang jika data sudah ditampilkan
            if (container.innerHTML.trim() !== '') return;

            loading.style.display = 'block';

            fetch(`<?= base_url('rekap/getPersonelByPenempatan') ?>/${penempatanId}`)
                .then(response => response.text())
                .then(html => {
                    loading.style.display = 'none';
                    container.innerHTML = html;
                })
                .catch(err => {
                    loading.style.display = 'none';
                    container.innerHTML =
                        `<div class="text-danger text-center">Gagal memuat data.</div>`;
                });
        });
    });
});
</script>

<?= $this->endSection() ?>