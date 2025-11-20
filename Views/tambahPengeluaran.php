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
<?php include '../Layout/footer.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengeluaran</title>
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

<body class="h-full flex bg-gray-100">

<div class="flex flex-col flex-1">

    <?php include '../Layout/header.php'; ?>

    <main class="p-6 pt-24 pl-32 flex-1">

        <div class="bg-white border border-1 rounded-lg shadow relative m-10">

            <!-- HEADER -->
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">Tambah Pengeluaran</h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <!-- FORM BODY -->
            <div class="p-6 space-y-6">
                <form method="POST">
                    <div class="grid grid-cols-6 gap-6">

                        <!-- TANGGAL -->
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Tanggal</label>
                            <input type="date" name="tanggal" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                       focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                        </div>

                        <!-- KATEGORI -->
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Kategori</label>
                            <select name="kategori" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                       focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                                <option value="Belanja">Belanja</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>

                        <!-- KEPERLUAN -->
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Keperluan</label>
                            <input type="text" name="keperluan" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                       focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                placeholder="Masukkan keperluan di sini">
                        </div>

                        <!-- JUMLAH -->
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Jumlah</label>
                            <input type="number" name="jumlah"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                       focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                placeholder="Opsional">
                        </div>

                        <!-- TOTAL -->
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Total</label>
                            <input type="number" name="total" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                       focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                placeholder="Masukkan total di sini">
                        </div>

                        <!-- SUPPLIER -->
                        <div class="col-span-6 sm:col-span-3">
                            <label class="text-sm font-medium text-gray-900 block mb-2">Supplier</label>
                            <input type="text" name="supplier"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                       focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                placeholder="Masukkan supplier di sini">
                        </div>

                    </div>
            </div>

            <!-- FOOTER -->
            <div class="p-6 border-t border-gray-200 rounded-b">
                <button type="submit"
                    class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 
                           font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Simpan
                </button>
            </div>

            </form>

        </div>

    </main>
</div>

</body>
</html>
