<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Dashboard Utama Inventaris Aset
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
/* Wrapper posisi marker */
.pulse-wrapper {
    position: absolute;
    transform: translate(-50%, -50%);
    cursor: pointer;
}

/* Titik pusat marker */
.pulse-dot {
    width: 18px;
    height: 18px;
    background-color: #0F4CFF;
    /* Hijau TNI */
    border-radius: 50%;
    box-shadow: 0 0 15px rgba(0, 38, 128, 0.8);
    position: relative;
    z-index: 3;
}

/* Ring gelombang umum */
.pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin-left: -10px;
    margin-top: -10px;
    border-radius: 50%;
    background-color: rgba(25, 65, 135, 0.45);
    animation: pulse 3.2s ease-out infinite;
    transform-origin: center;
    pointer-events: none;
}

/* Ring kedua dan ketiga dengan delay */
.pulse-ring:nth-child(2) {
    animation-delay: 0.8s;
}

.pulse-ring:nth-child(3) {
    animation-delay: 1.6s;
}

/* Efek gelombang berdenyut */
@keyframes pulse {
    0% {
        transform: scale(0.8);
        opacity: 0.9;
    }

    40% {
        transform: scale(2.5);
        opacity: 0.4;
    }

    70% {
        transform: scale(4);
        opacity: 0.15;
    }

    100% {
        transform: scale(0.8);
        opacity: 0;
    }
}
</style>


<?php 
    // ========================================================================
    // CATATAN PENTING:
    // Pastikan di Controller Anda, Anda telah mengisi variabel $assets 
    // dengan data aset properti yang memiliki kolom 'id', 'lokasi', 'latitude',
    // dan 'longitude' dari database.
    // ========================================================================
    
    // --- SIMULASI DATA ASET PROPERTI (Hapus ini jika sudah diisi di Controller) ---
    $assets = $assets ?? [];
    
    // Encode data aset properti untuk digunakan oleh JavaScript di bagian <script>
    $assets_json = json_encode($assets);
?>

<div class="mb-5 p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-9">
            <h1 class="display-5 fw-bold mb-1 text-dark">Ringkasan PKS & Inventaris Aset</h1>
            <p class="lead text-muted">
                Status terkini dari seluruh aset perusahaan dan Perjanjian Kerja Sama (PKS) dengan Mitra.
            </p>
        </div>
        <div class="col-md-3 text-md-end">
            <span class="badge bg-primary py-2 px-3 shadow-sm">
                <i class="fas fa-calendar-alt me-1"></i> Data Hari Ini: <?= date('d M Y') ?>
            </span>
        </div>
    </div>
</div>
<!-- --- Kartu KPI Utama --- -->
<div class="row mb-5">
    <!-- Total Mitra Kerja -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Mitra Kerja</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            <?= number_format($summary['totalMitra'] ?? 0, 0, ',', '.') ?> Mitra</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total PKS Tercatat -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total PKS Tercatat</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            <?= number_format($summary['totalPks'] ?? 0, 0, ',', '.') ?> Dokumen</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PKS Akan Kadaluarsa (Peringatan Penting) -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            PKS Akan Kadaluarsa (90 Hari)
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            <?= number_format($summary['expiringPks'] ?? 0, 0, ',', '.') ?> PKS</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Unit Kendaraan -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Unit Kendaraan</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            <?= number_format($summary['total_aset_kendaraan'] ?? 0, 0, ',', '.') ?> Unit</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-car fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Unit Properti -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-start border-4 border-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Unit Properti</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            <?= number_format($summary['total_aset_tanah'] ?? 0, 0, ',', '.') ?> Unit</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Luas Tanah (M²) -->
    <div class="col-lg-3 col-md-12 mb-4">
        <div class="card border-start border-4 border-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Luas Tanah (M²)
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            <?= number_format($summary['total_luas_tanah'] ?? 0, 0, ',', '.') ?> M²</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Bangunan (M²) -->
    <div class="col-lg-3 col-md-12 mb-4">
        <div class="card border-start border-4 border-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total Bangunan (M²)
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            <?= number_format($summary['total_luas_bangunan'] ?? 0, 0, ',', '.') ?> M²</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- --- Chart Section --- -->
<div class="row mb-5">
    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-cogs me-2"></i> Distribusi Kondisi Kendaraan</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 300px;">
                    <canvas id="kendaraanKondisiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-success text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-balance-scale me-2"></i> Distribusi Bukti Kepemilikan Properti
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 300px;">
                    <canvas id="tanahKepemilikanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- --- Peta Distribusi Aset (NEW SECTION) --- -->
