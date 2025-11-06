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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="flex bg-gray-100 h-full">
    <div class="flex flex-col flex-1">
        <?php include '../Layout/header.php'; ?>

        <main class="p-6 pt-17 flex-1">
            <div class="bg-white rounded-lg shadow p-6">
                <form method="POST">
                    <div class="mb-3">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Hutang</label>
                        <input type="number" step="0.01" name="jumlah_hutang" class="form-control" value="<?= $transaksi['total_transaksi'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="belum">Belum Lunas</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Kontak</label>
                        <input type="text" name="kontak" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control"></textarea>
                    </div>

                    <button type="submit" name="simpan_hutang" class="btn btn-primary">Simpan Hutang</button>
                    <a href="transaksi.php" class="btn btn-secondary">Batal</a>
                </form>

                <hr>

                <h4>Rincian Transaksi</h4>
                <table class="w-full">
                    <thead class="bg-gray-100 border-gray-200 border-b">
                        <tr>
                            <th class="p-3 tracking-wide text-sm font-semibold text-left text-gray-700">Barang</th>
                            <th class="p-3 tracking-wide text-sm font-semibold text-left text-gray-700">Jumlah</th>
                            <th class="p-3 tracking-wide text-sm font-semibold text-left text-gray-700">Harga</th>
                            <th class="p-3 tracking-wide text-sm font-semibold text-left text-gray-700">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_detail->fetch_assoc()) { ?>
                            <tr class="odd:bg-white even:bg-gray-50">
                                <td class="p-3 text-gray-700 text-md"><?= $row['nama_barang'] ?></td>
                                <td class="p-3 text-gray-700 text-md"><?= $row['jumlah'] ?></td>
                                <td class="p-3 text-gray-700 text-md"><?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                                <td class="p-3 text-gray-700 text-md"><?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
        <h2>Tambah Hutang untuk Transaksi #<?= $id_transaksi ?></h2>
</body>
    </div>


</html>