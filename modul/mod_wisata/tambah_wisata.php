<?php
include 'koneksi.php'; // Pastikan koneksi ke database sudah ada

// Ambil data fasilitas dari database
$query_fasilitas = "SELECT * FROM tbl_fasilitas";
$result_fasilitas = mysqli_query($koneksi, $query_fasilitas);

?>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tambah Data Wisata</title>
    <!-- Tambahkan link CSS dan JS di sini jika ada -->
</head>
<body>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><strong>Form Tambah Data Wisata</strong></div>
            <div class="card-body">
                <?php
                // Fungsi untuk memformat angka ke format Rupiah
                function formatRupiah($angka) {
                    return "Rp. " . number_format($angka, 0, ',', '.');
                }
                ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Input Nama Wisata -->
                    <div class="form-group">
                        <label>Nama Wisata</label>
                        <input type="text" class="form-control" name="nama_wisata" placeholder="Masukkan Nama Wisata" required>
                    </div>

                    <!-- Input Kategori Wisata -->
                    <div class="form-group">
                        <label>Kategori Wisata</label>
                        <select class="form-control" name="id_kategori" required>
                            <option value="">--Pilih Kategori Wisata--</option>
                            <?php
                            $sql = mysqli_query($koneksi, "SELECT * FROM tbl_kategori ORDER BY id_kategori ASC");
                            while ($r = mysqli_fetch_array($sql)) { ?>
                                <option value="<?php echo $r['id_kategori']; ?>"><?php echo htmlspecialchars($r['nama_kategori']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- Input Fasilitas Wisata -->
                    <label>Fasilitas (Ceklis Untuk Fasilitas)</label>
                    <div class="d-flex flex-wrap gap-3">
                        <?php while ($row = mysqli_fetch_assoc($result_fasilitas)) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fasilitas[]" value="<?= $row['id_fasilitas']; ?>" id="fasilitas_<?= $row['id_fasilitas']; ?>">
                                <label class="form-check-label" for="fasilitas_<?= $row['id_fasilitas']; ?>">
                                    <?= $row['nama_fasilitas']; ?>
                                </label>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Input Lokasi Wisata -->
                    <div class="form-group">
                        <label>Lokasi Wisata</label>
                        <input type="text" class="form-control" name="lokasi_wisata" placeholder="Masukkan Alamat Wisata" required>
                    </div>

                    <!-- Input Perkiraan Harga -->
                    <div class="form-group">
                        <label>Perkiraan Harga (Rp) <small>(Opsional)</small></label>
                        <input type="text" class="form-control" name="harga" placeholder="Contoh: 25.000 - 50.000">
                    </div>

                    <!-- Input Latitude -->
                    <div class="form-group">
                        <label>Latitude (Lintang)</label>
                        <input type="text" class="form-control" name="latitude" placeholder="Masukkan Latitude atau Lintang" required>
                    </div>

                    <!-- Input Longitude -->
                    <div class="form-group">
                        <label>Longitude (Bujur)</label>
                        <input type="text" class="form-control" name="longitude" placeholder="Masukkan Longitude atau Bujur" required>
                    </div>

                    <!-- Input Deskripsi -->
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control ckeditor" id="ckeditor" name="deskripsi" rows="5" placeholder="Masukkan Deskripsi" required></textarea>
                    </div>

                    <!-- Input Gambar -->
                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" class="form-control" name="gambar" accept="image/*" required>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

                <?php
                // Proses Tambah Data
                if (isset($_POST['submit'])) {
                    $nama_wisata = $_POST['nama_wisata'];
                    $id_kategori = $_POST['id_kategori'];
                    $lokasi_wisata = $_POST['lokasi_wisata'];
                    $harga = !empty($_POST['harga']) ? $_POST['harga'] : NULL; // Jika kosong, simpan NULL
                    $latitude = $_POST['latitude'];
                    $longitude = $_POST['longitude'];
                    // $deskripsi = $_POST['deskripsi'];
                    $deskripsi = strip_tags($_POST['deskripsi']);  // Menghapus tag HTML
                    $deskripsi = html_entity_decode($deskripsi, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Mengubah semua entitas HTML menjadi karakter asli
                    $deskripsi = str_replace(["\xc2\xa0", "\u00a0", "&nbsp;"], ' ', $deskripsi); // Menghapus karakter non-breaking space
                    $deskripsi = preg_replace('/\s+/', ' ', $deskripsi);  // Mengganti spasi lebih dari satu dengan satu spasi
                    $deskripsi = trim($deskripsi);  // Menghapus spasi di awal dan akhir

                    // Proses unggah gambar
                    $gambar = $_FILES['gambar']['name'];
                    $tmp_name = $_FILES['gambar']['tmp_name'];
                    $upload_dir = "././modul_user/image/"; // Path ke folder image
                    $upload_path = $upload_dir . $gambar;

                    // Debugging untuk memastikan path benar
                    echo "Path folder tujuan: " . realpath($upload_dir) . "<br>";
                    echo "Path lengkap file: " . $upload_path . "<br>";

                    // Validasi format harga
                    if (!preg_match('/^\d{1,3}(\.\d{3})*( - \d{1,3}(\.\d{3})*)?$/', $_POST['harga'])) {
                        echo "<script>alert('Format harga tidak valid. Gunakan format: 25.000 atau 25.000 - 50.000');</script>";
                        exit;
                    }

                    // Validasi folder tujuan
                    if (!is_dir($upload_dir)) {
                        echo "<script>alert('Folder tujuan tidak ditemukan. Pastikan folder image ada.');</script>";
                        exit;
                    }
                    if (!is_writable($upload_dir)) {
                        echo "<script>alert('Folder tujuan tidak memiliki izin tulis. Pastikan izin sudah diset.');</script>";
                        exit;
                    }

                    // Validasi file upload
                    if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
                        echo "<script>alert('Gagal mengunggah gambar. Error kode: {$_FILES['gambar']['error']}');</script>";
                        exit;
                    }

                    // Pindahkan file gambar ke folder tujuan
                    if (move_uploaded_file($tmp_name, $upload_path)) {
                        // Masukkan data ke database jika gambar berhasil diunggah
                        $query = "INSERT INTO tbl_wisata (nama_wisata, id_kategori, lokasi_wisata, harga, latitude, longitude, deskripsi, gambar) 
                                  VALUES ('$nama_wisata', '$id_kategori', '$lokasi_wisata', " . ($harga ? "'$harga'" : "NULL") . ", '$latitude', '$longitude', '$deskripsi', '$gambar')";

                        if (mysqli_query($koneksi, $query)) {
                            $id_wisata = mysqli_insert_id($koneksi); // Ambil ID wisata yang baru ditambahkan

                            // Cek apakah fasilitas dipilih
                            if (!empty($_POST['fasilitas'])) {
                                foreach ($_POST['fasilitas'] as $id_fasilitas) {
                                    $query_fasilitas = "INSERT INTO tbl_wisata_fasilitas (id_wisata, id_fasilitas) VALUES ('$id_wisata', '$id_fasilitas')";
                                    if (!mysqli_query($koneksi, $query_fasilitas)) {
                                        echo "<script>alert('Gagal menambahkan fasilitas. Error: " . mysqli_error($koneksi) . "');</script>";
                                        exit;
                                    }
                                }
                            }

                            echo "<script>alert('Wisata Berhasil Ditambahkan'); window.location = 'dashboard.php?hal=wisata';</script>";
                        } else {
                            echo "<script>alert('Gagal menambahkan data wisata. Error: " . mysqli_error($koneksi) . "');</script>";
                        }

                    } else {
                        echo "<script>alert('Gagal memindahkan file gambar ke folder tujuan.');</script>";
                    }
                }
                ?>

                
            </div>
        </div>
    </div>
</body>
</html>
