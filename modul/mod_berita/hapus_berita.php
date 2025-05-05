<?php
    $id = $_GET['id'];

    // echo  $id;
    $sql = mysqli_query($koneksi, "SELECT * FROM tbl_berita WHERE id_berita='$id'");
    $r = mysqli_fetch_array($sql);

    unlink("././img_berita/".$r['foto_berita']);

    $sql = mysqli_query($koneksi, "DELETE FROM tbl_berita WHERE id_berita='$id'");

    echo "<script>alert('Berita Berhasil di Hapus!'); window.location = 'dashboard.php?hal=berita'</script>";
?>