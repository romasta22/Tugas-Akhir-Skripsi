<?php
    $id = $_GET['id'];
    // echo $id;

    mysqli_query($koneksi, "DELETE FROM tbl_kategori WHERE id_kategori='$id'");

    echo "<script>alert('Data Kategori Berhasil di Hapus!'); window.location = 'dashboard.php?hal=kategori_wisata'</script>"

?>