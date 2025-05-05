<div class="contaier-fluid">
    <div class="card">
        <div class="card-header"><strong> Ubah Konten Profil</strong></div>
        <div class="card-body">
            <?php
                $sql = mysqli_query($koneksi, "SELECT * FROM tbl_profil");

                $r = mysqli_fetch_array($sql);
            ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Konten Profil</label>
                  <textarea class="form-control ckeditor" name="konten_profil" id="ckeditor" rows="10"><?php  echo $r["konten_profil"]?></textarea>
                </div>
                <div class="form-group">
                  <label>Foto Profil</label>
                  <input type="file" class="form-control-file" name="foto_profil">
                  <small id="fileHelpId" class="form-text text-muted">Upload file image (jpg, jpeg, png)</small>
                </div>
                <div class="form-group">
                <!-- <img src="././img_profil/foto.jpg" alt="" height="100" width="200"> -->
                <img src="././img_profil/<?php echo $r["foto_profil"]; ?>" alt="Foto Profil" height="100" width="200">

                
                    <!-- <img src="././img_profil/<?php echo $r["foto_profil"] ?>" alt="<?php echo $r["foto_profil"] ?>" height="100" width="200"> -->
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
            </form>

            <?php
                if (isset($_POST["submit"])) {
                    $konten_profil = $_POST["konten_profil"]; // Mengambil data dari textarea
                    $id_profil = $r["id_profil"]; // Mengambil ID profil dari hasil query sebelumnya
                    // echo $konten_profil;
                    // echo $id_profil;
                    $foto_profil = $_FILES["foto_profil"]["name"]; // Nama file yang diupload
                    // echo $foto_profil;
                    $path_foto_profil = "././img_profil/" . $r["foto_profil"]; // Path foto lama
                    // echo $path_foto_profil;

                    $file_extension = array('png','jpeg','gif','jpg');
                    $extension = pathinfo($foto_profil, PATHINFO_EXTENSION);
                    $size_foto_profil = $_FILES['foto_profil']['size'];
                    $rand = rand();

                    // Cek apakah ada file yang diupload
                    if (empty($foto_profil)) {
                    
                        // Hanya update konten jika foto tidak diupload                       
                        $query = "UPDATE tbl_profil SET konten_profil='$konten_profil', foto_profil='$foto_profil' WHERE id_profil='$id_profil'";
        mysqli_query($koneksi,  "UPDATE tbl_profil SET konten_profil='$konten_profil', foto_profil='$foto_profil' WHERE id_profil='$id_profil'");
                        // mysqli_query($koneksi, $query);
                        if (mysqli_query($koneksi, $query)) {
                            echo "Update berhasil";
                        } else {
                            echo "Update gagal: " . mysqli_error($koneksi);
                        }

                        echo "<script>alert('Konten Profil Berhasil Diubah'); window.location = 'dashboard.php?hal=profil';</script>";

                    } else {                        
                                    
                       if (!in_array($extension, $file_extension)) {
                            echo "<script>alert('File Tidak Didukung!'); window.location = 'dashboard.php?hal=profil'</script>";
                       }  else {
                            if ($size_foto_profil <409600){
                                $nama_foto_profil = $rand.'_'.$foto_profil;
                                
                            if (file_exists($path_foto_profil)) {
                                    unlink($path_foto_profil);
                                    echo "File lama dihapus.";
                            } else {
                                    echo "File lama tidak ditemukan.";
                            }
                                 
                                move_uploaded_file($_FILES['foto_profil']['tmp_name'], '././img_profil/'.$foto_profil);
                                
                                // Update konten dan foto di database
                                mysqli_query($koneksi, "UPDATE tbl_profil SET konten_profil='$konten_profil', foto_profil='$foto_profil' WHERE id_profil='$id_profil'");
        
                                echo "<script>alert('Konten dan Foto Berhasil Diubah!'); window.location = 'dashboard.php?hal=profil' </script>";      


                            } else {
                                echo "<script>alert('Ukuran Foto terlalu Besar!');  window.location = 'dashboard.php?hal=profil'</script>";
                            }
                       }                    
                    }
                }
                
            ?>
        </div>
    </div>
 </div>
 
