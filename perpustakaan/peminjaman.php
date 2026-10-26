<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require "koneksi.php";

// HAPUS
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM peminjaman WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: peminjaman.php");
    exit;
}

// TAMBAH atau UBAH
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = (int) $_POST['id'];
    $nama    = trim($_POST['nama_peminjam']);
    $buku_id = (int) $_POST['buku_id'];
    $pinjam  = $_POST['tanggal_pinjam'];
    $kembali = $_POST['tanggal_kembali'] !== "" ? $_POST['tanggal_kembali'] : null;

    if ($nama !== "" && $buku_id > 0 && $pinjam !== "") {
        if ($id > 0) {
            $stmt = mysqli_prepare($koneksi, "UPDATE peminjaman SET nama_peminjam = ?, buku_id = ?, tanggal_pinjam = ?, tanggal_kembali = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sissi", $nama, $buku_id, $pinjam, $kembali, $id);
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO peminjaman (nama_peminjam, buku_id, tanggal_pinjam, tanggal_kembali) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "siss", $nama, $buku_id, $pinjam, $kembali);
        }
        mysqli_stmt_execute($stmt);
        header("Location: peminjaman.php");
        exit;
    }
}

// DATA UNTUK FORM UBAH
$ubah = null;
if (isset($_GET['ubah'])) {
    $id = (int) $_GET['ubah'];
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM peminjaman WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $ubah = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

// DAFTAR PEMINJAMAN
$daftar = mysqli_query($koneksi, "SELECT peminjaman.*, buku.judul
    FROM peminjaman JOIN buku ON peminjaman.buku_id = buku.id
    ORDER BY peminjaman.id DESC");

// PILIHAN BUKU
$buku = mysqli_query($koneksi, "SELECT id, judul FROM buku ORDER BY judul ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <main class="kartu">

    <h1>Daftar Peminjaman</h1>

    <h3><?php echo $ubah ? "Ubah Peminjaman" : "Tambah Peminjaman"; ?></h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $ubah ? $ubah['id'] : 0; ?>">
        <input type="text" name="nama_peminjam" placeholder="Nama peminjam" required
               value="<?php echo $ubah ? htmlspecialchars($ubah['nama_peminjam']) : ''; ?>">
        <select name="buku_id" required>
            <option value="">-- Pilih buku --</option>
            <?php while ($b = mysqli_fetch_assoc($buku)) { ?>
                <option value="<?php echo $b['id']; ?>"
                    <?php echo ($ubah && $ubah['buku_id'] == $b['id']) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($b['judul']); ?>
                </option>
            <?php } ?>
        </select>
        <label>Tanggal pinjam</label>
        <input type="date" name="tanggal_pinjam" required
               value="<?php echo $ubah ? $ubah['tanggal_pinjam'] : date('Y-m-d'); ?>">
        <label>Tanggal kembali</label>
        <input type="date" name="tanggal_kembali"
               value="<?php echo $ubah ? $ubah['tanggal_kembali'] : ''; ?>">
        <button type="submit"><?php echo $ubah ? "Simpan Perubahan" : "Tambah"; ?></button>
        <?php if ($ubah) { echo "<a href='peminjaman.php'>Batal</a>"; } ?>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Peminjam</th>
            <th>Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        if (mysqli_num_rows($daftar) === 0) {
            echo "<tr><td colspan='6'>Belum ada data.</td></tr>";
        }
        while ($p = mysqli_fetch_assoc($daftar)) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($p['nama_peminjam']); ?></td>
            <td><?php echo htmlspecialchars($p['judul']); ?></td>
            <td><?php echo $p['tanggal_pinjam']; ?></td>
            <td><?php echo $p['tanggal_kembali'] ?? 'Belum dikembalikan'; ?></td>
            <td>
                <a href="peminjaman.php?ubah=<?php echo $p['id']; ?>">Ubah</a>
                <a href="peminjaman.php?hapus=<?php echo $p['id']; ?>"
                   onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    </main>
</body>
</html>