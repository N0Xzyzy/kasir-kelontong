<?php
session_start();
if (!isset($_SESSION['id_user'])) {
  header('Location: index.php');
  die("Anda Bukan User");
}
require '../Config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM pengeluaran ORDER BY id_pengeluaran DESC");

$totalPengeluaran = mysqli_query($conn, "SELECT SUM(total) as total_semua FROM pengeluaran");
$totalRow = mysqli_fetch_assoc($totalPengeluaran);
$grandTotal = $totalRow['total_semua'] ?? 0;

include '../Layout/sidebar.php';
include '../Layout/footer.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengeluaran</title>
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

<body class="bg-gray-100 h-full flex">
  <div class="flex flex-1 flex-col">
    <?php include '../Layout/header.php';
    if (isset($_SESSION['msg'])) {
      echo "<p style='color: green;'>" . $_SESSION['msg'] . "</p>";
      unset($_SESSION['msg']);
    }
    ?>
    <main class="p-6 pt-17 flex-1">
      <div class="bg-white rounded-lg shadow p-6">
        <h1 class="font-bold text-xl">Pengeluaran</h1>
        <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700">
          <a href="tambahPengeluaran.php">+ Tambah Pengeluaran</a></button>
        <table class="w-full">
          <thead class="border-b border-gray-200 bg-gray-50">
            <tr>
              <th class="p-3 text-sm font-semibold text-left text-gray-700">ID</th>
              <th class="p-3 text-sm font-semibold text-left text-gray-700">Tanggal</th>
              <th class="p-3 text-sm font-semibold text-left text-gray-700">Kategori</th>
              <th class="p-3 text-sm font-semibold text-left text-gray-700">Keperluan</th>
              <th class="p-3 text-sm font-semibold text-left text-gray-700">Jumlah Barang</th>
              <th class="p-3 text-sm font-semibold text-left text-gray-700">Total</th>
              <th class="p-3 text-sm font-semibold text-left text-gray-700">Supplier</th>
              <?php if (isset($_SESSION['id_user']) && ($_SESSION['role'] === 'owner')) { ?>
                <th class="p-3 text-sm font-semibold text-left text-gray-700">Aksi</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) : ?>
            <tr class="odd:bg-white even:bg-gray-50">
                <td class="p-3 text-sm text-gray-700"><?= $row['id_pengeluaran'] ?></td>
                <td class="p-3 text-sm text-gray-700"><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                <td class="p-3 text-sm text-gray-700"><?= ucfirst($row['kategori']) ?></td>
                <td class="p-3 text-sm text-gray-700"><?= htmlspecialchars($row['keperluan']) ?></td>
                <td class="p-3 text-sm text-gray-700"><?= $row['jumlah'] ?></td>
                <td class="p-3 text-sm text-gray-700"><?= number_format($row['total'], 2, ',', '.') ?></td>
                <td class="p-3 text-sm text-gray-700"><?= htmlspecialchars($row['supplier']) ?></td>
                <td class="p-3 text-sm text-gray-700">
                  <a class="p-1.5 tracking-wider bg-green-300 text-green-800 rounded-sm bg-opacity-30 cursor-pointer" href="editPengeluaran.php?id_pengeluaran=<?= $row['id_pengeluaran'] ?>">Edit</a> |
                  <a class="p-1.5 tracking-wider bg-red-300 text-red-800 rounded-sm bg-opacity-30 cursor-pointer" href="hapusPengeluaran.php?id_pengeluaran=<?= $row['id_pengeluaran'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
          <tfoot>
            <tr class="bg-gray-100 font-bold ">
              <td colspan="5"></td>
              <td class="p-3 text-gray-700 text-right text-sm">Jumlah Pengeluaran</td>
              <td colspan="2"><?= number_format($grandTotal, 2, ',', '.') ?></td>
            </tr>
          </tfoot>
        </table>
      </div>

    </main>
  </div>

</body>

</html>