<div class="row mb-5">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header bg-info text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-map-marked-alt me-2"></i> Peta Distribusi Lokasi Aset Properti
                    (<?= count($assets) ?> Titik)</h6>
            </div>
            <div class="card-body p-0">
                <!-- Kontainer Peta -->
                <div id="map" style="height: 600px; border-radius: 0; border: none;">
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        Memuat peta...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- --- Peringatan Section --- -->
<div class="row">
    <!-- PKS Mendekati Kedaluwarsa -->
    <div class="col-12 mb-4">
        <div class="card shadow border-left-danger">
            <div class="card-header bg-danger text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-file-contract me-2"></i> Peringatan: PKS Mendekati Kedaluwarsa
                </h6>
                <div class="form-text text-white-50">Terdapat **<?= $summary['expiringPks'] ?? 0 ?>** PKS yang akan
                    habis masa berlakunya dalam 90 hari ke depan.</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Judul PKS</th>
                                <th>Mitra Kerja</th>
                                <th>Status</th>
                                <th>Berakhir pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($summary['expiringPksList'])): ?>
                            <?php foreach ($summary['expiringPksList'] as $pks): 
                                // Pastikan kunci array ada sebelum diakses
                                $tanggalBerakhir = $pks['tanggal_berakhir'] ?? null;
                            ?>
                            <tr class="table-warning">
                                <td><?= esc($pks['nama_proyek'] ?? 'N/A') ?></td>
                                <td><?= esc($pks['nama_mitra'] ?? 'N/A') ?></td>
                                <td><span
                                        class="badge bg-danger"><?= ucfirst(esc($pks['status_pks'] ?? 'N/A')) ?></span>
                                </td>
                                <td>
                                    <?php if ($tanggalBerakhir && strtotime($tanggalBerakhir)): ?>
                                    <span class="fw-bold"><?= date('d M Y', strtotime($tanggalBerakhir)) ?></span>
                                    <?php else: ?>
                                    <span class="fw-bold text-muted">Tanggal Tidak Valid</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= site_url('pks/detail/' . ($pks['id'] ?? '')) ?>"
                                        class="btn btn-sm btn-danger">
                                        <i class="fas fa-eye me-1"></i> Tinjau PKS
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-success fw-bold">Tidak ada PKS yang mendesak
                                    kadaluarsa dalam waktu 90 hari.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Dokumen Aset Mendekati Kedaluwarsa -->
    <div class="col-12">
        <div class="card shadow mb-4 border-left-danger">
            <div class="card-header bg-danger text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Peringatan: Dokumen Aset
                    Mendekati Kedaluwarsa</h6>
                <div class="form-text text-white-50">Daftar dokumen (Pajak, Sertifikat, Sewa) yang akan habis masa
                    berlakunya dalam 90 hari ke depan.</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Jenis Aset</th>
                                <th>ID/Lokasi</th>
                                <th>Dokumen</th>
                                <th>Berlaku s/d</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($summary['expiring_docs'])): ?>
                            <?php foreach ($summary['expiring_docs'] as $doc): ?>
                            <tr class="table-warning">
                                <td><span
                                        class="badge <?= ($doc['jenis'] ?? null) == 'Kendaraan' ? 'bg-info' : 'bg-success' ?>"><?= esc($doc['jenis'] ?? 'N/A') ?></span>
                                </td>
                                <td><?= esc($doc['lokasi_id'] ?? 'N/A') ?></td>
                                <td><?= esc($doc['dokumen'] ?? 'N/A') ?></td>
                                <td><span
                                        class="fw-bold"><?= date('d M Y', strtotime($doc['berlaku_sd'] ?? 'now')) ?></span>
                                </td>
                                <td>
                                    <a href="<?= $doc['url'] ?? '#' ?>" class="btn btn-sm btn-danger">
                                        <i class="fas fa-edit me-1"></i> Perbarui
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-success fw-bold">Tidak ada dokumen yang akan
                                    kedaluwarsa dalam waktu dekat.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<!-- MarkerClusterer resmi -->
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>

