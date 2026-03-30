<?php
// --- 1. KEAMANAN (SATPAM) ---
session_start();
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("location:login.php?pesan=belum_login");
    exit();
}
// ----------------------------

include 'koneksi.php';

$pesan_pinjam = "";
$pesan_kembali = "";
$hari_ini = date('Y-m-d');

// --- LOGIC 1: PROSES PEMINJAMAN ---
if (isset($_POST['submit_pinjam'])) {
    $nim_nip = $_POST['nim_nip'];
    $buku_id = $_POST['buku_id'];
    $tgl_kembali_nanti = date('Y-m-d', strtotime('+7 days')); // Pinjam 7 hari

    $cek_buku = mysqli_query($koneksi, "SELECT stok FROM tbl_buku WHERE buku_id = '$buku_id'");
    
    if (mysqli_num_rows($cek_buku) > 0) {
        $data_buku = mysqli_fetch_assoc($cek_buku);
        
        if ($data_buku['stok'] > 0) {
            $cek_anggota = mysqli_query($koneksi, "SELECT anggota_id FROM tbl_anggota WHERE nim_nip = '$nim_nip'");
            
            if (mysqli_num_rows($cek_anggota) > 0) {
                $data_anggota = mysqli_fetch_assoc($cek_anggota);
                $anggota_id = $data_anggota['anggota_id'];

                $query1 = "INSERT INTO tbl_peminjaman (anggota_id, tgl_pinjam, tgl_jatuh_tempo, status_pinjam) 
                           VALUES ('$anggota_id', '$hari_ini', '$tgl_kembali_nanti', 'Dipinjam')";
                
                if(mysqli_query($koneksi, $query1)) {
                    $peminjaman_id = mysqli_insert_id($koneksi);
                    mysqli_query($koneksi, "INSERT INTO tbl_detail_peminjaman (peminjaman_id, buku_id) VALUES ('$peminjaman_id', '$buku_id')");
                    mysqli_query($koneksi, "UPDATE tbl_buku SET stok = stok - 1 WHERE buku_id = '$buku_id'");

                    $pesan_pinjam = "Berhasil! Buku ID $buku_id dipinjam oleh $nim_nip. Kembali tgl $tgl_kembali_nanti.";
                }
            } else {
                $pesan_pinjam = "Gagal: NIM Anggota tidak ditemukan.";
            }
        } else {
            $pesan_pinjam = "Gagal: Stok buku habis.";
        }
    } else {
        $pesan_pinjam = "Gagal: ID Buku tidak ditemukan.";
    }
}

