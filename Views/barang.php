<?php
require '../Config/koneksi.php';
session_start();


$query = mysqli_query($conn, "SELECT * FROM barang");
include '../Layout/sidebar.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Data Barang</title>
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
    <div class="flex flex-col flex-1">
        <?php include '../Layout/header.php';
            if (isset($_SESSION['msg'])) {
            echo "<p style='color: green;'>" . $_SESSION['msg'] . "</p>";
            unset($_SESSION['msg']);
        }
        ?>
        <main class="p-6 pt-17 flex-1">

            <h1 class="font-bold text-xl">Data Barang</h1>
            <button href="tambahBarang.php" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700">
                <a href="tambahBarang.php">+ Tambah Barang</a></button>
            <table class="w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">ID</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Nama Barang</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Stok</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Harga Beli</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Harga Jual</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Status</th>
                    <th class="p-3 text-sm font-semibold tracking-wide text-left">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr class="odd:bg-white even:bg-gray-100">
                        <td class="p-3 text-sm text-gray-700"><?= $row['id_barang'] ?></td>
                        <td class="p-3 text-sm text-gray-700"><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td class="p-3 text-sm text-gray-700"><?= $row['stok'] ?></td>
                        <td class="p-3 text-sm text-gray-700"><?= number_format($row['harga_beli'], 2, ',', '.') ?></td>
                        <td class="p-3 text-sm text-gray-700"><?= number_format($row['harga_jual'], 2, ',', '.') ?></td>
                        <td class="p-3 text-sm text-gray-700"><?= $row['status'] ?></td>
                        <td class="p-3 text-sm text-gray-700">
                            <?php if ($row['status'] === 'aktif') :?>
                            <a class="cursor-pointer p-1.5 tracking-wider bg-green-300 text-green-800 rounded-sm bg-opacity-30" href="editBarang.php?id_barang=<?= $row['id_barang'] ?>">Edit</a>
                            <a class="cursor-pointer p-1.5 tracking-wider bg-red-300 text-red-800 rounded-sm bg-opacity-30" href="hapusBarang.php?id_barang=<?= $row['id_barang'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            <?php else :?>
                                <a class="cursor-pointer p-1.5 tracking-wider bg-yellow-300 text-yellow-800 rounded-sm bg-opacity-30" href="editBarang.php?id_barang=<?= $row['id_barang'] ?>">Aktifkan</a>
                            <?php endif?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>