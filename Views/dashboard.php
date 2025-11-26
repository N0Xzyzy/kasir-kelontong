<?php
include '../Config/koneksi.php';
session_start();

$pemasukan = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pemasukan WHERE sumber != 'hutang'");
$pengeluaran = mysqli_query($conn, "SELECT SUM(total) AS total FROM pengeluaran");

$totalPemasukan = mysqli_fetch_assoc($pemasukan)['total'] ?? 0;
$totalPengeluaran = mysqli_fetch_assoc($pengeluaran)['total'] ?? 0;
$laba = $totalPemasukan - $totalPengeluaran;

$q_hutang = mysqli_query($conn, "SELECT SUM(jumlah_hutang) AS total FROM hutang_pelanggan WHERE status='belum'");
$totalHutang = mysqli_fetch_assoc($q_hutang)['total'] ?? 0;

// Jumlah user
$q_users = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$totalUsers = mysqli_fetch_assoc($q_users)['total'];

// Total barang aktif
$q_barang = mysqli_query($conn, "SELECT COUNT(*) AS total FROM barang WHERE status='aktif'");
$totalBarang = mysqli_fetch_assoc($q_barang)['total'];

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

    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <!-- CARD SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

      <!-- Total Pemasukan -->
      <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
        <div class="p-3 bg-green-100 rounded-full">
          💰
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Pemasukan</p>
          <h2 class="text-xl font-bold text-green-600">
            Rp <?= number_format($totalPemasukan, 0, ',', '.') ?>
          </h2>
        </div>
      </div>

      <!-- Total Pengeluaran -->
      <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
        <div class="p-3 bg-red-100 rounded-full">
          📦
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Pengeluaran</p>
          <h2 class="text-xl font-bold text-red-600">
            Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?>
          </h2>
        </div>
      </div>

      <!-- Laba -->
      <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
        <div class="p-3 bg-blue-100 rounded-full">
          📊
        </div>
        <div>
          <p class="text-sm text-gray-500">Laba Bersih</p>
          <h2 class="text-xl font-bold text-blue-600">
            Rp <?= number_format($laba, 0, ',', '.') ?>
          </h2>
        </div>
      </div>

      <!-- Total Hutang -->
      <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
        <div class="p-3 bg-yellow-100 rounded-full">
          📝
        </div>
        <div>
          <p class="text-sm text-gray-500">Hutang Belum Lunas</p>
          <h2 class="text-xl font-bold text-yellow-600">
            Rp <?= number_format($totalHutang, 0, ',', '.') ?>
          </h2>
        </div>
      </div>

    </div>

    <!-- CARD GRID 2 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

      <!-- Total Barang Aktif -->
      <div class="bg-white p-5 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Total Barang Aktif</p>
        <h2 class="text-3xl font-bold text-indigo-600"><?= $totalBarang ?></h2>
      </div>

      <!-- Total User -->
      <div class="bg-white p-5 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Total Pengguna</p>
        <h2 class="text-3xl font-bold text-purple-600"><?= $totalUsers ?></h2>
      </div>

      <!-- Keterangan -->
      <div class="bg-white p-5 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Update Terakhir</p>
        <h2 class="text-lg font-bold"><?= date('d M Y') ?></h2>
      </div>

    </div>

    <!-- Grafik -->
    <div class="bg-white p-6 rounded-xl shadow">
      <h2 class="text-lg font-bold mb-4">Grafik Ringkasan Keuangan</h2>
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
