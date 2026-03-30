<?php
// ==========================================================
// INDEX.PHP (Sisi Mahasiswa / Katalog Publik)
// ==========================================================
include 'koneksi.php'; // 1. Hubungkan ke database
$result = null;
$message = "";
$keyword = "";

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    if (!empty($keyword)) {
        $safe_keyword = mysqli_real_escape_string($koneksi, $keyword);
        $sql = "SELECT * FROM tbl_buku 
                WHERE judul_buku LIKE '%$safe_keyword%' 
                OR pengarang LIKE '%$safe_keyword%'
                ORDER BY judul_buku ASC";
        $result = mysqli_query($koneksi, $sql);
        if (mysqli_num_rows($result) == 0) {
            $message = "Buku dengan keyword '" . htmlspecialchars($keyword) . "' tidak ditemukan.";
        }
    } else {
        $message = "Silakan masukkan kata kunci (judul atau pengarang).";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Perpustakaan UMB (OPAC)</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; margin: 20px; background-color: #f4f7f6; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        h2 { color: #333; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 10px; width: 300px; border: 1px solid #ddd; border-radius: 4px; }
        input[type="submit"] { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        input[type="submit"]:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f8f8; }
        tr:nth-child(even) { background-color: #fdfdfd; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📚 Pencarian Katalog Buku (OPAC)</h2>
        <p>Lihat ketersediaan buku sebelum datang ke perpustakaan.</p>
        <form action="index.php" method="GET">
            <input type="text" name="keyword" placeholder="Ketik judul atau pengarang..." value="<?php echo htmlspecialchars($keyword); ?>">
            <input type="submit" value="Cari Buku">
        </form>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Lokasi Rak</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['judul_buku']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pengarang']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['lokasi_rak']) . "</td>";
                        if($row['stok'] > 0) {
                            echo "<td><strong>Tersedia (" . $row['stok'] . ")</strong></td>";
                        } else {
                            echo "<td style='color: red;'>Sedang Dipinjam (0)</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>" . $message . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php mysqli_close($koneksi); ?>
</body>
</html>