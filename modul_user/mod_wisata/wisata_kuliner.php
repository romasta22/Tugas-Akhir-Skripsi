<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "dbs_wisata");

if ($conn->connect_error) {
    die("<p class='text-danger text-center'>Koneksi gagal: " . $conn->connect_error . "</p>");
}

// Pagination
$entriesPerPage = isset($_GET['entriesPerPage']) ? (int)$_GET['entriesPerPage'] : 12; // Default 5 entries per page
$entriesPerPage = $entriesPerPage > 0 ? $entriesPerPage : 12; // Jika invalid, kembali ke 5
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $entriesPerPage;

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

// id_kategori untuk wisata Kuliner
$id_kategori = 3;

// Query pencarian dengan rata-rata rating
$search = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : '%';
$query = "SELECT w.*, COALESCE(AVG(u.rating), 0) AS avg_rating 
          FROM tbl_wisata w 
          LEFT JOIN tbl_ulasan u ON w.id_wisata = u.id_wisata 
          WHERE w.nama_wisata LIKE ? AND w.id_kategori = ? 
          GROUP BY w.id_wisata 
          LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("siii", $search, $id_kategori, $offset, $entriesPerPage);
$stmt->execute();
$result = $stmt->get_result();

// Total data
$countQuery = "SELECT COUNT(*) as total FROM tbl_wisata WHERE nama_wisata LIKE ? AND id_kategori = ?";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("si", $search, $id_kategori);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalEntries = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalEntries / $entriesPerPage);
$countStmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wisata Kuliner</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .card {
            height: 350px; /* Sesuaikan dengan kebutuhan */
            display: flex;
            flex-direction: column;
        }
        .card-body {
            flex-grow: 1; /* Memastikan bagian ini mengisi ruang kosong */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-img-top {
            height: 200px; /* Sesuaikan tinggi gambar */
            object-fit: cover; /* Memastikan gambar tidak terdistorsi */
            width: 100%; /* Agar gambar memenuhi lebar card */
        }
        .card-title {
            font-size: 1rem;
            font-weight: bold;
            white-space: nowrap;         /* Menghindari teks terpecah dalam beberapa baris */
            overflow: hidden;            /* Menyembunyikan teks yang melebihi batas */
            text-overflow: ellipsis;     /* Menambahkan '...' jika teks terlalu panjang */
            max-width: 100%;             /* Membatasi lebar card */
            
        }
        .card-rating {
            color: #f39c12;
        }
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .card-body p {
            white-space: nowrap;         /* Menghindari teks terpecah dalam beberapa baris */
            overflow: hidden;            /* Menyembunyikan teks yang melebihi batas */
            text-overflow: ellipsis;     /* Menambahkan '...' jika teks terlalu panjang */
        }
        h1.text-center {
            margin-top: -40px; /* Negatif margin untuk menarik elemen lebih ke atas */
        }

    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-3">Daftar Wisata Kuliner</h1>
        <p class="text-center">"Temukan Keindahan Rasa yang Tersembunyi!"</p>
        <!-- Form pencarian -->
        <form method="GET" action="" class="mb-4">
            <input type="hidden" name="hal" value="wisata_kuliner">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari wisata..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>

        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($wisata = $result->fetch_assoc()) {
                    $gambarPath = '././image/' . $wisata['gambar'];
                    $gambar = (file_exists($gambarPath) && !empty($wisata['gambar'])) 
                              ? $gambarPath 
                              : '././image/default.jpg';
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card">
                            <a href="index_user.php?hal=detail_wisata_kuliner&id=<?php echo $wisata['id_wisata']; ?>">
                                <img src="<?php echo $gambar; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($wisata['nama_wisata']); ?>">
                            </a>
                            <div class="card-body">
                                <a href="index_user.php?hal=detail_wisata_kuliner&id=<?php echo $wisata['id_wisata']; ?>" class="text-decoration-none">
                                    <h5 class="card-title text-dark"><?php echo htmlspecialchars($wisata['nama_wisata']); ?></h5>
                                </a>
                                 <p><small>Perkiraan HTM: <?php echo !empty($wisata['harga']) ? htmlspecialchars(formatRupiah($wisata['harga'])) : 'Tidak tersedia';?></small></p>


                                <p class="card-rating">
                                    <?php echo $wisata['avg_rating'] > 0 ? number_format($wisata['avg_rating'], 1) . ' ⭐' : 'Belum ada rating'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center w-100'>Wisata yang dicari tidak ditemukan.</p>";
            }

            $stmt->close();
            ?>
        </div>

        <!-- Pagination Links and Info -->
        <div class="pagination-container mt-4">
            <div>
                <form method="GET" action="" class="form-inline">
                    <label for="entriesPerPage" class="mr-2">Entries per page:</label>
                    <select name="entriesPerPage" id="entriesPerPage" class="form-control mr-2">
                        <option value="10" <?php echo isset($_GET['entriesPerPage']) && $_GET['entriesPerPage'] == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="25" <?php echo isset($_GET['entriesPerPage']) && $_GET['entriesPerPage'] == 25 ? 'selected' : ''; ?>>25</option>
                        <option value="50" <?php echo isset($_GET['entriesPerPage']) && $_GET['entriesPerPage'] == 50 ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo isset($_GET['entriesPerPage']) && $_GET['entriesPerPage'] == 100 ? 'selected' : ''; ?>>100</option>
                    </select>
                    <input type="hidden" name="hal" value="wisata_kuliner">
                    <input type="hidden" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn btn-primary" type="submit">Apply</button>
                </form>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    <!-- Tombol ke halaman pertama -->
                    <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?hal=wisata_kulinersearch=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>&entriesPerPage=<?php echo $entriesPerPage; ?>&page=1" aria-label="First">
                            <<
                        </a>
                    </li>
                    <!-- Tombol ke halaman sebelumnya -->
                    <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?hal=wisata_kuliner&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>&entriesPerPage=<?php echo $entriesPerPage; ?>&page=<?php echo max(1, $currentPage - 1); ?>" aria-label="Previous">
                            <
                        </a>
                    </li>
                    <!-- Nomor Halaman Saat Ini -->
                    <li class="page-item active">
                        <span class="page-link">
                            <?php echo $currentPage; ?>
                        </span>
                    </li>
                    <!-- Tombol ke halaman berikutnya -->
                    <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?hal=wisata_kuliner&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>&entriesPerPage=<?php echo $entriesPerPage; ?>&page=<?php echo min($totalPages, $currentPage + 1); ?>" aria-label="Next">
                            >
                        </a>
                    </li>
                    <!-- Tombol ke halaman terakhir -->
                    <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?hal=wisata_kuliner&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>&entriesPerPage=<?php echo $entriesPerPage; ?>&page=<?php echo $totalPages; ?>" aria-label="Last">
                            >>
                        </a>
                    </li>
                </ul>
            </nav>

            <div>
                Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $entriesPerPage, $totalEntries); ?> of <?php echo $totalEntries; ?> entries
            </div>
        </div>
    </div>
</body>
</html>
