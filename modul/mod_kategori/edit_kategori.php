<div class="container-fluid">
    <div class="card">
        <div class="card-header"><strong>Form Ubah Data Kategori Wisata</strong></div>
        <div class="card-body">
            <?php
               $id = $_GET['id'];
               $sql = mysqli_query($koneksi, "SELECT * FROM tbl_kategori WHERE id_kategori='$id'");
               $r = mysqli_fetch_array($sql);
            ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" class="form-control" name="nama_kategori" value="<?php  echo $r['nama_kategori'] ?>">
                </div>                
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2"><?php echo $r['keterangan'] ?></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    $nama_kategori = $_POST['nama_kategori'];
                    $keterangan = $_POST['keterangan'];

                    mysqli_query($koneksi, "UPDATE tbl_kategori SET nama_kategori='$nama_kategori', keterangan='$keterangan' WHERE id_kategori='$id'");

                    echo "<script>alert('Data Kategori Berhasil Diubah'); window.location = 'dashboard.php?hal=kategori_wisata'</script>";
                }

            ?>
        </div>
    </div>
</div>