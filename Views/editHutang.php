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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hutang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex flex-1">
    <div class="flex flex-col flex-1">
        <?php include "../Layout/header.php"; ?>
        <main class="flex-1 pt-17 p-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-3">Edit Hutang</h1>
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block font-medium">Nama Pelanggan:</label>
                        <input type="text" name="nama_pelanggan" value="<?= htmlspecialchars($hutang['nama_pelanggan']) ?>" required class="border rounded p-2 w-full">
                    </div>
                    <div>
                        <label class="block font-medium">Jumlah Hutang:</label>
                        <input type="number" name="jumlah_hutang" value="<?= htmlspecialchars($hutang['jumlah_hutang']) ?>" required class="border rounded p-2 w-full">
                    </div>
                    <div>
                        <label class="block font-medium">Tanggal Jatuh Tempo:</label>
                        <input type="date" name="tanggal_jatuh_tempo" value="<?= htmlspecialchars($hutang['tanggal_jatuh_tempo']) ?>" required class="border rounded p-2 w-full">
                    </div>
                    <div>
                        <label class="block font-medium">Status:</label>
                        <select name="status" class="border rounded p-2 w-full">
                            <option value="belum" <?= $hutang['status'] == 'belum' ? 'selected' : '' ?>>Belum Lunas</option>
                            <option value="lunas" <?= $hutang['status'] == 'lunas' ? 'selected' : '' ?>>Lunas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium">Kontak:</label>
                        <input type="text" name="kontak" value="<?= htmlspecialchars($hutang['kontak']) ?>" class="border rounded p-2 w-full">
                    </div>
                    <div>
                        <label class="block font-medium">Catatan:</label>
                        <textarea name="catatan" class="border rounded p-2 w-full"><?= htmlspecialchars($hutang['catatan']) ?></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
