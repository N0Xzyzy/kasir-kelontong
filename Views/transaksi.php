<?php
session_start();
$id_user = $_SESSION['id_user'];

include "../Config/koneksi.php";

// Ambil data barang
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql_barang = "SELECT * FROM barang WHERE nama_barang LIKE '%$search%'";
$result_barang = $conn->query($sql_barang);

// Proses transaksi
if (isset($_POST['checkout'])) {
    $metode = $_POST['metode_pembayaran'];
    $total = $_POST['total_transaksi'];
    $id_user = $_SESSION['id_user'];

    // 1. Simpan transaksi utama
    $query = "INSERT INTO transaksi (tanggal, total_transaksi, metode_pembayaran, id_user) VALUES (NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dsi", $total, $metode, $id_user);
    $stmt->execute();

    $id_transaksi = $stmt->insert_id;

    // 2. Simpan detail transaksi
    foreach ($_POST['id_barang'] as $i => $id_barang) {
        $jumlah = $_POST['jumlah'][$i];
        $harga = $_POST['harga'][$i]; // <-- sekarang sudah ada
        $subtotal = $jumlah * $harga;

        $query_detail = "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, harga_jual, subtotal) VALUES (?, ?, ?, ?, ?)";
        $stmt_detail = $conn->prepare($query_detail);
        $stmt_detail->bind_param("iiidd", $id_transaksi, $id_barang, $jumlah, $harga, $subtotal);
        $stmt_detail->execute();
    }

    // 3. Redirect
    if ($metode === "Hutang") {
        header("Location: tambahHutang.php?id_transaksi=" . $id_transaksi);
        exit();
    } else {
        echo "<script>alert('Transaksi Tunai berhasil disimpan!'); window.location.href='index.php';</script>";
    }
}


include '../Layout/sidebar.php';
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
        <?php include '../Layout/header.php'; ?>

        <div class="flex">
            <!-- Konfirmasi Transaksi -->
            <div class="w-1/3 p-4 bg-white border border-gray-300 rounded shadow h-screen flex flex-col">
                <h2 class="text-xl font-bold mb-4">Konfirmasi Transaksi</h2>
                <form method="POST" class="flex flex-col flex-1">

                    <!-- Bagian atas: Total & tombol -->
                    <div class="">
                        <div id="totalHarga" class="text-lg font-bold">Total: Rp 0</div>
                        <input type="hidden" name="total_transaksi" id="total_transaksi" value="0">
                    </div>

                    <div class="mt-2">
                        <label class="block font-semibold">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="w-full p-2 border rounded" onchange="toggleNamaPelanggan()">
                            <option value="Tunai">Tunai</option>
                            <option value="Hutang">Hutang</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="checkout" id="btnCheckout"
                            class="w-full bg-green-500 text-white p-2 rounded hover:bg-green-600">
                            Bayar
                        </button>
                    </div>


                    <!-- Bagian bawah: Daftar barang -->
                    <div id="cartItems" class="flex-1 overflow-y-auto mt-4 space-y-3"></div>
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
                cart.push({
                    id: id,
                    nama: nama,
                    harga: harga,
                    jumlah: 1
                });
            }
            renderCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            renderCart();
        }

        function renderCart() {
            let cartContainer = document.getElementById('cartItems');
            let totalHargaContainer = document.getElementById('totalHarga');
            let hiddenTotal = document.getElementById('total_transaksi');
            cartContainer.innerHTML = '';

            let totalHarga = 0;

            cart.forEach(item => {
                let subtotal = item.harga * item.jumlah;
                totalHarga += subtotal;

                cartContainer.innerHTML += `
            <div class="border p-2 rounded flex justify-between items-center">
                <div>
                    <p class="font-bold">${item.nama}</p>
                    <p>Rp ${item.harga.toLocaleString()} / satuan</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="decreaseQty(${item.id})"
                        class="bg-gray-300 px-2 py-1 rounded hover:bg-gray-400">-</button>
                    
                    <input type="number" min="0.5" step="0.5" value="${item.jumlah}" 
                        onchange="updateQty(${item.id}, this.value)"
                        class="w-16 text-center border rounded">

                    <button type="button" onclick="increaseQty(${item.id})"
                        class="bg-gray-300 px-2 py-1 rounded hover:bg-gray-400">+</button>

                    <button type="button" onclick="removeFromCart(${item.id})"
                        class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-sm">Hapus</button>

                    <input type="hidden" name="id_barang[]" value="${item.id}">
                    <input type="hidden" name="jumlah[]" value="${item.jumlah}">
                    <input type="hidden" name="harga[]" value="${item.harga}">
                </div>
            </div>
        `;
            });

            totalHargaContainer.innerHTML = `Total: Rp ${totalHarga.toLocaleString()}`;
            hiddenTotal.value = totalHarga; // <-- ini yang penting
        }


        function updateQty(id, value) {
            let item = cart.find(i => i.id === id);
            let jumlah = parseFloat(value);
            if (!isNaN(jumlah) && jumlah > 0) {
                item.jumlah = jumlah;
            } else {
                item.jumlah = 1;
            }
            renderCart();
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

        document.getElementById('metode_pembayaran').addEventListener('change', function() {
            let metode = this.value;
            let btnCheckout = document.getElementById('btnCheckout');

            if (metode === 'Hutang') {
                btnCheckout.innerText = "Tambah Hutang";
            } else {
                btnCheckout.innerText = "Bayar";
            }
        });
    </script>

</body>

</html>