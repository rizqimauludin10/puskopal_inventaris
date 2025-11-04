<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Tidak Ditemukan | 404</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= base_url('logo/logo_puskopal.png') ?>">
    <style>
    body {
        background: #f8f9fa;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
    }

    .error-container {
        text-align: center;
        padding: 40px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        max-width: 500px;
        width: 90%;
    }

    .error-code {
        font-size: 6rem;
        font-weight: 700;
        color: #0d6efd;
        line-height: 1;
    }

    .error-text {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: #6c757d;
    }

    .btn-back {
        padding: 10px 25px;
        border-radius: 50px;
    }

    .illustration {
        max-width: 250px;
        margin: 0 auto 20px;
    }
    </style>
</head>

<body>

    <div class="error-container">
        <img src="/logo/error-404.png" alt="404 Illustration" class="illustration img-fluid">
        <!-- <div class="error-code">404</div> -->
        <div class="error-text">Oops! Halaman yang kamu cari tidak ditemukan.</div>
        <p class="text-muted mb-4">Kemungkinan halaman telah dihapus atau alamat URL salah.</p>
        <a href="<?= base_url() ?>" class="btn btn-primary btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Icon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>

</html>