<?php
session_start(); 

include '../koneksi.php';

// Proses Tambah Favorit
if (isset($_GET['hal']) && $_GET['hal'] == 'tambah_favorite' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $id_wisata = $_POST['id_wisata'];

        // Cek apakah sudah ada di favorit
        $check_query = "SELECT * FROM tbl_favorite WHERE id_user = '$id_user' AND id_wisata = '$id_wisata'";
        $check_result = mysqli_query($koneksi, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            $insert_query = "INSERT INTO tbl_favorite (id_user, id_wisata) VALUES ('$id_user', '$id_wisata')";
            mysqli_query($koneksi, $insert_query);
        }

        header("Location: dashboard_user.php?hal=favorite");
        exit();
    } else {
        header("Location: login.php?redirect=tambah_favorite&id_wisata=$id_wisata");
        exit();
    }
}


if (isset($_GET['hal']) && $_GET['hal'] == 'login') {
    include "mod_login_user/login.php"; // Menampilkan form login
    exit(); // Pastikan setelah form login ditampilkan, script berhenti
}

ob_start(); // Mulai output buffering

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Dashboard - <?php echo isset($_GET['hal']) ? ucfirst($_GET['hal']) : 'Home'; ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Boostrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body>
  <style>
    body {
    margin: 0;
    padding: 0;
    padding-top: 56px; /* Gunakan padding yang sesuai dengan tinggi navbar */
    box-sizing: border-box;
    width: 100%;
    overflow-x: hidden; /* Hindari scroll horizontal */
}
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

footer {
    width: 100%;
    text-align: center;  /* Pastikan teks di tengah */
    background-color: #333;
    color: white;
    padding: 10px;
    margin-top: auto;  /* Memastikan footer tetap di bawah */
    position: relative;
}
.container-fluid {
    width: 100%;  /* Membuat container menggunakan seluruh lebar */
    display: flex;
    justify-content: center; /* Menyelaraskan konten di tengah */
    align-items: center;  /* Vertikal align */
    text-align: center; /* Horizontal align */
}



    /* Navbar Full Lebar */
    .navbar {
        width: 100%; /* Membuat navbar full lebar */
        background-color: #3b3f46;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Diperhalus */
        position: sticky;
        top: 0;
        z-index: 1000;
        margin: 0; /* Hilangkan margin */
    }

    /* Konten utama full lebar */
    .container {
        width: 100%;
        max-width: none;
        margin: auto; /* Pusatkan konten */
        padding: 20px;
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .navbar a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
        font-weight: 500;
    }

    .navbar a:hover {
        color: #f39c12; /* Warna hover kuning */
    }

    .navbar .active {
        font-weight: bold;
        border-bottom: 2px solid #f39c12;
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
        color: white;
    }

    .navbar-toggler {
        border: none;
        background-color: transparent;
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.7)' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .nav-link.dropdown-toggle {
        display: flex;
        align-items: center;
    }

    .nav-link.dropdown-toggle i {
        margin-right: 5px;
    }

    .dropdown-menu {
        background-color: #3b3f46;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .dropdown-menu a {
        color: white;
    }

    .dropdown-menu a:hover {
        background-color: rgb(50, 51, 53);
    }

    /* Responsif Navbar */
    @media (max-width: 992px) {
        .navbar-collapse {
            background-color: #3b3f46;
            padding: 20px;
            border-radius: 10px;
        }

        .navbar-nav .nav-item {
            margin-bottom: 10px;
        }
        }
        .fixed-top {
        width: 100%;
        left: 0;
        right: 0;
        }
        .navbar {
            width: 100%;
        }
        .navbar {
            box-shadow: none !important; /* Hapus bayangan navbar */
            border: none !important; /* Hapus border */
        }

        .container {
            width: 100%; /* Membuat konten mengisi lebar layar */
            margin: 0 auto; /* Pusatkan konten */
            padding: 20px;
        }


        @media (min-width: 6px) {
            .container {
                padding-left: 2px;
                padding-right: 2px;
            }
        }
        body {
            padding-top: 18px; /* Atau margin-top di elemen lain */
        }
