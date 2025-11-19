<?php
require '../Config/koneksi.php';

$id = $_GET['id_barang'];
$result = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = $id");
$barang = mysqli_fetch_assoc($result);

if (!$barang) {
    die("Barang tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = $_POST['nama_barang'];
    $stok  = $_POST['stok'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    // Logika otomatis: stok habis = nonaktif, stok ada = aktif
    if ($stok <= 0) {
        $status = 'nonaktif';
    } else {
        $status = 'aktif';
    }

    mysqli_query($conn, "UPDATE barang SET 
        nama_barang='$nama', 
        stok='$stok', 
        harga='$harga', 
        status='$status'
        WHERE id_barang=$id");

    header("Location: barang.php");
    exit;
}

include "../Layout/sidebar.php";
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

            <div class="bg-white border border-1 rounded-lg shadow relative m-10">

                <div class="flex items-start justify-between p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold">Edit Barang</h3>
                </div>

                <div class="p-6 space-y-6">
                    <form method="POST">
                        <div class="grid grid-cols-6 gap-6">

                            <!-- Nama Barang -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Nama Barang</label>
                                <input type="text" 
                                    name="nama_barang" 
                                    value="<?= htmlspecialchars($barang['nama_barang']) ?>"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                    required>
                            </div>

                            <!-- Stok -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Stok</label>
                                <input type="number" 
                                    name="stok" 
                                    value="<?= $barang['stok'] ?>"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                    required>
                            </div>

                            <!-- Harga -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Harga</label>
                                <input type="number" 
                                    name="harga" 
                                    step="0.01"
                                    value="<?= $barang['harga'] ?>"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                    required>
                            </div>

                            <!-- Status -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Status</label>
                                <select name="status"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                                    <option value="aktif" <?= $barang['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="nonaktif" <?= $barang['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                </select>
                            </div>

                        </div>

                        <div class="p-6 mt-3 border-t border-gray-200 rounded-b">
                            <button class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 
                                           focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                    type="submit">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </main>
    </div>

</body>

</html>
