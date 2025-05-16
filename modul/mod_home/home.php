<div class="container-fluid">

    <!-- Tambahkan bagian ini -->
    <div class="row">
    <div class="col-md-12 text-center my-3">
        <h1 class="fw-bold text-secondary" style="font-family: 'Cambria', sans-serif; font-size: 2,5rem;">
            SELAMAT DATANG di Go Danau Toba!
        </h1>
        <p class="text-secondary" style="font-style: italic; font-size: 1.25rem;">
            Sistem Pendukung Keputusan Rekomendasi Wisata Danau Toba 
        </p>
    </div>
</div>
    <!-- Akhir bagian tambahan -->

    <div class="row">
        <div class="col-md-12 border">
            <div class="card-group">

            <!-- card untuk galeri -->
            <div class="card text-white bg-secondary mb-3 m-3 rounded-lg" style="max-width: 18rem;">
                <div class="card-body">
                    <?php
                        $sql_galeri = mysqli_query ($koneksi, "SELECT * FROM tbl_galeri");
                        $jumlah_galeri = mysqli_num_rows($sql_galeri);

                    ?>
                    <h5 class="card-title"><?php echo $jumlah_galeri ?></h1>
                    <p class="card-text">Total Data Galeri</p>
                </div>
                <div class="card-footer text-center"><a class="text-white" href="dashboard.php?hal=galeri">Lihat Data <i class="bi bi-arrow-right-circle-fill"></i></a></div>
            </div>
            <!-- card untuk wisata -->
            <div class="card text-white bg-primary mb-3 m-3 rounded-lg" style="max-width: 18rem;">
                <div class="card-body">
                <?php
                        $sql_wisata = mysqli_query($koneksi, "SELECT * FROM tbl_wisata");
                        $jumlah_wisata = mysqli_num_rows($sql_wisata);

                    ?>
                    <h5 class="card-title"><?php echo $jumlah_wisata ?></h1>
                    <p class="card-text">Total Data Wisata</p>
                </div>
                <div class="card-footer text-center"><a class="text-white" href="dashboard.php?hal=wisata">Lihat Data <i class="bi bi-arrow-right-circle-fill"></i></a></div>
            </div>
            <!-- card untuk kategori -->
            <div class="card text-white bg-success mb-3 m-3 rounded-lg" style="max-width: 18rem;">
                <div class="card-body">
                <?php
                        $sql_kategori = mysqli_query($koneksi, "SELECT * FROM tbl_kategori");
                        $jumlah_kategori = mysqli_num_rows($sql_kategori);

                    ?>
                    <h5 class="card-title"><?php echo $jumlah_kategori ?></h1>
                    <p class="card-text">Total Data Kategori</p>
                </div>
                <div class="card-footer text-center"><a class="text-white" href="dashboard.php?hal=kategori_wisata">Lihat Data <i class="bi bi-arrow-right-circle-fill"></i></a></div>
            </div>
            <!-- card untuk berita-->
            <div class="card text-white bg-danger mb-3 m-3 rounded-lg" style="max-width: 18rem;">
                <div class="card-body">
                    <?php
                        $sql_berita = mysqli_query($koneksi, "SELECT * FROM tbl_berita");
                        $jumlah_berita = mysqli_num_rows($sql_berita);

                    ?>
                    <h5 class="card-title"><?php echo $jumlah_berita ?></h1>
                    <p class="card-text">Total Data Berita</p>
                </div>
                <div class="card-footer text-center"><a class="text-white" href="dashboard.php?hal=berita">Lihat Data <i class="bi bi-arrow-right-circle-fill"></i></a></div>
            </div>            
            </div>
        </div>
    </div>       
        <div class="row border mt-2">
        <div class="col-md-12 center-graph">
                <canvas id="grafikwisata" class="p-4" style="width:100%;max-width:600px;"></canvas>
            </div>
        </div>  
    <style>.center-graph { display: flex; justify-content: center; align-items: center; height: 50vh;  width: 100%; } </style>

    <div class="row border mt-2">
        
              
                    <script>
    // Pastikan elemen dengan id "grafikwisata" ada di halaman sebelum mencoba membuat grafik
    if (document.getElementById("grafikwisata")) {
        // Ambil data dari PHP (nama kategori dan total wisata)
        var x = <?php echo json_encode($nama_kategori); ?>;
        var y = <?php echo json_encode($total_wisata); ?>;
        var warna_bar = [
            "#0dcaf0", //blue
            "#d63384", //pink
            "#6610f2", //yellow
            "#198754"  //green
        ];

        // Membuat grafik bar (OLAP) menggunakan Chart.js
        new Chart("grafikwisata", {
            type: "bar",  // Mengganti tipe grafik menjadi bar (OLAP-style)
            data: {
                labels: x,  // Menampilkan kategori wisata
                datasets: [{
                    backgroundColor: warna_bar,  // Warna untuk setiap kategori
                    data: y  // Data yang akan ditampilkan
                }]
            },
            options: {
                responsive: true,  // Menjadikan grafik responsif
                scales: {
                    y: {
                        beginAtZero: true  // Mulai sumbu Y dari nol
                    }
                },
                title: {
                    display: true,
                    text: "Jumlah Data Wisata Berdasarkan Kategori"
                }
            }
        });
    }
</script>

               </tbody>
            </table>
        </div>
    </div>
</div>