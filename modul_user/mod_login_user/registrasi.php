<?php
// Pastikan koneksi ke database sudah terhubung
include('../koneksi.php'); // Mengambil file koneksi database

// Cek jika sudah ada pesan error
if (isset($error_message)) {
    echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Go Danau Toba</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 100px;  /* Mengatur margin top */
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-container input {
            margin-bottom: 15px;
        }
        .login-container .btn {
            width: 100%;
        }
        .login-container .form-text {
            font-size: 0.875rem;
            text-align: center;
        }
        .eye-icon {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="login-container">
        <h2>Registrasi Akun</h2>
        <form action="dashboard_user.php?hal=proses_registrasi" method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" name="password" class="form-control" placeholder="Password" required id="password">
                <i class="bi bi-eye-slash eye-icon" id="toggle-password"></i> <!-- Icon mata -->
            </div>
            <div class="mb-3 position-relative">
                <input type="password" name="confirm_password" class="form-control" placeholder="Konfirmasi Password" required id="confirm-password">
                <i class="bi bi-eye-slash eye-icon" id="toggle-confirm-password"></i> <!-- Icon mata untuk konfirmasi -->
            </div>
            <button type="submit" class="btn btn-primary">Registrasi</button>
        </form>

        <div class="form-text mt-3">
            Sudah punya akun? <a href="dashboard_user.php?hal=login">Login di sini</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<!-- Script untuk toggle password -->
<script>
    // Toggle Password Visibility
    const togglePassword = document.getElementById('toggle-password');
    const passwordField = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        // Cek apakah tipe password adalah 'password' atau 'text'
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Ubah ikon eye sesuai dengan kondisi
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });

    // Toggle Confirm Password Visibility
    const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
    const confirmPasswordField = document.getElementById('confirm-password');

    toggleConfirmPassword.addEventListener('click', function() {
        // Cek apakah tipe password adalah 'password' atau 'text'
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;

        // Ubah ikon eye sesuai dengan kondisi
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

</body>
</html>
