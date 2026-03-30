<?php
include 'koneksi.php';
session_start();
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:login.php?pesan=belum_login");
    exit();
}

// --- LOGIKA PENCARIAN ---
$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = $_GET['cari'];
    $query_anggota = mysqli_query($koneksi, "SELECT * FROM tbl_anggota WHERE nama_lengkap LIKE '%$keyword%' OR nim_nip LIKE '%$keyword%' ORDER BY anggota_id DESC");
} else {
    $query_anggota = mysqli_query($koneksi, "SELECT * FROM tbl_anggota ORDER BY anggota_id DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Anggota - SIP UMB</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .search-container { margin-bottom: 20px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .search-container input { padding: 8px; width: 250px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-cari { background-color: #3498db; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
        .btn-edit { background-color: #f39c12; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .btn-hapus { background-color: #e74c3c; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">Manajemen Data Anggota Perpustakaan</div>
    <div class="wrapper">
        <div class="sidebar">
            <h3>Menu</h3>
            <a href="admin.php">Dashboard & Sirkulasi</a>
            <a href="anggota.php" style="background-color: #34495e; border-left: 4px solid #3498db;">Data Anggota</a>
            <a href="buku.php">Data Buku</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="main-content">
            <div class="search-container">
                <form action="anggota.php" method="GET">
                    <input type="text" name="cari" placeholder="Cari Nama atau NIM..." value="<?php echo $keyword; ?>">
                    <button type="submit" class="btn-cari">Cari Mahasiswa</button>
                    <?php if($keyword != ""): ?>
                        <a href="anggota.php" style="margin-left:10px; font-size:14px; color:#e74c3c;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="card">
                <h3>Daftar Mahasiswa (Anggota)</h3>
                <table border="1" style="width:100%; border-collapse:collapse; border:1px solid #ddd;">
                    <thead style="background-color:#f8f9fa;">
                        <tr>
                            <th style="padding:10px;">ID</th>
                            <th style="padding:10px;">NIM / NIP</th>
                            <th style="padding:10px;">Nama Lengkap</th>
                            <th style="padding:10px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($query_anggota) > 0){
                            while ($row = mysqli_fetch_assoc($query_anggota)) { ?>
                            <tr>
                                <td style="padding:10px; text-align:center;"><?php echo $row['anggota_id']; ?></td>
                                <td style="padding:10px;"><?php echo $row['nim_nip']; ?></td>
                                <td style="padding:10px;"><?php echo $row['nama_lengkap']; ?></td>
                                <td style="padding:10px; text-align:center;">
                                    <a href="edit_anggota.php?id=<?php echo $row['anggota_id']; ?>" class="btn-edit">Edit</a>
                                    <a href="hapus_anggota.php?id=<?php echo $row['anggota_id']; ?>" class="btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini? (Data yang dihapus tidak bisa dikembalikan)')">Hapus</a>
                                </td>
                            </tr>
                        <?php } 
                        } else {
                            echo "<tr><td colspan='4' style='padding:20px; text-align:center;'>Data tidak ditemukan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>