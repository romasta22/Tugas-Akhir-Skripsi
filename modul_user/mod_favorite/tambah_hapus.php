<?php
 
include '../koneksi.php'; // Pastikan koneksi database sudah ada

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "User belum login.";
    exit;
}

// Cek apakah form dikirim dengan metode POST dan apakah ada data id_wisata yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_wisata'])) {
        $id_user = $_SESSION['id_user']; // Ambil ID pengguna dari session
        $id_wisata = $_POST['id_wisata']; // Ambil ID wisata yang dipilih
        
        // Debugging: Cek apakah data diterima dengan benar
        echo "ID User: " . $id_user . "<br>";
        echo "ID Wisata: " . $id_wisata . "<br>";

        // Menggunakan prepared statement untuk menghindari SQL injection
        $query = "INSERT INTO tbl_favorite (id_user, id_wisata) VALUES (?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        
        if ($stmt) {
            // Binding parameter untuk prepared statement
            mysqli_stmt_bind_param($stmt, "ii", $id_user, $id_wisata);

            // Eksekusi query
            if (mysqli_stmt_execute($stmt)) {
                // Jika berhasil, alihkan ke halaman favorit atau halaman lain
                echo "Wisata berhasil ditambahkan ke favorit!";
                //header("Location: dashboard_user.php"); // Ganti dengan URL yang sesuai jika ingin redirect
                exit();
            } else {
                echo "Gagal menambahkan wisata ke favorit: " . mysqli_error($koneksi);
            }

            // Tutup prepared statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement: " . mysqli_error($koneksi);
        }
    } else {
        echo "Data ID Wisata tidak diterima.";
    }
} else {
    echo "Metode request bukan POST.";
}

// Tutup koneksi database
mysqli_close($koneksi);
?>
