<?php
session_start();
include 'Config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $total = $_POST['total'];
    $id_user = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO transaksi (tanggal, total, id_user) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $tanggal, $total, $id_user);
    $stmt->execute();
    echo "Transaksi berhasil";
}

include 'Layout/sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
<body class="bg-gray-100 h-screen flex">
  <section class="flex-1 flex flex-col">
    <?php include 'Layout/header.php';?>
    <form method="POST">
  <input type="date" name="tanggal" required>
  <input type="number" step="0.01" name="total" placeholder="Total" required>
  <button type="submit">Simpan Transaksi</button>
</form>
  </section>
  
</body>
</html>

