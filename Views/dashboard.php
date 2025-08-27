<?php
// Mulai session sebelum HTML
session_start();
include '../Config/koneksi.php';
if (!isset($_SESSION['id_user'])) {
    die("Akses ditolak. Anda bukan user.");
}


// Include sidebar
include '../Layout/sidebar.php';
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
        <?php include '../Layout/header.php'; ?>

        <main class="p-6 pt-17 flex-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-lg font-bold">Selamat datang!</h1>
                <p class="text-gray-600">Ini adalah halaman dashboard kamu.</p>
            </div>
        </main>
    </section>

</body>

</html>