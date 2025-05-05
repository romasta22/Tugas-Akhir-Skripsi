<?php
include '../koneksi.php'; // Pastikan koneksi database sudah ada

// ketika ingin menambahkan favorite apakh sudah login atau belum
// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: dashboard_user.php?hal=login"); // Arahkan ke halaman login
    exit();
}
 
// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'User belum login.']);
    exit;
}

// Cek apakah form dikirim dengan method POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_wisata'])) {
    $id_user = $_SESSION['id_user']; // Ambil ID pengguna dari session
    $id_wisata = $_POST['id_wisata']; // Ambil ID wisata yang dipilih

    // Cek apakah data favorit sudah ada di database
    $query_check = "SELECT * FROM tbl_favorite WHERE id_user = ? AND id_wisata = ?";
    $stmt_check = mysqli_prepare($koneksi, $query_check);
    
    if ($stmt_check) {
        // Binding parameter untuk prepared statement
        mysqli_stmt_bind_param($stmt_check, "ii", $id_user, $id_wisata);
        
        // Eksekusi query
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) > 0) {
            // Jika sudah ada, beri pesan dan kirim status
            echo json_encode(['status' => 'exists', 'message' => 'Wisata ini sudah ada di favorit Anda.']);
            mysqli_stmt_close($stmt_check);
            exit();
        } else {
            // Jika belum ada, lanjutkan untuk menambah data ke favorit
            $query = "INSERT INTO tbl_favorite (id_user, id_wisata) VALUES (?, ?)";
            $stmt = mysqli_prepare($koneksi, $query);
            
            if ($stmt) {
                // Binding parameter untuk prepared statement
                mysqli_stmt_bind_param($stmt, "ii", $id_user, $id_wisata);

                // Eksekusi query
                if (mysqli_stmt_execute($stmt)) {
                    // Jika berhasil, kirim status sukses
                    echo json_encode(['status' => 'success', 'message' => 'Wisata berhasil ditambahkan ke favorit!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan wisata ke favorit: ' . mysqli_error($koneksi)]);
                }

                // Tutup prepared statement
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . mysqli_error($koneksi)]);
            }
        }
        
        // Tutup prepared statement pengecekan
        mysqli_stmt_close($stmt_check);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error preparing check statement: ' . mysqli_error($koneksi)]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID wisata tidak valid.']);
        }

        // Tutup koneksi database
        mysqli_close($koneksi);
?>