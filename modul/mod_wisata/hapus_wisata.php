<?php
    $id = $_GET['id'];

    mysqli_query($koneksi, "DELETE FROM tbl_wisata WHERE id_wisata='$id'");

    echo "<script>alert('Wisata Berhasil di Hapus!'); window.location = 'dashboard.php?hal=wisata' </script>";
?>