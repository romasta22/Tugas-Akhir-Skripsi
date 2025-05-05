<?php
        $conn = new mysqli("localhost", "root", "", "dbs_wisata");

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // untuk menambahkan wisata ke favorite
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_wisata'])) {
            // Ambil data dari form
            $id_wisata = $_POST['id_wisata'];
            $user_id = $_SESSION['id_user']; // Asumsikan user_id sudah disimpan di session saat login

            // Koneksi ke database (sesuaikan dengan pengaturan koneksi Anda)
            include('../koneksi.php'); // Gantilah dengan file koneksi database Anda

            // Periksa apakah wisata sudah ada di favorit pengguna
            $query_check_favorite = "SELECT * FROM tbl_favorite WHERE id_user = ? AND id_wisata = ?";
            $stmt = $conn->prepare($query_check_favorite);
            $stmt->bind_param('ii', $user_id, $id_wisata);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                // Jika wisata belum ada di favorit, tambahkan ke favorit
                $query_add_favorite = "INSERT INTO tbl_favorite (id_user, id_wisata) VALUES (?, ?)";
                $stmt_add = $conn->prepare($query_add_favorite);
                $stmt_add->bind_param('ii', $user_id, $id_wisata);
                $stmt_add->execute();

                // Memberikan pesan sukses atau mengalihkan ke halaman favorit
                echo "<script>alert('Wisata berhasil ditambahkan ke favorit!');</script>";
                echo "<script>window.location.href = 'dashboard_user.php?hal=favorite';</script>";
            } else {
                // Jika wisata sudah ada di favorit
                echo "<script>alert('Wisata sudah ada di favorit Anda.'); window.location.href = 'index_user.php?hal=detail_wisata_alam&id=$id_wisata'; </script>";
            }
            // Tutup koneksi
            $stmt->close();
            $conn->close();
        } // batass untuk favorite


        // untuk rekomendasi wisata serupa
        // $id_wisata = $_GET['id']; // Ambil ID wisata dari URL
        // Pastikan ada ID di URL
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id_wisata = $_GET['id'];

        // Query untuk mengambil detail wisata
        $query = "SELECT * FROM tbl_wisata WHERE id_wisata = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_wisata);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        // akhir untuk rekomendasi serupa


            // Fungsi untuk memformat angka atau rentang harga ke format Rupiah
            function formatRupiah($angka) {
                // Cek apakah input berupa rentang harga (mengandung "-")
                if (strpos($angka, '-') !== false) {
                    $range = explode('-', $angka); // Pisahkan rentang menjadi array
                    $min = trim($range[0]); // Ambil nilai pertama
                    $max = trim($range[1]); // Ambil nilai kedua
                    // Format kedua angka
                    return "Rp. " . number_format((float)str_replace('.', '', $min), 0, ',', '.') . " - Rp. " . number_format((float)str_replace('.', '', $max), 0, ',', '.');
                }
                // Jika input bukan rentang, format langsung
                return "Rp. " . number_format((float)str_replace('.', '', $angka), 0, ',', '.');
            }
                

        // Mendapatkan ID wisata dari URL
        $id_wisata = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id_wisata === 0) {
            die("ID wisata tidak valid.");
        }

        // Query untuk mendapatkan detail wisata
        $query = "SELECT * FROM tbl_wisata WHERE id_wisata = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_wisata);
        $stmt->execute();
        $result = $stmt->get_result();
        $wisata = $result->fetch_assoc();

        if (!$wisata) {
            die("Wisata tidak ditemukan.");
        }

        // Path gambar
        $gambar = !empty($wisata['gambar']) ? '././image/' . $wisata['gambar'] : '././image/default.jpg';

        // Query untuk galeri foto wisata
        $galeriQuery = "SELECT nama_foto FROM tbl_galeri WHERE id_wisata = ?";
        $galeriStmt = $conn->prepare($galeriQuery);
        $galeriStmt->bind_param("i", $id_wisata);
        $galeriStmt->execute();
        $galeriResult = $galeriStmt->get_result();
        $galeriFotos = $galeriResult->fetch_all(MYSQLI_ASSOC);

        $currentRating = $wisata['rating'] ?? 0;

        // Koordinat untuk Google Maps
        $latitude = $wisata['latitude'];
        $longitude = $wisata['longitude'];
        $google_maps_url = "https://www.google.com/maps/dir/?api=1&destination=$latitude,$longitude";

        // Proses menyimpan ulasan jika ada data POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ulasan = $_POST['review'];         // Isi ulasan dari pengguna
            $rating = (int) $_POST['rating'];   // Rating dari pengguna
            $nama_pengguna = 'Anonim';          // Default nama pengguna menjadi 'Anonim'

            // Jika memilih dengan nama pengguna
        if (isset($_POST['is_anonim']) && $_POST['is_anonim'] == 0) {
                // Ambil nama pengguna dari session jika login
                if (isset($_SESSION['username'])) {
                    $nama_pengguna = $_SESSION['username'];
                }
            }

            // Query untuk menyimpan ulasan baru ke tbl_ulasan
            $queryInsert = "INSERT INTO tbl_ulasan (id_wisata, nama_pengguna, ulasan, rating) VALUES (?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($queryInsert);
            if (!$stmtInsert) {
                die("Error pada query insert: " . $conn->error);
            }
            $stmtInsert->bind_param("issi", $id_wisata, $nama_pengguna, $ulasan, $rating);
            $stmtInsert->execute();
            if ($stmtInsert->affected_rows <= 0) {
                die("Ulasan gagal disimpan.");
            }

            // Update rata-rata rating di tbl_wisata
            $queryAvg = "SELECT AVG(rating) AS rata_rating FROM tbl_ulasan WHERE id_wisata = ?";
            $stmtAvg = $conn->prepare($queryAvg);
            $stmtAvg->bind_param("i", $id_wisata);
            $stmtAvg->execute();
            $resultAvg = $stmtAvg->get_result();
            $rataRating = $resultAvg->fetch_assoc()['rata_rating'];

            $queryUpdate = "UPDATE tbl_wisata SET rating = ? WHERE id_wisata = ?";
            $stmtUpdate = $conn->prepare($queryUpdate);
            $stmtUpdate->bind_param("di", $rataRating, $id_wisata);
            $stmtUpdate->execute();

            
