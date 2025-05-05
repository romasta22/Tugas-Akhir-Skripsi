<?php
include "../koneksi.php";

session_start();

// Ambil data dari form login
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password']; // Jangan di-hash karena password_verify akan menangani ini

// Query untuk mencari pengguna berdasarkan username
$query = mysqli_query($koneksi, "SELECT * FROM tbl_pengguna WHERE username='$username'");

// Periksa apakah pengguna ditemukan
if (mysqli_num_rows($query) > 0) {
    // Ambil data pengguna
    $r = mysqli_fetch_array($query);

    // Verifikasi password dengan password_verify()
    if (password_verify($password, $r['password'])) {
        // Password cocok, buat session
        $_SESSION['username'] = $r['username'];
        $_SESSION['email'] = $r['email'];
        $_SESSION['id_user'] = $r['id_user'];
        $_SESSION['user_logged_in'] = 1;

        // Redirect ke dashboard
        header("location: dashboard_user.php?hal=home");
    } else {
        // Password tidak cocok
        header("location: gagal_login.php?error=wrong_credentials");
    exit();
    }
} else {
    // Username tidak ditemukan
    header("location: gagal_login.php");
}
?>
