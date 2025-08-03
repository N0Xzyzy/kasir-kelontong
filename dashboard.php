<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    
    <?php
session_start();


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


</body>
</html>

