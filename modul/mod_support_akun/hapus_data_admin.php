<?php 
    $id = $_GET['id'];
    
    // Menambahkan konfirmasi menggunakan JavaScript
    echo "<script>
            if(confirm('Apakah Anda yakin ingin menghapus data admin?')) {
                window.location = 'dashboard.php?hal=action_hapus&id=$id'; // Pindah ke aksi penghapusan
            } else {
                window.location = 'dashboard.php?hal=admin'; // Kembali ke halaman admin
            }
          </script>";
?>
