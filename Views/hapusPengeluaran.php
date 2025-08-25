<?php
session_start();
include '../Config/koneksi.php';

$id_pengeluaran = isset($_GET['id_pengeluaran']) ? (int) $_GET['id_pengeluaran'] : 0;
if ($id_pengeluaran > 0){
    mysqli_query($conn, "DELETE FROM pengeluaran WHERE id_pengeluaran = $id_pengeluaran");
    $_SESSION['msg'] = 'Pengeluaran berhasil dihapus';
}else {
    $_SESSION['msg'] = 'Pengeluaran gagal dihapus';
}
header("Location: pengeluaran.php")

?>