<?php
        if (!isset($conn)) {
            $conn = new mysqli("localhost", "root", "", "dbs_wisata");

            // Cek koneksi
            if ($conn->connect_error) {
                die("Koneksi gagal: " . $conn->connect_error);
            }
        }

       // Fungsi untuk memformat angka atau rentang harga ke format Rupiah
        function formatRupiah($angka) {
            if (empty($angka)) {
                return "Tidak tersedia";
            }
            // Cek apakah input berupa rentang harga (mengandung "-")
            if (strpos($angka, '-') !== false) {
                $range = explode('-', $angka); // Pisahkan rentang menjadi array
                $min = trim(str_replace('.', '', $range[0])); // Hilangkan titik dari angka pertama
                $max = trim(str_replace('.', '', $range[1])); // Hilangkan titik dari angka kedua
                // Format kedua angka ke format Rupiah
                return "Rp. " . number_format((float)$min, 0, ',', '.') . " - Rp. " . number_format((float)$max, 0, ',', '.');
            }
            // Jika input bukan rentang, format langsung
            return "Rp. " . number_format((float)str_replace('.', '', $angka), 0, ',', '.');
        }

    // Query untuk mengambil wisata alam dengan rating tertinggi
    $queryTopWisataAlam = "SELECT w.*, COALESCE(AVG(u.rating), 0) AS avg_rating
                        FROM tbl_wisata w
                        LEFT JOIN tbl_ulasan u ON w.id_wisata = u.id_wisata
                        WHERE w.id_kategori = 1
                        GROUP BY w.id_wisata
                        ORDER BY avg_rating DESC
                        LIMIT 1";
    $resultTopWisataAlam = $conn->query($queryTopWisataAlam);
    $topWisataAlam = $resultTopWisataAlam->fetch_assoc();

    // Query untuk mengambil wisata budaya dengan rating tertinggi
    $queryTopWisataBudaya = "SELECT w.*, COALESCE(AVG(u.rating), 0) AS avg_rating
                            FROM tbl_wisata w
                            LEFT JOIN tbl_ulasan u ON w.id_wisata = u.id_wisata
                            WHERE w.id_kategori = 2
                            GROUP BY w.id_wisata
                            ORDER BY avg_rating DESC
                            LIMIT 1";
    $resultTopWisataBudaya = $conn->query($queryTopWisataBudaya);
    $topWisataBudaya = $resultTopWisataBudaya->fetch_assoc();

    // Query untuk wisata kuliner dengan rating tertinggi
    $queryTopWisataKuliner = "SELECT w.*, COALESCE(AVG(u.rating), 0) AS avg_rating
                            FROM tbl_wisata w
                            LEFT JOIN tbl_ulasan u ON w.id_wisata = u.id_wisata
                            WHERE w.id_kategori = 3
                            GROUP BY w.id_wisata
                            ORDER BY avg_rating DESC
                            LIMIT 1";
    $resultTopWisataKuliner = $conn->query($queryTopWisataKuliner);
    $topWisataKuliner = $resultTopWisataKuliner->fetch_assoc();

     // Query untuk akomodasi dengan rating tertinggi
     $queryTopAkomodasi = "SELECT w.*, COALESCE(AVG(u.rating), 0) AS avg_rating
                        FROM tbl_wisata w
                        LEFT JOIN tbl_ulasan u ON w.id_wisata = u.id_wisata
                        WHERE w.id_kategori = 4
                        GROUP BY w.id_wisata
                        ORDER BY avg_rating DESC
                        LIMIT 1";
    $resultTopAkomodasi = $conn->query($queryTopAkomodasi);
    $topAkomodasi = $resultTopAkomodasi->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Wisata</title>
    <style>
        .kategori-wisata {
            position: relative;
            background-image: url('../modul_user/image/wisata3.jpg'); /* Pastikan path benar */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 550px;
            width: 100%;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
            color: white; /* Biar teks lebih jelas */
        }

        /* Overlay biar teks terbaca dengan jelas */
        .kategori-wisata::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* Overlay hitam transparan */
            z-index: -1;
        }

        /* Kontainer kartu kategori */
        .kategori-card-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 80%;
        }

        .kategori-card {
            background: black; /* Background card jadi hitam */
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            color: white; /* Warna teks dalam card jadi putih */
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2); /* Shadow lebih tebal */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .kategori-card img {
            width: 120px; /* Ukuran gambar kategori lebih proporsional */
            height: 120px;
            border-radius: 50%;
        }

        .kategori-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(255, 255, 255, 0.3); /* Efek hover lebih menarik */
        }

        /* Responsif untuk mobile */
        @media (max-width: 768px) {
            .kategori-wisata {
                min-height: 400px;
            }

            .kategori-card-container {
                flex-direction: column;
                align-items: center;
            }

            .kategori-card {
                width: 90%; /* Supaya lebih rapih di layar kecil */
            }

            .kategori-card img {
                width: 90px;
                height: 90px;
            }
        }
    </style>
