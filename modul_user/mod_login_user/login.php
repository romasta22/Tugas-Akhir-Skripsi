<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../koneksi.php';

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM tbl_user WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    
        // Cek password hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
    
            // Redirect ke halaman sebelumnya
            if (isset($_GET['redirect']) && isset($_GET['id_wisata'])) {
                $redirect_page = $_GET['redirect'];
                $id_wisata = $_GET['id_wisata'];
                header("Location: dashboard_user.php?hal=favorite$redirect_page&id_wisata=$id_wisata");
                exit();
            } else {
                header("Location: dashboard_user.php?hal=detail_wisata_alam&id");
                exit();
            }
        } else {
            echo "<script>alert('Password salah!'); window.location.href='dashboard_user.php?hal=login';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location.href='dashboard_user.php?hal=login';</script>";
    }
}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Go Danau Toba</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

<div class="container mt-5">  <!-- Menggunakan mt-5 untuk margin top -->
    <div class="login-container">
        <h2>Login</h2>
        <p class="text-center" style="color: rgba(0, 0, 0, 0.4); filter: blur(0.5px);">Kalau udah punya akun login aja :3</p>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="cek_login.php" method="POST"> 
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" name="password" class="form-control" placeholder="Password" required id="password">
                <i class="bi bi-eye-slash eye-icon" id="toggle-password"></i> <!-- Icon mata -->
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="form-text mt-3">
            Belum punya akun? <a href="dashboard_user.php?hal=registrasi">Daftar di sini</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<!-- Font Awesome for Eye Icon (Optional) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
</script>

</body>
</html>
