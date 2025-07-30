<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login_form.html');
    exit;
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