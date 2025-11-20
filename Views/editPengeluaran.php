<?php
require '../Config/koneksi.php';
session_start();

$id = $_GET['id_pengeluaran'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengeluaran WHERE id_pengeluaran = $id"));

if (!$data) {
    die("Data pengeluaran tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tanggal   = $_POST['tanggal'];
    $kategori  = $_POST['kategori'];
    $keperluan = $_POST['keperluan'];
    $total     = $_POST['total'];
    $supplier  = $_POST['supplier'];

    mysqli_query($conn, "UPDATE pengeluaran SET 
        tanggal='$tanggal',
        kategori='$kategori',
        keperluan='$keperluan',
        total='$total',
        supplier='$supplier'
        WHERE id_pengeluaran=$id
    ");

    header("Location: pengeluaran.php");
    exit;
}

include "../Layout/sidebar.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Pengeluaran</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <style> body { font-family:'Montserrat',sans-serif; } </style>
</head>

<body class="bg-gray-100 flex flex-1">
<div class="flex flex-col flex-1">
    <?php include "../Layout/header.php"; ?>

    <main class="p-6 pt-24 pl-32 flex-1">

        <div class="bg-white border rounded-lg shadow m-10">

            <div class="flex items-start justify-between p-5 border-b">
                <h3 class="text-xl font-semibold">Edit Pengeluaran</h3>
            </div>

            <div class="p-6 space-y-6">
                <form method="POST">
                    <div class="grid grid-cols-6 gap-6">

                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium">Tanggal</label>
                            <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" 
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium">Kategori</label>
                            <select name="kategori"
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                                <option value="Belanja" <?= $data['kategori']=='Belanja'?'selected':'' ?>>Belanja</option>
                                <option value="Operasional" <?= $data['kategori']=='Operasional'?'selected':'' ?>>Operasional</option>
                                <option value="Lain-lain" <?= $data['kategori']=='Lain-lain'?'selected':'' ?>>Lain-lain</option>
                            </select>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium">Keperluan</label>
                            <input type="text" name="keperluan" value="<?= $data['keperluan'] ?>" 
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium">Total</label>
                            <input type="number" step="0.01" name="total" value="<?= $data['total'] ?>"
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium">Supplier</label>
                            <input type="text" name="supplier" value="<?= $data['supplier'] ?>"
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                        </div>

                    </div>

                    <div class="p-6 mt-3 border-t">
                        <button class="bg-cyan-600 hover:bg-cyan-700 text-white px-5 py-2.5 rounded-lg text-sm">
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
