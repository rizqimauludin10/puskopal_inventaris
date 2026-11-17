<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Dashboard Utama
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-3">

    <!-- 🔹 KPI Summary -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Aset Kendaraan</h6>
                    <h4 class="fw-bold"><?= $summary['total_aset_kendaraan'] ?? 0 ?></h4>
                    <small class="text-primary"><i class="fas fa-car me-1"></i>Kendaraan</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Aset Properti</h6>
                    <h4 class="fw-bold"><?= $summary['total_aset_tanah'] ?? 0 ?></h4>
                    <small class="text-success"><i class="fas fa-building me-1"></i>Tanah & Bangunan</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">PKS Aktif</h6>
                    <h4 class="fw-bold"><?= $summary['totalPks'] ?? 0 ?></h4>
                    <small class="text-warning"><i class="fas fa-handshake me-1"></i>Perjanjian</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Personel</h6>
                    <h4 class="fw-bold"><?= count($summary['personelAll'] ?? []) ?></h4>
                    <small class="text-info"><i class="fas fa-users me-1"></i>Personel</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 Tabs Navigation -->
    <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="tabPeringatan-tab" data-bs-toggle="tab" data-bs-target="#tabPeringatan"
                type="button">Peringatan</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tabAset-tab" data-bs-toggle="tab" data-bs-target="#tabAset"
                type="button">Aset</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tabPersonel-tab" data-bs-toggle="tab" data-bs-target="#tabPersonel"
                type="button">Personel</button>
        </li>
    </ul>

    <!-- 🔹 Tab Content -->
    <div class="tab-content mt-3" id="dashboardTabsContent">

        <!-- 🟡 Tab PERINGATAN -->
        <div class="tab-pane fade show active" id="tabPeringatan" role="tabpanel">

            <div class="accordion" id="accordionPeringatan">

                <!-- Peringatan Kendaraan -->
                <div class="accordion-item mb-2">
                    <h2 class="accordion-header" id="headingKendaraan">
                        <button class="accordion-button bg-warning text-dark fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseKendaraan">
                            <i class="fas fa-car me-2"></i> Pajak Kendaraan Tahunan (60 Hari)
                        </button>
                    </h2>
                    <div id="collapseKendaraan" class="accordion-collapse collapse show"
                        data-bs-parent="#accordionPeringatan">
                        <div class="accordion-body p-2">

                        </div>
                    </div>
                </div>

                <!-- Peringatan PKS -->
                <div class="accordion-item mb-2">
                    <h2 class="accordion-header" id="headingPKS">
                        <button class="accordion-button collapsed bg-info text-dark fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapsePKS">
                            <i class="fas fa-handshake me-2"></i> PKS Mendekati Kedaluwarsa (90 Hari)
                        </button>
                    </h2>
                    <div id="collapsePKS" class="accordion-collapse collapse" data-bs-parent="#accordionPeringatan">
                        <div class="accordion-body p-2">

                        </div>
                    </div>
                </div>

                <!-- Peringatan Personel BP -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPersonel">
                        <button class="accordion-button collapsed bg-danger text-white fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapsePersonel">
                            <i class="fas fa-user-clock me-2"></i> Personel BP (60 Hari)
                        </button>
                    </h2>
                    <div id="collapsePersonel" class="accordion-collapse collapse"
                        data-bs-parent="#accordionPeringatan">
                        <div class="accordion-body p-2">

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- 🟢 Tab ASET -->
        <div class="tab-pane fade" id="tabAset" role="tabpanel">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-chart-pie me-2"></i> Distribusi Kondisi Kendaraan
                        </div>
                        <div class="card-body">
                            <canvas id="chartKendaraan"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-chart-area me-2"></i> Distribusi Kepemilikan Tanah
                        </div>
                        <div class="card-body">
                            <canvas id="chartProperti"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔵 Tab PERSONEL -->
        <div class="tab-pane fade" id="tabPersonel" role="tabpanel">
            <div class="card shadow-sm border-start border-danger border-4">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-users me-2"></i> Distribusi Personel per Penempatan
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Tambahkan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxK = document.getElementById('chartKendaraan');
if (ctxK) {
    new Chart(ctxK, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($summary['chart_kendaraan_kondisi']['labels'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($summary['chart_kendaraan_kondisi']['counts'] ?? []) ?>,
                borderWidth: 1
            }]
        }
    });
}
const ctxP = document.getElementById('chartProperti');
if (ctxP) {
    new Chart(ctxP, {
        type: 'pie',
        data: {
            labels: <?= json_encode($summary['chart_tanah_kepemilikan']['labels'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($summary['chart_tanah_kepemilikan']['counts'] ?? []) ?>,
                borderWidth: 1
            }]
        }
    });
}
</script>

<?= $this->endSection() ?>