<?php
session_start();
include '../Config/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    die("Akses ditolak. Anda bukan owner.");
}

if (isset($_GET['id_user'])) {
    $id_user = (int) $_GET['id_user'];

    if ($id_user == $_SESSION['id_user']) {
        die("Anda tidak bisa menghapus akun Anda sendiri!");
    }

    $check_stmt = $conn->prepare("SELECT id_user FROM users WHERE id_user = ?");
    $check_stmt->bind_param("i", $id_user);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "User berhasil dihapus!";
        } else {
            $_SESSION['msg'] = "Gagal menghapus user!";
        }
    } else {
        $_SESSION['msg'] = "User tidak ditemukan!";
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    $_SESSION['msg'] = "ID user tidak valid!";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>