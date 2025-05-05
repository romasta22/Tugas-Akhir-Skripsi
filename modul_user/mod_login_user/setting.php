<?php
// Periksa apakah session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

// Pastikan pengguna sudah login
if (empty($_SESSION['username'])) {
    echo '<center><b>Maaf, Anda Harus Login Dulu!</b></center>';
    exit(); // Hentikan script jika belum login
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .update-password-container {
            max-width: 400px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        .update-password-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .update-password-container input {
            margin-bottom: 15px;
        }
        .update-password-container .btn {
            width: 100%;
        }
        .eye-icon {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
        }
        .password-field-wrapper {
            position: relative;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="update-password-container">
        <h2>Update Password</h2>
        <p class="text-center">Silakan ganti password Anda di bawah ini.</p>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="dashboard_user.php?hal=update_password" method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">Password Lama</label>
                <div class="password-field-wrapper">
                    <input type="password" name="current_password" class="form-control" placeholder="Masukkan Password Lama" required id="current_password">
                    <i class="bi bi-eye-slash eye-icon" id="toggle-current-password"></i> <!-- Mata untuk password lama -->
                </div>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">Password Baru</label>
                <div class="password-field-wrapper">
                    <input type="password" name="new_password" class="form-control" placeholder="Masukkan Password Baru" required id="new_password">
                    <i class="bi bi-eye-slash eye-icon" id="toggle-new-password"></i> <!-- Mata untuk password baru -->
                </div>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                <div class="password-field-wrapper">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Konfirmasi Password Baru" required id="confirm_password">
                    <i class="bi bi-eye-slash eye-icon" id="toggle-confirm-password"></i> <!-- Mata untuk konfirmasi password baru -->
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

<!-- Font Awesome untuk Ikon Mata -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<script>
    // Fungsi untuk toggle password visibility
    const toggleCurrentPassword = document.getElementById('toggle-current-password');
    const currentPasswordField = document.getElementById('current_password');
    toggleCurrentPassword.addEventListener('click', function() {
        const type = currentPasswordField.type === 'password' ? 'text' : 'password';
        currentPasswordField.type = type;
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });

    const toggleNewPassword = document.getElementById('toggle-new-password');
    const newPasswordField = document.getElementById('new_password');
    toggleNewPassword.addEventListener('click', function() {
        const type = newPasswordField.type === 'password' ? 'text' : 'password';
        newPasswordField.type = type;
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });

    const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
    const confirmPasswordField = document.getElementById('confirm_password');
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

</body>
</html>
