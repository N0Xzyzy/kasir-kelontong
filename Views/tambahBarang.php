<?php
require '../Config/koneksi.php';
session_start();

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
include '../Layout/sidebar.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Barang</title>
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

<body class="bg-gray-100 h-screen flex">

    <section class="flex-1 flex flex-col">
        <?php include '../Layout/header.php'; ?>
        <main class="p-6 flex-1 pt-17">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="font-bold text-xl">Tambah Barang</h1>
                <form method="POST">
                    Nama Barang: <input type="text" name="nama_barang" required><br>
                    Stok: <input type="number" name="stok" required><br>
                    Harga Beli: <input type="number" step="0.01" name="harga_beli" required><br>
                    Harga Jual: <input type="number" step="0.01" name="harga_jual" required><br>
                    <button class="cursor-pointer p-1.5 bg-green-500 text-white rounded-sm font-bold tracking-wider bg-opacity-30" type="submit">Simpan</button>
                </form>
            </div>

        </main>

    </section>
</body>

</html>