echo "<script type='text/javascript'>alert('Ulasan berhasil dikirim!');window.location.href = 'dashboard_user.php?hal=detail_wisata_alam&id=" . $id_wisata . "';</script>";

        }

        // Query untuk mendapatkan ulasan terbaru (hanya 3 ulasan)
        $queryUlasan = "SELECT nama_pengguna, ulasan, rating, tanggal FROM tbl_ulasan WHERE id_wisata = ? ORDER BY tanggal DESC LIMIT 3";
        $stmtUlasan = $conn->prepare($queryUlasan);
        $stmtUlasan->bind_param("i", $id_wisata);
        $stmtUlasan->execute();
        $resultUlasan = $stmtUlasan->get_result();
        $ulasanList = $resultUlasan->fetch_all(MYSQLI_ASSOC);

        // untuk fasilitas wisata        
        $id_wisata = $wisata['id_wisata']; // ID wisata yang sedang ditampilkan

        $query_fasilitas = "SELECT f.nama_fasilitas 
                            FROM tbl_wisata_fasilitas wf
                            JOIN tbl_fasilitas f ON wf.id_fasilitas = f.id_fasilitas
                            WHERE wf.id_wisata = ?";
        $stmt = $conn->prepare($query_fasilitas);
        $stmt->bind_param("i", $id_wisata);
        $stmt->execute();
        $result_fasilitas = $stmt->get_result();

        $fasilitas = [];
        while ($row = $result_fasilitas->fetch_assoc()) {
            $fasilitas[] = $row['nama_fasilitas'];
        }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Wisata Alam</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .carousel-inner img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-description {
            font-size: 1rem;
        }
        .card-rating {
            color: #f39c12;
        }
        /* //////////////////////// */
        /* Desain untuk tombol */
