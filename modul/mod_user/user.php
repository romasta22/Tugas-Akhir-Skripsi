<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow" style="width: 100%; max-width: 400px; border-radius: 10px;">
        <div class="card-header bg-primary text-white text-center" style="border-radius: 10px 10px 0 0;">
            <h5 class="mb-0"><strong>Pengaturan Akun</strong></h5>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="form-group w-100 mb-3">
                    <label for="nama_admin" class="form-label">Nama Admin</label>
                    <input type="text" name="nama_admin" id="nama_admin" class="form-control" placeholder="Masukkan Nama Anda" autocomplete="off" required>
                </div>
                <div class="form-group w-100 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan Username" autocomplete="off" required>
                </div>
                <div class="form-group w-100 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required>
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>
                <div class="form-group w-100 mb-3">
                    <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="konfirmasi_password" id="confirmPassword" class="form-control" placeholder="Masukkan Kembali Password" required>
                        <i class="fas fa-eye toggle-password" id="toggleConfirmPassword"></i>
                    </div>
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-100">Update</button>
            </form>

            <?php
                if (isset($_POST["submit"])) {
                    $nama_admin = $_POST["nama_admin"];
                    $username = $_POST["username"];
                    $password = $_POST["password"];
                    $konfirmasi_password = $_POST["konfirmasi_password"];
                    $id_user_login = $_SESSION["idadmin"];

                    if ($password == $konfirmasi_password) {
                        mysqli_query($koneksi, "UPDATE tbl_admin SET nama_admin='$nama_admin', username='$username', password='$password' 
                        WHERE id_admin=$id_user_login");

                        echo "<script> alert('Berhasil Diubah!'); window.location = 'dashboard.php?hal=home'</script>";
                    } else {
                        echo "<script>alert('Password Tidak Sama!'); window.location = 'dashboard.php?hal=home'</script>";
                    }
                }
            ?>
        </div>
    </div>
</div>

<!-- Tambahkan JavaScript -->
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordField = document.getElementById('confirmPassword');

    // Toggle Password Visibility for Password Field
    togglePassword.addEventListener('click', function () {
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.classList.toggle('fa-eye-slash');
    });

    // Toggle Password Visibility for Confirm Password Field
    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        this.classList.toggle('fa-eye-slash');
    });
</script>

<!-- Tambahkan Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Tambahkan CSS -->
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .password-wrapper input {
        padding-right: 40px; /* Ruang untuk ikon mata */
    }
    .password-wrapper .toggle-password {
        position: absolute;
        right: 10px;
        cursor: pointer;
        color: #6c757d;
    }
    .password-wrapper .toggle-password:hover {
        color: #000;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
</style>
