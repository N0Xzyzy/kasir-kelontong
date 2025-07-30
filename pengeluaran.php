<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'kasir', 'operator'])) {
    header('Location: login_form.html');
    exit;
}
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $jumlah = $_POST['jumlah'];
    $id_user = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO pengeluaran (tanggal, keterangan, jumlah, id_user) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $tanggal, $keterangan, $jumlah, $id_user);
    $stmt->execute();
    echo "Pengeluaran berhasil ditambahkan";
}
?>

<form method="POST">
  <input type="date" name="tanggal" required>
  <input type="text" name="keterangan" placeholder="Keterangan" required>
  <input type="number" step="0.01" name="jumlah" placeholder="Jumlah" required>
  <button type="submit">Simpan Pengeluaran</button>
</form>