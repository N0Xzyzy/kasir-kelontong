<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login_form.html');
    exit;
}
require '../Configkoneksi.php';

$transaksi = $conn->query("SELECT * FROM transaksi");
$pengeluaran = $conn->query("SELECT * FROM pengeluaran");

echo "<h2>Transaksi</h2>";
while ($row = $transaksi->fetch_assoc()) {
    echo $row['tanggal'] . " - Rp" . $row['total'] . "<br>";
}

echo "<h2>Pengeluaran</h2>";
while ($row = $pengeluaran->fetch_assoc()) {
    echo $row['tanggal'] . " - Rp" . $row['jumlah'] . " (" . $row['keterangan'] . ")<br>";
}
?>