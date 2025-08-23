<?php
session_start();
include '../Config/koneksi.php';

// Cek apakah user login dan role = owner
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    die("Akses ditolak. Anda bukan owner.");
}

$id = $_GET['id_pengeluaran'];
mysqli_query($conn, "DELETE FROM pengeluaran WHERE id_pengeluaran = $id");

header("Location: barang.php");
exit;
?>