<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Edit Data Wisata</title>
    <!-- Tambahkan link CSS dan JS di sini jika ada -->
</head>
<body>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><strong>Form Edit Data Wisata</strong></div>
            <div class="card-body">
                <?php
                $id = $_GET['id'];

                // Ambil data wisata berdasarkan ID
                $sql = mysqli_query($koneksi, "SELECT * FROM tbl_wisata WHERE id_wisata='$id'");
                $r = mysqli_fetch_array($sql);
                ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Input Nama Wisata -->
                    <div class="form-group">
                        <label>Nama Wisata</label>
                        <input type="text" name="nama_wisata" class="form-control" value="<?php echo htmlspecialchars($r['nama_wisata']); ?>" required>
                    </div>

                    <!-- Input Kategori Wisata -->
                    <div class="form-group">
                        <label>Kategori Wisata</label>
                        <select name="id_kategori" class="form-control" required>
                            <option value="">--Pilih Kategori Wisata--</option>
                            <?php
                            $tampil = mysqli_query($koneksi, "SELECT * FROM tbl_kategori");
                            while ($a = mysqli_fetch_array($tampil)) {
                                $selected = $r['id_kategori'] == $a['id_kategori'] ? "selected" : "";
                                echo "<option value='{$a['id_kategori']}' {$selected}>{$a['nama_kategori']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Input Fasilitas Wisata -->
                    <div class="form-group">
                        <label>Fasilitas</label>
                        <div class="row">
                            <?php
                            // Ambil semua fasilitas dari tbl_fasilitas
                            $fasilitas = mysqli_query($koneksi, "SELECT * FROM tbl_fasilitas");

                            // Ambil fasilitas yang sudah dipilih dari tbl_wisata_fasilitas
                            $fasilitas_terpilih = [];
                            $query_selected = mysqli_query($koneksi, "SELECT id_fasilitas FROM tbl_wisata_fasilitas WHERE id_wisata = '$id'");
                            while ($row = mysqli_fetch_assoc($query_selected)) {
                                $fasilitas_terpilih[] = $row['id_fasilitas'];
                            }

                            // Tampilkan fasilitas dalam checkbox
                            while ($f = mysqli_fetch_assoc($fasilitas)) {
                                $checked = in_array($f['id_fasilitas'], $fasilitas_terpilih) ? "checked" : "";
                                echo '<div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="' . $f['id_fasilitas'] . '" ' . $checked . '>
                                            <label class="form-check-label">' . $f['nama_fasilitas'] . '</label>
                                        </div>
                                    </div>';
                            }
                            ?>
                        </div>
                    </div>


                    <!-- Input Lokasi Wisata -->
                    <div class="form-group">
                        <label>Lokasi Wisata</label>
                        <input type="text" name="lokasi_wisata" class="form-control" value="<?php echo htmlspecialchars($r['lokasi_wisata']); ?>" required>
                    </div>

                    <!-- Input Perkiraan Harga -->
                    <div class="form-group">
                        <label>Perkiraan Harga (Rp) <small>(Opsional)</small></label>
                        <input type="text" name="harga" class="form-control" value="<?php echo htmlspecialchars($r['harga']); ?>" placeholder="Contoh: Rp. 25.000 - 50.000">
                    </div>

                    <!-- Input Latitude -->
                    <div class="form-group">
                        <label>Latitude (Lintang)</label>
                        <input type="text" name="latitude" class="form-control" value="<?php echo htmlspecialchars($r['latitude']); ?>" required>
                    </div>

                    <!-- Input Longitude -->
                    <div class="form-group">
                        <label>Longitude (Bujur)</label>
                        <input type="text" name="longitude" class="form-control" value="<?php echo htmlspecialchars($r['longitude']); ?>" required>
                    </div>
                    
                    <!-- Input Deskripsi -->
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control ckeditor" id="ckeditor" name="deskripsi" rows="5"><?php echo $r['deskripsi']; ?></textarea>
                    </div>


                    <!-- Input Gambar -->
                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small>Jika ingin mengganti gambar, silakan pilih file. Jika tidak, biarkan kosong.</small>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

                <?php
                // Proses Edit Data
                if (isset($_POST['submit'])) {
                    $nama_wisata = $_POST['nama_wisata'];
                    $id_kategori = $_POST['id_kategori'];
                    $lokasi_wisata = $_POST['lokasi_wisata'];
                    $harga = !empty($_POST['harga']) ? $_POST['harga'] : NULL; // Jika kosong, set NULL
                    $latitude = $_POST['latitude'];
                    $longitude = $_POST['longitude'];
                    $deskripsi = $_POST['deskripsi'];
                    $deskripsi = strip_tags($_POST['deskripsi']);
                    // $deskripsi = htmlspecialchars($_POST['deskripsi']);
                    // $deskripsi = htmlspecialchars($_POST['deskripsi'], ENT_QUOTES);



                // Hapus fasilitas lama dari tbl_wisata_fasilitas
                    mysqli_query($koneksi, "DELETE FROM tbl_wisata_fasilitas WHERE id_wisata = '$id'");

                    // Simpan fasilitas baru yang dipilih
                    if (!empty($_POST['fasilitas'])) {
                        foreach ($_POST['fasilitas'] as $id_fasilitas) {
                            mysqli_query($koneksi, "INSERT INTO tbl_wisata_fasilitas (id_wisata, id_fasilitas) VALUES ('$id', '$id_fasilitas')");
                        }
                    }


                    // Proses unggah gambar (jika ada perubahan gambar)
                    if (!empty($_FILES['gambar']['name'])) {
                        $gambar = $_FILES['gambar']['name'];
                        $tmp_name = $_FILES['gambar']['tmp_name'];
                        $upload_dir = "././modul_user/image/"; // Path ke folder image
                        $upload_path = $upload_dir . $gambar;

                        if (move_uploaded_file($tmp_name, $upload_path)) {
                            $update_query = "UPDATE tbl_wisata SET 
                                nama_wisata = '$nama_wisata', 
                                id_kategori = '$id_kategori', 
                                lokasi_wisata = '$lokasi_wisata', 
                                harga = " . ($harga ? "'$harga'" : "NULL") . ", 
                                latitude = '$latitude', 
                                longitude = '$longitude', 
                                deskripsi = '$deskripsi',
                                gambar = '$gambar' 
                                WHERE id_wisata = '$id'";
                        } else {
                            echo "<script>alert('Gagal mengunggah gambar.');</script>";
                        }
                    } else {
                        // Jika tidak mengganti gambar, gunakan gambar yang lama
                        $update_query = "UPDATE tbl_wisata SET 
                            nama_wisata = '$nama_wisata', 
                            id_kategori = '$id_kategori', 
                            lokasi_wisata = '$lokasi_wisata', 
                            harga = " . ($harga ? "'$harga'" : "NULL") . ", 
                            latitude = '$latitude', 
                            longitude = '$longitude', 
                            deskripsi = '$deskripsi' 
                            WHERE id_wisata = '$id'";
                    }

                    if (mysqli_query($koneksi, $update_query)) {
                        echo "<script>alert('Wisata Berhasil di Ubah!'); window.location='dashboard.php?hal=wisata';</script>";
                    } else {
                        echo "<script>alert('Gagal mengubah data. Error: " . mysqli_error($koneksi) . "');</script>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
