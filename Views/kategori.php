<?php
require '../Config/koneksi.php';
session_start();


$query = mysqli_query($conn, "SELECT * FROM kategori");
include '../Layout/sidebar.php';
include '../Layout/footer.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Data Kategori</title>
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
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="font-bold text-xl">Data Kategori</h1>
                <button onclick="tambahKategori.php" type="button" class="font-semibold text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <a href="tambahKategori.php">+ Tambah Kategori</a></button>
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">ID</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">Nama</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">Deskripsi</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">Status</th>
                            <th class="p-3 text-sm font-semibold tracking-wide text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr class="odd:bg-white even:bg-gray-100">
                                <td class="p-3 text-sm text-gray-700"><?= $row['id'] ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= htmlspecialchars($row['deskripsi']) ?></td>
                                <td class="p-3 text-sm text-gray-700"><?= $row['status'] ?></td>
                                <td class="p-3 text-sm text-gray-700">
                                    <?php if ($row['status'] === 'aktif') : ?>
                                        <a class="cursor-pointer p-1.5 tracking-wider bg-green-300 text-green-800 rounded-sm bg-opacity-30" href="editKategori.php?id=<?= $row['id'] ?>">Edit</a>
                                        <a class="cursor-pointer p-1.5 tracking-wider bg-red-300 text-red-800 rounded-sm bg-opacity-30" href="hapusKategori.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                    <?php else : ?>
                                        <a class="cursor-pointer p-1.5 tracking-wider bg-yellow-300 text-yellow-800 rounded-sm bg-opacity-30" href="editKategori.php?id=<?= $row['id'] ?>">Aktifkan</a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>