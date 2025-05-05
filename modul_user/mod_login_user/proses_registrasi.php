<?php
// Menyertakan koneksi database
include('../koneksi.php'); // Pastikan file koneksi.php ada di folder yang sama

// Memeriksa apakah form registrasi sudah dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error_message = "Password dan Konfirmasi Password tidak cocok!";
    } else {
        // Cek apakah username sudah ada di database
        $query = "SELECT * FROM tbl_pengguna WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "Username atau Email sudah digunakan!";
        } else {
            // Hash password sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Masukkan data pengguna ke dalam tabel
            $created_at = date('Y-m-d H:i:s'); // Menyimpan tanggal dan waktu saat registrasi
            $insert_query = "INSERT INTO tbl_pengguna (username, password, email, created_at) 
                             VALUES ('$username', '$hashed_password', '$email', '$created_at')";

            if (mysqli_query($koneksi, $insert_query)) {
                // Jika registrasi berhasil, arahkan ke halaman login
                header("Location: dashboard_user.php?hal=login");
                exit();
            } else {
                $error_message = "Terjadi kesalahan, coba lagi!";
            }
        }
    }
}
?>

<!-- Menampilkan error jika ada -->
<?php if (isset($error_message)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>
