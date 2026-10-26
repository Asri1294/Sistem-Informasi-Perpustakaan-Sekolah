<?php
session_start();
require "koneksi.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($koneksi, "SELECT id, password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($hasil);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau kata sandi salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masuk - Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="kotak">
        <img src="gambar/logo.jpg" alt="Logo Perpustakaan" class="logo">
        <h2>Masuk</h2>
        <?php if ($error) { echo "<div class='galat'>$error</div>"; } ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Kata sandi</label>
            <input type="password" name="password" required>
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>