<?php
include '../Config/koneksi.php';
session_start();

$pemasukan = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pemasukan WHERE sumber != 'hutang'");
$pengeluaran = mysqli_query($conn, "SELECT SUM(total) AS total FROM pengeluaran");

$totalPemasukan = mysqli_fetch_assoc($pemasukan)['total'] ?? 0;
$totalPengeluaran = mysqli_fetch_assoc($pengeluaran)['total'] ?? 0;
$laba = $totalPemasukan - $totalPengeluaran;

include '../Layout/sidebar.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Keuangan</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
  </style>
</head>
<body class="h-screen bg-gray-50 flex">

  <div class="flex flex-1 flex-col">
    <!-- HEADER -->
    <?php include '../Layout/header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="p-6 pt-24 pl-64">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-center mb-6">Dashboard Ringkasan Keuangan</h1>

        <!-- Ringkasan Box -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white p-4 rounded shadow text-center">
            <h2 class="text-sm text-gray-500">Total Pemasukan</h2>
            <p class="text-xl font-semibold text-green-600">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></p>
          </div>
          <div class="bg-white p-4 rounded shadow text-center">
            <h2 class="text-sm text-gray-500">Total Pengeluaran</h2>
            <p class="text-xl font-semibold text-red-600">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></p>
          </div>
          <div class="bg-white p-4 rounded shadow text-center">
            <h2 class="text-sm text-gray-500">Laba Bersih</h2>
            <p class="text-xl font-semibold text-blue-600">Rp <?= number_format($laba, 0, ',', '.') ?></p>
          </div>
        </div>

        <!-- Grafik -->
        <div class="bg-white p-6 rounded shadow">
          <h2 class="text-lg font-bold mb-4">Grafik Ringkasan</h2>
          <canvas id="keuanganChart"></canvas>
        </div>
      </div>
    </main>

    <!-- FOOTER -->
    <div>
      <?php include '../Layout/footer.php'; ?>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('keuanganChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Pemasukan', 'Pengeluaran', 'Laba'],
        datasets: [{
          label: 'Jumlah (Rp)',
          data: [<?= $totalPemasukan ?>, <?= $totalPengeluaran ?>, <?= $laba ?>],
          backgroundColor: ['#22c55e', '#ef4444', '#3b82f6'],
          borderRadius: 5
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          title: {
            display: true,
            text: 'Ringkasan Keuangan',
            font: { size: 16 }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => 'Rp ' + value.toLocaleString('id-ID')
            }
          }
        }
      }
    });
  </script>

</body>
</html>
