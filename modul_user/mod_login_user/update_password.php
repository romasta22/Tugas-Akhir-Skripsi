<?php
 
if (empty($_SESSION['username'])) {
    echo '<center><b>Maaf, Anda Harus Login Dulu!</b></center>';
    exit(); // Hentikan script jika belum login
}

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Lakukan validasi, misalnya:
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('Semua field harus diisi.'); window.location.href = 'dashboard_user.php?hal=update_password';</script>";
        exit();
    }

    if ($new_password != $confirm_password) {
        echo "<script>alert('Password baru dan konfirmasi password tidak cocok.'); window.location.href = 'dashboard_user.php?hal=update_password';</script>";
        exit();
    }

    // Ambil username dari session
    $username = $_SESSION['username'];

    // Koneksi ke database (sesuaikan dengan pengaturanmu)
    $conn = new mysqli('localhost', 'root', '', 'dbs_wisata'); // Ganti dengan pengaturan database

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil password lama dari database berdasarkan username
    $sql = "SELECT password FROM tbl_pengguna WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Ambil data password yang ada di database
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Cek apakah password lama yang dimasukkan sesuai dengan password di database
        if (password_verify($current_password, $stored_password)) {
            // Jika password lama sesuai, update password baru
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT); // Enkripsi password baru

            $update_sql = "UPDATE tbl_pengguna SET password = '$hashed_new_password' WHERE username = '$username'";

            if ($conn->query($update_sql) === TRUE) {
                echo "<script>alert('Password berhasil diubah.'); window.location.href = 'dashboard_user.php?hal=home';</script>";
                exit();
            } else {
                echo "<script>alert('Terjadi kesalahan saat mengubah password: " . $conn->error . "'); window.location.href = 'dashboard_user.php?hal=update_password';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Password lama tidak sesuai.'); window.location.href = 'dashboard_user.php?hal=update_password';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Pengguna tidak ditemukan.'); window.location.href = 'dashboard_user.php?hal=update_password';</script>";
        exit();
    }

    // Tutup koneksi
    $conn->close();
}
?>
