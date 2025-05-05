<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita</title>
    <style>
        h1.text-center {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 10px;
        }
        p.text-center {
            font-size: 1.2rem;
            color: #555;
            margin-top: 0;
        }
        .card {
            border-radius: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            color: inherit;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .card-title {
            font-size: 1.25rem;
            color: #333;
        }
        .card-footer small {
            color: #999;
        }
        .pagination .page-link {
            color: #007bff;
            border: 1px solid #ddd;
        }
        .pagination .page-link:hover {
            background-color: #007bff;
            color: #fff;
        }
        .input-group input {
            border-radius: 20px 0 0 20px;
        }
        .input-group .btn {
            border-radius: 0 20px 20px 0;
            background-color: #007bff;
            color: #fff;
        }
        .input-group .btn:hover {
            background-color: #0056b3;
        }
        .container {
            padding: 0 40px; /* Jarak ke kiri dan kanan */
        }
        .row {
            margin-top: 20px; /* Jarak antar berita */
        }

    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center">BERITA</h1>
        <p class="text-center">Seputar Informasi Berita Danau Toba</p>

        <!-- Kolom Pencarian -->
        <form method="GET" class="my-4">
            <input type="hidden" name="hal" value="berita">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berita..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <div class="row">
            <?php
            $conn = new mysqli("localhost", "root", "", "dbs_wisata");

            if ($conn->connect_error) {
                die("Koneksi gagal: " . $conn->connect_error);
            }

            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $limit = isset($_GET['entries']) ? (int)$_GET['entries'] : 3;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            $query = "SELECT id_berita, judul_berita, konten_berita, foto_berita, sumber_berita, tanggal_berita, jumlah_pembaca 
                      FROM tbl_berita 
                      WHERE judul_berita LIKE '%$search%' 
                      LIMIT $limit OFFSET $offset";
            $result = $conn->query($query);

            $countQuery = "SELECT COUNT(*) AS total FROM tbl_berita WHERE judul_berita LIKE '%$search%'";
            $countResult = $conn->query($countQuery);
            $totalEntries = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalEntries / $limit);

            if ($result->num_rows > 0):
                while ($berita = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <!-- Seluruh Kartu Dijadikan Klik-able -->
                        <a href="index_user.php?hal=detail_berita&id=<?php echo $berita['id_berita']; ?>" class="card h-100 shadow-sm text-decoration-none">
                            <!-- Path Gambar -->
                            <img src="../img_berita/<?php echo $berita['foto_berita']; ?>" class="card-img-top" alt="<?php echo $berita['judul_berita']; ?>" style="height: 200px; object-fit: cover;">
                            
                            <div class="card-body">
                                <h5 class="card-title text-truncate"><?php echo $berita['judul_berita']; ?></h5>
                                <p class="card-text text-muted text-truncate"><?php echo substr(strip_tags($berita['konten_berita']), 0, 100); ?>...</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">
                                    <i><?php echo $berita['sumber_berita']; ?></i> - <?php echo date('l, d F Y', strtotime($berita['tanggal_berita'])); ?><br>
                                    <span><i class="bi bi-eye"></i> <?php echo $berita['jumlah_pembaca']; ?> Pembaca</span>
                                </small>
                            </div>
                        </a>
                    </div>
                <?php endwhile;
            else: ?>
                <p class="text-center">Tidak ada berita ditemukan.</p>
            <?php endif;

            $conn->close();
            ?>
        </div>

        <!-- Footer Entries dan Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <!-- Dropdown Entries Per Page -->
            <form method="GET" class="d-inline-block">
                <input type="hidden" name="hal" value="berita">
                <label for="entries">Entries per page:</label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="25" <?php echo $limit == 25 ? 'selected' : ''; ?>>25</option>
                    <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?php echo $limit == 100 ? 'selected' : ''; ?>>100</option>
                </select>
                <input type="hidden" name="search" value="<?php echo $search; ?>">
                <input type="hidden" name="page" value="<?php echo $page; ?>">
            </form>
            <!-- Informasi Entries -->
            <p>Showing <?php echo min($offset + 1, $totalEntries); ?> to <?php echo min($offset + $limit, $totalEntries); ?> of <?php echo $totalEntries; ?> entries</p>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?hal=berita&entries=<?php echo $limit; ?>&page=1&search=<?php echo $search; ?>">«</a>
                </li>
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?hal=berita&entries=<?php echo $limit; ?>&page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>">‹</a>
                </li>
                <li class="page-item active">
                    <span class="page-link"><?php echo $page; ?></span>
                </li>
                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?hal=berita&entries=<?php echo $limit; ?>&page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>">›</a>
                </li>
                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?hal=berita&entries=<?php echo $limit; ?>&page=<?php echo $totalPages; ?>&search=<?php echo $search; ?>">»</a>
                </li>
            </ul>
        </nav>
    </div>
</body>
</html>
