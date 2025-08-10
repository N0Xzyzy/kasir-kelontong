<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];

    mysqli_query($conn, "INSERT INTO barang (nama_barang, stok, harga_beli, harga_jual) 
                         VALUES ('$nama', '$stok', '$harga_beli', '$harga_jual')");

    header("Location: barang.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
</head>
<body>
    <h1>Tambah Barang</h1>
    <form method="POST">
        Nama Barang: <input type="text" name="nama_barang" required><br>
        Stok: <input type="number" name="stok" required><br>
        Harga Beli: <input type="number" step="0.01" name="harga_beli" required><br>
        Harga Jual: <input type="number" step="0.01" name="harga_jual" required><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
