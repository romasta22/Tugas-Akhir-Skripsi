<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - Go Danau Toba</title>
    <!-- Link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4" style="margin-top: 25px;">Kontak Kami</h1>
        <p class="text-center text-muted">Hubungi kami untuk informasi lebih lanjut tentang wisata Danau Toba</p>

        <!-- Informasi Kontak -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5><i class="bi bi-geo-alt-fill"></i> Alamat</h5>
                <p>Jl. Parapat Raya No. 10, Toba, Sumatera Utara, Medan, indonesia</p>
            </div>
            <div class="col-md-6">
                <h5><i class="bi bi-telephone-fill"></i> Hotline</h5>
                <p>+62 811-0000-5555</p>
                <h5><i class="bi bi-envelope-fill"></i> Email</h5>
                <p>info@godanautoba.com</p>
            </div>
        </div>

        <!-- Formulir Kontak -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <h5>Formulir Kontak</h5>
                <form action="dashboard_user.php?hal=proses_kontak" method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" required>
                    </div>
                    <div class="mb-3">
                        <label for="pesan" class="form-label">Pesan</label>
                        <textarea class="form-control" id="pesan" name="pesan" rows="4" placeholder="Tuliskan pesan Anda" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>

            <!-- Peta Lokasi -->
            <div class="col-md-6 mb-4">
                <h5>Lokasi Kami</h5>
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31871.365763594867!2d99.0043311!3d2.3836788!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30321f6f3a8e2e2d%3A0xf8d5f68234ce92f7!2sDanau%20Toba!5e0!3m2!1sid!2sid!4v1673545821268!5m2!1sid!2sid" 
                    style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>

        <!-- Ikon Media Sosial -->
        <div class="text-center">
            <h5>Ikuti Kami</h5>
            <a href="#" class="btn btn-outline-dark btn-sm rounded-circle me-2"><i class="bi bi-facebook"></i></a>
            <a href="#" class="btn btn-outline-dark btn-sm rounded-circle me-2"><i class="bi bi-instagram"></i></a>
            <a href="#" class="btn btn-outline-dark btn-sm rounded-circle me-2"><i class="bi bi-youtube"></i></a>
            <a href="#" class="btn btn-outline-dark btn-sm rounded-circle"><i class="bi bi-tiktok"></i></a>
        </div>
    </div>

    <!-- Link JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
