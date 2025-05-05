<?php

session_start();

session_destroy();

echo "<script>alert('Anda keluar dari sistem'); window.location = 'index.php'</script>";