-- SQL Dump untuk Sistem Informasi Perpustakaan (SIP) UMB
-- Versi Database: 1.0
-- Host: localhost

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- 1. Tabel Admin (Akses Login Pustakawan)
-- --------------------------------------------------------

CREATE TABLE `tbl_admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Login: Username = admin | Password = perpusUMB2025
INSERT INTO `tbl_admin` (`username`, `password`) VALUES
('admin', 'perpusUMB2025');

-- --------------------------------------------------------
-- 2. Tabel Anggota (Mahasiswa UMB)
-- --------------------------------------------------------

CREATE TABLE `tbl_anggota` (
  `anggota_id` int(11) NOT NULL AUTO_INCREMENT,
  `nim_nip` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  PRIMARY KEY (`anggota_id`),
  UNIQUE KEY `nim_nip` (`nim_nip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 3. Tabel Buku (Koleksi Perpustakaan)
-- --------------------------------------------------------

CREATE TABLE `tbl_buku` (
  `buku_id` int(11) NOT NULL AUTO_INCREMENT,
  `judul_buku` varchar(255) NOT NULL,
  `pengarang` varchar(100) DEFAULT NULL,
  `lokasi_rak` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  PRIMARY KEY (`buku_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh Data Buku Awal
INSERT INTO `tbl_buku` (`judul_buku`, `pengarang`, `lokasi_rak`, `stok`) VALUES
('Pengantar Sistem Informasi', 'Jogiyanto Hartono', 'Rak A1', 5),
('Dasar-Dasar Pemrograman', 'Rinaldi Munir', 'Rak A2', 4),
('Manajemen Perpustakaan Modern', 'Sutarno NS', 'Rak B1', 3),
('Jaringan Komputer', 'Andrew S. Tanenbaum', 'Rak D1', 7),
('Algoritma dan Struktur Data', 'Dony Ariyus', 'Rak E2', 6);

-- --------------------------------------------------------
-- 4. Tabel Peminjaman (Transaksi Sirkulasi)
-- --------------------------------------------------------

CREATE TABLE `tbl_peminjaman` (
  `peminjaman_id` int(11) NOT NULL AUTO_INCREMENT,
  `anggota_id` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_jatuh_tempo` date NOT NULL,
  `status_pinjam` enum('Dipinjam','Selesai') DEFAULT 'Dipinjam',
  PRIMARY KEY (`peminjaman_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 5. Tabel Detail Peminjaman (Riwayat Buku)
-- --------------------------------------------------------

CREATE TABLE `tbl_detail_peminjaman` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `peminjaman_id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `tgl_kembali` date DEFAULT NULL,
  PRIMARY KEY (`detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;