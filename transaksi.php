<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $total = $_POST['total'];
    $id_user = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO transaksi (tanggal, total, id_user) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $tanggal, $total, $id_user);
    $stmt->execute();
    echo "Transaksi berhasil";
}
?>

<form method="POST">
  <input type="date" name="tanggal" required>
  <input type="number" step="0.01" name="total" placeholder="Total" required>
  <button type="submit">Simpan Transaksi</button>
</form>