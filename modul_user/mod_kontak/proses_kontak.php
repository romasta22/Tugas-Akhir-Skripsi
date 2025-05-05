<?php
// Sertakan file koneksi
include('../koneksi.php');

// Mengecek apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menangkap data dari formulir
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email  = mysqli_real_escape_string($koneksi, $_POST['email']);
    $pesan  = mysqli_real_escape_string($koneksi, $_POST['pesan']);

    // Query untuk memasukkan data ke dalam tabel kontak
    $query = "INSERT INTO tbl_kontak (nama, email, pesan) VALUES ('$nama', '$email', '$pesan')";

    // Menjalankan query
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, redirect ke halaman sukses atau tampilkan pesan sukses
        echo "<script>alert('Pesan Anda berhasil dikirim!'); window.location = 'dashboard_user.php?hal=kontak';</script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>alert('Gagal mengirim pesan!'); window.location = 'kontak.php';</script>";
    }
}

// Menutup koneksi
mysqli_close($koneksi);
?>
