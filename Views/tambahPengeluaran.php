<?php
include '../Config/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $kategori = $_POST['kategori'];
    $keperluan = $_POST['keperluan'];
    $jumlah = $_POST['jumlah'] !== '' ? (int)$_POST['jumlah'] : "NULL";
    $total = $_POST['total'];
    $supplier = $_POST['supplier'];

    $query = "INSERT INTO pengeluaran (tanggal, kategori, keperluan, jumlah, total, supplier) 
              VALUES ('$tanggal', '$kategori', '$keperluan', $jumlah, '$total', '$supplier')";

    if (mysqli_query($conn, $query)) {
        header("Location: pengeluaran.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<?php include '../Layout/sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengeluaran</title>
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
<body class="h-full flex bg-gray-100">
    <div class="flex flex-col flex-1">
        <?php include '../Layout/header.php';?>
        <main class="flex-1 pt-17 p-6">
            <h1 class="font-bold text-xl mb-4">Tambah Pengeluaran</h1>
            <form method="POST">
                <label for="tanggal">Tanggal:</label>
                <input type="date" name="tanggal" required> <br>

                <label for="kategori">Kategori:</label>
                <select name="kategori" required>
                    <option value="Belanja">Belanja</option>
                    <option value="Operasional">Operasional</option>
                    <option value="Lain-lain">Lain-lain</option>
                </select><br>

                <label for="keperluan">Keperluan:</label>
                <input type="text" name="keperluan" required><br>

                <label for="jumlah">Jumlah:</label>
                <input type="number" name="jumlah"><br>

                <label for="total">Total:</label>
                <input type="number" name="total" required><br>

                <label for="supplier">Supplier:</label>
                <input type="text" name="supplier"><br>

                <button type="submit">Simpan</button>
            </form>
        </main>
    </div>
</body>
</html>