<script>
const ASSET_DATA = <?= $assets_json ?>;
const API_KEY = "<?= getenv('GOOGLE_API_KEY') ?>";

/**
 * Fungsi untuk memuat Google Maps API secara dinamis
 */
function loadGoogleMapsScript(apiKey) {
    return new Promise((resolve, reject) => {
        if (typeof google !== 'undefined' && google.maps) {
            return resolve();
        }
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&v=weekly`;
        script.async = true;
        script.defer = true;
        script.onload = resolve;
        script.onerror = () => reject(new Error("Gagal memuat Google Maps API"));
        document.head.appendChild(script);
    });
}

/**
 * Fungsi utama setup peta
 */
async function setupMap() {
    const mapContainer = document.getElementById("map");

    // Validasi API key
    if (!API_KEY || API_KEY.length < 20 || API_KEY.includes("GOOGLE_API_KEY")) {
        mapContainer.innerHTML = `
        <div class="alert alert-warning h-100 d-flex align-items-center justify-content-center m-0">
            <i class="fas fa-exclamation-triangle me-2"></i> Kunci API Maps tidak valid.
        </div>`;
        return;
    }

    // Validasi data aset
    if (!ASSET_DATA || ASSET_DATA.length === 0) {
        mapContainer.innerHTML = `
        <div class="alert alert-info h-100 d-flex align-items-center justify-content-center m-0">
            <i class="fas fa-info-circle me-2"></i> Tidak ada data aset yang memiliki koordinat.
        </div>`;
        return;
    }

    try {
        await loadGoogleMapsScript(API_KEY);

        const map = new google.maps.Map(mapContainer, {
            center: {
                lat: -7.2504,
                lng: 112.7688
            },
            zoom: 5,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });

        const bounds = new google.maps.LatLngBounds();
        const infoWindow = new google.maps.InfoWindow();
        const invisibleMarkers = [];
        const pulseOverlays = []; // simpan overlay agar bisa dikontrol nanti

        // Tambahkan setiap aset ke map
        ASSET_DATA.forEach(asset => {
            const lat = parseFloat(asset.latitude);
            const lng = parseFloat(asset.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const position = new google.maps.LatLng(lat, lng);
            bounds.extend(position);

            // Marker tak terlihat untuk clustering
            const marker = new google.maps.Marker({
                position,
                map,
                visible: false
            });
            invisibleMarkers.push(marker);

            // Overlay untuk animasi pulse
            const pulseOverlay = new google.maps.OverlayView();

            pulseOverlay.onAdd = function() {
                const div = document.createElement("div");
                div.className = "pulse-wrapper";
                div.innerHTML = `
                    <div class="pulse-ring"></div>
                    <div class="pulse-dot"></div>
                `;
                const panes = this.getPanes();
                panes.overlayMouseTarget.appendChild(div);

                // klik marker → tampilkan info aset
                div.addEventListener("click", () => {
                    const content = `
                        <div style="max-width:230px;">
                            <h6 class="fw-bold text-primary mb-1">${asset.lokasi}</h6>
                            <div class="text-muted small mb-1">Lat: ${lat}, Lng: ${lng}</div>
                            <a href="<?= site_url('asettanahbangunan/detail/') ?>${asset.id}" 
                               class="btn btn-sm btn-outline-primary">
                               <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>`;
                    infoWindow.setContent(content);
                    infoWindow.setPosition(position);
                    infoWindow.open(map);
                });

                this.div = div;
            };

            pulseOverlay.draw = function() {
                const projection = this.getProjection();
                const point = projection.fromLatLngToDivPixel(position);
                if (point && this.div) {
                    this.div.style.left = point.x + "px";
                    this.div.style.top = point.y + "px";
                }
            };

            pulseOverlay.onRemove = function() {
                if (this.div) this.div.remove();
                this.div = null;
            };

            pulseOverlay.setMap(map);
            pulseOverlays.push(pulseOverlay);
        });

        // Sesuaikan tampilan peta
        if (ASSET_DATA.length > 0) {
            map.fitBounds(bounds);
            if (map.getZoom() > 15) map.setZoom(15);
        }

        // === CLUSTERING ===
        new markerClusterer.MarkerClusterer({
            map,
            markers: invisibleMarkers,
            renderer: {
                render: ({
                    markers,
                    position
                }) => {
                    const count = markers?.length || 0;
                    const color =
                        count < 10 ? "#4137d0ff" :
                        count < 50 ? "#ffc107" : "#dc3545";
                    if (showAsCluster || count > 1) {
                        const svg = `
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50">
                    <circle cx="25" cy="25" r="25" fill="${color}" opacity="0.85"/>
                    <text x="25" y="30" text-anchor="middle" fill="#fff" 
                          font-size="16" font-weight="bold">${count}</text>
                </svg>`;
                        return new google.maps.Marker({
                            position,
                            icon: {
                                url: "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(
                                    svg),
                                scaledSize: new google.maps.Size(50, 50),
                            },
                            zIndex: google.maps.Marker.MAX_ZINDEX + count,
                        });
                    }
                },
            },
        });
        // 🔍 Kontrol visibilitas pulse marker berdasarkan zoom
        map.addListener("zoom_changed", () => {
            const zoom = map.getZoom();
            pulseOverlays.forEach(overlay => {
                if (overlay.div) {
                    overlay.div.style.display = zoom >= 15 ? "block" : "none";
                }
            });
        });

    } catch (err) {
        console.error("Map error:", err);
        mapContainer.innerHTML = `
        <div class="alert alert-danger h-100 d-flex align-items-center justify-content-center m-0">
            <i class="fas fa-exclamation-circle me-2"></i> ${err.message}
        </div>`;
    }
}

document.addEventListener("DOMContentLoaded", setupMap);
</script>



<script>
$(document).ready(function() {
    // --- AMBIL DATA ASLI DARI PHP (Controller) ---
    // Menggunakan JSON.parse untuk memastikan data dibaca sebagai objek JS
    const summary = <?= json_encode($summary) ?>;

    const dataKondisi = {
        labels: summary.chart_kendaraan_kondisi.labels,
        counts: summary.chart_kendaraan_kondisi.counts
    };

    const dataKepemilikan = {
        labels: summary.chart_tanah_kepemilikan.labels,
        counts: summary.chart_tanah_kepemilikan.counts
    };
    // -----------------------------------------------------------------------

    // FUNGSI MEMBUAT CHART (Chart.js)
    function createPieChart(ctxId, data, title) {
        // Cek jika tidak ada data untuk di-chart (semua count adalah 0)
        // Pastikan array counts ada dan tidak kosong
        if (!data.counts || data.counts.length === 0 || data.counts.every(count => count === 0)) {
            const container = document.getElementById(ctxId).parentElement;
            container.innerHTML =
                '<div class="alert alert-info text-center mt-5">Tidak ada data untuk ditampilkan dalam grafik ini.</div>';
            return;
        }

        const ctx = document.getElementById(ctxId).getContext('2d');

        // Pilihan warna yang lebih baik dan konsisten
        const backgroundColors = [
            '#4e73df', // Primary Blue (Cocok untuk Baik / SHM)
            '#f6c23e', // Warning Yellow (Cocok untuk Rusak Ringan / HGB)
            '#e74a3b', // Danger Red (Cocok untuk Rusak Berat / Sewa)
            '#1cc88a', // Success Green
            '#36b9cc', // Info Cyan
        ];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels.map(label => {
                    // Membersihkan label, berguna untuk kondisi kendaraan
                    return (label ?? 'Tidak Diketahui').replace(/_/g, ' ').replace(/\b\w/g, l =>
                        l.toUpperCase());
                }),
                datasets: [{
                    data: data.counts,
                    backgroundColor: backgroundColors.slice(0, data.labels.length),
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: false,
                        text: title
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                let label = tooltipItem.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += tooltipItem.raw + ' Unit';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // 1. Inisialisasi Chart Kondisi Kendaraan
    createPieChart('kendaraanKondisiChart', dataKondisi, 'Distribusi Kondisi Kendaraan');

    // 2. Inisialisasi Chart Kepemilikan Tanah
    createPieChart('tanahKepemilikanChart', dataKepemilikan, 'Distribusi Bukti Kepemilikan Properti');
});
</script>

<?= $this->endSection() ?>