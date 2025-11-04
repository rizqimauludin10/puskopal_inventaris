<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Tambah Aset Tanah & Bangunan Baru
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4 p-3 border-bottom">
    <h1 class="display-6 fw-bold mb-1 text-dark">
        <i class="fas fa-plus-circle me-2 text-primary"></i> Tambah Aset Tanah & Bangunan
    </h1>
    <p class="lead text-muted">
        Isi detail spesifikasi fisik dan status legalitas aset properti.
    </p>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Formulir Data Properti</h5>
    </div>
    <div class="card-body">

        <form action="<?= site_url('asettanahbangunan/store') ?>" method="post" enctype="multipart/form-data">
            <div class="row">

                <!-- KOLOM KIRI -->
                <div class="col-lg-6">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i> Detail Fisik & Lokasi
                    </h6>

                    <!-- Input Lokasi -->
                    <div class="mb-3">
                        <label for="lokasi" class="form-label fw-bold">Lokasi Aset <span
                                class="text-danger">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control"
                            placeholder="Contoh: Jl. Sudirman No. 12, Jakarta" required>
                    </div>

                    <!-- Pencarian Alamat -->
                    <div class="mb-3">
                        <label for="searchBox" class="form-label fw-bold">Cari Alamat atau Lokasi</label>
                        <input id="searchBox" type="text" class="form-control"
                            placeholder="Ketik alamat atau nama tempat...">
                        <div class="form-text">Ketik alamat untuk mempermudah memilih lokasi pada peta.</div>
                    </div>

                    <!-- Map -->
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
                                value="-7.2462836">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label fw-bold">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" readonly
                                value="112.7377672">
                        </div>
                    </div>

                    <!-- Luas -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="luas_tanah" class="form-label fw-bold">Luas Tanah (M²) <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="luas_tanah" id="luas_tanah" class="form-control"
                                placeholder="Contoh: 500,50" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="luas_bangunan" class="form-label fw-bold">Luas Bangunan (M²)</label>
                            <input type="text" name="luas_bangunan" id="luas_bangunan" class="form-control"
                                placeholder="Contoh: 350,75">
                        </div>


                    </div>


                </div>

                <!-- KOLOM KANAN -->
                <div class="col-lg-6">
                    <h6 class="text-success border-bottom pb-2 mb-3">
                        <i class="fas fa-clipboard-list me-2"></i> Legalitas dan Administrasi
                    </h6>

                    <div class="mb-3">
                        <label for="kepemilikan" class="form-label fw-bold">Bukti Kepemilikan <span
                                class="text-danger">*</span></label>
                        <select name="kepemilikan" id="kepemilikan" class="form-select" required>
                            <option value="">-- Pilih Jenis Kepemilikan --</option>
                            <?php if (isset($kepemilikan) && is_array($kepemilikan)): ?>
                            <?php foreach ($kepemilikan as $kp): ?>
                            <!-- Asumsi: $kp['id'] adalah kode unik seperti 'SHM', 'HGB', 'SEWA', 'LAIN' -->
                            <option value="<?= $kp['id'] ?>"><?= $kp['jenis'] ?> (<?= $kp['deskripsi'] ?>)</option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3" id="detailContainer">
                        <label for="detail_kepemilikan" class="form-label fw-bold">Nomor dan Tanggal Bukti Kepemilikan
                            <span class="text-danger">*</span></label>
                        <input type="text" name="detail_kepemilikan" id="detail_kepemilikan" class="form-control"
                            placeholder="Contoh: No. 123/SHM/2023, Tanggal 10/01/2023" required>
                        <div class="form-text">Contoh: Nomor Sertifikat, Tanggal Terbit, atau detail lainnya yang
                            relevan.</div>
                    </div>

                    <!-- CONTAINER MASA BERLAKU (Ini yang akan disembunyikan/ditampilkan) -->
                    <div class="mb-3" id="masaBerlakuContainer">
                        <label for="berlaku" class="form-label">Tanggal Berakhir/Berlaku s/d</label>
                        <input type="date" name="berlaku" id="berlaku" class="form-control">
                        <div class="form-text">Untuk HGB/Sewa, tanggal ini digunakan sebagai batas masa berlaku.</div>
                    </div>
                    <!-- END CONTAINER MASA BERLAKU -->

                    <h6 class="text-danger border-bottom pb-2 mb-3 mt-4">
                        <i class="fas fa-file-pdf me-2"></i> Dokumen Sertifikat/Legalitas
                    </h6>

                    <div class="mb-3">
                        <label for="dokumen_legalitas" class="form-label fw-bold">Upload Dokumen Legalitas (PDF)</label>
                        <input type="file" name="dokumen_legalitas" id="dokumen_legalitas" class="form-control"
                            accept=".pdf">
                        <div class="form-text">Maksimal ukuran file 5MB. Hanya format PDF yang diizinkan.</div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">
                        <i class="fas fa-book me-2"></i> Keterangan
                    </h6>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="5"
                            placeholder="Tuliskan deskripsi fisik, penggunaan saat ini, atau catatan khusus..."></textarea>
                    </div>
                </div>
            </div>

            <hr class="mt-4">
            <button type="submit" class="btn btn-success btn-lg shadow-sm me-2">
                <i class="fas fa-save me-1"></i> Simpan Aset Properti
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');

    form.addEventListener('submit', function() {
        ['luas_tanah', 'luas_bangunan'].forEach(function(id) {
            const input = document.getElementById(id);
            if (input && input.value) {
                // Hapus semua titik (ribuan)
                let value = input.value.replace(/\./g, '');
                // Ganti koma desimal menjadi titik
                value = value.replace(',', '.');
                input.value = value;
            }
        });
    });
});
</script>



