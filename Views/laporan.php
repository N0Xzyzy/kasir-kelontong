<?php
include '../Config/koneksi.php';
session_start();

$periode = $_GET['periode'] ?? 'hari';

switch ($periode) {
    case 'bulan':
        $sql = "SELECT 
                    YEAR(tanggal) AS tahun,
                    MONTH(tanggal) AS bulan,
                    SUM(total_pemasukan) AS pemasukan,
                    SUM(total_pengeluaran) AS pengeluaran,
                    SUM(laba) AS laba,
                    SUM(jumlah_transaksi) AS transaksi
                FROM laporan_keuangan
                GROUP BY YEAR(tanggal), MONTH(tanggal)
                ORDER BY tahun DESC, bulan DESC";
        break;

    case 'tahun':
        $sql = "SELECT 
                    YEAR(tanggal) AS tahun,
                    SUM(total_pemasukan) AS pemasukan,
                    SUM(total_pengeluaran) AS pengeluaran,
                    SUM(laba) AS laba,
                    SUM(jumlah_transaksi) AS transaksi
                FROM laporan_keuangan
                GROUP BY YEAR(tanggal)
                ORDER BY tahun DESC";
        break;

    case 'hari':
    default:
        $sql = "SELECT 
                    tanggal,
                    total_pemasukan AS pemasukan,
                    total_pengeluaran AS pengeluaran,
                    laba,
                    jumlah_transaksi AS transaksi
                FROM laporan_keuangan
                ORDER BY tanggal DESC";
        break;
}

include '../Layout/sidebar.php';
include '../Layout/footer.php';
$result = mysqli_query($conn, $sql);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

$totalPemasukan = 0;
$totalPengeluaran = 0;
$totalLaba = 0;
$totalTransaksi = 0;
foreach ($data as $row) {
    $totalPemasukan += $row['pemasukan'];
    $totalPengeluaran += $row['pengeluaran'];
    $totalLaba += $row['laba'];
    $totalTransaksi += $row['transaksi'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Montserrat', sans-serif; } </style>
</head>
<body class="bg-gray-100 h-full flex">
<div class="flex flex-1 flex-col">
    <?php include '../Layout/header.php'; ?>
    <main class="p-3 pt-18 flex-1">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-4">Laporan Keuangan</h1>
            <form method="get" class="mb-4 flex gap-4 items-center">
                <div>
                    <label for="periode" class="mr-2 font-medium">Pilih Periode:</label>
                    <select name="periode" id="periode" onchange="this.form.submit()" class="border rounded p-2">
                        <option value="hari" <?= $periode == 'hari' ? 'selected' : '' ?>>Harian</option>
                        <option value="bulan" <?= $periode == 'bulan' ? 'selected' : '' ?>>Bulanan</option>
                        <option value="tahun" <?= $periode == 'tahun' ? 'selected' : '' ?>>Tahunan</option>
                    </select>
                </div>
            </form>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Periode</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Pemasukan</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Pengeluaran</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Laba</th>
                            <th class="p-3 font-semibold text-gray-700 text-left text-sm">Jumlah Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row): ?>
                            <tr class="odd:bg-white even:bg-gray-50">
                                <td class="p-3 text-gray-700 text-sm font-medium">
                                    <?php
                                    if ($periode == 'bulan') {
                                        echo $row['bulan'] . '-' . $row['tahun'];
                                    } elseif ($periode == 'tahun') {
                                        echo $row['tahun'];
                                    } else {
                                        echo date('d-m-Y', strtotime($row['tanggal']));
                                    }
                                    ?>
                                </td>
                                <td class="p-3 text-gray-700 text-sm">Rp <?= number_format($row['pemasukan'], 0, ',', '.') ?></td>
                                <td class="p-3 text-gray-700 text-sm">Rp <?= number_format($row['pengeluaran'], 0, ',', '.') ?></td>
                                <td class="p-3 text-gray-700 text-sm">Rp <?= number_format($row['laba'], 0, ',', '.') ?></td>
                                <td class="p-3 text-gray-700 text-sm"><?= $row['transaksi'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="bg-gray-200 font-bold">
                            <td class="p-3 text-right">TOTAL</td>
                            <td class="p-3">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></td>
                            <td class="p-3">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></td>
                            <td class="p-3">Rp <?= number_format($totalLaba, 0, ',', '.') ?></td>
                            <td class="p-3"><?= $totalTransaksi ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
