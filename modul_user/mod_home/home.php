<?php

if (!isset($conn)) {
    $conn = new mysqli("localhost", "root", "", "dbs_wisata");


    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
}
// untuk pop up
// Ambil preferensi user dari sessionStorage (via JavaScript)
$jarak_max = isset($_GET['jarak']) ? $_GET['jarak'] : 100;
$harga_max = isset($_GET['harga']) ? $_GET['harga'] : 500000;
$rating_min = isset($_GET['rating']) ? $_GET['rating'] : 3;

// Query untuk mendapatkan wisata yang sesuai
$user_lat = 2.790814; // Contoh koordinat
$user_lon = 98.532865;

// Query 1: Wisata berdasarkan harga dan rating saja
$query1 = "SELECT * FROM tbl_wisata 
           WHERE harga_num <= $harga_max 
           AND rating >= $rating_min";

// Query 2: Wisata dengan filter harga, rating, dan jarak pengguna
$query2 = "SELECT *, (6371 * ACOS(COS(RADIANS($user_lat)) * COS(RADIANS(latitude)) 
        * COS(RADIANS(longitude) - RADIANS($user_lon)) + SIN(RADIANS($user_lat)) 
        * SIN(RADIANS(latitude)))) AS jarak 
        FROM tbl_wisata 
        WHERE harga_num <= $harga_max
        AND rating >= $rating_min
        HAVING jarak <= $jarak_max
        ORDER BY jarak ASC";



// untuk pop up