</head>
<body>

    <!-- Kategori Wisata -->
    <section class="kategori-wisata">
        <div class="kategori-wisata-overlay">
            <h1 class="fw-bold">JELAJAHI KATEGORI WISATA</h1>
            <p class="lead">Temukan kategori wisata terbaik di destinasi favorit Anda</p>
        </div>
        <div class="kategori-card-container">
            <div class="kategori-card">
            <a href="index_user.php?hal=wisata_alam" style="text-decoration: none; color: inherit; display: block;">
                <img src="../modul_user/image/wisataalam.jpg" alt="Wisata Alam" class="img-fluid rounded-circle">
                <h5 class="mt-2">Wisata Alam</h5>
            </a>
            </div>
            
            <div class="kategori-card">
            <a href="index_user.php?hal=wisata_budaya" style="text-decoration: none; color: inherit; display: block;">
                <img src="../modul_user/image/wisatabudaya.jpg" alt="Wisata Budaya" class="img-fluid rounded-circle">
                <h5 class="mt-2">Wisata Budaya</h5>
            </a>
            </div>
            
            <div class="kategori-card">
            <a href="index_user.php?hal=wisata_kuliner" style="text-decoration: none; color: inherit; display: block;">
                <img src="../modul_user/image/wisatakuliner.jpg" alt="Wisata Kuliner" class="img-fluid rounded-circle">
                <h5 class="mt-2">Wisata Kuliner</h5>
            </a>
            </div>
            
            <div class="kategori-card">
            <a href="index_user.php?hal=akomodasi" style="text-decoration: none; color: inherit; display: block;">
                <img src="../modul_user/image/akomodasi.jpg" alt="Wisata Kuliner" class="img-fluid rounded-circle">
                <h5 class="mt-2">Akomodasi</h5>
                </a>
            </div>
            
        </div>
    </section>

    <!-- Destinasi Populer -->
<section class="container py-5">
    <h2 class="text-center">Destinasi Populer</h2>
    <div class="row mt-4">
        
        <?php 
        $wisataList = [
            ['title' => 'Wisata Alam Populer', 'data' => $topWisataAlam, 'link' => 'detail_wisata_alam'],
            ['title' => 'Wisata Budaya Populer', 'data' => $topWisataBudaya, 'link' => 'detail_wisata_budaya'],
            ['title' => 'Wisata Kuliner Populer', 'data' => $topWisataKuliner, 'link' => 'detail_wisata_kuliner'],
            ['title' => 'Akomodasi Populer', 'data' => $topAkomodasi, 'link' => 'detail_wisata_akomodasi']
        ];
        
        foreach ($wisataList as $wisata) {
            $data = $wisata['data'];
            ?>
            <div class="col-md-3 d-flex flex-column">
                <!-- Pindahkan Judul ke Luar Card -->
                <h5 class="text-center mb-2"><?php echo htmlspecialchars($wisata['title']); ?></h5>
                <div class="card h-100 d-flex flex-column w-100">
                    <img src="./image/<?php echo $data['gambar'] ?? 'default.jpg'; ?>" 
                        alt="<?php echo htmlspecialchars($data['nama_wisata'] ?? ''); ?>" 
                        class="card-img-top" 
                        style="object-fit: cover; height: 200px;">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($data['nama_wisata'] ?? ''); ?></h5>
                        <p>Alamat: <?php echo htmlspecialchars($data['lokasi_wisata'] ?? 'Tidak tersedia'); ?></p>
                        <p>Rating: ⭐<?php echo isset($data['avg_rating']) ? number_format($data['avg_rating'], 1) : 'Belum ada rating'; ?></p>
                        <p><small>Perkiraan HTM: <?php echo !empty($data['harga']) ? htmlspecialchars(formatRupiah($data['harga'])) : 'Tidak tersedia'; ?></small></p>
                        
                        <a href="index_user.php?hal=<?php echo $wisata['link']; ?>&id=<?php echo $data['id_wisata']; ?>" 
                           class="btn btn-secondary mt-auto">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</section>


   <!-- Manfaat Website -->
<!-- Manfaat Website -->
<section class="container-fluid bg-light py-5">
    <h2 class="text-center mb-4">Manfaat Website Kami</h2>
    <div class="d-flex flex-column flex-md-row text-center justify-content-center gap-4">
        <div class="col-md-4">
            <h5 class="mb-3">Informasi Terlengkap</h5>
            <p>Dapatkan informasi wisata terbaru dan terlengkap.</p>
        </div>
        <div class="col-md-4">
            <h5 class="mb-3">Kemudahan Akses</h5>
            <p>Akses website kami dari mana saja, kapan saja.</p>
        </div>
        <div class="col-md-4">
            <h5 class="mb-3">Panduan Perjalanan</h5>
            <p>Temukan panduan perjalanan untuk destinasi favorit Anda.</p>
        </div>
    </div>
</section>




</body>
</html>