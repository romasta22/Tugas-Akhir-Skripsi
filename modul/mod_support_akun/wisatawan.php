<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <a href="" class="btn btn-primary"><strong>Data Halaman Pengguna</strong></a>
        </div>
        <div class="card-body">
            <table id="example" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Query untuk mengambil data dari tabel tbl_pengguna
                        $sql = mysqli_query($koneksi, "SELECT * FROM tbl_pengguna");
                        $no = 1; // Menyimpan nomor urut
                        while($r = mysqli_fetch_array($sql)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $r['username']; ?></td>
                        <td><?php echo $r['email']; ?></td>
                        <td><?php echo date('d-m-y', strtotime($r['created_at'])); ?></td>
                    </tr>
                    <?php
                        }
                    ?>            
                </tbody>
            </table>
        </div>
    </div>
</div>