// Fungsi untuk memformat angka atau rentang harga ke format Rupiah
function formatRupiah($angka)
{
    if (empty($angka)) {
        return "Tidak tersedia";
    }
    // Cek apakah input berupa rentang harga (mengandung "-")
    if (strpos($angka, '-') !== false) {
        $range = explode('-', $angka); // Pisahkan rentang menjadi array
        $min = trim(str_replace('.', '', $range[0])); // Hilangkan titik dari angka pertama
        $max = trim(str_replace('.', '', $range[1])); // Hilangkan titik dari angka kedua
        // Format kedua angka ke format Rupiah
        return "Rp. " . number_format((float) $min, 0, ',', '.') . " - Rp. " . number_format((float) $max, 0, ',', '.');
    }
    // Jika input bukan rentang, format langsung
    return "Rp. " . number_format((float) str_replace('.', '', $angka), 0, ',', '.');
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

$queryProfil = "SELECT konten_profil FROM tbl_profil LIMIT 1";
$resultProfil = $conn->query($queryProfil);
$profil = $resultProfil->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Danau Toba - Wisata Terbaik Indonesia</title>
    
    <!-- Inline CSS -->
    <style>
        /* Hero Section Style */
        .hero-section {
            position: relative;
            height: 95vh;
            background-image: url('../modul_user/image/wisata3.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            text-align: center;
            width: 100%;
        }

        .hero-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            width: 100%;
            padding: 0 10px;
            /* Padding for mobile */
        }

        .hero-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            width: 90%;
            /* Membatasi lebar agar teks bisa membungkus pada layar kecil */
            max-width: 600px;
            /* Atur agar tidak terlalu lebar di layar besar */
            padding: 0 10px;
            /* Menambah padding di kiri dan kanan */
        }

        .hero-overlay h1 {
            font-size: 3rem;
            font-weight: bold;
            white-space: normal;
            /* Membiarkan teks membungkus ke baris berikutnya */
            overflow: visible;
            text-overflow: unset;
            word-wrap: break-word;
            /* Memastikan teks bisa membungkus dengan baik */
        }

        .hero-overlay p {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            font-weight: 900;
            color: rgb(243, 240, 240);
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.6);
            word-wrap: break-word;
            /* Memastikan teks dapat membungkus */
        }

        /* Card Image Adjustment */
        .card-img-top {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        /* Keistimewaan Daya Tarik Section */
        .container-fluid.mt-5.text-center {
            background-color: #e2f7f1;
            padding: 40px 0;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 2.5rem;
            color: #00796b;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            color: #333;
            line-height: 1.8;
            text-align: justify;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .container.py-5 {
            padding-left: 15px;
            padding-right: 15px;
        }

        .card {
            margin: 10px;
        }

        .profil,
        .daya-tarik {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: auto;
            padding: 20px;
        }

        .icon {
            margin-right: 10px;
            font-size: 1.5em;
        }

        .profil h2,
        .daya-tarik h2 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        .profil p,
        .daya-tarik p {
            font-size: 1.1em;
            line-height: 1.6;
            max-width: 800px;
            margin-bottom: 20px;
        }

        .destinasi-populer h5 {
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 20px;
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 767px) {
            .hero-overlay h1 {
                font-size: 2.5rem;
                /* Smaller text for mobile */
            }

            .hero-overlay p {
                font-size: 1rem;
                margin-bottom: 1rem;
            }

            .container-fluid.mt-5.text-center {
                padding: 20px 0;
            }

            .card-body {
                padding: 15px;
            }

            .col-12 {
                margin-bottom: 20px;
            }

            .card {
                margin-bottom: 100px;

            }

            .card-img-top {
                height: 200px;
            }

            h5 {
                font-size: 1.1rem;
            }

            .profil p,
            .daya-tarik p {
                font-size: 1em;
                line-height: 1.5;
                padding: 0 10px;
            }

            .hero-overlay {
                padding: 10px;
            }
        }

        /* Responsive adjustments for larger mobile/tablets */
        @media (min-width: 768px) and (max-width: 1024px) {
            .hero-overlay h1 {
                font-size: 2.8rem;
            }

            .card-img-top {
                height: 220px;
            }

            .container-fluid.mt-5.text-center {
                padding: 30px 0;
            }

            .profil,
            .daya-tarik {
                padding: 30px;
            }
        }

        /* Adjustments for desktop */
        @media (min-width: 1025px) {
            .hero-overlay h1 {
                font-size: 4rem;
                /* Larger title for desktop */
            }

            .card-img-top {
                height: 250px;
                /* Adjust image size for large screens */
            }

            .container-fluid.mt-5.text-center {
                padding: 50px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center justify-content-center text-center">
        <div class="hero-overlay">
            <h1>Selamat Datang di Danau Toba</h1>
            <p class="lead text-center">Temukan Keindahan di Danau Toba, Indonesia</p>
            <a href="dashboard_user.php?hal=wisata" class="btn btn-secondary btn-lg lead text-center">Jelajahi
                Destinasi</a>
        </div>
    </section>

    <!-- SPK Rekomendasi Wisata Danau Toba -->
    <section class="container-fluid mt-5 text-center daya-tarik">
        <h2>SISTEM PENDUKUNG KEPUTUSAN</h2>
        <p style="text-align: justify;">
            Sistem ini dirancang untuk membantu pengguna dalam menentukan destinasi wisata terbaik di kawasan Danau Toba
            berdasarkan preferensi yang diinginkan. Sistem ini menggunakan kombinasi metode dan algoritma untuk
            menghasilkan rekomendasi yang lebih akurat dan personal.
        </p>
    </section>

    <!-- Profil -->
    <section class="container-fluid mt-5 text-center profil">
        <h2><i class="fas fa-user-circle icon"></i> Profil</h2>
        <p style="text-align: justify;">
            <?php echo isset($profil['konten_profil']) ? $profil['konten_profil'] : 'Konten profil tidak tersedia.'; ?>
        </p>
    </section>

    <!-- Keistimewaan Daya Tarik -->
    <section class="container-fluid mt-5 text-center daya-tarik">
        <h2><i class="icon fas fa-mountain"></i> Keistimewaan Daya Tarik</h2>
        <p style="text-align: justify;">
            Pemandangan Alam yang Memukau, Keindahan Danau Toba yang dikelilingi oleh perbukitan hijau, udara sejuk,
            serta air danau yang jernih membuatnya menjadi tempat yang ideal untuk berlibur. Nikmati keindahan alam yang
            luar biasa baik di atas kapal ataupun dari tepi danau.
        </p>
        <p style="text-align: justify;">
            Pulau Samosir, Pulau yang terletak di tengah danau ini menjadi daya tarik utama dengan budaya Batak yang
            kental. Anda bisa mengunjungi situs bersejarah seperti Tao Simalungun dan Bukit Holbung untuk melihat
            panorama menakjubkan.
        </p>
        <p style="text-align: justify;">
            Wisata Budaya Batak, Pelajari sejarah dan tradisi suku Batak, termasuk tarian tradisional, musik, dan ritual
            adat yang berlangsung di beberapa tempat wisata.
        </p>
    </section>

    <!-- Destinasi Populer -->
    <section class="container py-5">
        <h2 class="text-center">Destinasi Populer</h2>
        <div class="row mt-4">
            <!-- Wisata Alam Populer -->
            <div class="col-md-3 col-12 mb-4">
                <h5 class="text-center"><strong>Wisata Alam Populer</strong></h5>
                <div class="card h-100 d-flex flex-column">
                    <img src="./image/<?php echo $topWisataAlam['gambar'] ?? 'default.jpg'; ?>"
                        alt="<?php echo htmlspecialchars($topWisataAlam['nama_wisata']); ?>" class="card-img-top">
                    <div class="card-body flex-grow-1 d-flex flex-column">
                        <h5 class="card-title text-center">
                            <small><?php echo htmlspecialchars($topWisataAlam['nama_wisata']); ?></small>
                        </h5>
                        <p class="text-center">Alamat:
                            <?php echo htmlspecialchars(strlen($topWisataAlam['lokasi_wisata']) > 30 ? substr($topWisataAlam['lokasi_wisata'], 0, 30) . '...' : $topWisataAlam['lokasi_wisata']); ?>
                        </p>
                        <p class="text-center">Rating: ⭐<?php echo number_format($topWisataAlam['avg_rating'], 1); ?>
                        </p>
                        <div class="mt-auto text-center">
                            <a href="index_user.php?hal=detail_wisata_alam&id=<?php echo $topWisataAlam['id_wisata']; ?>"
                                class="btn btn-link">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wisata Budaya Populer -->
            <div class="col-md-3 col-12 mb-4">
                <h5 class="text-center"><strong>Wisata Budaya Populer</strong></h5>
                <div class="card h-100 d-flex flex-column">
                    <img src="./image/<?php echo $topWisataBudaya['gambar'] ?? 'default.jpg'; ?>"
                        alt="<?php echo htmlspecialchars($topWisataBudaya['nama_wisata']); ?>" class="card-img-top">
                    <div class="card-body flex-grow-1 d-flex flex-column">
                        <h5 class="card-title text-center">
                            <small><?php echo htmlspecialchars($topWisataBudaya['nama_wisata']); ?></small>
                        </h5>
                        <p class="text-center">Alamat:
                            <?php echo htmlspecialchars(strlen($topWisataBudaya['lokasi_wisata']) > 30 ? substr($topWisataBudaya['lokasi_wisata'], 0, 30) . '...' : $topWisataBudaya['lokasi_wisata']); ?>
                        </p>
                        <p class="text-center">Rating: ⭐<?php echo number_format($topWisataBudaya['avg_rating'], 1); ?>
                        </p>
                        <div class="mt-auto text-center">
                            <a href="index_user.php?hal=detail_wisata_budaya&id=<?php echo $topWisataBudaya['id_wisata']; ?>"
                                class="btn btn-link">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wisata Kuliner Populer -->
            <div class="col-md-3 col-12 mb-4">
                <h5 class="text-center"><strong>Wisata Kuliner Populer</strong></h5>
                <div class="card h-100 d-flex flex-column">
                    <img src="./image/<?php echo $topWisataKuliner['gambar'] ?? 'default.jpg'; ?>"
                        alt="<?php echo htmlspecialchars($topWisataKuliner['nama_wisata']); ?>" class="card-img-top">
                    <div class="card-body flex-grow-1 d-flex flex-column">
                        <h5 class="card-title text-center">
                            <small><?php echo htmlspecialchars($topWisataKuliner['nama_wisata']); ?></small>
                        </h5>
                        <p class="text-center">Alamat:
                            <?php echo htmlspecialchars(strlen($topWisataKuliner['lokasi_wisata']) > 30 ? substr($topWisataKuliner['lokasi_wisata'], 0, 30) . '...' : $topWisataKuliner['lokasi_wisata']); ?>
                        </p>
                        <p class="text-center">Rating: ⭐<?php echo number_format($topWisataKuliner['avg_rating'], 1); ?>
                        </p>
                        <div class="mt-auto text-center">
                            <a href="index_user.php?hal=detail_wisata_kuliner&id=<?php echo $topWisataKuliner['id_wisata']; ?>"
                                class="btn btn-link">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wisata Akomodasi Populer -->
            <div class="col-md-3 col-12 mb-4">
                <h5 class="text-center"><strong>Akomodasi Populer</strong></h5>
                <div class="card h-100 d-flex flex-column">
                    <img src="./image/<?php echo $topAkomodasi['gambar'] ?? 'default.jpg'; ?>"
                        alt="<?php echo htmlspecialchars($topAkomodasi['nama_wisata']); ?>" class="card-img-top">
                    <div class="card-body flex-grow-1 d-flex flex-column">
                        <h5 class="card-title text-center">
                            <small><?php echo htmlspecialchars($topAkomodasi['nama_wisata']); ?></small>
                        </h5>
                        <p class="text-center">Alamat:
                            <?php echo htmlspecialchars(strlen($topAkomodasi['lokasi_wisata']) > 30 ? substr($topAkomodasi['lokasi_wisata'], 0, 30) . '...' : $topAkomodasi['lokasi_wisata']); ?>
                        </p>
                        <p class="text-center">Rating: ⭐<?php echo number_format($topAkomodasi['avg_rating'], 1); ?></p>
                        <div class="mt-auto text-center">
                            <a href="index_user.php?hal=detail_akomodasi&id=<?php echo $topAkomodasi['id_wisata']; ?>"
                                class="btn btn-link">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal Popup -->
<style>
    .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 9999;
    overflow-y: auto;
    padding: 2rem 1rem;
    display: flex;
    justify-content: center;
}

.modal-content {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    position: relative;
    margin: auto;
}


    .modal-title {
        text-align: center;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        font-weight: bold;
        color: #333;
    }

    .modal-description {
        text-align: center;
        font-size: 1rem;
        color: #555;
        margin-bottom: 1.5rem;
    }

    .form-group,
    .checkbox-group,
    select,
    input[type="text"] {
        margin-bottom: 1rem;
        width: 100%;
    }

    select,
    input[type="text"] {
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .checkbox-group label {
        display: block;
        margin-bottom: 0.5rem;
    }

    .btn-cari {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 0.7rem 1.2rem;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        font-size: 1rem;
        margin-top: 1rem;
    }

    .btn-cari:hover {
        background-color: #218838;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
    }

    @media screen and (max-width: 480px) {
        .modal-content {
            padding: 1.5rem;
        }

        .modal-title {
            font-size: 1.3rem;
        }

        .modal-description {
            font-size: 0.95rem;
        }

        .btn-cari {
            font-size: 0.95rem;
        }
    }
</style>

<div class="modal-overlay" id="popupForm">
    <div class="modal-content">
        <form action="dashboard_user.php" method="GET">
            <input type="hidden" name="hal" value="hasil">

            <button type="button" class="close" onclick="closeModal()">&times;</button>

            <h3 class="modal-title">Hai, Welcome!!! <br>
            Yuk, cari tahu wisata yang paling pas buat kamu!</h3>
            <p class="modal-description">
                Jawab form di bawah, dan kami tunjukkan rekomendasi wisata terbaik buat kamu.
            </p>

            <!-- Kategori Wisata -->
            <label for="kategori"><strong>Kategori Wisata:</strong></label>
            <select name="kategori" id="kategori">
                <option value="">--pilih kategori wisata--</option>
                <option value="1">Wisata Alam</option>
                <option value="2">Wisata Budaya</option>
                <option value="3">Wisata Kuliner</option>
                <option value="4">Akomodasi</option>
            </select>

            <!-- Lokasi -->
            <div class="form-group mb-3">
                <label for="lokasi"><strong>Lokasi</strong> (masukkan Lokasi Anda):</label>
                <input type="text" class="form-control" name="lokasi" id="lokasi"
                    placeholder="masukkan Kota atau Provinsi anda!" required>
            </div>

            <!-- Harga -->
            <label for="harga"><strong>Harga</strong> (pilih salah satu):</label>
            <div class="checkbox-group">
                <label><input type="radio" name="harga" value="0-10000"> Rp 0 - 10.000</label>
                <label><input type="radio" name="harga" value="10000-30000"> Rp 10.000 - 30.000</label>
                <label><input type="radio" name="harga" value="30000-50000"> Rp 30.000 - 50.000</label>
                <label><input type="radio" name="harga" value="50000+"> Rp 50.000 +</label>
            </div>

            <!-- Rating -->
            <label for="rating"><strong>Rating</strong> (pilih rating):</label>
            <div class="checkbox-group">
                <label><input type="radio" name="rating" value="5"> ⭐⭐⭐⭐⭐</label>
                <label><input type="radio" name="rating" value="4"> ⭐⭐⭐⭐</label>
                <label><input type="radio" name="rating" value="3"> ⭐⭐⭐</label>
                <label><input type="radio" name="rating" value="2"> ⭐⭐</label>
                <label><input type="radio" name="rating" value="1"> ⭐</label>
            </div>
                
           <!-- Fasilitas -->
                <label for="fasilitas"><strong>Fasilitas</strong> (opsional, pilih tingkat kelengkapan):</label><br>
                <label><input type="radio" name="fasilitas" value="kurang"> Kurang Lengkap</label><br>
                <label><input type="radio" name="fasilitas" value="cukup"> Cukup Lengkap</label><br>
                <label><input type="radio" name="fasilitas" value="lengkap"> Lengkap</label>

            <!-- Tombol Cari Wisata -->
            <button type="submit" class="btn-cari">Cari Wisata</button>
        </form>
    </div>
</div>

    <!-- JavaScript -->
    <script>
        function closeModal() {
            document.getElementById('popupForm').style.display = 'none';
        }

        window.onload = function () {
            document.getElementById('popupForm').style.display = 'block';
        };
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const popup = document.getElementById("popupForm");
            const form = document.getElementById("filterForm");
            const skipButton = document.getElementById("skipButton");

            // Tampilkan pop-up hanya jika belum ditutup sebelumnya
            if (!sessionStorage.getItem("popupClosed")) {
                popup.classList.remove("hidden");
            }

            // Simpan pilihan pengguna ke sessionStorage dan tutup pop-up
            form.addEventListener("submit", function (event) {
                event.preventDefault();
                const jarak = document.getElementById("jarak").value;
                const harga = document.getElementById("harga").value;
                const rating = document.getElementById("rating").value;

                sessionStorage.setItem("jarak", jarak);
                sessionStorage.setItem("harga", harga);
                sessionStorage.setItem("rating", rating);
                sessionStorage.setItem("popupClosed", "true");

                popup.classList.add("hidden");
                window.location.reload(); // Refresh halaman untuk menerapkan filter
            });

            // Tombol untuk melewati pop-up
            skipButton.addEventListener("click", function () {
                sessionStorage.setItem("popupClosed", "true");
                popup.classList.add("hidden");
            });
        });

        //    lokasi jauh agar Menggunakan Lokasi User
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    document.getElementById("latitude").value = position.coords.latitude;
                    document.getElementById("longitude").value = position.coords.longitude;
                });
            } else {
                alert("Geolocation tidak didukung di browser ini.");
            }
        }

        function closePopup(event) {
            if (!event || event.target.classList.contains('popup-container')) {
                document.getElementById('popupForm').style.display = 'none';
            }
        }


        // Mendapatkan elemen modal dan tombol close
        var modal = document.getElementById("preferenceModal");
        var closeModalBtn = document.getElementById("closeModalBtn");

        // Modal muncul otomatis saat halaman di-load
        window.onload = function () {
            modal.style.display = "flex";
        }

        // Ketika tombol close diklik, sembunyikan modal
        closeModalBtn.onclick = function () {
            modal.style.display = "none";
        }

        // Menyembunyikan modal jika klik di luar modal
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<!-- finish -->