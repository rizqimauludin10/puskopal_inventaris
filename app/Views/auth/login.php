<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAKOPAL</title>

    <!-- Bootstrap 5 & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <?php if (function_exists('base_url')): ?>
    <link rel="icon" type="image/png" href="<?= base_url('logo/logo_puskopal.png') ?>">
    <?php endif; ?>

    <style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1e3a8a;
        --bg-gradient: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--bg-gradient);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border-radius: 1.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-header {
        text-align: center;
        padding: 2rem 1rem 1rem;
    }

    .login-header h1 {
        font-weight: 800;
        color: var(--primary);
        letter-spacing: -0.5px;
    }

    .login-header p {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .form-control {
        border-radius: 0.75rem;
        padding: 0.75rem 0.75rem 0.75rem 2.75rem;
        border: 1px solid #d1d5db;
        background-color: #f9fafb;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.2);
    }

    .input-icon {
        position: absolute;
        top: 50%;
        left: 1rem;
        transform: translateY(-50%);
        color: #6b7280;
    }

    .form-group {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .btn-primary-custom {
        background: var(--primary);
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem;
        font-weight: 700;
        transition: all 0.2s ease;
        color: #FFF;
    }

    .btn-primary-custom:hover {
        background: var(--primary-dark);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        color: #e7e4e4ff;
    }

    .footer-text {
        text-align: center;
        margin-top: 1.5rem;
        color: #9ca3af;
        font-size: 0.85rem;
    }

    .alert {
        font-size: 0.9rem;
        border-radius: 0.75rem;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 0.75rem;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0;
        z-index: 5;
    }

    .toggle-password:hover {
        color: var(--primary);
    }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-header">
            <h1><i class="fas fa-cubes me-2"></i> SIAKOPAL</h1>
            <p>Sistem Informasi Aset Koperasi Puskopal</p>
        </div>

        <div class="p-4">
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <form action="<?= site_url('auth/attemptLogin') ?>" method="post" autocomplete="off">
                <?= csrf_field() ?>

                <div class="form-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username"
                        value="<?= old('username') ?>" required autofocus>
                </div>

                <!-- <div class="form-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Kata Sandi"
                        required>
                </div> -->

                <div class="form-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Kata Sandi"
                        required>

                    <!-- Tombol Intip Password -->
                    <button type="button" class="btn toggle-password" tabindex="-1">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="fas fa-right-to-bracket me-2"></i> Masuk
                </button>
            </form>

            <div class="footer-text mt-3">
                &copy; <?= date('Y') ?> SIAKOPAL. All rights reserved.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById("password").addEventListener("keypress", function(e) {
        if (e.key === "Enter") this.form.submit();
    });
    </script>
</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const togglePassword = document.querySelector(".toggle-password");
    const passwordInput = document.getElementById("password");
    const icon = togglePassword.querySelector("i");

    togglePassword.addEventListener("click", function() {
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
    });
});
</script>

</html>