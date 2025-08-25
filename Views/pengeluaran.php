<?php
session_start();
if (!isset($_SESSION['id_user'])) {
  header('Location: index.php');
  die("Anda Bukan User");
}
require '../Config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM pengeluaran ORDER BY id_pengeluaran DESC");
include '../Layout/sidebar.php';
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
      <h1 class="font-bold text-xl">Pengeluaran</h1>
      <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700">
        <a href="tambahPengeluaran.php">+ Tambah Pengeluaran</a></button>
      <table>
        <tr>
          <th>ID</th>
          <th>Tanggal</th>
          <th>Kategori</th>
          <th>Keperluan</th>
          <th>Jumlah Barang</th>
          <th>Total</th>
          <th>Supplier</th>
          <th>Aksi</th>
        </tr>
        <tr>
          <?php while ($row = mysqli_fetch_assoc($query)) : ?>
            <td><?= $row['id_pengeluaran'] ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
            <td><?= ucfirst($row['kategori']) ?></td>
            <td><?= htmlspecialchars($row['keperluan']) ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td><?= number_format($row['total'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($row['supplier']) ?></td>
            <td>
              <a href="editPengeluaran.php?id_pengeluaran=<?= $row['id_pengeluaran'] ?>">Edit</a> |
              <a href="hapusPengeluaran.php?id_pengeluaran=<?= $row['id_pengeluaran'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
          <?php endwhile; ?>
        </tr>
      </table>
    </main>
  </div>

</body>

</html>