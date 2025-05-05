<?php
// Cek apakah ada parameter error dari halaman login
if (isset($_GET['error']) && $_GET['error'] == 'wrong_password') {
    $message = "Username atau Password Salah!";
} else {
    $message = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gagal Login</title>

    <!-- Menambahkan Bootstrap untuk desain yang lebih baik -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Bagian utama halaman -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Card untuk pesan gagal login -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center text-danger">Gagal Login</h5>

                        <!-- Jika ada pesan kesalahan, tampilkan alert Bootstrap -->
                        <?php if ($message): ?>
                            <div class="alert alert-danger text-center">
                                <strong>Error:</strong> <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <p class="text-center">Maaf, username atau password yang Anda masukkan salah. Silakan coba lagi.</p>
                        <div class="d-flex justify-content-center">
                            <a href="index.php" class="btn btn-primary">Kembali ke Halaman Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
