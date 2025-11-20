<?php
include '../Config/koneksi.php';
session_start();

include 'laporan_data.php'; // fungsi get_laporan_data()

$results = get_laporan_data($conn);
$dataAll = $results['data'];
$totals = $results['totals'];
$periode = $results['periode'];

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 10;
$totalRows = count($dataAll);
$totalPages = (int)ceil($totalRows / $perPage);
$start = ($page - 1) * $perPage;
$data = array_slice($dataAll, $start, $perPage);

function build_qs($overrides = []) {
    $qs = array_merge($_GET, $overrides);
    return http_build_query($qs);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* FIX chart tidak memanjang */
        #chart-wrapper {
            height: 350px; 
            position: relative;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex">

<!-- ===================== SIDEBAR ===================== -->
<?php include "../Layout/sidebar.php"; ?>

<div class="flex flex-col w-full">
    
    <!-- ===================== HEADER ===================== -->
    <?php include "../Layout/header.php"; ?>

    <main class="p-6 pt-24">

        <div class="max-w-7xl mx-auto">

            <header class="mb-6">
                <h1 class="text-2xl font-bold">Laporan Keuangan</h1>
                <p class="text-sm text-gray-600">Gabungan pemasukan & pengeluaran — tampilkan, cetak, atau ekspor.</p>
            </header>

            <!-- FILTER -->
            <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-white p-4 rounded shadow mb-4">
                <div>
                    <label class="block text-sm font-medium">Periode</label>
                    <select name="periode" class="mt-1 block w-full border rounded p-2">
                        <option value="hari" <?= ($periode=='hari')?'selected':'' ?>>Harian</option>
                        <option value="bulan" <?= ($periode=='bulan')?'selected':'' ?>>Bulanan</option>
                        <option value="tahun" <?= ($periode=='tahun')?'selected':'' ?>>Tahunan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Tanggal Mulai</label>
                    <input type="date" name="mulai" value="<?= htmlspecialchars($_GET['mulai'] ?? '') ?>" class="mt-1 block w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Tanggal Akhir</label>
                    <input type="date" name="akhir" value="<?= htmlspecialchars($_GET['akhir'] ?? '') ?>" class="mt-1 block w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Tanggal Tertentu</label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($_GET['tanggal'] ?? '') ?>" class="mt-1 block w-full border rounded p-2">
                </div>

                <div class="md:col-span-4 flex gap-2 mt-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Terapkan</button>
                    <a href="laporan.php" class="px-4 py-2 bg-gray-200 rounded">Reset</a>

                    <!-- EXPORT -->
                    <a href="export_csv.php?<?= build_qs() ?>" class="ml-auto px-4 py-2 bg-indigo-600 text-white rounded">Export CSV</a>
                    <a href="export_pdf.php?<?= build_qs() ?>" class="px-4 py-2 bg-red-600 text-white rounded">Export PDF</a>

                    <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-800 text-white rounded">Print</button>
                </div>
            </form>

            <!-- SUMMARY -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm text-gray-500">Total Pemasukan</div>
                    <div class="text-xl font-bold">Rp <?= number_format($totals['totalPemasukan'],0,',','.') ?></div>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm text-gray-500">Total Pengeluaran</div>
                    <div class="text-xl font-bold">Rp <?= number_format($totals['totalPengeluaran'],0,',','.') ?></div>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <div class="text-sm text-gray-500">Total Laba</div>
                    <div class="text-xl font-bold">Rp <?= number_format($totals['totalLaba'],0,',','.') ?></div>
                </div>
            </div>

            <!-- CHART SECTION -->
            <div class="bg-white p-4 rounded shadow mb-4">

                <div class="flex gap-4 items-center mb-3">
                    <h2 class="font-semibold">Grafik</h2>
                    <label><input type="radio" name="chartType" value="line" checked> Line</label>
                    <label><input type="radio" name="chartType" value="bar"> Bar</label>
                    <label><input type="radio" name="chartType" value="pie"> Pie</label>
                </div>

                <div id="chart-wrapper">
                    <canvas id="chartLaporan"></canvas>
                </div>
            </div>

            <!-- TABLE -->
            <div class="bg-white p-4 rounded shadow mb-6 overflow-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-2">Periode</th>
                            <th class="p-2">Pemasukan</th>
                            <th class="p-2">Pengeluaran</th>
                            <th class="p-2">Laba</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($data) === 0): ?>
                            <tr><td colspan="4" class="p-4 text-center text-gray-500">Tidak ada data</td></tr>
                        <?php endif; ?>

                        <?php foreach ($data as $row): ?>
                            <tr class="border-b">
                                <td class="p-2"><?= htmlspecialchars($row['periode']) ?></td>
                                <td class="p-2">Rp <?= number_format($row['pemasukan'],0,',','.') ?></td>
                                <td class="p-2">Rp <?= number_format($row['pengeluaran'],0,',','.') ?></td>
                                <td class="p-2">Rp <?= number_format($row['laba'],0,',','.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- PAGINATION -->
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Menampilkan <?= ($start+1) ?> - <?= min($start + $perPage, $totalRows) ?> dari <?= $totalRows ?> baris
                    </div>

                    <div class="flex gap-2">
                        <?php for ($i=1; $i<=$totalPages; $i++): ?>
                            <a href="?<?= build_qs(['page'=>$i]) ?>" class="px-3 py-1 rounded <?= $i==$page?'bg-blue-600 text-white':'bg-gray-100' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- ===================== FOOTER ===================== -->
    <?php include "../Layout/footer.php"; ?>

</div>

<script>
const labels = <?= json_encode(array_column($dataAll,'periode')) ?>;
const pemasukanData = <?= json_encode(array_column($dataAll,'pemasukan')) ?>;
const pengeluaranData = <?= json_encode(array_column($dataAll,'pengeluaran')) ?>;
const labaData = <?= json_encode(array_column($dataAll,'laba')) ?>;

let chartObj = null;
function renderChart(type='line') {
    const ctx = document.getElementById('chartLaporan').getContext('2d');
    if (chartObj) chartObj.destroy();

    const datasets = [
        { label:'Pemasukan', data:pemasukanData, borderWidth:2, fill:false },
        { label:'Pengeluaran', data:pengeluaranData, borderWidth:2, fill:false },
        { label:'Laba', data:labaData, borderWidth:2, fill:false }
    ];

    if (type === 'pie') {
        chartObj = new Chart(ctx, {
            type:'pie',
            data:{
                labels:['Pemasukan','Pengeluaran','Laba'],
                datasets:[{
                    data:[
                        <?= (float)$totals['totalPemasukan'] ?>,
                        <?= (float)$totals['totalPengeluaran'] ?>,
                        <?= (float)$totals['totalLaba'] ?>
                    ]
                }]
            }
        });
    } else {
        chartObj = new Chart(ctx, {
            type:type,
            data:{ labels:labels, datasets:datasets },
            options:{ responsive:true, maintainAspectRatio:false }
        });
    }
}

renderChart('line');

document.querySelectorAll('input[name="chartType"]').forEach(el=>{
    el.addEventListener('change', ()=> renderChart(el.value));
});
</script>

</body>
</html>
