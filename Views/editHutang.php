<?php
include ("../Config/koneksi.php");
session_start();

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM hutang_pelanggan WHERE id = $id");
$hutang = mysqli_fetch_assoc($result);

if (!$hutang) {
    die("Hutang tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nama = $_POST["nama_pelanggan"];
    $jumlah = $_POST["jumlah_hutang"];
    $tanggal = $_POST["tanggal_jatuh_tempo"];
    $status = $_POST["status"];
    $kontak = $_POST["kontak"];
    $catatan = $_POST["catatan"];

    $cekStatus = mysqli_query($conn, "SELECT status FROM hutang_pelanggan WHERE id = $id");
    $rowStatus = mysqli_fetch_assoc($cekStatus);
    $statusLama = $rowStatus['status'];

    $update = mysqli_query($conn, "UPDATE hutang_pelanggan SET
            nama_pelanggan = '$nama',
            jumlah_hutang = '$jumlah',
            tanggal_jatuh_tempo = '$tanggal',
            status = '$status',
            kontak = '$kontak',
            catatan = '$catatan'
            WHERE id = $id
        ");

    if (!$update) {
        die("Error update hutang: " . mysqli_error($conn));
    }

    if ($statusLama != "lunas" && $status == "lunas") {
        $tanggalSekarang = date("Y-m-d");

        $insert = mysqli_query($conn, "INSERT INTO pemasukan 
            (tanggal, jumlah, sumber, id_hutang, id_transaksi, id_laporan)
            VALUES 
            ('$tanggalSekarang', '$jumlah', 'pelunasan_hutang', '$id', NULL, NULL)");

        if (!$insert) {
            die("Error insert pemasukan: " . mysqli_error($conn));
        }
    }

    header("Location: hutang.php");
    exit();
}

include "../Layout/sidebar.php";
include '../Layout/footer.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hutang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Montserrat', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex flex-1">

<div class="flex flex-col flex-1">
    <?php include "../Layout/header.php"; ?>
    <main class="flex-1 pt-17 p-6">
        <div class="bg-white border border-1 rounded-lg shadow relative m-10">

            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Edit Hutang Pelanggan</h3>
            </div>

            <div class="p-6 space-y-6">
                <form method="POST">
                    <div class="grid grid-cols-6 gap-6">

                        <div class="col-span-6 sm:col-span-3">
                            <label for="nama_pelanggan" class="text-sm font-medium text-gray-900 block mb-2">Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="<?= htmlspecialchars($hutang['nama_pelanggan']) ?>" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="jumlah_hutang" class="text-sm font-medium text-gray-900 block mb-2">Jumlah Hutang</label>
                            <input type="number" name="jumlah_hutang" id="jumlah_hutang" value="<?= htmlspecialchars($hutang['jumlah_hutang']) ?>" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="tanggal_jatuh_tempo" class="text-sm font-medium text-gray-900 block mb-2">Tanggal Jatuh Tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="<?= htmlspecialchars($hutang['tanggal_jatuh_tempo']) ?>" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="status" class="text-sm font-medium text-gray-900 block mb-2">Status</label>
                            <select name="status" id="status" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                                <option value="belum" <?= $hutang['status'] == 'belum' ? 'selected' : '' ?>>Belum Lunas</option>
                                <option value="lunas" <?= $hutang['status'] == 'lunas' ? 'selected' : '' ?>>Lunas</option>
                            </select>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="kontak" class="text-sm font-medium text-gray-900 block mb-2">Kontak</label>
                            <input type="text" name="kontak" id="kontak" value="<?= htmlspecialchars($hutang['kontak']) ?>"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                        </div>

                        <div class="col-span-full">
                            <label for="catatan" class="text-sm font-medium text-gray-900 block mb-2">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="4"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"><?= htmlspecialchars($hutang['catatan']) ?></textarea>
                        </div>

                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5">Update Hutang</button>
                    </div>
                </form>
            </div>

        </div>
    </main>
</div>

</body>
</html>
