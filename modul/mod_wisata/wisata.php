<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <a href="dashboard.php?hal=tambah_wisata" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Wisata</a>
        </div>
        <div class="card-body">
            <table id="example" class="display" style="width: 100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Wisata</th>
                        <th>Kategori</th>
                        <th>Alamat Wisata</th>
                        <th>Perkiraan Harga/HTM</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
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

                    $sql = mysqli_query($koneksi, "SELECT tbl_wisata.*, tbl_kategori.nama_kategori 
                                                   FROM tbl_wisata 
                                                   JOIN tbl_kategori ON tbl_wisata.id_kategori = tbl_kategori.id_kategori");
                    $no = 1;
                    while ($r = mysqli_fetch_array($sql)) { ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($r['nama_wisata']); ?></td>
                            <td><?php echo htmlspecialchars($r['nama_kategori']); ?></td>
                            <td><?php echo htmlspecialchars($r['lokasi_wisata']); ?></td>
                            <!-- Menampilkan harga dengan format Rupiah -->
                            <td>
                                <?php 
                                echo !empty($r['harga']) ? htmlspecialchars(formatRupiah($r['harga'])) : 'Tidak tersedia'; 
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($r['deskripsi']); ?></td>
                            <td>
                                <a href="dashboard.php?hal=edit_wisata&id=<?php echo $r['id_wisata']; ?>" class="btn btn-success"><i class="bi bi-pencil-square"></i></a>
                                <a href="dashboard.php?hal=hapus_wisata&id=<?php echo $r['id_wisata']; ?>" class="btn btn-danger"><i class="bi bi-x-square"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
