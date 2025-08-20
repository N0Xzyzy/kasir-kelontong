<?php
require '../Config/koneksi.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = $id");
$barang = mysqli_fetch_assoc($result);

if (!$barang) {
    die("Barang tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];

    mysqli_query($conn, "UPDATE barang SET 
        nama_barang='$nama', 
        stok='$stok', 
        harga_beli='$harga_beli', 
        harga_jual='$harga_jual' 
        WHERE id_barang=$id");

    header("Location: barang.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
</head>
<body>
    <h1>Edit Barang</h1>
    <form method="POST">
        Nama Barang: <input type="text" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required><br>
        Stok: <input type="number" name="stok" value="<?= $barang['stok'] ?>" required><br>
        Harga Beli: <input type="number" step="0.01" name="harga_beli" value="<?= $barang['harga_beli'] ?>" required><br>
        Harga Jual: <input type="number" step="0.01" name="harga_jual" value="<?= $barang['harga_jual'] ?>" required><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
