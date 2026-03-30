<?php
// --- 1. KODE SATPAM (CEK LOGIN) ---
session_start();
if($_SESSION['status'] != "login"){
    header("location:login.php?pesan=belum_login");
    exit();
}
// ----------------------------------

include 'koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan - Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="header">
        Sistem Informasi Perpustakaan UMB
    </div>

    <div class="wrapper">
        <div class="sidebar">
            <h3>Menu</h3>
            <a href="admin.php">Dashboard & Sirkulasi</a>
            <a href="anggota.php">Data Anggota</a>
            <a href="buku.php">Data Buku</a>
            <a href="laporan.php" style="background-color: #34495e; border-left: 4px solid #3498db;">Laporan</a>
            
            <a href="logout.php" style="margin-top:20px; border-top:1px solid #555;">Logout System</a>
        </div>

        <div class="main-content">
            <div class="card">
                <h3>Laporan Riwayat Peminjaman</h3>
                <p>Berikut adalah data gabungan dari tabel peminjaman, buku, dan anggota.</p>
                <br>
                
                <table border="1" style="width:100%; border-collapse:collapse; border:1px solid #ddd;">
                    <thead style="background-color:#f8f9fa;">
                        <tr>
                            <th style="padding:10px;">No</th>
                            <th style="padding:10px;">Peminjam</th>
                            <th style="padding:10px;">Buku</th>
                            <th style="padding:10px;">Tgl Pinjam</th>
                            <th style="padding:10px;">Jatuh Tempo</th>
                            <th style="padding:10px;">Tgl Kembali</th>
                            <th style="padding:10px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query JOIN 3 Tabel untuk menampilkan nama peminjam dan judul buku
                        // Kita ambil semua data (baik yang Dipinjam maupun Selesai)
                        $sql = "SELECT p.peminjaman_id, a.nama_lengkap, b.judul_buku, p.tgl_pinjam, p.tgl_jatuh_tempo, p.status_pinjam, dp.tgl_kembali
                                FROM tbl_peminjaman p
                                JOIN tbl_anggota a ON p.anggota_id = a.anggota_id
                                JOIN tbl_detail_peminjaman dp ON p.peminjaman_id = dp.peminjaman_id
                                JOIN tbl_buku b ON dp.buku_id = b.buku_id
                                ORDER BY p.peminjaman_id DESC";
                        
                        $query = mysqli_query($koneksi, $sql);
                        $no = 1;

                        while ($row = mysqli_fetch_assoc($query)) {
                            echo "<tr>";
                            echo "<td style='padding:10px;'>".$no++."</td>";
                            echo "<td style='padding:10px;'>".$row['nama_lengkap']."</td>";
                            echo "<td style='padding:10px;'>".$row['judul_buku']."</td>";
                            echo "<td style='padding:10px;'>".$row['tgl_pinjam']."</td>";
                            echo "<td style='padding:10px;'>".$row['tgl_jatuh_tempo']."</td>";
                            
                            // Tampilkan tanggal kembali (jika belum kembali, tampilkan strip -)
                            $tgl_kembali = $row['tgl_kembali'];
                            if($tgl_kembali == NULL || $tgl_kembali == '0000-00-00') {
                                echo "<td style='padding:10px; text-align:center;'>-</td>";
                            } else {
                                echo "<td style='padding:10px;'>".$tgl_kembali."</td>";
                            }
                            
                            // Pewarnaan Status
                            $status = $row['status_pinjam'];
                            if ($status == 'Dipinjam') {
                                echo "<td style='padding:10px; color:red; font-weight:bold;'>Dipinjam</td>";
                            } else {
                                echo "<td style='padding:10px; color:green; font-weight:bold;'>Selesai</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>