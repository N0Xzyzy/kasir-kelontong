<?php
session_start();
include '../Config/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    die("Akses ditolak. Anda bukan owner.");
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $sql = "UPDATE kategori SET status = 'nonaktif' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "Kategori berhasil dinonaktifkan!";
    } else {
        $_SESSION['msg'] = "Gagal menonaktifkan kategori! Error: " . mysqli_error($conn);
    }
} else {
    $_SESSION['msg'] = "Kategori tidak valid!";
}

header("Location: kategori.php");
exit;
?>
