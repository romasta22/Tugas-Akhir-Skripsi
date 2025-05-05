<?php
include '../koneksi.php'; // Pastikan koneksi database sudah ada

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: dashboard_user.php?hal=login");
    exit();
}

$id_user = $_SESSION['id_user']; // Ambil ID pengguna dari session

// Ambil data wisata favorit
$query = "SELECT * FROM tbl_favorite f 
          JOIN tbl_wisata w ON f.id_wisata = w.id_wisata
          WHERE f.id_user = '$id_user'";
$result = mysqli_query($koneksi, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wisata Favorit</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ6bK6pW8oA6gkmlq9jF2AWlY2a52ErPbA4Itxh7g3Qkzv27P5X2e9tF8h0v" crossorigin="anonymous">
    <style>
        /* Custom CSS for styling */
        .wisata-item {
            background-color: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease-in-out;
        }

        .wisata-item:hover {
            transform: translateY(-5px);
        }

        .wisata-item img {
            width: 100%; /* Membuat gambar agar sesuai dengan lebar card */
            height: 120px; /* Mengatur tinggi gambar agar kecil */
            object-fit: cover;
            border-radius: 5px;
        }

        .wisata-item h5 {
            font-size: 1.2rem;
            color:rgb(89, 164, 235);
            margin-top: 10px;
        }

        .wisata-item p {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 10px;
        }

        .card-body {
            padding: 10px;
        }

        .btn-custom {
            font-size: 0.8rem;
            padding: 6px 12px;
        }

        /* Responsive layout */
        @media (max-width: 576px) {
            .wisata-item {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Wisata Favorit Anda</h1>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col mb-4">
                        <div class="wisata-item card">
                             
                            <img src="./image/<?php echo $row['gambar']; ?>" alt="Foto Wisata">
                            
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['nama_wisata']; ?></h5>
                                <h5 class="card-title" style="font-size: 0.8rem; color: black;">⭐<?php echo $row['rating']; ?></h5>
                                <h5 class="card-title" style="font-size: 0.8rem; color: black;">HTM: <?php echo $row['harga']; ?></h5>
                                <a href="index_user.php?hal=detail_wisata_alam&id=<?php echo $row['id_wisata']; ?>" class="btn btn-secondary btn-custom">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center" role="alert">
                Tidak ada wisata favorit.
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS (optional for interactivity) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybU7q5xcsY4Yl2smvy6j+R4kzo5hZZkUJ3WeD5pZkkjtpU8b5" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0d3H1Q3P4yHf8E9kOe9JqXl68n5bD/7uX6z7qtk6RMq1Fu/Z" crossorigin="anonymous"></script>
</body>
</html>

<?php
mysqli_close($koneksi);
?>
