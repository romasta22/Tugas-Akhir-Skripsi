<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <a href="" class="btn btn-primary"><strong>Data Halaman Admin</strong></a>
        </div>
        <div class="card-body">
            <table id="example" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Admin</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Query untuk mengambil data dari tabel tbl_admin
                        $sql = mysqli_query($koneksi, "SELECT * FROM tbl_admin");
                        $no = 1; // Menyimpan nomor urut
                        while($r = mysqli_fetch_array($sql)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $r['nama_admin']; ?></td>
                        <td><?php echo $r['username']; ?></td>
                        <td>
                            <a class="btn btn-success" title="Edit" href="dashboard.php?hal=edit_admin&id=<?php echo $r['id_admin']; ?>"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger" title="Hapus" href="dashboard.php?hal=hapus_data_admin&id=<?php echo $r['id_admin']; ?>"><i class="bi bi-trash3"></i></a>
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
