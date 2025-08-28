<?php
session_start();
include '../Config/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    die("Akses ditolak. Anda bukan owner.");
}

if (isset($_GET['id_barang'])) {
    $id = (int) $_GET['id_barang'];

    $sql = "UPDATE barang SET status = 'nonaktif' WHERE id_barang = $id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "Barang berhasil dinonaktifkan!";
    } else {
        $_SESSION['msg'] = "Gagal menonaktifkan barang!";
    }
} else {
    $_SESSION['msg'] = "Barang tidak valid!";
}

header("Location: barang.php");
exit;
?>
