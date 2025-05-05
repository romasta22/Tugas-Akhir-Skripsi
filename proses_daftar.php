<?php
// Sambungkan ke database
$conn = new mysqli('localhost', 'root', '', 'dbs_wisata');

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama_admin = $_POST['nama_admin'];
$username = $_POST['username'];
$password = $_POST['password']; // Simpan password tanpa enkripsi

// Masukkan data ke tabel tbl_admin
$sql = "INSERT INTO tbl_admin (nama_admin, username, password) VALUES ('$nama_admin', '$username', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='index.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup koneksi
$conn->close();
?>
