 <div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <a href="dashboard.php?hal=tambah_berita" class="btn btn-primary"><strong><i class="bi bi-plus-lg"></i> Data Berita</strong></a>
        </div>
        <div class="card-body">
        <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Berita</th>
                <th>Konten Berita</th>
                <th>Penulis</th>
                <th>Tanggal</th>
                <th>Gambar</th>
                <th>Sumber</th>
                <th>Jumlah Pembaca</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = mysqli_query($koneksi, "SELECT * FROM tbl_berita, tbl_admin WHERE tbl_berita.id_admin_berita=tbl_admin.id_admin" );
                while($r = mysqli_fetch_array($sql)) {
            ?>
            <tr>
                <td></td>
                <td><?php echo $r['judul_berita'] ?></td>
                <td><?php echo $r['konten_berita'] ?></td>
                <td><?php echo $r['nama_admin'] ?></td>
                <td><?php echo date('d-m-y', strtotime($r['tanggal_kejadian'])) ?></td>
                <td><img src="././img_berita/<?php echo $r['foto_berita'] ?>" height="100" width="200"></td>
                <td><?php echo $r['sumber_berita'] ?></td>
                <td><?php echo $r['jumlah_pembaca'] ?></td>
                <td>
                    <a class="btn btn-success" title="edit" href="dashboard.php?hal=edit_berita&id=<?php echo $r['id_berita'] ?>"><i class="bi bi-pencil-square"></i></a>
                    <a class="btn btn-danger" title="hapus" href="dashboard.php?hal=hapus_berita&id=<?php echo $r['id_berita'] ?>"><i class="bi bi-trash3"></i></a>
                </td>
                
            </tr>
            <?php
                }
            ?>            
        </tbody>
    </table>
        </div>
    </div>
 </div>