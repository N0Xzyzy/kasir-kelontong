<?php
require '../Config/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $desc = $_POST['desc'];

    mysqli_query($conn, "INSERT INTO kategori (nama, deskripsi) 
                         VALUES ('$nama', '$desc')");

    header("Location: kategori.php");
    exit;
}
include '../Layout/sidebar.php';
include '../Layout/footer.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Kategori</title>
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
            <div class="bg-white border border-1 rounded-lg shadow relative m-10">

                <div class="flex items-start justify-between p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold">
                        Tambah Kategori
                    </h3>
                </div>


                <form method="POST">
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-full">
                                <label for="nama" class="text-sm font-medium text-gray-900 block mb-2">Nama Kategori</label>
                                <input type="text" name="nama" id="nama" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required="" placeholder="Masukkan nama kategori di sini">
                            </div>

                            <div class="col-span-full">
                                <label for="product-details" class="text-sm font-medium text-gray-900 block mb-2">Deskripsi</label>
                                <textarea name="desc" id="desc" rows="6" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-4" placeholder="Masukkan deskripsi di sini"></textarea>
                            </div>
                        </div>


                        <div class="p-6 border-t border-gray-200 rounded-b">
                            <button class="font-semibold text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="submit">Simpan</button>
                        </div>
                    </div>
                </form>


            </div>

        </main>

    </section>
</body>

</html>