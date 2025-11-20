<?php
include '../Config/koneksi.php';
session_start();

if (!isset($_GET['id_transaksi'])) {
    die("ID Transaksi tidak ditemukan.");
}

$id_transaksi = intval($_GET['id_transaksi']);

$query_transaksi = "SELECT * FROM transaksi WHERE id_transaksi = ?";
$stmt = $conn->prepare($query_transaksi);
$stmt->bind_param("i", $id_transaksi);
$stmt->execute();
$result_transaksi = $stmt->get_result();
$transaksi = $result_transaksi->fetch_assoc();

$query_detail = "SELECT dt.*, b.nama_barang 
                 FROM detail_transaksi dt
                 JOIN barang b ON dt.id_barang = b.id_barang
                 WHERE dt.id_transaksi = ?";
$stmt_detail = $conn->prepare($query_detail);
$stmt_detail->bind_param("i", $id_transaksi);
$stmt_detail->execute();
$result_detail = $stmt_detail->get_result();

if (isset($_POST['simpan_hutang'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jumlah_hutang = $_POST['jumlah_hutang'];
    $tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
    $status = $_POST['status'];
    $kontak = $_POST['kontak'];
    $catatan = $_POST['catatan'];

    $query_hutang = "INSERT INTO hutang_pelanggan (nama_pelanggan, id_transaksi, jumlah_hutang, tanggal_jatuh_tempo, status, kontak, catatan)
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_hutang = $conn->prepare($query_hutang);
    $stmt_hutang->bind_param("sidssss", $nama_pelanggan, $id_transaksi, $jumlah_hutang, $tanggal_jatuh_tempo, $status, $kontak, $catatan);

    if ($stmt_hutang->execute()) {
        echo "<script>alert('Data Hutang berhasil disimpan!'); window.location.href='hutang.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan hutang!');</script>";
    }
}

include '../Layout/sidebar.php';
include '../Layout/footer.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Hutang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="flex bg-gray-100 h-full">
    <div class="flex flex-col flex-1">
        <?php include '../Layout/header.php'; ?>

        <main class="p-6 pt-24 pl-32 flex-1">

            <!-- CARD TEMPLATE -->
            <div class="bg-white border border-1 rounded-lg shadow relative m-10">

                <!-- HEADER -->
                <div class="flex items-start justify-between p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold">
                        Tambah Hutang – Transaksi #<?= $id_transaksi ?>
                    </h3>
                </div>

                <!-- FORM CONTENT -->
                <div class="p-6 space-y-6">
                    <form method="POST">
                        <div class="grid grid-cols-6 gap-6">

                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Nama Pelanggan</label>
                                <input type="text" name="nama_pelanggan"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                    required>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Jumlah Hutang</label>
                                <input type="number" step="0.01" name="jumlah_hutang"
                                    value="<?= $transaksi['total_transaksi'] ?>"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Tanggal Jatuh Tempo</label>
                                <input type="date" name="tanggal_jatuh_tempo"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5" required>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Status</label>
                                <select name="status"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                                    <option value="belum">Belum Lunas</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Kontak</label>
                                <input type="text" name="kontak"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                            </div>

                            <div class="col-span-full">
                                <label class="text-sm font-medium text-gray-900 block mb-2">Catatan</label>
                                <textarea name="catatan" rows="4"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg 
                                           focus:ring-cyan-600 focus:border-cyan-600 block w-full p-4"></textarea>
                            </div>

                        </div>
                </div>

                <!-- FOOTER (BUTTONS) -->
                <div class="p-6 border-t border-gray-200 rounded-b flex gap-3">
                    <button type="submit" name="simpan_hutang"
                        class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 
                               font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Simpan Hutang
                    </button>

                    <a href="transaksi.php"
                        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-200 
                               font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Batal
                    </a>
                </div>

                </form>

                <!-- DETAIL TRANSAKSI -->
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-3">Rincian Transaksi</h4>

                    <table class="w-full">
                        <thead class="bg-gray-100 border-gray-200 border-b">
                            <tr>
                                <th class="p-3 text-sm font-semibold text-left text-gray-700">Barang</th>
                                <th class="p-3 text-sm font-semibold text-left text-gray-700">Jumlah</th>
                                <th class="p-3 text-sm font-semibold text-left text-gray-700">Harga</th>
                                <th class="p-3 text-sm font-semibold text-left text-gray-700">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_detail->fetch_assoc()) { ?>
                                <tr class="odd:bg-white even:bg-gray-50">
                                    <td class="p-3 text-gray-700"><?= $row['nama_barang'] ?></td>
                                    <td class="p-3 text-gray-700"><?= $row['jumlah'] ?></td>
                                    <td class="p-3 text-gray-700"><?= number_format($row['harga'], 0, ',', '.') ?></td>
                                    <td class="p-3 text-gray-700"><?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

</body>

</html>
