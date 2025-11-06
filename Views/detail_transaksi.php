<?php

use BcMath\Number;

session_start();
include "../Config/koneksi.php";

if (!isset($_GET['id_transaksi'])) {
    die("ID transaksi tidak ditemukan.");
}
$id_transaksi = intval($_GET['id_transaksi']);

$sqlTransaksi = "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi";
$resultTransaksi = mysqli_query($conn, $sqlTransaksi);
$transaksi = mysqli_fetch_assoc($resultTransaksi);

if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

$sqlDetail = "SELECT 
                dt.id_detail,
                b.nama_barang,
                dt.jumlah,
                dt.harga_jual,
                dt.subtotal
              FROM detail_transaksi dt
              JOIN barang b ON dt.id_barang = b.id_barang
              WHERE dt.id_transaksi = $id_transaksi";
$resultDetail = mysqli_query($conn, $sqlDetail);

include '../Layout/sidebar.php';
include '../Layout/footer.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
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
<body class="bg-gray-100 flex h-full">
    <div class="flex flex-col flex-1">
        <?php include '../Layout/header.php'; ?>

        <main class="p-6 pt-18 flex-1">
        <div class="mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Detail Transaksi #<?= $transaksi['id_transaksi'] ?></h1>
        <p><strong>Tanggal:</strong> <?= $transaksi['tanggal'] ?></p>
        <p><strong>Metode Pembayaran:</strong> <?= $transaksi['metode_pembayaran'] ?></p>
        <p><strong>Total:</strong> Rp<?= number_format($transaksi['total_transaksi'], 0, ',', '.') ?></p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Barang yang Dibeli</h2>
        <table class="w-full">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="p-3 text-gray-700 text-sm font-semibold text-left tracking-wide">Nama Barang</th>
                    <th class="p-3 text-gray-700 text-sm font-semibold text-left tracking-wide">Jumlah</th>
                    <th class="p-3 text-gray-700 text-sm font-semibold text-left tracking-wide">Harga Jual</th>
                    <th class="p-3 text-gray-700 text-sm font-semibold text-left tracking-wide">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultDetail)) { ?>
                <tr class="odd:bg-white even:bg-gray-100">
                    <td class="p-3 text-gray-700 tracking-wide font-sm"><?= $row['nama_barang'] ?></td>
                    <td class="p-3 text-gray-700 tracking-wide font-sm"><?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                    <td class="p-3 text-gray-700 tracking-wide font-sm">Rp<?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                    <td class="p-3 text-gray-700 tracking-wide font-sm">Rp<?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="mt-6">
            <a href="pemasukan.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">← Kembali</a>
        </div>
    </div>
        </main>
    </div>
</body>
</html>
