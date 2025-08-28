<?php
require '../Config/koneksi.php';

$id = $_GET['id_barang'];
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
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE barang SET 
        nama_barang='$nama', 
        stok='$stok', 
        harga_beli='$harga_beli', 
        harga_jual='$harga_jual',
        status = '$status'
        WHERE id_barang=$id");

    header("Location: barang.php");
    exit;
}
include "../Layout/sidebar.php"
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Barang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-1">
    <div class="flex flex-col flex-1">
        <?php include "../Layout/header.php"; ?>
        <main class="flex-1 pt-17 p-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-3">Edit Barang</h1>
                    <form method="POST">
                        Nama Barang: <input type="text" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required><br>
                        Stok: <input type="number" name="stok" value="<?= $barang['stok'] ?>" required><br>
                        Harga Beli: <input type="number" step="0.01" name="harga_beli" value="<?= $barang['harga_beli'] ?>" required><br>
                        Harga Jual: <input type="number" step="0.01" name="harga_jual" value="<?= $barang['harga_jual'] ?>" required><br>
                        Status: <select name="status">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">nonaktif</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
            </div>
            
        </main>
    </div>

</body>

</html>