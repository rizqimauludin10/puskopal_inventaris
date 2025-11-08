<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title') ?></title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('logo/logo_puskopal.png') ?>">

    <style>
    .navbar-nav .nav-link.active {
        font-weight: bold;
        color: #0d6efd !important;
    }

    .navbar-nav .nav-link:hover {
        color: #0d6efd;
    }

    .dropdown-menu {
        border-radius: 0.5rem;
    }

    .user-role {
        font-size: 0.75rem;
        color: #6c757d;
    }

    footer {
        font-size: 0.9rem;
    }

    footer h6 {
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    @media (max-width: 576px) {
        footer img {
            height: 28px;
        }
    }

    /* ====== LOADER STYLING ====== */
    #splash-logo {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.6);
        /* semi transparan, bisa diubah jadi rgba(0,0,0,0.4) kalau mau gelap */
        backdrop-filter: blur(3px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2000;
        transition: opacity 0.4s ease, visibility 0.4s ease;
    }

    #splash-logo.fade-out {
        opacity: 0;
        visibility: hidden;
    }

    .logo-animate {
        width: 80px;
        animation: zoomin 0.8s ease-in-out;
    }

    @keyframes zoomin {
        from {
            transform: scale(0.8);
            opacity: 0.5;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    </style>
</head>

<body>
    <?php
        // Mendapatkan URI untuk menandai menu aktif
        $uri = service('uri');
        $currentPath = $uri->getPath();
        $session = session();
    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3">
        <div class="container">
            <!-- Logo & Brand -->
            <a class="navbar-brand fw-bold text-primary" href="<?= site_url('/') ?>">
                <img src="<?= base_url('logo/logo_puskopal.png') ?>" alt="Logo" style="height: 30px;" class="me-2">
                SIAKOPAL
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPath === '' || $currentPath === '/' || strpos($currentPath, 'dashboard') !== false) ? 'active' : '' ?>"
                            href="<?= site_url('/') ?>">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>

                    <!-- Aset Properti -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentPath, 'asettanahbangunan') !== false ? 'active' : '' ?>"
                            href="<?= site_url('asettanahbangunan') ?>">
                            <i class="fas fa-building me-1"></i> Aset Properti
                        </a>
                    </li>

                    <!-- Aset Kendaraan -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentPath, 'asetkendaraan') !== false ? 'active' : '' ?>"
                            href="<?= site_url('asetkendaraan') ?>">
                            <i class="fas fa-car me-1"></i> Aset Kendaraan
                        </a>
                    </li>

                    <!-- Mitra -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentPath, 'mitra') !== false ? 'active' : '' ?>"
                            href="<?= site_url('mitra') ?>">
                            <i class="fas fa-handshake"></i> Mitra Kerja
                        </a>
                    </li>

                    <!-- Personel -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentPath, 'personel') !== false ? 'active' : '' ?>"
                            href="<?= site_url('personel') ?>">
                            <i class="fas fa-users"></i> Personel
                        </a>
                    </li>


                    <!-- Divider -->
                    <li class="nav-item mx-2 d-none d-lg-block">
                        <span class="text-muted">|</span>
                    </li>

                    <!-- User Dropdown -->
                    <?php if ($session->get('isLoggedIn')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            <?= esc($session->get('nama_lengkap')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="<?= site_url('/') ?>">
                                    <i class="fas fa-house me-2 text-secondary"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <!-- 🎯 Ini adalah Pemicu Modal Konfirmasi -->
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#logoutConfirmModal">
                                    <i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-lg-3" href="<?= site_url('login') ?>">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <?= $this->renderSection('content') ?>
    </div>

    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="logoutConfirmModalLabel"><i class="fas fa-sign-out-alt me-2"></i>
                        Konfirmasi Logout</h5>
                    <!-- Tombol close putih agar terlihat di header merah -->
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin keluar dari sistem? </p>
                    <div class="text-muted small mt-3">
                        Pastikan semua pekerjaan Anda sudah tersimpan sebelum keluar.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                    <!-- Tombol Konfirmasi yang akan menuju ke URL Logout sebenarnya -->
                    <!-- URL ini harus sesuai dengan Controller/Method Logout Anda -->
                    <a id="confirmLogoutButton" href="<?= site_url('/logout') ?>" class="btn btn-danger fw-bold">
                        Ya, Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- <div id="splash-logo">
        <img src="<?= base_url('logo/logo_puskopal.png') ?>" alt="Logo SIAKOPAL" class="logo-animate">
    </div> -->


    <!-- Footer -->
    <footer class="bg-light border-top py-4 mt-5">
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <!-- Logo dan Nama -->
                <div
                    class="col-md-6 mb-3 mb-md-0 d-flex align-items-center justify-content-center justify-content-md-start">
                    <img src="<?= base_url('logo/logo_puskopal.png') ?>" alt="Logo Puskopal" height="35" class="me-2">
                    <div>
                        <h6 class="mb-0 fw-bold text-primary">PUSKOPAL KOARMADA II</h6>
                        <small class="text-muted">Pusat Koperasi Angkatan Laut Komando Armada II</small>
                    </div>
                </div>

                <!-- Hak Cipta -->
                <div class="col-md-6 text-center text-md-end">
                    <small class="text-muted">
                        &copy; <?= date('Y') ?> <strong>SIAKOPAL</strong> — Sistem Informasi Aset & Kerja Sama |
                        Dikembangkan oleh <span class="text-primary fw-semibold">Rizqi Mauludin.</span>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script>
    window.addEventListener("load", function() {
        const splash = document.getElementById("splash-logo");
        setTimeout(() => {
            splash.classList.add("fade-out");
        }, 100); // cepat, setengah detik aja
    });
    </script>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <?= $this->renderSection('scripts') ?>
</body>

</html>