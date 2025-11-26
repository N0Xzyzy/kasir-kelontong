<?php
include '../Config/koneksi.php';
session_start();
include 'laporan_data.php';

$results = get_laporan_data($conn);
$dataAll = $results['data'];
$totals = $results['totals'];
$periode = $results['periode'];

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
  <meta charset="UTF-8">
  <title>Laporan Keuangan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Montserrat', sans-serif; }
  </style>
</head>

<body class="h-screen bg-gray-50 flex">

<!-- SIDEBAR -->
<?php include "../Layout/sidebar.php"; ?>

<div class="flex flex-1 flex-col">

  <!-- HEADER -->
  <?php include "../Layout/header.php"; ?>

  <!-- MAIN -->
  <main class="p-6 pt-24 lg:pl-64">
    <div class="max-w-7xl mx-auto">

      <!-- JUDUL -->
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold">Laporan Keuangan</h1>
        <p class="text-gray-500 text-sm">Rekap pemasukan & pengeluaran</p>
      </div>

      <!-- FILTER -->
      <form method="get" class="bg-white rounded shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="text-sm font-medium">Periode</label>
          <select name="periode" class="mt-1 w-full border rounded p-2">
            <option value="hari" <?=($periode=='hari')?'selected':''?>>Harian</option>
            <option value="bulan" <?=($periode=='bulan')?'selected':''?>>Bulanan</option>
            <option value="tahun" <?=($periode=='tahun')?'selected':''?>>Tahunan</option>
          </select>
        </div>

        <div>
          <label class="text-sm font-medium">Tanggal Mulai</label>
          <input type="date" name="mulai" value="<?= $_GET['mulai'] ?? '' ?>" class="mt-1 w-full border rounded p-2">
        </div>

        <div>
          <label class="text-sm font-medium">Tanggal Akhir</label>
          <input type="date" name="akhir" value="<?= $_GET['akhir'] ?? '' ?>" class="mt-1 w-full border rounded p-2">
        </div>

        <div>
          <label class="text-sm font-medium">Tanggal Tertentu</label>
          <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? '' ?>" class="mt-1 w-full border rounded p-2">
        </div>

        <div class="md:col-span-4 flex flex-wrap gap-2 justify-between mt-2">
          <div class="flex gap-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Terapkan</button>
            <a href="laporan.php" class="bg-gray-200 px-4 py-2 rounded text-sm">Reset</a>
          </div>

          <div class="flex gap-2">
            <a href="export_csv.php?<?=build_qs()?>" class="bg-indigo-600 text-white px-3 py-2 rounded text-sm">CSV</a>
            <a href="export_pdf.php?<?=build_qs()?>" class="bg-red-600 text-white px-3 py-2 rounded text-sm">PDF</a>
            <button onclick="window.print()" type="button" class="bg-gray-800 text-white px-3 py-2 rounded text-sm">Print</button>
          </div>
        </div>
      </form>

      <!-- SUMMARY -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-4 rounded shadow text-center">
          <h2 class="text-sm text-gray-500">Total Pemasukan</h2>
          <p class="text-xl font-semibold text-green-600">Rp <?= number_format($totals['totalPemasukan'],0,',','.') ?></p>
        </div>

        <div class="bg-white p-4 rounded shadow text-center">
          <h2 class="text-sm text-gray-500">Total Pengeluaran</h2>
          <p class="text-xl font-semibold text-red-600">Rp <?= number_format($totals['totalPengeluaran'],0,',','.') ?></p>
        </div>

        <div class="bg-white p-4 rounded shadow text-center">
          <h2 class="text-sm text-gray-500">Total Laba</h2>
          <p class="text-xl font-semibold text-blue-600">Rp <?= number_format($totals['totalLaba'],0,',','.') ?></p>
        </div>
      </div>

      <!-- GRAFIK -->
      <div class="bg-white p-6 rounded shadow mb-6">
        <div class="flex flex-wrap items-center justify-between mb-4">
          <h2 class="text-lg font-bold">Grafik Laporan</h2>
          <div class="flex gap-3 text-sm">
            <label><input type="radio" name="chartType" value="line" checked> Line</label>
            <label><input type="radio" name="chartType" value="bar"> Bar</label>
            <label><input type="radio" name="chartType" value="pie"> Pie</label>
          </div>
        </div>

        <div class="relative h-[320px]">
          <canvas id="chartLaporan"></canvas>
        </div>
      </div>

      <!-- TABEL -->
      <div class="bg-white p-4 rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-2 text-left">Periode</th>
              <th class="p-2 text-left">Pemasukan</th>
              <th class="p-2 text-left">Pengeluaran</th>
              <th class="p-2 text-left">Laba</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!$data): ?>
              <tr>
                <td colspan="4" class="text-center p-4 text-gray-500">Tidak ada data</td>
              </tr>
            <?php endif; ?>

            <?php foreach ($data as $row): ?>
              <tr class="border-b">
                <td class="p-2"><?= htmlspecialchars($row['periode']) ?></td>
                <td class="p-2 text-green-600">Rp <?= number_format($row['pemasukan'],0,',','.') ?></td>
                <td class="p-2 text-red-600">Rp <?= number_format($row['pengeluaran'],0,',','.') ?></td>
                <td class="p-2 text-blue-600">Rp <?= number_format($row['laba'],0,',','.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- PAGINATION -->
        <div class="flex flex-wrap justify-between items-center mt-4 text-sm">
          <span class="text-gray-600">
            Menampilkan <?= ($start+1) ?>–<?= min($start+$perPage, $totalRows) ?> dari <?= $totalRows ?>
          </span>

          <div class="flex gap-1 mt-2 sm:mt-0">
            <?php for($i=1;$i<=$totalPages;$i++): ?>
              <a href="?<?=build_qs(['page'=>$i])?>" 
                 class="px-3 py-1 rounded <?= $i==$page?'bg-blue-600 text-white':'bg-gray-200' ?>">
                 <?= $i ?>
              </a>
            <?php endfor; ?>
          </div>
        </div>
      </div>

    </div>
  </main>

  <?php include "../Layout/footer.php"; ?>

</div>

<script>
const labels = <?= json_encode(array_column($dataAll,'periode')) ?>;
const pemasukan = <?= json_encode(array_column($dataAll,'pemasukan')) ?>;
const pengeluaran = <?= json_encode(array_column($dataAll,'pengeluaran')) ?>;
const laba = <?= json_encode(array_column($dataAll,'laba')) ?>;

let chart = null;
function renderChart(type='line'){
  const ctx = document.getElementById('chartLaporan').getContext('2d');
  if(chart) chart.destroy();

  if(type==='pie'){
    chart = new Chart(ctx,{
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
    chart = new Chart(ctx,{
      type:type,
      data:{
        labels:labels,
        datasets:[
          { label:'Pemasukan', data:pemasukan, borderWidth:2 },
          { label:'Pengeluaran', data:pengeluaran, borderWidth:2 },
          { label:'Laba', data:laba, borderWidth:2 }
        ]
      },
      options:{
        responsive:true,
        maintainAspectRatio:false
      }
    });
  }
}

renderChart();
document.querySelectorAll('input[name=chartType]').forEach(el=>{
  el.addEventListener('change',()=>renderChart(el.value));
});
</script>

</body>
</html>
