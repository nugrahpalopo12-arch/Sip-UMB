<?php
session_start();
include 'koneksi.php';

$pesan = "";

// Jika tombol login ditekan
if (isset($_POST['cek_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username dan password di database
    // Kita filter agar hanya role 'admin' atau 'pustakawan' yang bisa masuk
    $login = mysqli_query($koneksi, "SELECT * FROM tbl_anggota WHERE nim_nip='$username' AND password='$password'");
    $cek = mysqli_num_rows($login);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($login);

        // Cek Role: Hanya ADMIN yang boleh masuk dashboard
        if ($data['role'] == "admin") {
            // Buat Session
            $_SESSION['username'] = $username;
            $_SESSION['nama'] = $data['nama_lengkap'];
            $_SESSION['status'] = "login";
            
            header("location:admin.php"); // Alihkan ke halaman admin
        } else {
            $pesan = "Maaf, akun Anda bukan Admin. Silakan akses halaman Katalog.";
        }
    } else {
        $pesan = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Petugas - Perpustakaan UMB</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #2c3e50; /* Latar belakang biru tua */
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 400px;
            text-align: center;
        }
        .login-box h2 { margin-bottom: 20px; color: #333; }
        .login-box input { margin-bottom: 15px; }
        .logo { font-size: 50px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>

    <div class="login-box">
        <span class="logo">📚</span>
        <h2>Login Admin</h2>
        
        <?php if($pesan != "") { echo "<div class='message msg-error'>$pesan</div>"; } ?>
        
        <?php 
        if(isset($_GET['pesan'])){
            if($_GET['pesan'] == "belum_login"){
                echo "<div class='message msg-error'>Anda harus login untuk mengakses halaman Admin!</div>";
            }
            else if($_GET['pesan'] == "logout"){
                echo "<div class='message msg-success'>Berhasil Logout.</div>";
            }
        }
        ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username / NIP Admin" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="cek_login" class="btn btn-blue" style="width:100%;">Masuk Sistem</button>
        </form>
        <br>
        <a href="index.php" style="text-decoration:none; color:#555;">&larr; Kembali ke Katalog Buku</a>
    </div>

</body>
</html>