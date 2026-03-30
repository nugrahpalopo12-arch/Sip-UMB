<?php 
include 'koneksi.php';
session_start();
if(!isset($_SESSION['status'])){ header("location:login.php"); exit(); }

$id = $_GET['id'];

// Proses Hapus
$query = mysqli_query($koneksi, "DELETE FROM tbl_anggota WHERE anggota_id='$id'");

if($query){
    echo "<script>alert('Data mahasiswa berhasil dihapus!'); window.location='anggota.php';</script>";
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>