# 📚 SIP-UMB: Sistem Informasi Perpustakaan
### Universitas Mega Buana Palopo

---

## 📝 Deskripsi Project
**SIP-UMB** adalah platform digital yang dirancang untuk mengotomatisasi proses administrasi di Perpustakaan Universitas Mega Buana. Sistem ini fokus pada efisiensi pengelolaan data buku, data anggota (mahasiswa), serta akurasi transaksi peminjaman dan pengembalian buku.

Project ini dikembangkan sebagai solusi untuk meminimalisir kesalahan manual dan mempercepat pelayanan pustakawan kepada mahasiswa melalui antarmuka web yang sederhana namun fungsional.

## 🚀 Fitur Utama (CRUD & Search)
Sistem ini telah dilengkapi dengan fitur pengelolaan data yang lengkap:

* **🛡️ Autentikasi Admin:** Sistem login menggunakan *Session* PHP untuk mengamankan akses ke dashboard.
* **📖 Sirkulasi & Stok:** * Peminjaman otomatis dengan durasi 7 hari.
    * Pengurangan stok buku otomatis saat dipinjam dan penambahan kembali saat dikembalikan.
    * **Hitung Denda Otomatis:** Sistem mendeteksi keterlambatan dan menghitung denda Rp 1.000/hari.
* **👥 Manajemen Mahasiswa:**
    * Fitur **Pencarian:** Cari mahasiswa berdasarkan Nama atau NIM secara instan.
    * Fitur **Edit & Hapus:** Memudahkan pembersihan data mahasiswa yang sudah tidak aktif atau lulus.
* **📚 Manajemen Koleksi:**
    * Fitur **Edit & Hapus** buku untuk memperbarui judul, pengarang, atau jumlah stok di rak.
* **📊 Monitoring:** Dashboard yang menampilkan tabel transaksi aktif dengan penanda khusus (warna merah) untuk pinjaman yang sudah jatuh tempo.

## 🛠️ Tech Stack
* **Front-End:** HTML5, CSS3 (Custom Design)
* **Back-End:** PHP 8.x
* **Database:** MySQL / MariaDB
* **Server:** XAMPP (Apache)

## 📂 Struktur File Project
```text
perpustakaan/
├── koneksi.php         # Konfigurasi koneksi database
├── login.php           # Halaman masuk admin
├── logout.php          # Halaman keluar sistem
├── admin.php           # Dashboard utama & proses sirkulasi
├── anggota.php         # Daftar & pencarian mahasiswa
├── style.css           # File desain antarmuka
├── edit.php            # Form perubahan data buku
├── hapus.php           # Proses penghapusan buku
├── edit_anggota.php    # Form perubahan data mahasiswa
└── hapus_anggota.php   # Proses penghapusan mahasiswa