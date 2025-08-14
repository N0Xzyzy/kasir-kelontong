<?php
session_start();
$id_user = $_SESSION['id_user'];

// Koneksi database
include "Config/koneksi.php";

// Ambil data barang
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql_barang = "SELECT * FROM barang WHERE nama_barang LIKE '%$search%'";
$result_barang = $conn->query($sql_barang);

// Proses transaksi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $tanggal = date('Y-m-d');
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $id_user = $_SESSION['id_user'];

    $total_transaksi = 0;

    foreach ($id_barang as $key => $id) {
        $jumlah_beli = intval($jumlah[$key]);

        // Ambil harga jual barang
        $barang_res = $conn->query("SELECT harga_jual, stok FROM barang WHERE id_barang=$id")->fetch_assoc();
        $harga_jual = $barang_res['harga_jual'];
        $stok = $barang_res['stok'];

        // Hitung subtotal
        $subtotal = $harga_jual * $jumlah_beli;
        $total_transaksi += $subtotal;

        // Masukkan ke tabel transaksi
        $conn->query("INSERT INTO transaksi (tanggal, id_barang, jumlah, harga_jual, subtotal, total_transaksi, metode_pembayaran, id_user) 
                      VALUES ('$tanggal', $id, $jumlah_beli, $harga_jual, $subtotal, $total_transaksi, '$metode_pembayaran', $id_user)");

        // Update stok barang
        $conn->query("UPDATE barang SET stok = stok - $jumlah_beli WHERE id_barang=$id");
    }
    echo "<script>alert('Transaksi berhasil!'); window.location='transaksi.php';</script>";
}
include 'Layout/sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
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
  <?php include 'Layout/header.php'; ?>

  <div class="flex">
        <!-- Kiri: Konfirmasi Transaksi -->
        <div class="w-1/3 p-4 bg-white border rounded shadow h-screen overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Konfirmasi Transaksi</h2>
            <form method="POST">
                <div id="cartItems" class="space-y-3"></div>
                <div class="mt-4">
                    <label class="block font-semibold">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="w-full p-2 border rounded">
                        <option value="Tunai">Tunai</option>
                        <option value="Hutang">Hutang</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" name="checkout" class="w-full bg-green-500 text-white p-2 rounded hover:bg-green-600">Bayar</button>
                </div>

            </form>
        </div>

        <!-- Kanan: Pilih Barang -->
        <div class="w-2/3 p-4">
            <form method="GET" class="mb-4 flex">
                <input type="text" name="search" placeholder="Cari barang..." value="<?= htmlspecialchars($search) ?>" class="flex-1 p-2 border rounded-l">
                <button class="bg-blue-500 text-white px-4 rounded-r">Cari</button>
            </form>
            <div class="grid grid-cols-3 gap-4">
                <?php while ($row = $result_barang->fetch_assoc()) : ?>
                    <div class="bg-white p-4 border rounded shadow flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold"><?= $row['nama_barang'] ?></h3>
                            <p>Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></p>
                            <p class="text-sm text-gray-500">Stok: <?= $row['stok'] ?></p>
                        </div>
                        <button type="button" onclick="addToCart(<?= $row['id_barang'] ?>, '<?= $row['nama_barang'] ?>', <?= $row['harga_jual'] ?>)" class="mt-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Tambah</button>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</section>
    

<script>
    let cart = [];

    function addToCart(id, nama, harga) {
        let item = cart.find(i => i.id === id);
        if (item) {
            item.jumlah += 1;
        } else {
            cart.push({ id: id, nama: nama, harga: harga, jumlah: 1 });
        }
        renderCart();
    }

    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        renderCart();
    }

    function renderCart() {
    let cartContainer = document.getElementById('cartItems');
    cartContainer.innerHTML = '';

    let totalHarga = 0;

    cart.forEach(item => {
        let subtotal = item.harga * item.jumlah;
        totalHarga += subtotal;

        cartContainer.innerHTML += `
            <div class="border p-2 rounded flex justify-between items-center">
                <div>
                    <p class="font-bold">${item.nama}</p>
                    <p>Rp ${item.harga.toLocaleString()} x ${item.jumlah}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="decreaseQty(${item.id})"
                        class="bg-gray-300 px-2 py-1 rounded hover:bg-gray-400">-</button>
                    
                    <span>${item.jumlah}</span>
                    
                    <button type="button" onclick="increaseQty(${item.id})"
                        class="bg-gray-300 px-2 py-1 rounded hover:bg-gray-400">+</button>

                    <button type="button" onclick="removeFromCart(${item.id})"
                        class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-sm">Hapus</button>

                    <input type="hidden" name="id_barang[]" value="${item.id}">
                    <input type="hidden" name="jumlah[]" value="${item.jumlah}">
                </div>
            </div>
        `;
    });

    // Tambahkan total harga di bawah daftar item
    cartContainer.innerHTML += `
        <div class="mt-4 p-2 border-t font-bold text-lg">
            Total: Rp ${totalHarga.toLocaleString()}
        </div>
    `;
}


function decreaseQty(id) {
    let item = cart.find(i => i.id === id);
    if (item && item.jumlah > 1) {
        item.jumlah -= 1;
    } else {
        cart = cart.filter(i => i.id !== id);
    }
    renderCart();
}

function increaseQty(id) {
    let item = cart.find(i => i.id === id);
    if (item) {
        item.jumlah += 1;
    }
    renderCart();
}


</script>

</body>
</html>