<?php
include 'koneksi.php';

// 1. Ambil ID buku yang mau diedit
$id = $_GET['id'];
$ambil_data = mysqli_query($koneksi, "SELECT * FROM tbl_buku WHERE buku_id='$id'");
$buku = mysqli_fetch_assoc($ambil_data);

// 2. Jika tombol Simpan diklik
if(isset($_POST['update'])){
    $judul     = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang'];
    $stok      = $_POST['stok'];

    // Update database
    $query_update = mysqli_query($koneksi, "UPDATE tbl_buku SET 
                    judul_buku = '$judul', 
                    pengarang  = '$pengarang', 
                    stok       = '$stok' 
                    WHERE buku_id = '$id'");

    if($query_update){
        echo "<script>alert('Data buku berhasil diperbarui!'); window.location='admin.php';</script>";
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku - SIP UMB</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container-edit { width: 50%; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        .btn-save { background-color: #2ecc71; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
        .btn-back { background-color: #95a5a6; color: white; text-decoration: none; padding: 10px 20px; border-radius: 4px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container-edit">
        <h2>Edit Informasi Buku</h2>
        <hr>
        <form action="" method="POST">
            <label>Judul Buku</label>
            <input type="text" name="judul_buku" value="<?php echo $buku['judul_buku']; ?>" required>

            <label>Pengarang</label>
            <input type="text" name="pengarang" value="<?php echo $buku['pengarang']; ?>" required>

            <label>Jumlah Stok</label>
            <input type="number" name="stok" value="<?php echo $buku['stok']; ?>" required>

            <div style="margin-top: 20px;">
                <button type="submit" name="update" class="btn-save">Simpan Perubahan</button>
                <a href="admin.php" class="btn-back">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>