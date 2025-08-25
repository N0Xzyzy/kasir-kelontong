<?php
include '../Config/koneksi.php';

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
        <?php include '../Layout/header.php';?>
        <main class="p-6 pt-17 flex-1">
            <h1 class="font-bold text-xl">Hutang Pelanggan</h1>
            <table >
                <tr>
                    <th>ID</th>
                    <th>Nama Pelanggan</th>
                    <th>ID Transaksi</th>
                    <th>Jumlah Hutang</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Kontak</th>
                    <th>Catatan</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                        <td><?= $row['id_transaksi']?></td>
                        <td><?= number_format($row['jumlah_hutang'], 2, ',', '.') ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tanggal_jatuh_tempo'])) ?></td>
                        <td><?= ucfirst($row['status']) ?></td>
                        <td><?= $row['kontak'] ?></td>
                        <td><?= htmlspecialchars($row['catatan']) ?></td>
                        <td>
                            <a href="editHutang.php?id=<?= $row['id'] ?>">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>
    
</body>
</html>