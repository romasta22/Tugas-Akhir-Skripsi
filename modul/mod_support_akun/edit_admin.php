<div class="container-fluid">
    <div class="card">
        <div class="card-header"><strong>Pemberitahuan Edit Wisata</strong></div>
        <div class="card-body">
            <p>Apakah Anda yakin ingin mengedit atau memperbarui data? jika iya, silahkan ke bagian kanan atas, klik dropdwon administrasi, lalu pilih Setting dan silahkan Update data anda!</p>
            <form action="" method="POST" id="editWisataForm">
                <div class="form-group">
                    <button type="button" class="btn btn-warning" onclick="confirmUpdate()">Ya, Saya Ingin mengubah Data saya!</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi konfirmasi untuk update password
    function confirmUpdate() {
        // Tampilkan alert konfirmasi
        var confirmation = confirm("Apakah anda yakin mau update data anda? Anda Akan ke halaman Setting.");
        
        // Jika klik "OK", arahkan ke halaman user.php
        if (confirmation) {
            window.location.href = 'dashboard.php?hal=user'; // Redirect ke halaman user.php
        }
    }
</script>
