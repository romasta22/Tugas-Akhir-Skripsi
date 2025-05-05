<div class="container-fluid">
    <div class="card">
        <div class="card-header"><strong>Form Tambah Data Galeri</strong></div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Deskripsi Foto</label>
                    <textarea rows="2" class="form-control" name="keterangan_foto" placeholder="Masukkan Keterangan Foto"></textarea>
                </div>
                <div class="form-group">
                    <label>Nama Wisata</label>
                    <select name="id_wisata" class="form-control">
                        <option value="0">-- Pilih Wisata --</option>
                        <?php
                            $sql = mysqli_query($koneksi, "SELECT * FROM tbl_wisata ORDER BY id_wisata ASC");
                            while ($r = mysqli_fetch_array($sql)) {
                        ?>
                            <option value="<?php echo $r['id_wisata']; ?>"><?php echo $r['nama_wisata']; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Foto</label>
                    <div class="d-flex align-items-center">
                        <input type="file" class="form-control-file" name="nama_foto[]" multiple id="fileInput">
                        <!-- <input type="text" id="fileNames" class="form-control ml-3" placeholder="Tidak ada file yang dipilih" readonly> -->
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
            </form>
            <?php
            if (isset($_POST['submit'])) {
                $keterangan_foto = $_POST['keterangan_foto'];
                $id_wisata = $_POST['id_wisata'];

                if (!empty($_FILES['nama_foto']['name'][0])) {
                    // Loop untuk setiap file
                    foreach ($_FILES['nama_foto']['tmp_name'] as $key => $tmpName) {
                        $nama_foto = $_FILES['nama_foto']['name'][$key];
                        $tmp_file = $_FILES['nama_foto']['tmp_name'][$key];
                        $extension_foto = pathinfo($nama_foto, PATHINFO_EXTENSION);
                        $size_foto = $_FILES['nama_foto']['size'][$key];

                        // Validasi ekstensi dan ukuran file
                        if (!in_array($extension_foto, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
                            echo "<script>alert('File $nama_foto tidak didukung!');</script>";
                            continue;
                        } elseif ($size_foto > 409600) {
                            echo "<script>alert('File $nama_foto terlalu besar!');</script>";
                            continue;
                        }

                        // Simpan file dengan nama unik
                        $nama_foto_baru = rand() . '_' . $nama_foto;
                        move_uploaded_file($tmp_file, '././img_galeri/' . $nama_foto_baru);

                        // Masukkan data ke database
                        $query = "INSERT INTO tbl_galeri (keterangan_foto, id_wisata, nama_foto) VALUES ('$keterangan_foto', '$id_wisata', '$nama_foto_baru')";
                        mysqli_query($koneksi, $query);
                    }

                    echo "<script>alert('Galeri berhasil ditambahkan!'); window.location = 'dashboard.php?hal=galeri'</script>";
                } else {
                    echo "<script>alert('Tidak ada file yang dipilih!');</script>";
                }
            }
            ?>
        </div>
    </div>
</div>

<script>
    // JavaScript untuk menampilkan nama file di input sejajar
    const fileInput = document.getElementById('fileInput');
    const fileNames = document.getElementById('fileNames');

    fileInput.addEventListener('change', () => {
        const files = fileInput.files; // Ambil file yang dipilih
        if (files.length === 0) {
            fileNames.value = "Tidak ada file yang dipilih.";
        } else {
            // Gabungkan semua nama file menjadi satu string
            const fileList = Array.from(files).map(file => file.name).join(", ");
            fileNames.value = fileList; // Tampilkan nama file di input
        }
    });
</script>
