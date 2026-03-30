<?php 
include 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Hapus data dari database
$query = mysqli_query($koneksi, "DELETE FROM tbl_buku WHERE buku_id='$id'");

if($query){
    // Jika berhasil, balik ke admin.php dengan pesan sukses
    echo "<script>alert('Buku berhasil dihapus!'); window.location='admin.php';</script>";
} else {
    // Jika gagal, tampilkan error
    echo "Gagal menghapus: " . mysqli_error($koneksi);
}
?>