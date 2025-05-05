<div class="container-fluid">
    <div class="card">
    <div class="card-header">
        <a href="dashboard.php?hal=tambah_galeri" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Data Galeri</a>
    </div>
    <div class="card-body">
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Keterangan</th>
                    <th>Nama Wisata</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = mysqli_query($koneksi, "SELECT * FROM tbl_galeri, tbl_wisata WHERE tbl_galeri.id_wisata=tbl_wisata.id_wisata");

                    while ($r = mysqli_fetch_array($sql)) { ?>
                    <tr>
                        <td></td>
                        <td><?php echo $r['keterangan_foto']; ?></td>
                        <td><?php echo $r['nama_wisata']; ?></td>
                        <td><img src="././img_galeri/<?php echo $r['nama_foto'] ?>" height="100" width="150"></td>
                        <td>
                            <a href="dashboard.php?hal=edit_galeri&id=<?php echo $r['id_galeri']; ?>" class="btn btn-success"><i class="bi bi-pencil-square"></i></a>
                            <a href="dashboard.php?hal=hapus_galeri&id=<?php echo $r['id_galeri']; ?>" class="btn btn-danger"><i class="bi bi-x-square"></i></a>
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
</div>