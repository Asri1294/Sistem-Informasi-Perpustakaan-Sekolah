# Sistem Informasi Perpustakaan Sekolah

**Pretest — Game Lab Internship**

Aplikasi web sederhana untuk membantu petugas perpustakaan sekolah mengelola data buku, kategori buku, dan peminjaman. Proyek ini dikembangkan menggunakan PHP, MySQL, HTML, CSS, dan JavaScript tanpa framework sebagai bagian dari pretest pelatihan.

## Deskripsi Proyek

Sistem Informasi Perpustakaan Sekolah merupakan aplikasi berbasis web yang menyediakan fitur pengelolaan data perpustakaan melalui halaman admin. Aplikasi ini dibuat sebagai latihan awal pengembangan web dengan PHP dan MySQL.

## Teknologi yang Digunakan

- **Bahasa pemrograman:** PHP, JavaScript
- **Database:** MySQL
- **Frontend:** HTML, CSS
- **Lingkungan pengembangan lokal:** XAMPP
- **Editor kode:** Visual Studio Code

## Fitur

### 1. Autentikasi
- Login dan logout admin.
- Penyimpanan kata sandi dalam bentuk hash.
- Proteksi sesi untuk membatasi akses ke dashboard.

### 2. Dashboard
- Halaman utama admin setelah login.

### 3. Pengelolaan Kategori
- Menambahkan kategori buku.
- Melihat daftar kategori.
- Mengubah data kategori.
- Menghapus kategori.

### 4. Pengelolaan Buku
- Menambahkan data buku.
- Melihat daftar buku.
- Mengubah data buku.
- Menghapus data buku.
- Mencari buku.
- Mengatur pagination dan jumlah entri per halaman.

### 5. Pengelolaan Peminjaman
- Menambahkan data peminjaman.
- Melihat daftar peminjaman.
- Mengubah data peminjaman.
- Menghapus data peminjaman.

## Persyaratan

Sebelum menjalankan aplikasi, pastikan perangkat telah memiliki:

- XAMPP dengan Apache dan MySQL.
- Peramban web.
- Editor kode, seperti Visual Studio Code.

## Cara Menjalankan Aplikasi

**1. Jalankan XAMPP**

Buka XAMPP Control Panel, lalu aktifkan Apache dan MySQL.

**2. Tempatkan folder proyek**

Salin folder `perpustakaan` ke direktori:

`C:\xampp\htdocs\`

**3. Buat database**

Buka `http://localhost/phpmyadmin` melalui peramban.

Pilih **New**, kemudian buat database dengan nama:

`perpustakaan`

**4. Impor database**

Pilih database `perpustakaan`, buka tab **Import**, pilih berkas `perpustakaan.sql` dari folder `database`, lalu jalankan proses impor.

**5. Periksa konfigurasi koneksi**

Pastikan pengaturan koneksi database pada `koneksi.php` sesuai dengan konfigurasi MySQL lokal.

**6. Buka aplikasi**

Akses alamat berikut melalui peramban:

`http://localhost/perpustakaan/`

## Akun Login

Gunakan akun berikut untuk mengakses halaman admin pada instalasi awal:

- **Username:** `admin`
- **Password:** `ad********`


## Struktur Proyek

```text
📦perpustakaan
 ┣ 📂db_perpus
 ┃ ┗ 📜perpustakaan.sql
 ┣ 📂gambar
 ┃ ┣ 📜bg.jpg
 ┃ ┗ 📜logo.jpg
 ┣ 📜buku.php
 ┣ 📜dashboard.php
 ┣ 📜index.php
 ┣ 📜kategori.php
 ┣ 📜koneksi.php
 ┣ 📜login.php
 ┣ 📜logout.php
 ┣ 📜menu.php
 ┣ 📜peminjaman.php
 ┣ 📜README.txt
 ┗ 📜style.css
```


## Batasan dan Rencana Pengembangan

Versi awal aplikasi masih memiliki beberapa keterbatasan yang dapat dijadikan bahan pengembangan berikutnya:

- Data peminjam masih dicatat berdasarkan nama.
- Belum tersedia pengelolaan data anggota perpustakaan menggunakan ID anggota.
- Penambahan gambar sampul buku dapat ditambahkan sebagai pengembangan fitur.
- Fitur pencarian pada dashboard dapat ditambahkan untuk mempermudah akses informasi.

Pengembangan tersebut merupakan rencana lanjutan dan belum dinyatakan sebagai fitur yang sudah tersedia.

## Tujuan Pembelajaran

Proyek ini menjadi sarana latihan untuk memahami dasar pengembangan aplikasi web menggunakan PHP dan MySQL, termasuk autentikasi admin, pengelolaan data melalui operasi CRUD (*Create, Read, Update, Delete*), koneksi database, dan penyusunan antarmuka web.

## License

Dibuat oleh: **Asria Rusking**

Program: **Game Lab Internship — Pretest**

