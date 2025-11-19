<?php
session_start();
include "../Config/koneksi.php";

if (!isset($_GET['id_transaksi'])) {
    die("ID Transaksi tidak ditemukan");
}

$id_transaksi = intval($_GET['id_transaksi']);

$sql_transaksi = "
SELECT t.*, u.username
FROM transaksi t
LEFT JOIN users u ON t.id_user = u.id_user
WHERE t.id_transaksi = ?
";
$stmt = $conn->prepare($sql_transaksi);
$stmt->bind_param("i", $id_transaksi);
$stmt->execute();
$transaksi = $stmt->get_result()->fetch_assoc();

$sql_detail = "
SELECT d.*, b.nama_barang
FROM detail_transaksi d
LEFT JOIN barang b ON d.id_barang = b.id_barang
WHERE d.id_transaksi = ?
";
$stmt_detail = $conn->prepare($sql_detail);
$stmt_detail->bind_param("i", $id_transaksi);
$stmt_detail->execute();
$detail = $stmt_detail->get_result();

include '../Layout/sidebar.php';
include '../Layout/footer.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 flex h-full">
    <div class="flex flex-col flex-1">

        <?php include '../Layout/header.php'; ?>

        <main class="p-6 pt-20 flex-1">

            <div class="bg-white p-6 rounded shadow max-w-4xl mx-auto">

                <h1 class="text-2xl font-bold mb-4">Detail Transaksi #<?= $id_transaksi ?></h1>

                <div class="grid grid-cols-2 gap-4 text-gray-700 mb-6">
                    <div>
                        <p><strong>Tanggal:</strong> <?= $transaksi['tanggal'] ?></p>
                        <p><strong>Kasir:</strong> <?= $transaksi['username'] ?></p>
                    </div>
                    <div>
                        <p><strong>Metode Pembayaran:</strong> <?= $transaksi['metode_pembayaran'] ?></p>
                        <p><strong>Total:</strong> Rp<?= number_format($transaksi['total_transaksi'], 0, ',', '.') ?></p>
                    </div>
                </div>

                <h2 class="text-xl font-semibold mb-2">Barang Dibeli</h2>

                <table class="w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2">Nama</th>
                            <th class="p-2">Jumlah</th>
                            <th class="p-2">Harga</th>
                            <th class="p-2 text-right">Subtotal</th>
                            <th class="p-2">Bayar</th>
                            <th class="p-2">Kembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $detail->fetch_assoc()) : ?>
                            <tr class="border-b">
                                <td class="p-2"><?= $row['nama_barang'] ?></td>
                                <td class="p-2"><?= $row['jumlah'] ?></td>
                                <td class="p-2">Rp<?= number_format($row['harga']?? 0, 0, ',', '.') ?></td>
                                <td class="p-2 text-right">Rp<?= number_format($row['subtotal']?? 0, 0, ',', '.') ?></td>
                                <td class="p-2">Rp<?= number_format($row['bayar']?? 0, 0, ',', '.') ?></td>
                                <td class="p-2">Rp<?= number_format($row['kembalian']?? 0, 0, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <a href="transaksi.php" class="inline-block mt-5 bg-blue-600 text-white px-4 py-2 rounded">
                    ← Kembali
                </a>

            </div>

        </main>

        <?php include '../Layout/footer.php'; ?>
    </div>
</body>
</html>