</style>

      <div class="container mt-1">
        <div class="col-lg-12 py-2 bg-secondary fixed-top"> 
                <!-- untuk navbar -->
                    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
                        <a class="navbar-brand" href="#"> <strong>Go Danau Toba</strong></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                         <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                            <ul class="navbar-nav">
                            <li class="nav-item me-3"><a href="dashboard_user.php?hal=home" class="nav-link <?php echo (isset($_GET['hal']) && $_GET['hal'] == 'home') ? 'active' : ''; ?>"><i class="bi bi-house-check"></i> Beranda</a></li>
                            <li class="nav-item me-3"><a href="dashboard_user.php?hal=about" class="nav-link <?php echo (isset($_GET['hal']) && $_GET['hal'] == 'about') ? 'active' : ''; ?>"><i class="bi bi-file-earmark-person"></i> Tentang Kami</a></li>
                            <li class="nav-item me-3"><a href="dashboard_user.php?hal=wisata" class="nav-link <?php echo (isset($_GET['hal']) && in_array($_GET['hal'], ['wisata', 'wisata_alam', 'detail_wisata_alam', 'wisata_budaya', 'detail_wisata_budaya', 'wisata_kuliner', 'detail_wisata_kuliner'])) ? 'active' : ''; ?>"><i class="bi bi-geo-fill"></i> Wisata</a></li>                                                                                                              
                            <li class="nav-item me-3"><a href="dashboard_user.php?hal=berita" class="nav-link <?php echo (isset($_GET['hal']) && $_GET['hal'] == 'berita') ? 'active' : ''; ?>"><i class="bi bi-newspaper"></i> Berita</a></li>
                            <li class="nav-item me-3"><a href="dashboard_user.php?hal=kontak" class="nav-link <?php echo (isset($_GET['hal']) && $_GET['hal'] == 'kontak') ? 'active' : ''; ?>"><i class="bi bi-telephone"></i> Kontak</a></li>
                                                         
                            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 1): ?>
                                <!-- Jika sudah login, tampilkan dropdown dengan username -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span><i class="fas fa-user-circle"></i> <?php echo $_SESSION['username']; ?></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="dashboard_user.php?hal=favorite"><i class="bi bi-heart"></i> Favorit</a></li>
                                        <li><a class="dropdown-item" href="dashboard_user.php?hal=setting"><i class="bi bi-gear"></i> setting</a></li>
                                        <li><a class="dropdown-item" href="logout.php" onclick="return confirm('Apakah anda yakin ingin keluar?')"><i class="bi bi-box-arrow-in-right"></i> Logout</a></li>
                                    </ul>
                                    
                                </li>
                            <?php else: ?>
                                <!-- Jika belum login, tampilkan link login -->

                                
                                <li class="nav-item">
                                    <a class="nav-link" href="dashboard_user.php?hal=login"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                                </li>
                            <?php endif; ?>                            
                          </ul>
                        </div>
                        </nav>           
                 <!-- akhir navbar -->
            </div>
        </div>
        
        <div class="container" style="padding-bottom: 50px;">
            <div class="row">
            <div class="col-lg-12">               
                    <?php
                        // Periksa apakah parameter 'hal' ada
                        if (isset($_GET['hal'])) {
                            // Cek nilai parameter 'hal'
                            switch ($_GET['hal']) {
                                case "home":
                                    include "mod_home/home.php";
                                    break;
                                case "hasil":
                                    include "mod_home/hasil.php";
                                    break;
                                case "rekomendasi":
                                    include "mod_home/rekomendasi.php";
                                    break;
                                case "about":
                                    include "mod_about/about.php";
                                    break;
                                case "berita":
                                    include "mod_berita/berita.php";
                                    break;
                                case "detail_berita":
                                    include "mod_berita/detail_berita.php";
                                        break;
                                case "kontak":
                                    include "mod_kontak/kontak.php";
                                    break;
                                case "proses_kontak":
                                    include "mod_kontak/proses_kontak.php";
                                    break;
                                case "wisata":
                                    include "mod_wisata/wisata.php";
                                    break;
                                case "wisata_alam":
                                    include "mod_wisata/wisata_alam.php"; // Tambahkan case untuk Wisata Alam
                                    break;
                                case "detail_wisata_alam":
                                    include "mod_wisata/detail_wisata_alam.php";
                                    break;
                                case "detail_wisata_alam2":
                                    include "mod_wisata/detail_wisata_alam2.php";
                                    break;
                                case "wisata_budaya":
                                    include "mod_wisata/wisata_budaya.php"; // Tambahkan case untuk Wisata Alam
                                    break;
                                case "detail_wisata_budaya":
                                    include "mod_wisata/detail_wisata_budaya.php";
                                    break;
                                case "wisata_kuliner":
                                    include "mod_wisata/wisata_kuliner.php"; // Tambahkan case untuk Wisata Alam
                                    break;
                                case "detail_wisata_kuliner":
                                    include "mod_wisata/detail_wisata_kuliner.php";
                                    break;
                                case "akomodasi":
                                    include "mod_wisata/akomodasi.php";
                                    break;
                                case "detail_akomodasi":
                                    include "mod_wisata/detail_akomodasi.php";
                                    break;                                   
                                case "lihat_semua_ulasan":
                                    include "mod_wisata/lihat_semua_ulasan.php";
                                    break;
                                case "favorite":
                                    include "mod_favorite/favorite.php";
                                    break;
                                case "tambah_favorite":
                                    include "mod_favorite/tambah_favorite.php";
                                    break;
                                case "tambah_hapus":
                                    include "mod_favorite/tambah_hapus.php";
                                    break;
                                case "login":
                                    include "mod_login_user/login.php";
                                    break;
                                case "registrasi":
                                    include "mod_login_user/registrasi.php";
                                    break;
                                case "proses_registrasi":
                                    include "mod_login_user/proses_registrasi.php";
                                    break;
                                case "logout":
                                    include "mod_login_user/logout.php";
                                    break;
                                case "setting":
                                    include "mod_login_user/setting.php";
                                    break;
                                case "update_password":
                                    include "mod_login_user/update_password.php";
                                    break;
                                case "rekomendasi_api":
                                    include "mod_rekomendasi/rekomendasi_api.php";
                                    break;
                                default:
                                    // Jika nilai 'hal' tidak dikenal
                                    echo "<div class='text-center py-5'>
                                    <h1>404</h1><p>Halaman tidak ditemukan.</p><a href='dashboard_user.php?hal=home' class='btn btn-primary'>Kembali ke Beranda</a>
                                  </div>";
                                    break;
                            }
                        } else {
                            // Jika parameter 'hal' tidak ada, tampilkan pesan default
                            echo "Halaman belum dipilih.";
                        }
                    ?>
            </div>
        </div>
        </div>  
        <?php include 'footer.php'; ?> <!-- Tambahkan footer -->      
      </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


  </body>
</html>

<?php
ob_end_flush(); // Tampilkan semua output
?>
