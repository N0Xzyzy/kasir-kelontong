<?php
include '../Config/koneksi.php';
session_start();

// Ambil periode & filter tanggal
$periode        = $_GET['periode'] ?? 'hari';
$tanggal        = $_GET['tanggal'] ?? '';
$tanggal_awal   = $_GET['awal'] ?? '';
$tanggal_akhir  = $_GET['akhir'] ?? '';

$sql = "";

// ==============================
//  FILTER UTAMA BERDASARKAN PERIODE
// ==============================
switch ($periode) {
    case 'minggu':
        $sql = "SELECT YEARWEEK(tanggal, 1) AS kode_minggu, 
                       MIN(DATE(tanggal)) AS mulai, 
                       MAX(DATE(tanggal)) AS akhir,
                       SUM(jumlah) AS pemasukan,
                       sumber
                FROM pemasukan
                WHERE sumber != 'hutang' ";
        break;

    case 'bulan':
        $sql = "SELECT YEAR(tanggal) AS tahun, 
                       MONTH(tanggal) AS bulan, 
                       SUM(jumlah) AS pemasukan,
                       sumber
                FROM pemasukan
                WHERE sumber != 'hutang' ";
        break;

    case 'tahun':
        $sql = "SELECT YEAR(tanggal) AS tahun, 
                       SUM(jumlah) AS pemasukan,
                       sumber
                FROM pemasukan
                WHERE sumber != 'hutang' ";
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
        ";
        break;

    case 'hari':
    default:
        $sql = "SELECT DATE(tanggal) AS label, 
                       SUM(jumlah) AS pemasukan,
                       sumber
                FROM pemasukan
                WHERE sumber != 'hutang' ";
        break;
}


// ==============================
//  FILTER TAMBAHAN (TANGGAL TUNGGAL / RANGE)
// ==============================
$filterTanggal = "";
if (!empty($tanggal)) {
    $filterTanggal = " AND DATE(tanggal) = '$tanggal' ";
}

if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $filterTanggal = " AND DATE(tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir' ";
}

if ($periode !== 'transaksi') {
    $sql .= $filterTanggal;
}


// ==============================
//  GROUP BY BERDASARKAN PER PERIODE
// ==============================
if ($periode == 'minggu') {
    $sql .= " GROUP BY YEARWEEK(tanggal, 1), sumber 
              ORDER BY YEARWEEK(tanggal, 1) DESC";
} elseif ($periode == 'bulan') {
    $sql .= " GROUP BY YEAR(tanggal), MONTH(tanggal), sumber 
              ORDER BY tahun DESC, bulan DESC";
} elseif ($periode == 'tahun') {
    $sql .= " GROUP BY YEAR(tanggal), sumber 
              ORDER BY tahun DESC";
} elseif ($periode == 'hari') {
    $sql .= " GROUP BY DATE(tanggal), sumber 
              ORDER BY DATE(tanggal) DESC";
} else {
    // transaksi → tambahkan order by
    if (!empty($tanggal)) {
        $sql .= " AND DATE(tanggal) = '$tanggal' ";
    }
    if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
        $sql .= " AND DATE(tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir' ";
    }
    $sql .= " ORDER BY tanggal DESC, id_transaksi DESC";
}


// Eksekusi
include '../Layout/sidebar.php';
include '../Layout/footer.php';

$result = mysqli_query($conn, $sql);
$data = [];
$totalPemasukan = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
    $totalPemasukan += ($periode == 'transaksi') ? $row['total_transaksi'] : $row['pemasukan'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pemasukan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Auto Refresh 30 Detik -->
    <meta http-equiv="refresh" content="30">

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

        <main class="p-6 pt-24 pl-32 flex-1">
            <div class="bg-white rounded-lg shadow p-6">

                <h1 class="text-2xl font-bold mb-4">Laporan Pemasukan</h1>

                <!-- FILTER -->
                <form method="get" class="mb-4 flex gap-6 items-end flex-wrap">

                    <!-- Periode -->
                    <div>
                        <label class="font-medium block mb-1">Periode:</label>
                        <select name="periode" onchange="this.form.submit()" class="border rounded p-2">
                            <option value="hari"      <?= $periode == 'hari' ? 'selected' : '' ?>>Harian</option>
                            <option value="minggu"    <?= $periode == 'minggu' ? 'selected' : '' ?>>Mingguan</option>
                            <option value="bulan"     <?= $periode == 'bulan' ? 'selected' : '' ?>>Bulanan</option>
                            <option value="tahun"     <?= $periode == 'tahun' ? 'selected' : '' ?>>Tahunan</option>
                            <option value="transaksi" <?= $periode == 'transaksi' ? 'selected' : '' ?>>Per Transaksi</option>
                        </select>
                    </div>

                    <!-- Tanggal Tunggal -->
                    <div>
                        <label class="font-medium block mb-1">Tanggal:</label>
                        <input type="date" name="tanggal" value="<?= $tanggal ?>" class="border rounded p-2">
                    </div>

                    <!-- Range Awal -->
                    <div>
                        <label class="font-medium block mb-1">Tanggal Awal:</label>
                        <input type="date" name="awal" value="<?= $tanggal_awal ?>" class="border rounded p-2">
                    </div>

                    <!-- Range Akhir -->
                    <div>
                        <label class="font-medium block mb-1">Tanggal Akhir:</label>
                        <input type="date" name="akhir" value="<?= $tanggal_akhir ?>" class="border rounded p-2">
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                        Filter
                    </button>
                </form>

                <!-- TABEL -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <table class="w-full">

                        <?php if ($periode == 'transaksi'): ?>

                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="p-3 text-left text-sm font-semibold">ID Transaksi</th>
                                    <th class="p-3 text-left text-sm font-semibold">Tanggal</th>
                                    <th class="p-3 text-left text-sm font-semibold">Metode</th>
                                    <th class="p-3 text-left text-sm font-semibold">Total</th>
                                    <th class="p-3 text-left text-sm font-semibold">Aksi</th>
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
                                            <a href="detail_transaksi.php?id_transaksi=<?= $row['id_transaksi'] ?>" class="text-blue-600 hover:underline">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <tr class="bg-gray-200 font-bold">
                                    <td colspan="3" class="p-3 text-right text-sm">Total Pemasukan</td>
                                    <td class="p-3 text-sm">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></td>
                                    <td></td>
                                </tr>
                            </tbody>

                        <?php else: ?>

                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="p-3 text-left text-sm font-semibold">Periode</th>
                                    <th class="p-3 text-left text-sm font-semibold">Sumber</th>
                                    <th class="p-3 text-left text-sm font-semibold">Jumlah</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        <td class="p-3 text-sm font-semibold">
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

                                        <td class="p-3 text-sm font-semibold">
                                            <?= ucfirst($row['sumber']) ?>
                                        </td>

                                        <td class="p-3 text-sm font-semibold">
                                            Rp <?= number_format($row['pemasukan'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <tr class="bg-gray-200 font-bold">
                                    <td colspan="2" class="p-3 text-right text-sm">Total Pemasukan</td>
                                    <td class="p-3 text-sm">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></td>
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