.btn-custom {
    display: flex; /* Membuat ikon dan teks berada dalam satu baris */
    align-items: center; /* Menyelaraskan ikon dan teks secara vertikal */
    padding: 10px 20px; /* Menambahkan padding untuk tombol */
    border-radius: 50px; /* Membuat tombol berbentuk oval */
    background-color:rgb(192, 232, 240); /* Warna latar belakang tombol */
    border: 1px solid #ddd; /* Border halus di sekitar tombol */
    color: #333; /* Warna teks */
    font-size: 14px; /* Ukuran font */
    text-decoration: none; /* Menghilangkan underline pada link */
    transition: background-color 0.3s, color 0.3s; /* Transisi warna saat hover */
}

.btn-custom:hover {
    background-color: #007bff; /* Warna latar belakang saat hover */
    color: white; /* Warna teks saat hover */
}

.btn-custom i {
    margin-right: 8px; /* Memberikan jarak antara ikon dan teks */
}

/* Agar ikon dan teks berada dalam satu baris dengan jarak yang sesuai */
.d-flex {
    display: flex;
    gap: 10px; /* Memberikan jarak antar tombol */
}

/* Styling untuk review section */
.review {
    border-bottom: 1px solid #ddd; /* Batas bawah untuk setiap ulasan */
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.mt-3 {
    margin-top: 16px;
}

.mt-4 {
    margin-top: 30px;
}

    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-3"><?php echo htmlspecialchars($wisata['nama_wisata']); ?></h1>

        <!-- Carousel Section -->
        <div id="wisataCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <!-- Gambar Utama -->
                <div class="carousel-item active">
                    <img src="<?php echo './image/' . (file_exists('./image/' . $wisata['gambar']) && !empty($wisata['gambar']) ? $wisata['gambar'] : 'default.jpg'); ?>" alt="Gambar Utama">
                </div>
                <!-- Galeri Foto -->
                <?php foreach ($galeriFotos as $foto): ?>
                    <div class="carousel-item">
                        <img src="../img_galeri/<?php echo $foto['nama_foto']; ?>" alt="Foto Wisata">
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#wisataCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#wisataCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <!-- Detail Wisata -->
          <div class="card-body">
        
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <th style="white-space: nowrap; text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Nama Objek Wisata</th>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($wisata['nama_wisata']); ?></td>
                </tr>
                <tr>
                    <th style="white-space: nowrap; width: 200px; text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Rating Saat ini</th>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo $wisata['rating'] > 0 ? number_format($wisata['rating'], 1) . ' ⭐' : 'Belum ada rating'; ?></td>
                </tr>
                <tr>
                    <th style="white-space: nowrap; width: 200px; text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">ID Objek Wisata</th>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($wisata['id_wisata']); ?></td>
                </tr>
                <tr>
                    <th style="white-space: nowrap; text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Lokasi</th>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($wisata['lokasi_wisata']); ?></td>
                </tr>
                <tr>
                    <th style="white-space: nowrap; text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Perkiraan HTM</th>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo !empty($wisata['harga']) ? htmlspecialchars(formatRupiah($wisata['harga'])) : 'Tidak tersedia'; ?></td>
                </tr>
                <tr>
                    <th style="vertical-align: top; white-space: nowrap; text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">
                        Deskripsi
                    </th>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: justify;">
                        <?php echo strip_tags($wisata['deskripsi']); ?>
                    </td>
                    </tr>
                    <tr>
                        <th style="white-space: nowrap; text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Fasilitas</th>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                            <?php
                            if (!empty($fasilitas)) {
                                echo implode(", ", $fasilitas); // Menampilkan fasilitas dalam format teks biasa
                            } else {
                                echo "Tidak ada fasilitas tersedia";
                            }
                            ?>
                        </td>
                    </tr>


                
            </table>
        <div class="card mt-4"> 
        <div class="card-body">  
        <div class="mt-3">
                <h4>Ulasan Terbaru</h4>
                <?php foreach ($ulasanList as $ulasan): ?>
                    <div class="review">
                        <p><strong><?php echo htmlspecialchars($ulasan['nama_pengguna']); ?></strong></p>
                        <p><?php echo str_repeat("⭐", $ulasan['rating']); ?> - <small><?php echo date("d M Y, H:i", strtotime($ulasan['tanggal'])); ?></small></p>
                        <p><?php echo htmlspecialchars($ulasan['ulasan']); ?></p>
                    </div>
                <?php endforeach; ?>
                <div class="mt-2">
                    <a href="index_user.php?hal=lihat_semua_ulasan&id_wisata=<?php echo $id_wisata; ?>" class="text-primary" style="text-decoration: underline;">Lihat ulasan lainnya</a>
                </div>
            </div>
            <br>
            <div class="d-flex gap-2">
                <!-- Tombol Rute -->
                <a href="<?php echo $google_maps_url; ?>" 
                class="btn-custom">
                <i class="bi bi-signpost"></i> 
                Rute
                </a>
            <!-- Tombol Favorit -->
            <?php if (isset($_SESSION['id_user'])): ?>
                <!-- Jika sudah login -->
                <form method="POST" action="dashboard_user.php?hal=tambah_favorite" class="d-inline">
                    <input type="hidden" name="id_wisata" value="<?php echo $id_wisata; ?>">
                    <button type="submit" class="btn-custom">
                        <i class="bi bi-bookmark"></i> Favorit
                    </button>
                </form>
            <?php else: ?>
                <!-- Jika belum login -->
                <button onclick="alert('Anda belum Login, silakan login terlebih dahulu untuk menambahkan favorit!'); 
                window.location.href='dashboard_user.php?hal=login&redirect=tambah_favorite&id_wisata=<?php echo $id_wisata; ?>';" 
                class="btn-custom">
                    <i class="bi bi-bookmark"></i> Favorit
                </button>
            <?php endif; ?>             

                <!-- Tombol Bagikan -->
                <button class="btn-custom" id="btnShare">
                    <i class="bi bi-share"></i>
                    Bagikan
                </button>
            </div>
            <br>
            <!-- Form Ulasan dan Rating -->
        <!-- Form Ulasan dan Rating -->
<div class="mt-5 text-center">
    <h3>Ulasan dan Rating</h3>
    <form method="POST" class="review-form mt-3">
        <textarea name="review" rows="5" class="form-control" placeholder="Tulis ulasan Anda di sini..." required></textarea>
        <div class="star-rating mt-3"> 
        
    <!-- Bintang Rating 1 -->
    <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
    <!-- Bintang Rating 2 -->
    <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
    <!-- Bintang Rating 3 -->
    <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
    <!-- Bintang Rating 4 -->
    <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
    <!-- Bintang Rating 5 -->
    <input type="radio" name="rating" id="star5" value="5"><label for="star5">★</label>
</div>

        <div class="radio-buttons mt-3">
            <label>
                <input type="radio" name="is_anonim" value="1" checked> Anonim
            </label>
            <label>
                <input type="radio" name="is_anonim" value="0"> Dengan Nama Pengguna
            </label>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Kirim Ulasan dan Rating</button>
    </form>
</div>

<!-- CSS untuk Star Rating -->
<style>
.star-rating {
    display: flex; /* Gunakan flexbox untuk memastikan bintang berurutan dari kiri ke kanan */
    flex-direction: row-reverse; /* Menggunakan reverse agar input bintang 1 di kiri */
    justify-content: center;
}

.star-rating input[type="radio"] {
    display: none; /* Menyembunyikan input radio */
}

.star-rating label {
    font-size: 2rem;
    color: #ddd; /* Bintang yang tidak dipilih berwarna abu-abu */
    cursor: pointer; /* Menunjukkan bahwa bintang bisa diklik */
}

/* Mengaktifkan bintang yang dipilih */
.star-rating input[type="radio"]:checked ~ label,
.star-rating input[type="radio"]:checked + label {
    color: #ffcc00; /* Semua bintang sebelumnya menyala */
}

/* Hover effect untuk bintang */
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffcc00; /* Hover effect untuk semua bintang sebelumnya */
}
</style>

        <hr style="border: 2px solid blue; margin-top: 40px; margin-bottom: 40px;">
        <h2>Rekomendasi Wisata  Alam Serupa</h2>
        <div class="row d-flex justify-content-center">
            <?php
            // Query untuk mengambil rekomendasi wisata berdasarkan Cosine Similarity
            // $query_rekomendasi = "SELECT w.id_wisata, w.nama_wisata, w.gambar 
            //                     FROM tbl_rekomendasi r
            //                     JOIN tbl_wisata w ON r.id_rekomendasi_wisata = w.id_wisata
            //                     WHERE r.id_wisata = ? 
            //                     ORDER BY r.similarity_score DESC LIMIT 5";
           
            $query_rekomendasi = "SELECT DISTINCT w.id_wisata, w.nama_wisata, w.gambar
                                    FROM tbl_rekomendasi r
                                    JOIN tbl_wisata w ON r.id_rekomendasi_wisata = w.id_wisata
                                    WHERE r.id_wisata = ? AND r.id_rekomendasi_wisata != ?
                                    ORDER BY r.similarity_score DESC LIMIT 5";

            $stmt_rekomendasi = $conn->prepare($query_rekomendasi);
            $stmt_rekomendasi->bind_param("ii", $id_wisata, $id_wisata); // id_wisata yang sama untuk keduanya
            $stmt_rekomendasi->execute();
            $result_rekomendasi = $stmt_rekomendasi->get_result();

            $rekomendasi = array();
            while ($row = $result_rekomendasi->fetch_assoc()) {
                // Menghindari duplikasi dengan `DISTINCT` di query
                $rekomendasi[] = $row;
            }

            // Menampilkan rekomendasi wisata
            foreach ($rekomendasi as $item) {
                ?>
                <div class="col-md-2 col-6 mb-3">
                    <div class="card shadow-sm border-0 h-100">
                        <a href="index_user.php?hal=detail_wisata_alam&id=<?php echo $item['id_wisata']; ?>" class="text-decoration-none">
                            <img src="./image/<?php echo $item['gambar']; ?>" class="card-img-top rounded" alt="<?php echo $item['nama_wisata']; ?>" style="height: 100px; object-fit: cover;">
                            <div class="card-body p-2 text-center" style="min-height: 80px;">
                                <p class="card-title text-dark fw-bold mb-1" style="font-size: 14px; line-height: 1.4;"><?php echo $item['nama_wisata']; ?></p>
                                <p class="text-primary small">Lihat Detail</p>
                            </div>
                        </a>
                    </div>
                </div>
                <?php } ?>

                    </div>
        <!-- ---------------------- code untuk rekomendasi ------------------------------ -->
    </div>  
      <!-- Tombol Kembali -->
      <div class="mt-4">
            <a href="dashboard_user.php?hal=wisata_alam" class="btn btn-secondary">Kembali ke Daftar Wisata Alam</a>
        </div>
    </div>  
    <script>
        // Fungsi untuk berbagi
        document.getElementById('btnShare').addEventListener('click', function(event) {
            event.preventDefault();
            if (navigator.share) {
                navigator.share({
                    title: 'Detail Wisata Alam',
                    text: 'Lihat detail wisata alam ini!',
                    url: window.location.href
                }).catch(err => console.error('Gagal membagikan:', err));
            } else {
                alert('Fitur berbagi tidak didukung di browser Anda.');
            }
        });
    
        // Fungsi untuk menangani tombol favorit menggunakan AJAX
        document.getElementById('favoriteForm').addEventListener('submit', function(event) {
            event.preventDefault();  // Mencegah form dari pengiriman standar
            var formData = new FormData(this);
            
            fetch('tambah_favorite.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Menangani respons JSON
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);  // Menampilkan pesan sukses
                } else if (data.status === 'exists') {
                    alert(data.message);  // Menampilkan pesan jika wisata sudah ada di favorit
                } else {
                    alert("Terjadi kesalahan: " + data.message);  // Menampilkan pesan kesalahan
                }
            })
            .catch(error => console.error('Error:', error));
        }); 

        // cek ketika menambahkan wisata ke favorite apakah sudah login atau belom
        function cekLogin() {
            <?php if (!isset($_SESSION['id_user'])) { ?>
                // Jika belum login, arahkan ke halaman login
                alert('Anda harus LOGIN terlebih dahulu untuk menambahkan favorit.');
                window.location.href = 'dashboard_user.php?hal=login'; // Arahkan ke halaman login
                return false; // Hentikan submit form
            <?php } else { ?>
                return true; // Lanjutkan submit jika sudah login
            <?php } ?>
        }


    </script>
      
                
    <!-- Skrip JS untuk Bootstrap Carousel -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
<?php
$conn->close();

    }
?>