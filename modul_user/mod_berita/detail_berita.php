<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Berita</title>
    <!-- Tambahkan link ke Bootstrap Icons jika belum ada -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .content img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .content p {
            text-align: justify;
            line-height: 1.6;
        }
        .back-link {
            text-decoration: none;
            color: #007bff;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .back-share-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .view-share-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .view-count {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #555;
        }
        .view-count i {
            margin-right: 5px;
        }
        .share-icon {
            font-size: 18px;
            color: #007bff;
            cursor: pointer;
        }
        .share-icon:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Koneksi ke database
        $conn = new mysqli("localhost", "root", "", "dbs_wisata");

        // Periksa koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Ambil ID berita dari parameter URL
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        // Query untuk mendapatkan data berita berdasarkan ID
        $query = "SELECT judul_berita, konten_berita, foto_berita, sumber_berita, tanggal_berita, jumlah_pembaca 
                  FROM tbl_berita 
                  WHERE id_berita = $id";
        $result = $conn->query($query);

        if ($result->num_rows > 0):
            $berita = $result->fetch_assoc();

            // Update jumlah pembaca
            $updateQuery = "UPDATE tbl_berita SET jumlah_pembaca = jumlah_pembaca + 1 WHERE id_berita = $id";
            $conn->query($updateQuery);
        ?>
            <!-- Tampilkan Berita -->
            <h1 class="text-center"><?php echo $berita['judul_berita']; ?></h1>
            <p class="text-center text-muted">
                <i><?php echo $berita['sumber_berita']; ?></i> - <?php echo date('l, d F Y', strtotime($berita['tanggal_berita'])); ?>
            </p>
            <div class="content">
                <img src="../img_berita/<?php echo $berita['foto_berita']; ?>" alt="<?php echo $berita['judul_berita']; ?>">
                <p><?php echo nl2br($berita['konten_berita']); ?></p>
            </div>

            <!-- Bagian Back, Jumlah Pembaca, dan Share -->
            <div class="back-share-container">
                <!-- Link Kembali -->
                <a href="index_user.php?hal=berita" class="back-link">← Kembali ke Daftar Berita</a>

                <!-- Jumlah Pembaca dan Ikon Share -->
                <div class="view-share-container">
                    <!-- Jumlah Pembaca -->
                    <div class="view-count">
                        <i class="bi bi-eye"></i>
                        <span><?php echo $berita['jumlah_pembaca']; ?></span>
                    </div>
                    <!-- Ikon Share -->
                    <i class="bi bi-share share-icon" id="shareButton" title="Bagikan Berita"></i>
                </div>
            </div>

            <!-- JavaScript untuk Fitur Share -->
            <script>
                const shareButton = document.getElementById('shareButton');
                shareButton.addEventListener('click', async () => {
                    if (navigator.share) {
                        try {
                            await navigator.share({
                                title: '<?php echo addslashes($berita['judul_berita']); ?>',
                                text: 'Baca berita menarik ini!',
                                url: '<?php echo "http://yourwebsite.com/detail_berita.php?id=" . $id; ?>'
                            });
                            // alert('Berita berhasil dibagikan!');
                        } catch (error) {
                            alert('Gagal membagikan berita.');
                            console.error('Share failed:', error);
                        }
                    } else {
                        alert('Fitur berbagi tidak didukung di perangkat Anda.');
                    }
                });
            </script>
        <?php
        else:
            echo "<p>Berita tidak ditemukan.</p>";
        endif;

        $conn->close();
        ?>
    </div>
</body>
</html>
