<header class="atas">
    <button type="button" class="garis" id="tombolMenu" aria-label="Buka atau tutup menu">&#9776;</button>
    <img src="gambar/logo.jpg" alt="Logo" class="logo-header">
    <span class="judul">Perpustakaan Sekolah</span>

    <div class="profil">
        <button type="button" class="profil-tombol" id="tombolProfil">
            <span class="avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></span>
            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </button>
        <div class="profil-isi" id="profilIsi">
            <div class="profil-peran">Petugas Perpustakaan</div>
            <a href="logout.php">Keluar</a>
        </div>
    </div>
</header>

<nav class="samping">
    <a href="dashboard.php">Dashboard</a>
    <a href="buku.php">Buku</a>
    <a href="kategori.php">Kategori</a>
    <a href="peminjaman.php">Peminjaman</a>
    <a href="logout.php">Keluar</a>
</nav>

<script>
    // tombol tiga garis: buka atau tutup sidebar
    document.getElementById('tombolMenu').addEventListener('click', function () {
        document.body.classList.toggle('menu-tutup');
    });

    // profil: buka atau tutup kotak kecil
    var tombolProfil = document.getElementById('tombolProfil');
    var profilIsi = document.getElementById('profilIsi');
    tombolProfil.addEventListener('click', function (e) {
        e.stopPropagation();
        profilIsi.classList.toggle('terbuka');
    });
    // klik di mana saja di luar kotak: kotak menutup
    document.addEventListener('click', function () {
        profilIsi.classList.remove('terbuka');
    });
</script>