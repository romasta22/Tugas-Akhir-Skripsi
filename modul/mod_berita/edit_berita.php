<div class="container-fluid">
    <div class="card">
        <div class="card-header"><strong>Form Edit Berita</strong></div>
        <div class="card-body">
            <?php
                $id = $_GET['id'];
                // Mengambil data berita dan penulis dari tabel yang tepat
                $sql = mysqli_query($koneksi, "SELECT tbl_berita.*, tbl_admin.nama_admin FROM tbl_berita 
                                               JOIN tbl_admin ON tbl_berita.id_admin_berita = tbl_admin.id_admin 
                                               WHERE tbl_berita.id_berita = '$id'");
                $r = mysqli_fetch_array($sql);
            ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Judul Berita</label>
                    <textarea class="form-control" name="judul_berita" rows="2"><?php echo $r['judul_berita']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Sumber Berita</label>
                    <input type="text" class="form-control" name="sumber_berita" value="<?php echo $r['sumber_berita']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Kejadian</label>
                    <input type="date" class="form-control" name="tanggal_kejadian" value="<?php echo $r['tanggal_kejadian']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Konten Berita</label>
                    <textarea class="form-control" name="konten_berita" rows="2"><?php echo $r['konten_berita']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Penulis</label>
                    <!-- Menampilkan nama penulis (nama_admin dari tbl_admin) -->
                    <textarea class="form-control" name="penulis" rows="2" readonly><?php echo $r['nama_admin']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <!-- Menampilkan tanggal berita -->
                    <textarea class="form-control" name="tanggal_berita" rows="2" readonly><?php echo $r['tanggal_berita']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Foto</label>
                    <input type="file" class="form-control-file" name="foto_berita">
                    <img class="mt-4" src="././img_berita/<?php echo $r['foto_berita']; ?>" height="100" width="100">
                    <small class="form-text text-muted">*gambar lama</small>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    $judul_berita = $_POST['judul_berita'];
                    $sumber_berita = $_POST['sumber_berita'];
                    $tanggal_kejadian = $_POST['tanggal_kejadian'];
                    $konten_berita = $_POST['konten_berita'];
                    $penulis = $r['nama_admin']; // Nama penulis tetap diambil dari tabel admin
                    $tanggal = $_POST['tanggal_berita'];
                    $foto_berita = $_FILES['foto_berita']['name'];

                    $extension_foto = pathinfo($foto_berita, PATHINFO_EXTENSION);
                    $size_foto = $_FILES['foto_berita']['size'];
                     // echo $extension_foto;
                    // echo $size_foto;

                    // Jika tidak ada foto baru yang di-upload
                    if (empty($foto_berita)) {
                        mysqli_query($koneksi, "UPDATE tbl_berita SET judul_berita = '$judul_berita',
                                                                      sumber_berita = '$sumber_berita',
                                                                      tanggal_kejadian = '$tanggal_kejadian',
                                                                      konten_berita = '$konten_berita',
                                                                      tanggal_berita = '$tanggal'                                                                      
                                                                WHERE id_berita = '$id'");
                        echo "<script>alert('Berita Berhasil di Ubah!'); window.location = 'dashboard.php?hal=berita' </script>";
                    } else {
                        // Mengecek apakah ekstensi foto sesuai
                        if (!in_array($extension_foto, array('png', 'jpg', 'jpeg', 'gif', 'webp'))) {
                            echo "<script>alert('File Tidak Didukung!'); window.location = 'dashboard.php?hal=edit_berita&id=$id' </script>";
                        } else {
                            // Mengecek ukuran file foto
                            if ($size_foto > 409600) {
                                echo "<script>alert('File Terlalu Besar!'); window.location = 'dashboard.php?hal=edit_berita&id=$id' </script>";
                            } else {
                                $nama_foto_baru = rand() . '_' . $foto_berita;

                                // Menghapus foto lama jika ada
                                unlink("././img_berita/" . $r['foto_berita']);

                                // Memindahkan file foto yang di-upload
                                move_uploaded_file($_FILES['foto_berita']['tmp_name'], '././img_berita/' . $nama_foto_baru);

                                // Update berita dengan foto baru
                                mysqli_query($koneksi, "UPDATE tbl_berita SET judul_berita = '$judul_berita',
                                                                          sumber_berita = '$sumber_berita',
                                                                          tanggal_kejadian = '$tanggal_kejadian',
                                                                          konten_berita = '$konten_berita',
                                                                          tanggal_berita = '$tanggal',
                                                                          foto_berita = '$nama_foto_baru'                                                                      
                                                                WHERE id_berita = '$id'");

                                echo "<script>alert('Berita Berhasil di Ubah!'); window.location = 'dashboard.php?hal=berita' </script>";
                            }
                        }
                    }
                }
            ?>
        </div>
    </div>
</div>
