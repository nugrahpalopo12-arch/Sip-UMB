<?php
/* =====================================
KONEKSI.PHP
Menghubungkan aplikasi ke database
=====================================
*/

$host = "localhost";
$user = "root";
$pass = ""; // Kosongkan jika XAMPP Anda tidak pakai password
$db   = "perpustakaan";

// Buat koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Set timezone agar sesuai dengan server (WITA)
date_default_timezone_set('Asia/Makassar'); 
?>