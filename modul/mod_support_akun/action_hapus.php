<?php
    $id = $_GET['id'];

    // Mengambil data admin
    $sql = mysqli_query($koneksi, "SELECT * FROM tbl_admin WHERE id_admin='$id'");
    $r = mysqli_fetch_array($sql);

    // Menghapus data admin dari tabel
    $sql = mysqli_query($koneksi, "DELETE FROM tbl_admin WHERE id_admin='$id'");

    echo "<script>alert('Data Admin Berhasil di Hapus!'); window.location = 'dashboard.php?hal=admin'</script>";
?>