<!-- Logika Dropdown Kepemilikan -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Logika Dropdown Kepemilikan (REVISI DI SINI) ===
    const dropdownKepemilikan = document.getElementById('kepemilikan');
    const containerDetail = document.getElementById('detailContainer');
    const containerMasaBerlaku = document.getElementById('masaBerlakuContainer'); // New container
    const inputDetail = document.getElementById('detail_kepemilikan');
    const inputBerlaku = document.getElementById('berlaku');

    // Asumsi: ID kepemilikan yang TIDAK memiliki masa berlaku adalah 'SHM' dan 'LAINNYA'
    // Anda bisa menyesuaikan array ini jika ID di database Anda berbeda (misal: ['1', '99'])
    const NO_EXPIRY_IDS = ['SHM', 'LAINNYA', 'SHM (Sertifikat Hak Milik)', 'LAINNYA (Kepemilikan Lain)'];

    function toggleForms() {
        const selectedOption = dropdownKepemilikan.options[dropdownKepemilikan.selectedIndex];
        const selectedValue = selectedOption.value;
        const selectedText = selectedOption.text.toUpperCase();

        // 1. Logika Tampilkan/Sembunyikan Detail Kepemilikan
        const isSelected = selectedValue !== '';
        containerDetail.style.display = isSelected ? 'block' : 'none';
        if (inputDetail) {
            if (isSelected) inputDetail.setAttribute('required', 'required');
            else inputDetail.removeAttribute('required');
        }

        // 2. Logika Tampilkan/Sembunyikan Masa Berlaku
        // Cek apakah nilai/teks yang dipilih termasuk yang TIDAK butuh masa berlaku
        const isPermanent = NO_EXPIRY_IDS.includes(selectedValue.toUpperCase()) || NO_EXPIRY_IDS.some(id =>
            selectedText.includes(id.split(' ')[0]));

        if (isPermanent) {
            // Sembunyikan form masa berlaku
            containerMasaBerlaku.style.display = 'none';
            // Hapus atribut 'required' jika ada
            inputBerlaku.removeAttribute('required');
            // Kosongkan nilai agar tidak terkirim tanggal default
            inputBerlaku.value = '';
        } else {
            // Tampilkan form masa berlaku (untuk HGB, Sewa, dll)
            containerMasaBerlaku.style.display = 'block';
            // Tetapkan required jika diperlukan (sesuaikan dengan aturan validasi backend Anda)
            // inputBerlaku.setAttribute('required', 'required'); 
        }
    }

    // Tambahkan event listener untuk perubahan pada dropdown
    dropdownKepemilikan.addEventListener('change', toggleForms);

    // Jalankan fungsi saat DOM dimuat untuk mengatur status awal
    toggleForms();


    // === Inisialisasi Peta Saat DOM Siap (Kode Maps Tetap Sama) ===
    setupMap();
});
</script>

<!-- Google Maps Dynamic Loading and Logic (Versi Stabil) -->
<script>
let map, marker, searchBox;
const DEFAULT_LAT = -7.2462836;
const DEFAULT_LNG = 112.7377672;

const API_KEY = "<?= getenv('GOOGLE_API_KEY') ?>";

/**
 * Memuat skrip Google Maps secara dinamis, menggunakan versi RILIS (tanpa v=beta).
 */
