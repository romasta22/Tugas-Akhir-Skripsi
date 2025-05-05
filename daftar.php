<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .input-group .input-group-text {
            background: none;
            border-left: none;
        }
        .input-group .form-control {
            border-right: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <h4 class="text-center mb-4">Form Registrasi</h4>
        <form action="proses_daftar.php" method="POST">
            <div class="mb-3">
                <label for="nama_admin" class="form-label">Nama Admin</label>
                <input type="text" name="nama_admin" id="nama_admin" class="form-control" placeholder="Masukkan Nama Admin" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan Username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required>
                    <span class="input-group-text">
                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>
        <p class="text-center mt-3">Sudah punya akun? <a href="index.php" class="text-primary">Login di sini</a></p>
    </div>

    <!-- JavaScript -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Toggle tipe input antara "password" dan "text"
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Ubah ikon mata
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