// --- LOGIC 2: PROSES PENGEMBALIAN ---
if (isset($_POST['submit_kembali'])) {
    $buku_id_kembali = $_POST['buku_id_kembali'];
    $biaya_denda_per_hari = 1000;

    $query_cek = "SELECT dp.peminjaman_id, p.tgl_jatuh_tempo, b.judul_buku
                  FROM tbl_detail_peminjaman dp
                  JOIN tbl_peminjaman p ON dp.peminjaman_id = p.peminjaman_id
                  JOIN tbl_buku b ON dp.buku_id = b.buku_id
                  WHERE dp.buku_id = '$buku_id_kembali' AND p.status_pinjam = 'Dipinjam'";
    
    $hasil_cek = mysqli_query($koneksi, $query_cek);

    if (mysqli_num_rows($hasil_cek) > 0) {
        $data = mysqli_fetch_assoc($hasil_cek);
        $id_transaksi = $data['peminjaman_id'];
        $tgl_tempo = $data['tgl_jatuh_tempo'];
        $judul = $data['judul_buku'];

        $tgl_kembali_aktual = date('Y-m-d');
        $denda_msg = "";
        
        if ($tgl_kembali_aktual > $tgl_tempo) {
            $start = new DateTime($tgl_tempo);
            $end = new DateTime($tgl_kembali_aktual);
            $selisih = $end->diff($start);
            $jumlah_hari_telat = $selisih->days;
            $total_denda = $jumlah_hari_telat * $biaya_denda_per_hari;
            $denda_msg = "<br><b>Status: TERLAMBAT $jumlah_hari_telat Hari. Denda: Rp " . number_format($total_denda,0,',','.') . "</b>";
        } else {
            $denda_msg = "<br>Status: Tepat Waktu (Tidak ada denda).";
        }

        mysqli_query($koneksi, "UPDATE tbl_peminjaman SET status_pinjam = 'Selesai' WHERE peminjaman_id = '$id_transaksi'");
        mysqli_query($koneksi, "UPDATE tbl_detail_peminjaman SET tgl_kembali = '$hari_ini' WHERE peminjaman_id = '$id_transaksi'");
        mysqli_query($koneksi, "UPDATE tbl_buku SET stok = stok + 1 WHERE buku_id = '$buku_id_kembali'");

        $pesan_kembali = "Sukses! Buku <b>'$judul'</b> berhasil dikembalikan.$denda_msg";
    } else {
        $pesan_kembali = "Gagal: Buku ID $buku_id_kembali tidak sedang dipinjam (atau ID salah).";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .btn-edit { background-color: #f39c12; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .btn-hapus { background-color: #e74c3c; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .btn-edit:hover, .btn-hapus:hover { opacity: 0.8; }
    </style>
</head>
<body>

    <div class="header">Sistem Informasi Perpustakaan UMB</div>
    
    <div class="wrapper">
        
        <div class="sidebar">
            <h3>Menu</h3>
            <a href="admin.php" style="background-color: #34495e; border-left: 4px solid #3498db;">Dashboard & Sirkulasi</a>
            <a href="anggota.php">Data Anggota</a>
            <a href="buku.php">Data Buku</a>
            <a href="laporan.php">Laporan</a>
            <a href="logout.php" style="margin-top:20px; border-top:1px solid #555;">Logout System</a>
        </div>

        <div class="main-content">
            
            <div class="card">
                <h3>Form Peminjaman Buku</h3>
                <?php if($pesan_pinjam) { 
                    $cls = (strpos($pesan_pinjam, 'Gagal') !== false) ? 'msg-error' : 'msg-success';
                    echo "<div class='message $cls'>$pesan_pinjam</div>"; 
                } ?>
                <form action="admin.php" method="POST">
                    <div class="form-group">
                        <label>ID Anggota (NIM/NIP)</label>
                        <input type="text" name="nim_nip" placeholder="Masukkan NIM Mahasiswa..." required>
                    </div>
                    <div class="form-group">
                        <label>ID Buku</label>
                        <input type="text" name="buku_id" placeholder="Ketik Angka ID Buku..." required>
                    </div>
                    <button type="submit" name="submit_pinjam" class="btn btn-green">Proses Pinjam</button>
                    <button type="reset" class="btn btn-red">Reset</button>
                </form>
            </div>

            <div class="card">
                <h3>Form Pengembalian Buku</h3>
                <?php if($pesan_kembali) { 
                     $cls = (strpos($pesan_kembali, 'Gagal') !== false) ? 'msg-error' : 'msg-success';
                     echo "<div class='message $cls'>$pesan_kembali</div>"; 
                } ?>
                <form action="admin.php" method="POST">
                    <div class="form-group">
                        <label>ID Buku yang Dikembalikan</label>
                        <input type="text" name="buku_id_kembali" placeholder="Scan / Ketik Angka ID Buku..." required>
                    </div>
                    <button type="submit" name="submit_kembali" class="btn btn-blue">Proses Kembali</button>
                </form>
            </div>

            <div class="card">
                <h3>Data Peminjaman Terkini (Status: Dipinjam)</h3>
                <table border="1" style="width:100%; border-collapse:collapse; border:1px solid #ddd;">
                    <thead style="background-color:#f8f9fa;">
                        <tr>
                            <th style="padding:10px;">ID Pinjam</th>
                            <th style="padding:10px;">Peminjam</th>
                            <th style="padding:10px;">Judul Buku</th>
                            <th style="padding:10px;">Tgl Pinjam</th>
                            <th style="padding:10px;">Jatuh Tempo</th>
                            <th style="padding:10px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_tampil = mysqli_query($koneksi, "
                            SELECT p.peminjaman_id, a.nama_lengkap, b.judul_buku, p.tgl_pinjam, p.tgl_jatuh_tempo, p.status_pinjam 
                            FROM tbl_peminjaman p
                            JOIN tbl_anggota a ON p.anggota_id = a.anggota_id
                            JOIN tbl_detail_peminjaman dp ON p.peminjaman_id = dp.peminjaman_id
                            JOIN tbl_buku b ON dp.buku_id = b.buku_id
                            WHERE p.status_pinjam = 'Dipinjam'
                            ORDER BY p.peminjaman_id DESC
                        ");
                        while ($row = mysqli_fetch_assoc($query_tampil)) {
                            echo "<tr>";
                            echo "<td style='padding:10px;'>".$row['peminjaman_id']."</td>";
                            echo "<td style='padding:10px;'>".$row['nama_lengkap']."</td>";
                            echo "<td style='padding:10px;'>".$row['judul_buku']."</td>";
                            echo "<td style='padding:10px;'>".$row['tgl_pinjam']."</td>";
                            $jatuh_tempo = $row['tgl_jatuh_tempo'];
                            if($jatuh_tempo < date('Y-m-d')){
                                echo "<td style='padding:10px; color:red; font-weight:bold;'>$jatuh_tempo (Telat)</td>";
                            } else {
                                echo "<td style='padding:10px;'>$jatuh_tempo</td>";
                            }
                            echo "<td style='padding:10px; color:red;'>".$row['status_pinjam']."</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h3>Manajemen Koleksi Buku (Edit & Hapus)</h3>
                <table border="1" style="width:100%; border-collapse:collapse; border:1px solid #ddd;">
                    <thead style="background-color:#f8f9fa;">
                        <tr>
                            <th style="padding:10px;">ID Buku</th>
                            <th style="padding:10px;">Judul Buku</th>
                            <th style="padding:10px;">Pengarang</th>
                            <th style="padding:10px;">Stok</th>
                            <th style="padding:10px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_buku = mysqli_query($koneksi, "SELECT * FROM tbl_buku ORDER BY buku_id DESC");
                        while ($buku = mysqli_fetch_assoc($query_buku)) {
                            echo "<tr>";
                            echo "<td style='padding:10px; text-align:center;'>".$buku['buku_id']."</td>";
                            echo "<td style='padding:10px;'>".$buku['judul_buku']."</td>";
                            echo "<td style='padding:10px;'>".$buku['pengarang']."</td>";
                            echo "<td style='padding:10px; text-align:center;'>".$buku['stok']."</td>";
                            echo "<td style='padding:10px; text-align:center;'>
                                    <a href='edit.php?id=".$buku['buku_id']."' class='btn-edit'>Edit</a> 
                                    <a href='hapus.php?id=".$buku['buku_id']."' class='btn-hapus' onclick=\"return confirm('Yakin ingin menghapus buku ini?')\">Hapus</a>
                                  </td>";
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