function loadGoogleMapsScript(apiKey) {
    // ... (Fungsi loadGoogleMapsScript tetap sama)
    return new Promise((resolve, reject) => {
        // Cek jika Maps sudah dimuat
        if (typeof google !== 'undefined' && google.maps) {
            return resolve();
        }

        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places`;
        script.async = true;
        script.defer = true;
        script.onload = resolve;
        script.onerror = () => {
            reject(new Error("Gagal memuat skrip Google Maps. Cek koneksi atau URL skrip."));
        };
        document.head.appendChild(script);
    });
}

/**
 * Menginisialisasi Peta setelah skrip dimuat, menggunakan google.maps.Marker lama.
 */
async function setupMap() {
    // ... (Fungsi setupMap tetap sama)
    const mapContainer = document.getElementById("map");
    if (!mapContainer) return;

    if (!API_KEY || API_KEY.length < 20 || API_KEY.includes('GOOGLE_API_KEY')) {
        mapContainer.innerHTML = `<div class="alert alert-danger h-100 d-flex align-items-center justify-content-center m-0" style="height: 100%;">
                <i class="fas fa-exclamation-circle me-2"></i> **Error Maps:** API Key tidak valid. Pastikan di .env sudah benar.
            </div>`;
        console.error("GOOGLE API KEY NOT FOUND OR INVALID.");
        return;
    }

    try {
        await loadGoogleMapsScript(API_KEY);

        const initialLat = parseFloat(document.getElementById('latitude').value) || DEFAULT_LAT;
        const initialLng = parseFloat(document.getElementById('longitude').value) || DEFAULT_LNG;
        const initialLocation = {
            lat: initialLat,
            lng: initialLng
        };

        map = new google.maps.Map(mapContainer, {
            center: initialLocation,
            zoom: 13,
            mapTypeControl: false,
            streetViewControl: false,
        });

        // Definisikan ikon SVG
        const customSvgIcon = {
            path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z', // Path SVG untuk ikon lokasi standar
            fillColor: '#007bff', // Warna biru primer
            fillOpacity: 1,
            strokeWeight: 0,
            scale: 2, // Ukuran ikon
            anchor: new google.maps.Point(12, 24) // Titik tengah-bawah ikon
        };

        // *** KEMBALI MENGGUNAKAN google.maps.Marker (Versi Stabil) ***
        marker = new google.maps.Marker({
            position: initialLocation,
            map: map,
            draggable: true, // Kembali menggunakan properti draggable lama
            title: 'Lokasi Aset',
            icon: customSvgIcon
        });

        // Isi kolom input awal
        updateFields(initialLocation);

        // --- Listener 1: Map Click ---
        map.addListener("click", (mapsMouseEvent) => {
            const latLng = mapsMouseEvent.latLng;
            marker.setPosition(latLng); // Menggunakan setPosition()
            map.panTo(latLng);
            updateFields(latLng);
        });

        // --- Listener 2: Marker Drag End ---
        marker.addListener('dragend', function(event) {
            updateFields(event.latLng); // Menggunakan event.latLng
        });


        // --- Listener 3: SearchBox (Sama seperti sebelumnya) ---
        const searchInput = document.getElementById('searchBox');
        searchBox = new google.maps.places.SearchBox(searchInput);
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(searchInput);

        searchBox.addListener('places_changed', function() {
            const places = searchBox.getPlaces();
            if (places.length === 0) return;
            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            map.setCenter(place.geometry.location);
            map.setZoom(16);
            marker.setPosition(place.geometry.location); // Menggunakan setPosition()
            updateFields(place.geometry.location);
        });

    } catch (error) {
        console.error("Google Maps Setup Error:", error);
        mapContainer.innerHTML = `<div class="alert alert-danger h-100 d-flex align-items-center justify-content-center m-0" style="height: 100%;">
                <i class="fas fa-exclamation-circle me-2"></i> **Error Maps:** ${error.message || "Gagal inisialisasi peta. Cek konsol dan API key."}
            </div>`;
        mapContainer.style.height = '350px';
    }
}

/**
 * Fungsi updateFields (Sudah defensif, tetap dipertahankan)
 */
function updateFields(latLng) {
    // ... (Fungsi updateFields tetap sama)
    let lat, lng;

    // Cek apakah input adalah objek LatLng (dengan function .lat()) atau objek JS biasa (dengan property .lat)
    if (typeof latLng.lat === 'function') {
        lat = latLng.lat().toFixed(6);
        lng = latLng.lng().toFixed(6);
    } else {
        lat = latLng.lat.toFixed(6);
        lng = latLng.lng.toFixed(6);
    }

    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}

// Tangani kegagalan otorisasi API Key
window.gm_authFailure = function() {
    // ... (Fungsi gm_authFailure tetap sama)
    const mapContainer = document.getElementById("map");
    mapContainer.innerHTML = `<div class="alert alert-danger h-100 d-flex align-items-center justify-content-center m-0" style="height: 100%;">
            <i class="fas fa-exclamation-circle me-2"></i> **Otorisasi API Key Gagal.** Cek Google Cloud Console Anda.
        </div>`;
};
</script>

<?= $this->endSection() ?>