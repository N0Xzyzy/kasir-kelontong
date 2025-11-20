<?php
require '../Config/koneksi.php';
session_start();

// Ambil kategori aktif dari database
$kategori = mysqli_query($conn, "SELECT id, nama FROM kategori WHERE status='aktif'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $kategori_id = $_POST['kategori_id'];

    mysqli_query($conn, "INSERT INTO barang (nama_barang, stok, harga, kategori) 
                         VALUES ('$nama', '$stok', '$harga', '$kategori_id')");

    header("Location: barang.php");
    exit;
}

include '../Layout/sidebar.php';
include '../Layout/footer.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Barang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen flex">

<section class="flex-1 flex flex-col">

<?php include '../Layout/header.php'; ?>

<main class="p-6 pt-24 pl-32 flex-1">
    <div class="bg-white border border-1 rounded-lg shadow relative m-10">

        <!-- HEADER -->
        <div class="flex items-start justify-between p-5 border-b rounded-t">
            <h3 class="text-xl font-semibold">Tambah Barang</h3>
        </div>

        <!-- FORM BODY -->
        <div class="p-6 space-y-6">
            <form method="POST">
                <div class="grid grid-cols-6 gap-6">

                    <!-- NAMA BARANG -->
                    <div class="col-span-6 sm:col-span-3">
                        <label class="text-sm font-medium text-gray-900 block mb-2">Nama Barang</label>
                        <input type="text" name="nama_barang"
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                            placeholder="Masukkan nama barang di sini" required>
                    </div>

                    <!-- STOK -->
                    <div class="col-span-6 sm:col-span-3">
                        <label class="text-sm font-medium text-gray-900 block mb-2">Stok</label>
                        <input type="number" name="stok"
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                            placeholder="Masukkan stok di sini" required>
                    </div>

                    <!-- HARGA -->
                    <div class="col-span-6 sm:col-span-3">
                        <label class="text-sm font-medium text-gray-900 block mb-2">Harga</label>
                        <input type="number" step="0.01" name="harga"
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                            placeholder="Masukkan harga di sini" required>
                    </div>

                    <!-- KATEGORI (Dropdown) -->
                    <div class="col-span-6 sm:col-span-3">
                        <label class="text-sm font-medium text-gray-900 block mb-2">Kategori</label>
                        <select name="kategori_id"
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" 
                            required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php while ($row = mysqli_fetch_assoc($kategori)) : ?>
                                <option value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                </div>
        </div>

        <!-- FOOTER BUTTON -->
        <div class="p-6 border-t border-gray-200 rounded-b">
            <button class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="submit">
                Simpan
            </button>
        </div>

        </form>
    </div>
</main>

</section>
</body>
</html>
