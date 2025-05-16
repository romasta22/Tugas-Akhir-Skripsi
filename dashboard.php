<?php 
require_once "koneksi.php";
date_default_timezone_set("Asia/Jakarta");
 session_start();
 if(empty($_SESSION["username"]) and empty($_SESSION["password"])) {
    echo '
    <center>
    <br><br><br><br><br><br><br><br><br><br><br>
    <b>Maaf Silahkan Melakukan Login!</b><br><br>
    <>Anda Telah Keluar Dari Sistem</b><br>
    <>atau Anda belum melakukan Login</b><br>

    <a href="index.php" title="klik gambar ini untuk kembali ke Halaman Login"><img src="img/key_login.jpg" height="100" width="100"></img></a>
    </center>
    ';
 } else {
    // grafik wisata berdasarkan kategori
    $kategori_wisata = mysqli_query($koneksi, "SELECT * FROM tbl_kategori");
    while($r = mysqli_fetch_array($kategori_wisata)) {
        $nama_kategori[] = $r['nama_kategori'];
        $jml_wisata = mysqli_query($koneksi, "SELECT COUNT(id_kategori) AS total FROM tbl_wisata WHERE id_kategori = '$r[id_kategori]'");
        $a = mysqli_fetch_array($jml_wisata);
        $total_wisata[] = $a['total'];
    } 

    // grafik galeri berdasarkan wisata
    $wisata = mysqli_query($koneksi, "SELECT * FROM tbl_wisata");
    while($r1 = mysqli_fetch_array($wisata)) {
        $nama_wisata[] = $r1['nama_wisata'];
        $jml_galeri = mysqli_query($koneksi, "SELECT COUNT(id_wisata) AS total_galeri FROM tbl_galeri WHERE id_wisata = '$r1[id_wisata]'");
        $a1 = mysqli_fetch_array($jml_galeri);
        $total_galeri[] = $a1['total_galeri'];
    }
 ?>
 <!doctype html>
 <html lang="en">
   <head>
     <title>Dashboard - <?php echo ucwords(str_replace('_',' ', $_GET["hal"])) ?></title>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script> 
     <!-- <script src="https://cdn.ckeditor.com/4.25.0-lts/standard/ckeditor.js"></script> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <!-- GRAFIX JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <!-- script humburger -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    </head>
   <body style="margin:0; font-family:Arial, sans-serif; background-color:#f8f9fa;">
     <div class="header" style="background-color: #23282d; color:white; padding:10px 20px; display:flex; align-items:center; justify-content:space-between; position:fixed; top:0; width:100%; z-index:1000; box-shadow:0 4px 10px rgba(0, 0, 0, 0.1);">
        <span class="hamburger" id="hamburger-menu" style="font-size:px; cursor:pointer;"><i class="fa fa-bars"></i></span>
        <!-- <span class="brand-title" style="font-size:20px; font-weight:bold;">Go Danau Toba</span> -->
    
                <div class="dropdown">
                <button class="btn btn-secondary  dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;">
                    administrator
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">
                    <div class="media">
                        <img class="align-self-center mr-3" src="img/gambardanautoba.jpg" height="100" width="100" alt="Generic placeholder image">
                        <div class="media-body">
                            <h5 class="mt-0"><?php echo $_SESSION['namaadmin'] ?></h5>
                            <small><p class="mb-0"><i class="bi bi-clock"></i> Pukul <?php  echo date("H:i:s")?> WIB</p></small>
                        </div>
                    </div>  
                    </a>
                    <a class="dropdown-item" href="dashboard.php?hal=user"><i class="bi bi-gear"></i> Setting</a>
                    <a class="dropdown-item" href="logout.php" onclick="return confirm('Apakah Anda Yakin Ingin Keluar?')"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
                </div>  
            </div>
        </div>

        <div class="row mt-4" style="padding-top:40px">
            <!-- <div class="col-lg-2 position-fixed vh-100"> -->
            <div id="sidebar" class="col-lg-2 position-fixed vh-100">

            <?php
            // Menyimpan parameter hal dengan nilai default "home" jika tidak ada parameter
            $hal = isset($_GET["hal"]) ? $_GET["hal"] : "home";
            ?>                
            
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link <?php echo ($hal == "home") ? "active" : "" ?>" href="dashboard.php?hal=home"><i class="bi bi-house-check-fill" style="margin-right: 20px;"></i>Home</a>
                <a class="nav-link <?php echo ($hal == "profil") ? "active" : "" ?>" href="dashboard.php?hal=profil"><i class="bi bi-person-lines-fill" style="margin-right: 20px;"></i>Profil</a>
                <a class="nav-link <?php echo (($hal == "galeri") or ($_GET['hal'] == 'tambah_galeri') or ($_GET['hal'] == 'edit_galeri')) ? "active" : "" ?>" href="dashboard.php?hal=galeri"><i class="bi bi-images" style="margin-right: 20px;"></i>Galeri</a>
                <a class="nav-link <?php echo (($hal == "wisata") or ($_GET['hal'] == 'tambah_wisata') or ($_GET['hal'] == 'edit_wisata')) ? "active" : "" ?>" href="dashboard.php?hal=wisata"><i class="bi bi-geo-fill" style="margin-right: 20px;"></i>Wisata</a>
                <a class="nav-link <?php echo (($hal == "kategori_wisata") or ($_GET['hal'] == 'tambah_kategori') or ($_GET['hal'] == 'edit_kategori')) ? "active" : "" ?>" href="dashboard.php?hal=kategori_wisata"><i class="bi bi-tags-fill" style="margin-right: 20px;"></i>Kategori Wisata</a>
                <a class="nav-link <?php echo (($hal == "berita") or ($_GET['hal'] == 'tambah_berita') or ($_GET['hal'] == 'edit_berita')) ? "active" : "" ?>" href="dashboard.php?hal=berita"><i class="bi bi-newspaper" style="margin-right: 20px;"></i>Berita</a>
                <!-- <a class="nav-link <?php echo ($hal == "support_akun") ? "active" : "" ?>" href="dashboard.php?hal=support_akun"><i class="fas fa-user-circle" style="margin-right: 20px;"></i>Support Account</a>               -->
                 
                <!-- Accordion untuk Support Account -->
                
                <a class="nav-link <?php echo ($hal == "support_akun") ? "active" : "" ?>" href="#supportAccordion" data-toggle="collapse" aria-expanded="false" aria-controls="supportAccordion">
                    <i class="fas fa-user-circle" style="margin-right: 20px;"></i>Support Account
                     
                </a>
                <div id="supportAccordion" class="collapse <?php echo ($hal == "support_akun" || $hal == "admin" || $hal == "wisatawan") ? "show" : "" ?>" aria-labelledby="supportAccordion" data-parent="#sidebar">
                    <div class="nav flex-column nav-pills d-flex">
                        <a class="nav-link <?php echo ($hal == "admin") ? "active" : "" ?>" href="dashboard.php?hal=admin" style="margin-left: 20px;"><i class="bi bi-person" style="margin-right: 20px;"></i>Admin</a>
                        <a class="nav-link <?php echo ($hal == "wisatawan") ? "active" : "" ?>" href="dashboard.php?hal=wisatawan" style="margin-left: 20px;"><i class="bi bi-people" style="margin-right: 20px;"></i>wisatawan</a>
                        <!-- Menambahkan Link untuk Akses Halaman Pengguna -->
                        <a class="nav-link" onclick="window.open('modul_user/dashboard_user.php?hal=home', '_blank')" style="margin-left: 20px;"><i class="bi bi-box-arrow-right" style="margin-right: 20px;"></i>Lihat Halaman Pengguna</a>
                     </div>
                </div>
                <style>
                    /* Styling untuk menu Support Account dan sub-menu */
                    #sidebar a.nav-link {
                        color: #c3c4c7;
                        text-decoration: none;
                        padding: 10px 15px;
                        display: block;
                        transition: background-color 0.2s;
                    }

                    #sidebar a.nav-link:hover, #sidebar a.nav-link.active {
                        background-color: #007cba;
                        color: white;
                    }

                    /* Membuat submenu Admin, Wisatawan, dan Lihat Halaman Pengguna dalam satu baris */
                    #supportAccordion .nav-pills {
                        display: flex;
                        justify-content: flex-start; /* Menjaga submenu sebaris */
                        flex-wrap: wrap; /* Menjaga elemen tetap pada satu baris, pindah ke bawah jika perlu */
                    }

                    /* Menambahkan margin kiri pada submenu Admin, Wisatawan, dan Lihat Halaman Pengguna */
                    #supportAccordion .nav-pills .nav-link {
                        padding-left: 20px; /* Memberikan ruang agar lebih tidak sejajar */
                        margin-right: 20px; /* Memberikan jarak antar submenu */
                        display: flex; /* Membuat setiap link menggunakan flexbox */
                        align-items: center; /* Menyelaraskan teks dan ikon */
                    }

                    /* Ikon pada submenu Admin dan Pengguna */
                    #supportAccordion .nav-pills .nav-link i {
                        margin-right: 10px; /* Jarak ikon dan teks */
                    }
                    #supportAccordion.show .float-right {
                        transform: rotate(180deg);
                    }
                    #supportIcon {
                        transition: transform 0.3s ease; /* Efek transisi yang halus */
                        transform: rotate(0deg); /* Pastikan ikon mulai dari posisi bawah */
                    }

                </style>
            <!-- akhir  Accordion untuk Support Account -->

            </div>
            </div>
            <!-- <div class="col-lg-10 offset-2"> -->
            <div id="main-content" class="col-lg-10 offset-2">
                <!-- <div class="card vh-100"></div> -->
                 
                <?php

                    if (isset($_GET["hal"])) {

                        switch($_GET["hal"]) {
                            case "home":
                                include "modul/mod_home/home.php";
                                break;
                            case "profil":
                                include "modul/mod_profil/profil.php";
                                break;
                            case "galeri":
                                include "modul/mod_galeri/galeri.php";
                                break;
                            case "tambah_galeri";
                                include "modul/mod_galeri/tambah_galeri.php";
                                break;
                            case "edit_galeri";
                                include "modul/mod_galeri/edit_galeri.php";
                                break;
                            case "hapus_galeri";
                                include "modul/mod_galeri/hapus_galeri.php";
                                break;
                            case "wisata":
                                include "modul/mod_wisata/wisata.php";
                                break;
                            case "tambah_wisata";
                                include "modul/mod_wisata/tambah_wisata.php" ;
                                break;
                            case "edit_wisata";
                                include "modul/mod_wisata/edit_wisata.php";
                                break;
                            case "hapus_wisata";
                                include "modul/mod_wisata/hapus_wisata.php";
                                break;
                            case "kategori_wisata":
                                include "modul/mod_kategori/kategori.php";
                                break;
                            case "tambah_kategori";
                                include "modul/mod_kategori/tambah_kategori.php";
                                break;
                            case "edit_kategori";
                                include "modul/mod_kategori/edit_kategori.php";
                                break;
                            case "hapus_kategori";
                                include "modul/mod_kategori/hapus_kategori.php";
                                break;
                            case "kuliner":
                                include "modul/mod_kuliner/kuliner.php";
                                break;
                            case "berita":
                                include "modul/mod_berita/berita.php";
                                break;
                            case "tambah_berita";
                                include "modul/mod_berita/tambah_berita.php";
                                break;
                            case "hapus_berita";
                                include "modul/mod_berita/hapus_berita.php";
                                break;
                            case "edit_berita";
                                include "modul/mod_berita/edit_berita.php";
                                break;
                            case "user":
                                include "modul/mod_user/user.php";
                                break;
                            case "support_akun":
                                include "modul/mod_support_akun/support_akun.php";
                                break;                                
                            case "admin":
                                include "modul/mod_support_akun/admin.php";
                                break;
                            case "edit_admin":
                                include "modul/mod_support_akun/edit_admin.php";
                                break;
                            case "hapus_data_admin":
                                include "modul/mod_support_akun/hapus_data_admin.php";
                                break;
                            case "action_hapus":
                                include "modul/mod_support_akun/action_hapus.php";
                                break;
                            case "wisatawan":
                                include "modul/mod_support_akun/wisatawan.php";
                                break;
                            
                            
                            default:
                                echo "<h3>Halaman Tidak Ditemukan</h3>";
                        }

                    } else {
                        header("location:dashboard.php?hal=home");
                    }
                ?>
            </div>

        <!-- scrip humburger -->
        <script>
            const hamburger = document.getElementById('hamburger-menu');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            hamburger.addEventListener('click', () => {
                sidebar.classList.toggle('hide'); // Menyembunyikan sidebar
                if (sidebar.classList.contains('hide')) {
                    mainContent.style.marginLeft = '0'; // Artikel memenuhi layar
                    mainContent.style.width = '100%';
                } else {
                    mainContent.style.marginLeft = '250px'; // Artikel menyesuaikan sidebar
                    mainContent.style.width = 'calc(100% - 250px)';
                }
            });

            // untuk support akun   
        $(document).ready(function() {
        // Pastikan icon selalu dimulai dengan arah bawah
        $('#supportIcon').css('transform', 'rotate(0deg)');

        // Ketika accordion dibuka
        $('#supportAccordion').on('show.bs.collapse', function() {
            $('#supportIcon').css('transform', 'rotate(180deg)'); // Panah ke atas
        });

        // Ketika accordion ditutup
        $('#supportAccordion').on('hide.bs.collapse', function() {
            $('#supportIcon').css('transform', 'rotate(0deg)'); // Panah ke bawah
        });
    });
          </script>
            <style>
                #sidebar { background-color: #23282d; color: white; width: 220px; height: 100vh; position: fixed; top: 56px; left: 0; padding-top: 20px; box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease-in-out; z-index: 1000;  }
                #sidebar a { color: #c3c4c7; text-decoration: none; padding: 10px 15px; display: block; transition: background-color 0.2s; }
                #sidebar a:hover, #sidebar a.active { background-color: #007cba; color: white; }
                #sidebar.hide {
                    transform: translateX(-220px); /* Sesuaikan dengan lebar sidebar */
                }
            </style>
        </div>

       </div>
     
     <!-- Menambahkan jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Optional JavaScript -->
     <!-- jQuery first, then Popper.js, then Bootstrap JS -->
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
     <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
     <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    

     
     <script>
        const table = new DataTable('#example', {
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: 0
                }
            ],
            order: [[1, 'asc']]
        });
        
        table
            .on('order.dt search.dt', function () {
                let i = 1;
        
                table
                    .cells(null, 0, { search: 'applied', order: 'applied' })
                    .every(function (cell) {
                        this.data(i++);
                    });
            })
            .draw();
               
     </script>
   </body>
 </html>
 
 <?php
    }
 ?>
