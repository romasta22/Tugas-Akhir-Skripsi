<?php
$conn = new mysqli("localhost", "root", "", "dbs_wisata");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan ID wisata dari URL
$id_wisata = isset($_GET['id_wisata']) ? (int)$_GET['id_wisata'] : 0;
if ($id_wisata === 0) {
    die("ID wisata tidak valid.");
}

// Query untuk mendapatkan semua ulasan
$queryUlasan = "SELECT nama_pengguna, ulasan, rating, tanggal FROM tbl_ulasan WHERE id_wisata = ? ORDER BY tanggal DESC";
$stmtUlasan = $conn->prepare($queryUlasan);
$stmtUlasan->bind_param("i", $id_wisata);
$stmtUlasan->execute();
$resultUlasan = $stmtUlasan->get_result();
$ulasanList = $resultUlasan->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Semua Ulasan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .review {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .review p {
            margin: 0;
        }
        .review .username {
            font-weight: bold;
            font-size: 1.1em;
        }
        .review .date {
            font-size: 0.9em;
            color: #6c757d;
        }
        .review .rating {
            color: #ffd700;
        }
        .review .text {
            margin-top: 10px;
            font-size: 1em;
            color: #343a40;
        }
        .back-button {
            margin-top: 30px;
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">Semua Ulasan Wisata</h1>

        <?php if (count($ulasanList) > 0): ?>
            <?php foreach ($ulasanList as $ulasan): ?>
                <div class="review">
                    <p class="username"><?php echo htmlspecialchars($ulasan['nama_pengguna']); ?></p>
                    <p class="rating"><?php echo str_repeat("⭐", $ulasan['rating']); ?></p>
                    <p class="date"><?php echo date("d M Y, H:i", strtotime($ulasan['tanggal'])); ?></p>
                    <p class="text"><?php echo htmlspecialchars($ulasan['ulasan']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Belum ada ulasan untuk wisata ini.</p>
        <?php endif; ?>

        <a href="dashboard_user.php?hal=detail_wisata_alam&id=<?php echo $id_wisata; ?>" class="back-button">Kembali</a>
    </div>
</body>
</html>
<?php
$conn->close();
?>
