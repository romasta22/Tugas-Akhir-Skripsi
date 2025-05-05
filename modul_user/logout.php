<?php

    // session_start();

    // session_unset();
    // session_destroy();

    // echo "<script>alert('Anda telah keluar dari sistem'); window.location = 'dashboard_user.php?hal=login'</script>";
    // exit;


    session_start();  // Memulai session

    // Hapus semua variabel session
    session_unset();
    
    // Hancurkan session
    session_destroy();
    
    // Hapus data session yang tersisa dalam cookie jika ada
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"], $params["secure"], $params["httponly"]
        );
    }
    
    // Redirect ke halaman login atau halaman beranda setelah logout
    echo "<script>
            alert('Anda telah keluar dari sistem');
            window.location = 'dashboard_user.php?hal=home';  // Ganti dengan halaman login Anda
          </script>";
    exit();


?>