<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Detail Aset Properti: <?= esc($tanah['lokasi']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
    // Definisikan peran yang memiliki akses CRUD/aksi penuh
    $hasFullAccess = in_array($userRole, ['admin', 'superadmin']);
?>

<?php 
    // Ambil data Lat/Lng dengan fallback yang aman
    $lat = esc($tanah['latitude'] ?? '-7.250445');
    $lng = esc($tanah['longitude'] ?? '112.768845');
    
    // URL Google Maps untuk Navigasi Cepat
    $googleMapsUrl = "https://www.google.com/maps/search/?api=1&query={$lat},{$lng}";
?>

<!-- BLOK HEADER KONTEN -->
<div class="mb-5 p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h1 class="display-6 fw-bold mb-1 text-dark">
                Detail Aset Properti
            </h1>
            <p class="lead text-muted">
                Lokasi Utama: <span class="badge bg-secondary fs-6"><?= esc($tanah['lokasi']) ?></span>
            </p>
        </div>
        <div class="col-md-5 text-md-end">
            <!-- Tombol Kembali dan Edit -->
            <a href="<?= site_url('asettanahbangunan') ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            <?php if ($hasFullAccess): ?>
            <a href="<?= site_url('asettanahbangunan/edit/' . $tanah['id']) ?>" class="btn btn-warning shadow-sm">
                <i class="fas fa-edit me-1"></i> Edit Data
            </a>
            <?php endif; ?>

        </div>
    </div>
</div>

<div class="row">

    <!-- Kolom Kiri 1: SPESIFIKASI FISIK (Tetap 6) -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-ruler-combined me-2"></i> Spesifikasi Fisik & Ukuran</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Lokasi Detail Aset</strong>
                        <span class="fw-bold"><?= esc($tanah['lokasi']) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Luas Tanah</strong>
                        <span class="fw-bold text-success">
                            <?= number_format($tanah['luas_tanah'], 2, ',', '.') ?> M²
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Luas Bangunan</strong>
                        <span class="fw-bold text-info">
                            <?= number_format($tanah['luas_bangunan'], 2, ',', '.') ?> M²
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Rasio Bangunan/Tanah (%)</strong>
                        <?php
                            $rasio = ($tanah['luas_tanah'] > 0) ? ($tanah['luas_bangunan'] / $tanah['luas_tanah']) * 100 : 0;
                        ?>
                        <span class="fw-bold">
                            <?= number_format($rasio, 2) ?> %
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan 1: LEGALITAS & DOKUMEN (Kita buat lebih kecil, menjadi 6) -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-balance-scale me-2"></i> Legalitas dan Administrasi</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Bukti Kepemilikan</strong>
                        <span class="fw-bold text-uppercase text-success"><?= esc($tanah['nama_kepemilikan']) ?></span>
                    </li>
                    <li
                        class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row">
                        <strong>Detail Kepemilikan</strong>
                        <span class="fw-bold text-uppercase text-end mt-1 mt-md-0"
                            style="word-wrap: break-word; max-width: 70%;">
                            <?= esc($tanah['detail_kepemilikan']) ?>
                        </span>
                    </li>


                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Masa Berlaku s/d</strong>
                        <?php
                        $berlaku = $tanah['berlaku'];
                        // Cek jika nilainya adalah '0000-00-00' atau kosong/null
                        $isPermanent = (empty($berlaku) || $berlaku === '0000-00-00');
                        // Gunakan pesan khusus dari permintaan user
                        $displayText = $isPermanent ? 'Tidak ada tanggal berakhir' : $berlaku;
                        ?>
                        <span class="<?= $isPermanent ? 'text-secondary fst-italic' : '' ?>">
                            <?= esc($displayText) ?>
                        </span>

                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Dokumen Legalitas</strong>
                        <?php if ($tanah['dokumen_legalitas']): ?>
                        <span>
                            <a href="<?= base_url('uploads/tanahbangunan/' . $tanah['dokumen_legalitas']) ?>"
                                target="_blank" class="text-primary fw-bold">
                                <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen
                            </a>
                        </span>
                        <?php else: ?>
                        <span class="text-danger fst-italic">Dokumen Belum Diupload</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Baris Baru untuk Peta dan Keterangan (Peta 7, Keterangan 5) -->
    <div class="col-lg-7 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Lokasi Geospasial Aset</h5>
            </div>
            <div class="card-body p-0">
                <!-- Kontainer Peta -->
                <div id="map" style="height: 400px; border-radius: 0; border: none;">
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        Memuat peta...
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light">
                <p class="fw-bold mb-2">Koordinat GPS:</p>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                    <input type="text" id="latLngDisplay" class="form-control fw-medium"
                        value="<?= $lat . ', ' . $lng ?>" readonly>
                    <button class="btn btn-outline-primary" type="button" onclick="copyCoordinates()">
                        <i class="fas fa-copy"></i> Salin
                    </button>
                </div>

                <a href="<?= $googleMapsUrl ?>" target="_blank" class="btn btn-dark w-100 mt-2">
                    <i class="fas fa-route me-1"></i> Buka di Google Maps
                </a>
            </div>
        </div>
    </div>

    <!-- Baris Baru untuk Keterangan Tambahan -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-comment-dots me-2"></i> Keterangan Tambahan</h5>
            </div>
            <div class="card-body">
                <p class="text-dark fst-italic mb-0 fw-bold fs-4">
                    <?= nl2br(esc($tanah['keterangan']) ?: 'Tidak ada catatan khusus yang ditambahkan pada aset ini.') ?>
                </p>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<script>
const ASSET_LAT = parseFloat('<?= $lat ?>');
const ASSET_LNG = parseFloat('<?= $lng ?>');
// Gunakan variabel environment GOOGLE_API_KEY
const API_KEY = "<?= getenv('GOOGLE_API_KEY') ?>";

// --- Fungsi untuk Menyalin Koordinat ---
function copyCoordinates() {
    const latLngInput = document.getElementById('latLngDisplay');

    latLngInput.select();
    latLngInput.setSelectionRange(0, 99999);
    try {
        document.execCommand('copy');
        console.log("Koordinat berhasil disalin!");
    } catch (err) {
        console.error('Gagal menyalin:', err);
    }
}

// --- Peta Statis ---
function loadGoogleMapsScript(apiKey) {
    return new Promise((resolve, reject) => {
        if (typeof google !== 'undefined' && google.maps) {
            return resolve();
        }
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}`;
        script.async = true;
        script.defer = true;
        script.onload = resolve;
        script.onerror = () => {
            reject(new Error("Gagal memuat skrip Google Maps."));
        };
        document.head.appendChild(script);
    });
}

async function setupMap() {
    const mapContainer = document.getElementById("map");
    // Cek API Key
    if (!mapContainer || !API_KEY || API_KEY.length < 20 || API_KEY.includes('GOOGLE_API_KEY')) {
        mapContainer.innerHTML = `<div class="alert alert-warning h-100 d-flex align-items-center justify-content-center m-0" style="height: 100%;">
                <i class="fas fa-exclamation-triangle me-2"></i> Kunci API Maps tidak valid atau tidak ditemukan.
            </div>`;
        return;
    }

    try {
        await loadGoogleMapsScript(API_KEY);

        const assetLocation = {
            lat: ASSET_LAT,
            lng: ASSET_LNG
        };

        const map = new google.maps.Map(mapContainer, {
            center: assetLocation,
            zoom: 17,
            mapTypeControl: false,
            streetViewControl: false,
            gestureHandling: "cooperative",
            draggable: false,
        });

        // 1. Definisikan SVG untuk pin peta kustom (Pin Drop + Ikon RUMAH di dalam)
        const homePinSvg = `
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
                <!-- Bentuk rumah utama -->
                <path fill="#1500ffff" d="M12 3l9 8h-3v9h-12v-9h-3l9-8z"/>
                <!-- Pintu rumah -->
                <rect x="10" y="14" width="4" height="5" fill="#ffffff"/>
            </svg>
            `;

        // 2. Encode SVG menjadi Data URI
        const iconUrl = 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(homePinSvg);

        // 3. Tambahkan Marker dengan ikon SVG
        new google.maps.Marker({
            position: assetLocation,
            map: map,
            draggable: false,
            title: 'Lokasi Aset',
            icon: {
                url: iconUrl,
                scaledSize: new google.maps.Size(48, 48), // Ukuran ikon 48x48
                anchor: new google.maps.Point(24, 48) // Anchor di tengah bawah (ujung pin)
            }
        });

    } catch (error) {
        console.error("Google Maps Setup Error:", error);
        mapContainer.innerHTML = `<div class="alert alert-danger h-100 d-flex align-items-center justify-content-center m-0" style="height: 100%;">
                <i class="fas fa-exclamation-circle me-2"></i> Error: ${error.message || "Gagal inisialisasi peta."}
            </div>`;
    }
}

window.gm_authFailure = function() {
    const mapContainer = document.getElementById("map");
    if (mapContainer) {
        mapContainer.innerHTML = `<div class="alert alert-danger h-100 d-flex align-items-center justify-content-center m-0" style="height: 100%;">
            <i class="fas fa-exclamation-circle me-2"></i> **Otorisasi API Key Gagal.** Cek Google Cloud Console Anda.
        </div>`;
    }
};

// Panggil setupMap saat DOM siap
document.addEventListener('DOMContentLoaded', setupMap);
</script>

<?= $this->endSection() ?>