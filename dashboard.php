<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex">
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
        }
    </style>

<?php
include 'resource/sidebar.php';
include 'resource/header.php';
?>
    
    <div class="flex-1 p-4 bg-gray-100">
    <?php
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }


echo "Selamat datang, " . $_SESSION['username'] . "!<br>";
echo "Peran Anda: " . $_SESSION['role'] . "<br><br>";

if ($_SESSION['role'] == 'admin') {
    echo "<a href='kelola_user.php'>Kelola User</a><br>";
    echo "<a href='laporan.php'>Laporan Keuangan</a><br>";
}
if ($_SESSION['role'] == 'kasir' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'operator') {
    echo "<a href='transaksi.php'>Input Transaksi</a><br>";
    echo "<a href='pengeluaran.php'>Input Pengeluaran</a><br>";
}
echo "<a href='logout.php'>Logout</a>";
?>
</div>


</body>
</html>

