<?php
include '../Config/koneksi.php';
session_start();

$pemasukan = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM pemasukan WHERE sumber != 'hutang'");
$pengeluaran = mysqli_query($conn, "SELECT SUM(total) AS total FROM pengeluaran");
$laba = 0;

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
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
  </style>
</head>
<body class="h-screen flex bg-gray-50">
    <div class="flex flex-1 flex-col">
        <?php include '../Layout/header.php';?>
        <main class="p-6 pt-18 flex justify-center">
            <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-7xl">
                <h1 style="text-align:center; font-weight:bold;">Dashboard Ringkasan Keuangan</h1>
                <div class="chart-container">
                <canvas id="keuanganChart"></canvas>
                </div>
            </div>
        </main>
    </div>
  

  <script>
    const ctx = document.getElementById('keuanganChart').getContext('2d');
    const keuanganChart = new Chart(ctx, {
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
            font: { size: 18 }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp ' + value.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });
  </script>
</body>
</html>
