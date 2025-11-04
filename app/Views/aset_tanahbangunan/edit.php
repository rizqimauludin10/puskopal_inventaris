<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Edit Aset Tanah & Bangunan: <?= $tanah['lokasi'] ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-edit me-2 text-warning"></i> Edit Data Aset Properti
    </h1>
    <p class="lead text-muted">
        Ubah detail aset di lokasi <strong><?= esc($tanah['lokasi']) ?></strong> (ID: <?= $tanah['id'] ?>).
    </p>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0 p-2">Formulir Perubahan Data Properti</h5>
    </div>
    <div class="card-body">

        <form action="<?= site_url('asettanahbangunan/update/' . $tanah['id']) ?>" method="post"
            enctype="multipart/form-data">

            <div class="row">
                <!-- KOLOM KIRI -->
                <div class="col-lg-6">
                    <h6 class="text-primary border-bottom pb-2 mb-3"><i class="fas fa-map-marker-alt me-2"></i> Detail
                        Fisik & Lokasi</h6>

                    <!-- Input Lokasi -->
                    <div class="mb-3">
                        <label for="lokasi" class="form-label fw-bold">Lokasi Aset <span
                                class="text-danger">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control"
                            placeholder="Contoh: Jl. Sudirman No. 12" value="<?= esc($tanah['lokasi']) ?>" required>
                    </div>

                    <!-- Pencarian Alamat -->
                    <div class="mb-3">
                        <label for="searchBox" class="form-label fw-bold">Cari Alamat atau Lokasi</label>
                        <input id="searchBox" type="text" class="form-control"
                            placeholder="Ketik alamat atau nama tempat...">
                        <div class="form-text">Ketik alamat untuk mempermudah memilih lokasi di peta.</div>
                    </div>

                    <!-- Peta -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tandai Lokasi di Peta</label>
                        <div id="map" style="height: 350px; border-radius: 10px; border: 1px solid #ddd;">
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                Memuat peta...
                            </div>
                        </div>
                    </div>

                    <!-- Latitude & Longitude -->
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label fw-bold">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control" readonly
                                value="<?= esc($tanah['latitude'] ?? '-7.2462836') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label fw-bold">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" readonly
                                value="<?= esc($tanah['longitude'] ?? '112.7377672') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="luas_tanah" class="form-label fw-bold">Luas Tanah (M²) <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="luas_tanah" id="luas_tanah" class="form-control"
                                placeholder="Contoh: 500"
                                value="<?= number_format($tanah['luas_tanah'], 2, ',', '.') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="luas_bangunan" class="form-label fw-bold">Luas Bangunan (M²)</label>
                            <input type="text" name="luas_bangunan" id="luas_bangunan" class="form-control"
                                placeholder="Contoh: 350"
                                value="<?= number_format($tanah['luas_bangunan'], 2, ',', '.') ?>">
                        </div>

                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-book me-2"></i> Keterangan
                    </h6>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="5"
                            placeholder="Tuliskan deskripsi fisik atau catatan khusus..."><?= esc($tanah['keterangan']) ?></textarea>
                    </div>
                </div>

                <!-- KOLOM KANAN -->
                <div class="col-lg-6">
                    <h6 class="text-success border-bottom pb-2 mb-3"><i class="fas fa-clipboard-list me-2"></i>
                        Legalitas & Administrasi</h6>

                    <div class="mb-3">
                        <label for="kepemilikan" class="form-label fw-bold">Bukti Kepemilikan <span
                                class="text-danger">*</span></label>
                        <select name="kepemilikan" id="kepemilikan" class="form-select" required>
                            <option value="">-- Pilih Jenis Kepemilikan --</option>
                            <?php foreach ($kepemilikan as $kp): ?>
                            <option value="<?= $kp['id'] ?>"
                                <?= ($kp['id'] == $tanah['kepemilikan']) ? 'selected' : '' ?>>
                                <?= esc($kp['jenis']) ?> (<?= esc($kp['deskripsi']) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3" id="detailContainer"
                        style="<?= !empty($tanah['kepemilikan']) ? 'display:block;' : 'display:none;' ?>">
                        <label for="detail_kepemilikan" class="form-label fw-bold">Nomor & Tanggal Bukti Kepemilikan
                            <span class="text-danger">*</span></label>
                        <input type="text" name="detail_kepemilikan" id="detail_kepemilikan" class="form-control"
                            value="<?= esc($tanah['detail_kepemilikan'] ?? '') ?>">
                        <div class="form-text">Contoh: No. Sertifikat, tanggal terbit, atau detail lainnya.</div>
                    </div>

                    <!-- CONTAINER MASA BERLAKU (Diberi ID) -->
                    <div class="mb-3" id="masaBerlakuContainer">
                        <label for="berlaku" class="form-label">Tanggal Berakhir/Berlaku s/d</label>
                        <input type="date" name="berlaku" id="berlaku" class="form-control"
                            value="<?= esc($tanah['berlaku']) ?>">
                    </div>

                    <h6 class="text-danger border-bottom pb-2 mb-3 mt-4"><i class="fas fa-file-pdf me-2"></i> Dokumen
                        Legalitas</h6>

                    <div class="mb-3">
                        <label for="dokumen_legalitas" class="form-label fw-bold">Upload Dokumen Legalitas (PDF)</label>
                        <input type="file" name="dokumen_legalitas" id="dokumen_legalitas" class="form-control"
                            accept=".pdf">
                        <div class="form-text">Kosongkan jika tidak mengganti file.</div>
                        <?php if ($tanah['dokumen_legalitas']): ?>
                        <p class="mt-2 mb-0">
                            File Lama:
                            <a href="<?= base_url('uploads/tanahbangunan/' . $tanah['dokumen_legalitas']) ?>"
                                target="_blank" class="text-primary fw-bold">
                                <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen
                            </a>
                            <input type="hidden" name="dokumen_legalitas_lama"
                                value="<?= $tanah['dokumen_legalitas'] ?>">
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <hr class="mt-4">
            <button type="submit" class="btn btn-warning btn-lg shadow-sm me-2">
                <i class="fas fa-sync-alt me-1"></i> Update Data
            </button>
            <a href="<?= site_url('asettanahbangunan') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Batalkan
            </a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script>
function formatNumberInput(input) {
    let value = input.value;
    // Hilangkan titik ribuan dulu
    value = value.replace(/\./g, '');
    // Ganti koma menjadi titik untuk database
    value = value.replace(',', '.');
    return value;
}

document.querySelector('form').addEventListener('submit', function(e) {
    const luasTanah = document.getElementById('luas_tanah');
    const luasBangunan = document.getElementById('luas_bangunan');

    luasTanah.value = formatNumberInput(luasTanah);
    luasBangunan.value = formatNumberInput(luasBangunan);
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Logika Dropdown Kepemilikan (REVISI DI SINI) ===
    const dropdown = document.getElementById('kepemilikan');
    const containerDetail = document.getElementById('detailContainer');
    const inputDetail = document.getElementById('detail_kepemilikan');

    // Elemen Baru untuk Masa Berlaku
    const containerMasaBerlaku = document.getElementById('masaBerlakuContainer');
    const inputBerlaku = document.getElementById('berlaku');

    // Asumsi: ID kepemilikan yang TIDAK memiliki masa berlaku
    const NO_EXPIRY_IDS = ['SHM', 'LAINNYA'];

    function toggleForms() {
        const selectedValue = dropdown.value;
        const selectedText = dropdown.options[dropdown.selectedIndex].text.toUpperCase();

        // 1. Logika Tampilkan/Sembunyikan Detail Kepemilikan (Nomor & Tanggal)
        const isSelected = selectedValue !== '';
        containerDetail.style.display = isSelected ? 'block' : 'none';
        if (inputDetail) {
            if (isSelected) inputDetail.setAttribute('required', 'required');
            else inputDetail.removeAttribute('required');
        }

        // 2. Logika Tampilkan/Sembunyikan Masa Berlaku (Tanggal Berakhir)
        // Cek apakah nilai (ID) atau teks yang dipilih termasuk yang TIDAK butuh masa berlaku
        const isPermanent = NO_EXPIRY_IDS.includes(selectedValue.toUpperCase()) ||
            NO_EXPIRY_IDS.some(id => selectedText.includes(id));

        if (isPermanent) {
            // Sembunyikan form masa berlaku
            containerMasaBerlaku.style.display = 'none';
            inputBerlaku.removeAttribute('required');
            // Catatan: Nilai lama tidak dihapus agar tidak ada perubahan data tanpa user sadari.
            // Biarkan backend Anda yang menangani pembersihan data jika ini adalah SHM/Lainnya.
        } else {
            // Tampilkan form masa berlaku
            containerMasaBerlaku.style.display = 'block';
            // inputBerlaku.setAttribute('required', 'required'); // Bisa diaktifkan jika wajib
        }
    }

    dropdown.addEventListener('change', toggleForms);

    // Panggil saat DOM dimuat untuk mengatur status awal berdasarkan data yang sudah ada
    toggleForms();

    setupMap(); // inisialisasi peta
});

// === LOGIKA GOOGLE MAPS (SAMA DENGAN CREATE.PHP) ===
let map, marker, searchBox;
const DEFAULT_LAT = parseFloat("<?= esc($tanah['latitude'] ?? '-7.2462836') ?>");
const DEFAULT_LNG = parseFloat("<?= esc($tanah['longitude'] ?? '112.7377672') ?>");
const API_KEY = "<?= getenv('GOOGLE_API_KEY') ?>";

function loadGoogleMapsScript(apiKey) {
    return new Promise((resolve, reject) => {
        if (typeof google !== 'undefined' && google.maps) return resolve();
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places`;
        script.async = true;
        script.defer = true;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

async function setupMap() {
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return;

    try {
        await loadGoogleMapsScript(API_KEY);

        const initialLocation = {
            lat: DEFAULT_LAT,
            lng: DEFAULT_LNG
        };
        map = new google.maps.Map(mapContainer, {
            center: initialLocation,
            zoom: 13,
            mapTypeControl: false,
            streetViewControl: false,
        });

        const customSvgIcon = {
            path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z',
            fillColor: '#007bff',
            fillOpacity: 1,
            strokeWeight: 0,
            scale: 2,
            anchor: new google.maps.Point(12, 24)
        };

        marker = new google.maps.Marker({
            position: initialLocation,
            map,
            draggable: true,
            title: 'Lokasi Aset',
            icon: customSvgIcon
        });

        updateFields(initialLocation);

        map.addListener("click", (e) => {
            marker.setPosition(e.latLng);
            map.panTo(e.latLng);
            updateFields(e.latLng);
        });

        marker.addListener("dragend", (e) => updateFields(e.latLng));

        const searchInput = document.getElementById('searchBox');
        searchBox = new google.maps.places.SearchBox(searchInput);
        searchBox.addListener('places_changed', () => {
            const places = searchBox.getPlaces();
            if (places.length === 0) return;
            const place = places[0];
            if (!place.geometry) return;
            map.setCenter(place.geometry.location);
            map.setZoom(16);
            marker.setPosition(place.geometry.location);
            updateFields(place.geometry.location);
        });

    } catch (error) {
        console.error("Google Maps Error:", error);
        const mapContainer = document.getElementById('map');
        mapContainer.innerHTML =
            `<div class="alert alert-danger text-center">Gagal memuat peta. Cek API Key.</div>`;
    }
}

function updateFields(latLng) {
    const lat = typeof latLng.lat === 'function' ? latLng.lat().toFixed(6) : latLng.lat.toFixed(6);
    const lng = typeof latLng.lng === 'function' ? latLng.lng().toFixed(6) : latLng.lng.toFixed(6);
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}
</script>

<?= $this->endSection() ?>