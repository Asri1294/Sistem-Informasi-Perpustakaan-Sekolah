<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require "koneksi.php";

$pesan = "";

// HAPUS
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    try {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM kategori WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        header("Location: kategori.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        $pesan = "Kategori tidak bisa dihapus karena masih dipakai oleh buku.";
    }
}

// TAMBAH atau UBAH
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $id = (int) $_POST['id'];
    if ($nama !== "") {
        if ($id > 0) {
            $stmt = mysqli_prepare($koneksi, "UPDATE kategori SET nama = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $nama, $id);
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO kategori (nama) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $nama);
        }
        mysqli_stmt_execute($stmt);
        header("Location: kategori.php");
        exit;
    }
}

// AMBIL DATA UNTUK FORM UBAH
$ubah = null;
if (isset($_GET['ubah'])) {
    $id = (int) $_GET['ubah'];
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM kategori WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $ubah = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

// DAFTAR KATEGORI
$daftar = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <main class="kartu">

    <h1>Daftar Kategori</h1>

    <?php if ($pesan) { echo "<div class='galat'>$pesan</div>"; } ?>

    <h3><?php echo $ubah ? "Ubah Kategori" : "Tambah Kategori"; ?></h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $ubah ? $ubah['id'] : 0; ?>">
        <input type="text" name="nama" placeholder="Nama kategori" required
               value="<?php echo $ubah ? htmlspecialchars($ubah['nama']) : ''; ?>">
        <button type="submit"><?php echo $ubah ? "Simpan Perubahan" : "Tambah"; ?></button>
        <?php if ($ubah) { echo "<a href='kategori.php'>Batal</a>"; } ?>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($baris = mysqli_fetch_assoc($daftar)) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($baris['nama']); ?></td>
            <td>
                <a href="kategori.php?ubah=<?php echo $baris['id']; ?>">Ubah</a>
                <a href="kategori.php?hapus=<?php echo $baris['id']; ?>"
                   onclick="return confirm('Yakin hapus kategori ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    </main>
</body>
</html>