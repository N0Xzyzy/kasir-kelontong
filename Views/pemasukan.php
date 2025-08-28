<?php
include '../Config/koneksi.php';
session_start();

$periode = $_GET['periode'] ?? 'hari';

$sql = "";

switch ($periode) {
    case 'minggu':
        $sql = "SELECT YEARWEEK(tanggal, 1) AS kode_minggu, 
                       MIN(DATE(tanggal)) AS mulai, 
                       MAX(DATE(tanggal)) AS akhir,
                       SUM(jumlah) AS pemasukan,
                       sumber
                FROM pemasukan
                WHERE sumber != 'hutang'
                GROUP BY YEARWEEK(tanggal, 1), sumber
                ORDER BY YEARWEEK(tanggal, 1) DESC";
        break;

    case 'bulan':
        $sql = "SELECT YEAR(tanggal) AS tahun, 
                       MONTH(tanggal) AS bulan, 
                       SUM(jumlah) AS pemasukan,
                       sumber
                FROM pemasukan
                WHERE sumber != 'hutang'
                GROUP BY YEAR(tanggal), MONTH(tanggal), sumber
                ORDER BY tahun DESC, bulan DESC";
        break;

    case 'tahun':
        $sql = "SELECT YEAR(tanggal) AS tahun, 
                       SUM(jumlah) AS pemasukan,
                       sumber
                FROM pemasukan
                WHERE sumber != 'hutang'
                GROUP BY YEAR(tanggal), sumber
                ORDER BY tahun DESC";
        break;

        case 'transaksi':
            $sql = "
                SELECT 
                    t.id_transaksi,
                    t.tanggal,
                    t.total_transaksi AS total_transaksi,
                    t.metode_pembayaran
                FROM transaksi t
                WHERE t.metode_pembayaran != 'hutang'
        
                UNION ALL
        
                SELECT 
                    h.id AS id_transaksi,
                    h.tanggal_jatuh_tempo AS tanggal,
                    h.jumlah_hutang AS total_transaksi,
                    'pelunasan_hutang' AS metode_pembayaran
                FROM hutang_pelanggan h
                WHERE h.status = 'lunas'
        
                ORDER BY tanggal DESC, id_transaksi DESC
            ";
            break;
    case 'hari':
    default:
        $sql = "SELECT DATE(tanggal) AS label, 
                       SUM(jumlah) AS pemasukan,
                       sumber
                FROM pemasukan
                WHERE sumber != 'hutang'
                GROUP BY DATE(tanggal), sumber
                ORDER BY DATE(tanggal) DESC";
        break;
}

include '../Layout/sidebar.php';
$result = mysqli_query($conn, $sql);

$data = [];
$totalPemasukan = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
    if ($periode != 'transaksi') {
        $totalPemasukan += $row['pemasukan'];
    } else {
        $totalPemasukan += $row['total_transaksi'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pemasukan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 h-full flex">

    <div class="flex flex-1 flex-col">
        <?php include '../Layout/header.php'; ?>
        <main class="p-3 pt-18 flex-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-4">Laporan Pemasukan</h1>
                <form method="get" class="mb-4 flex gap-4 items-center">
                    <div>
                        <label for="periode" class="mr-2 font-medium">Pilih Periode:</label>
                        <select name="periode" id="periode" onchange="this.form.submit()" class="border rounded p-2">
                            <option value="hari" <?= $periode == 'hari' ? 'selected' : '' ?>>Harian</option>
                            <option value="minggu" <?= $periode == 'minggu' ? 'selected' : '' ?>>Mingguan</option>
                            <option value="bulan" <?= $periode == 'bulan' ? 'selected' : '' ?>>Bulanan</option>
                            <option value="tahun" <?= $periode == 'tahun' ? 'selected' : '' ?>>Tahunan</option>
                            <option value="transaksi" <?= $periode == 'transaksi' ? 'selected' : '' ?>>Per Transaksi</option>
                        </select>
                    </div>
                </form>

                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <table class="w-full">
                        <?php if ($periode == 'transaksi'): ?>
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="p-3 font-semibold text-gray-700 text-left text-sm">ID Transaksi</th>
                                    <th class="p-3 font-semibold text-gray-700 text-left text-sm">Tanggal</th>
                                    <th class="p-3 font-semibold text-gray-700 text-left text-sm">Metode</th>
                                    <th class="p-3 font-semibold text-gray-700 text-left text-sm">Total</th>
                                    <th class="p-3 font-semibold text-gray-700 text-left text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        <td class="p-3 text-sm"><?= $row['id_transaksi'] ?></td>
                                        <td class="p-3 text-sm"><?= $row['tanggal'] ?></td>
                                        <td class="p-3 text-sm"><?= $row['metode_pembayaran'] ?></td>
                                        <td class="p-3 text-sm">Rp <?= number_format($row['total_transaksi'], 0, ',', '.') ?></td>
                                        <td class="p-3 text-sm">
                                            <a href="detail_transaksi.php?id_transaksi=<?= $row['id_transaksi'] ?>" class="text-blue-600 hover:underline">Lihat Detail</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="bg-gray-200">
                                    <td colspan="4" class="p-3 text-gray-700 text-right font-bold text-sm">Jumlah Pemasukan</td>
                                    <td class="p-3 text-gray-700 text-left font-bold text-sm">
                                        Rp <?= number_format($totalPemasukan, 0, ',', '.') ?>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        <?php else: ?>
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="p-3 font-semibold text-gray-700 text-left text-sm">Periode</th>
                                    <th class="p-3 font-semibold text-gray-700 text-left text-sm">Sumber</th>
                                    <th class="p-3 font-semibold text-gray-700 text-left text-sm">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        <td class="p-3 text-gray-700 font-semibold text-sm">
                                            <?php
                                            if ($periode == 'bulan') {
                                                echo $row['bulan'] . '-' . $row['tahun'];
                                            } elseif ($periode == 'tahun') {
                                                echo $row['tahun'];
                                            } elseif ($periode == 'minggu') {
                                                echo date('d-m-Y', strtotime($row['mulai'])) . " s/d " . date('d-m-Y', strtotime($row['akhir']));
                                            } else {
                                                echo $row['label'];
                                            }
                                            ?>
                                        </td>
                                        <td class="p-3 text-gray-700 font-semibold text-sm">
                                            <?= ucfirst($row['sumber']) ?>
                                        </td>
                                        <td class="p-3 text-gray-700 font-semibold text-sm">
                                            Rp <?= number_format($row['pemasukan'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="bg-gray-200">
                                    <td colspan="2" class="p-3 text-gray-700 text-right font-bold text-sm">Jumlah Pemasukan</td>
                                    <td class="p-3 text-gray-700 text-left font-bold text-sm">
                                        Rp <?= number_format($totalPemasukan, 0, ',', '.') ?>
                                    </td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
