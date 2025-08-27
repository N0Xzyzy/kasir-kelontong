<?php
include '../Config/koneksi.php';
session_start();

$query = mysqli_query($conn, "SELECT * FROM hutang_pelanggan ORDER BY id DESC");
include '../Layout/sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hutang Pelanggan</title>
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

<body class="flex bg-gray-100 h-full">
    <div class="flex flex-col flex-1">
        <?php include '../Layout/header.php'; ?>
        <main class="p-6 pt-17 flex-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="font-bold text-xl">Hutang Pelanggan</h1>
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">ID</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Nama Pelanggan</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">ID Transaksi</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Jumlah Hutang</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Tanggal Jatuh Tempo</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Status</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Kontak</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Catatan</th>
                            <?php if (isset($_SESSION['id_user']) && ($_SESSION['role'] === 'owner' || $_SESSION['role'] === 'kasir')) { ?>
                                <th class="p-3 font-semibold text-gray-700 text-left text-sm">Aksi</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr class="odd:bg-white even:bg-gray-50">
                                <td class="p-3 text-sm text-gray-700"><?= $row['id'] ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= $row['id_transaksi'] ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= number_format($row['jumlah_hutang'], 2, ',', '.') ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= date('d-m-Y', strtotime($row['tanggal_jatuh_tempo'])) ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= ucfirst($row['status']) ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= $row['kontak'] ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= htmlspecialchars($row['catatan']) ?></td>
                                <?php if (isset($_SESSION['id_user']) && ($_SESSION['role'] === 'owner' || $_SESSION['role'] === 'kasir')) { ?>
                                    <td class="p-3 text-sm text-gray-700">
                                        <a class="p-1.5 bg-green-300 text-green-700 rounded-sm bg-opacity-30" href="editHutang.php?id=<?= $row['id'] ?>">Edit</a>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>