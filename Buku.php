<?php
// --- 1. KODE KEAMANAN (SATPAM) ---
session_start();
if($_SESSION['status'] != "login"){
    header("location:login.php?pesan=belum_login");
    exit();
}
// ---------------------------------

include 'koneksi.php';

// --- LOGIC TAMBAH BUKU (DENGAN PENANGANAN ERROR) ---
$pesan = "";
$tipe_pesan = "";

if (isset($_POST['simpan_buku'])) {
    $judul = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang'];
    $lokasi = $_POST['lokasi_rak'];
    $stok = $_POST['stok'];

    try {
        $sql = "INSERT INTO tbl_buku (judul_buku, pengarang, lokasi_rak, stok) 
                VALUES ('$judul', '$pengarang', '$lokasi', '$stok')";
        
        if (mysqli_query($koneksi, $sql)) {
            $pesan = "Berhasil! Buku '$judul' telah ditambahkan.";
            $tipe_pesan = "msg-success";
        }
    } catch (mysqli_sql_exception $e) {
        $pesan = "Terjadi Error: " . $e->getMessage();
        $tipe_pesan = "msg-error";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Buku - Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="header">Sistem Informasi Perpustakaan UMB</div>

    <div class="wrapper">
        
        <div class="sidebar">
            <h3>Menu</h3>
            <a href="admin.php">Dashboard & Sirkulasi</a>
            <a href="anggota.php">Data Anggota</a>
            <a href="buku.php" style="background-color: #34495e; border-left: 4px solid #3498db;">Data Buku</a>
            <a href="laporan.php">Laporan</a>
            
            <a href="logout.php" style="margin-top:20px; border-top:1px solid #555;">Logout System</a>
        </div>
        <div class="main-content">
            
            <div class="card">
                <h3>Tambah Buku Baru</h3>
                
                <?php if($pesan){ echo "<div class='message $tipe_pesan'>$pesan</div>"; } ?>

                <form action="buku.php" method="POST">
                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input type="text" name="judul_buku" placeholder="Judul Lengkap Buku" required>
                    </div>
                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang" placeholder="Nama Pengarang" required>
                    </div>
                    <div class="form-group">
                        <label>Lokasi Rak</label>
                        <input type="text" name="lokasi_rak" placeholder="Contoh: A-01" required>
                    </div>
                    <div class="form-group">
                        <label>Stok Awal</label>
                        <input type="number" name="stok" placeholder="Jumlah Buku" required>
                    </div>
                    <button type="submit" name="simpan_buku" class="btn btn-green">Simpan Buku</button>
                </form>
            </div>

            <div class="card">
                <h3>Daftar Koleksi Buku</h3>
                <table border="1" style="width:100%; border-collapse:collapse; border:1px solid #ddd;">
                    <thead style="background-color:#f8f9fa;">
                        <tr>
                            <th style="padding:10px;">ID</th>
                            <th style="padding:10px;">Judul</th>
                            <th style="padding:10px;">Pengarang</th>
                            <th style="padding:10px;">Rak</th>
                            <th style="padding:10px;">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM tbl_buku ORDER BY buku_id DESC");
                        while ($row = mysqli_fetch_assoc($query)) {
                            echo "<tr>";
                            echo "<td style='padding:10px;'>".$row['buku_id']."</td>";
                            echo "<td style='padding:10px;'>".$row['judul_buku']."</td>";
                            echo "<td style='padding:10px;'>".$row['pengarang']."</td>";
                            echo "<td style='padding:10px;'>".$row['lokasi_rak']."</td>";
                            
                            // Warna stok biar menarik (Merah jika 0)
                            $stok = $row['stok'];
                            $warna_stok = ($stok == 0) ? 'color:red; font-weight:bold;' : 'color:black;';
                            echo "<td style='padding:10px; $warna_stok'>".$stok."</td>";
                            
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div> </div> </body>
</html>