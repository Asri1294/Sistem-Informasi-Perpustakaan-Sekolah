<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require "koneksi.php";

$hasil = mysqli_query($koneksi, "SELECT buku.*, kategori.nama AS nama_kategori
    FROM buku LEFT JOIN kategori ON buku.kategori_id = kategori.id
    ORDER BY buku.id DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
   <?php include "menu.php"; ?>
   <main class="kartu">

    <h1>Dashboard</h1>
    <p>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

    <h3>10 Buku Terbaru</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun</th>
            <th>Kategori</th>
        </tr>
        <?php
        $no = 1;
        if (mysqli_num_rows($hasil) === 0) {
            echo "<tr><td colspan='5'>Belum ada data.</td></tr>";
        }
        while ($b = mysqli_fetch_assoc($hasil)) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($b['judul']); ?></td>
            <td><?php echo htmlspecialchars($b['penulis']); ?></td>
            <td><?php echo $b['tahun']; ?></td>
            <td><?php echo htmlspecialchars($b['nama_kategori'] ?? '-'); ?></td>
        </tr>
        <?php } ?>
    </table>
    </main>
</body>
</html>