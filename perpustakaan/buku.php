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
        $stmt = mysqli_prepare($koneksi, "DELETE FROM buku WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        header("Location: buku.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        $pesan = "Buku tidak bisa dihapus karena masih tercatat di peminjaman.";
    }
}

// TAMBAH atau UBAH
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int) $_POST['id'];
    $judul       = trim($_POST['judul']);
    $penulis     = trim($_POST['penulis']);
    $penerbit    = trim($_POST['penerbit']);
    $tahun       = (int) $_POST['tahun'];
    $isbn        = trim($_POST['isbn']);
    $kategori_id = (int) $_POST['kategori_id'];

    if ($judul !== "") {
        if ($id > 0) {
            $stmt = mysqli_prepare($koneksi, "UPDATE buku SET judul = ?, penulis = ?, penerbit = ?, tahun = ?, isbn = ?, kategori_id = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sssisii", $judul, $penulis, $penerbit, $tahun, $isbn, $kategori_id, $id);
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO buku (judul, penulis, penerbit, tahun, isbn, kategori_id) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssisi", $judul, $penulis, $penerbit, $tahun, $isbn, $kategori_id);
        }
        mysqli_stmt_execute($stmt);
        header("Location: buku.php");
        exit;
    }
}

// DATA UNTUK FORM UBAH
$ubah = null;
if (isset($_GET['ubah'])) {
    $id = (int) $_GET['ubah'];
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM buku WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $ubah = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

// PENCARIAN DAN PAGINATION
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : "";
$halaman = isset($_GET['halaman']) ? max(1, (int) $_GET['halaman']) : 1;
$pilihan = [5, 10, 25];
$per_halaman = (isset($_GET['tampil']) && in_array((int) $_GET['tampil'], $pilihan)) ? (int) $_GET['tampil'] : 5;
$offset = ($halaman - 1) * $per_halaman;
$kata = "%" . $cari . "%";

// hitung total data
$stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS total FROM buku WHERE judul LIKE ? OR penulis LIKE ?");
mysqli_stmt_bind_param($stmt, "ss", $kata, $kata);
mysqli_stmt_execute($stmt);
$total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
$total_halaman = max(1, ceil($total / $per_halaman));

// ambil data satu halaman
$stmt = mysqli_prepare($koneksi, "SELECT buku.*, kategori.nama AS nama_kategori
    FROM buku LEFT JOIN kategori ON buku.kategori_id = kategori.id
    WHERE buku.judul LIKE ? OR buku.penulis LIKE ?
    ORDER BY buku.id ASC LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($stmt, "ssii", $kata, $kata, $per_halaman, $offset);
mysqli_stmt_execute($stmt);
$daftar = mysqli_stmt_get_result($stmt);

// daftar kategori untuk pilihan
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "menu.php"; ?>
    <main class="kartu">

    <h1>Daftar Buku</h1>

    <?php if ($pesan) { echo "<div class='galat'>$pesan</div>"; } ?>

    <h3><?php echo $ubah ? "Ubah Buku" : "Tambah Buku"; ?></h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $ubah ? $ubah['id'] : 0; ?>">
        <input type="text" name="judul" placeholder="Judul" required
               value="<?php echo $ubah ? htmlspecialchars($ubah['judul']) : ''; ?>">
        <input type="text" name="penulis" placeholder="Penulis"
               value="<?php echo $ubah ? htmlspecialchars($ubah['penulis']) : ''; ?>">
        <input type="text" name="penerbit" placeholder="Penerbit"
               value="<?php echo $ubah ? htmlspecialchars($ubah['penerbit']) : ''; ?>">
        <input type="number" name="tahun" placeholder="Tahun" min="1000" max="2100"
               value="<?php echo $ubah ? $ubah['tahun'] : ''; ?>">
        <input type="text" name="isbn" placeholder="ISBN"
               value="<?php echo $ubah ? htmlspecialchars($ubah['isbn']) : ''; ?>">
        <select name="kategori_id" required>
            <option value="">-- Pilih kategori --</option>
            <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                <option value="<?php echo $k['id']; ?>"
                    <?php echo ($ubah && $ubah['kategori_id'] == $k['id']) ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($k['nama']); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit"><?php echo $ubah ? "Simpan Perubahan" : "Tambah"; ?></button>
        <?php if ($ubah) { echo "<a href='buku.php'>Batal</a>"; } ?>
    </form>

    <h3>Cari Buku</h3>
    <form method="GET">
        <input type="text" name="cari" placeholder="Cari judul atau penulis"
               value="<?php echo htmlspecialchars($cari); ?>">
        <select name="tampil" onchange="this.form.submit()">
            <?php foreach ($pilihan as $p) { ?>
                <option value="<?php echo $p; ?>" <?php echo $p == $per_halaman ? 'selected' : ''; ?>>
                    <?php echo $p; ?> entri
                </option>
            <?php } ?>
        </select>
        <button type="submit">Cari</button>
        <?php if ($cari !== "") { echo "<a href='buku.php'>Hapus pencarian</a>"; } ?>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>ISBN</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = $offset + 1;
        if (mysqli_num_rows($daftar) === 0) {
            echo "<tr><td colspan='8'>Belum ada data.</td></tr>";
        }
        while ($b = mysqli_fetch_assoc($daftar)) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($b['judul']); ?></td>
            <td><?php echo htmlspecialchars($b['penulis']); ?></td>
            <td><?php echo htmlspecialchars($b['penerbit']); ?></td>
            <td><?php echo $b['tahun']; ?></td>
            <td><?php echo htmlspecialchars($b['isbn'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($b['nama_kategori'] ?? '-'); ?></td>
            <td>
                <a href="buku.php?ubah=<?php echo $b['id']; ?>">Ubah</a>
                <a href="buku.php?hapus=<?php echo $b['id']; ?>"
                   onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="halaman" style="margin-top:15px;">
        Halaman:
        <?php for ($i = 1; $i <= $total_halaman; $i++) { ?>
            <a class="<?php echo $i == $halaman ? 'aktif' : ''; ?>"
               href="buku.php?halaman=<?php echo $i; ?>&cari=<?php echo urlencode($cari); ?>&tampil=<?php echo $per_halaman; ?>"><?php echo $i; ?></a>
        <?php } ?>
    </div>
    </main>
</body>
</html>