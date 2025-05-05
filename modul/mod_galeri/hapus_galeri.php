<?php
    $id = $_GET['id'];
    // echo $id;

    $sql = mysqli_query($koneksi, "SELECT * FROM tbl_galeri WHERE id_galeri='$id'");
    $r = mysqli_fetch_array($sql);

    unlink("././img_galeri/".$r['nama_foto']);

    mysqli_query($koneksi, "DELETE FROM tbl_galeri WHERE id_galeri='$id'");

    echo "<script>alert('Galeri Berhasil di Hapus'); window.location = 'dashboard.php?hal=galeri' </script>";    

?>