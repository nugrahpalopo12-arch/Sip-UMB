<?php
include 'koneksi.php';
session_start();
if(!isset($_SESSION['status'])){ header("location:login.php"); exit(); }

$id = $_GET['id'];
$data_lama = mysqli_query($koneksi, "SELECT * FROM tbl_anggota WHERE anggota_id='$id'");
$d = mysqli_fetch_assoc($data_lama);

if(isset($_POST['update'])){
    $nim  = $_POST['nim_nip'];
    $nama = $_POST['nama_lengkap'];

    $update = mysqli_query($koneksi, "UPDATE tbl_anggota SET nim_nip='$nim', nama_lengkap='$nama' WHERE anggota_id='$id'");

    if($update){
        echo "<script>alert('Data mahasiswa berhasil diperbarui!'); window.location='anggota.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mahasiswa - SIP UMB</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-edit { width: 400px; margin: 50px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-update { background-color: #2ecc71; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-batal { display: block; text-align: center; margin-top: 15px; color: #7f8c8d; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="form-edit">
        <h3>Edit Data Mahasiswa</h3>
        <hr>
        <form action="" method="POST">
            <label>NIM / NIP</label>
            <input type="text" name="nim_nip" value="<?php echo $d['nim_nip']; ?>" required>

            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="<?php echo $d['nama_lengkap']; ?>" required>

            <button type="submit" name="update" class="btn-update">Simpan Perubahan</button>
            <a href="anggota.php" class="btn-batal">Batal & Kembali</a>
        </form>
    </div>
</body>
</html>