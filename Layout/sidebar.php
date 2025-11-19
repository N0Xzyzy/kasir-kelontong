
<div class="border border-r border-gray-100 flex h-full w-45">
    

    <aside class="w-45 bg-white border border-r w-45 border-gray-100 p-4 flex flex-col justify-between h-full fixed">
      <div>

        <div class="mb-7 flex items-center space-x-2">
          <div class="w-6 h-6 bg-indigo-500 rounded-full"></div>
          <span class="font-semibold">
            Warung Kelontong
          </span>
        </div>

        <nav class="space-y-1.5 text-md font-semibold leading-7">
          <a href="../Views/dashboard.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600 ">
            <span>🏠</span><span>Dashboard</span>
          </a>
          <a href="../Views/transaksi.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600 ">
            <span>👥</span><span>Transaksi</span>
          </a>
          <a href="../Views/hutang.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600 ">
            <span>👥</span><span>Hutang</span>
          </a>
          <?php if (isset($_SESSION['id_user']) && ($_SESSION['role'] === 'owner' || $_SESSION['role'] === 'operator')) {?>
          <a href="../Views/barang.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600 ">
            <span>📁</span><span>Barang</span>
          </a>
          <?php } ?>
          <?php if (isset($_SESSION['id_user']) && ($_SESSION['role'] === 'owner' || $_SESSION['role'] === 'operator')) {?>
          <a href="../Views/kategori.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600 ">
            <span>📁</span><span>Kategori</span>
          </a>
          <?php } ?>
          <a href="../Views/pengeluaran.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600 ">
            <span>📄</span><span>Pengeluaran</span>
          </a>
          <a href="../Views/pemasukan.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600 ">
            <span>📊</span><span>Pemasukan</span>
          </a>
          <a href="../Views/laporan.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600">
            <span>📊</span><span>Laporan</span>
          </a>
          <?php if (isset($_SESSION['id_user']) && $_SESSION['role'] === 'owner') {?>
          <a href="../Views/kelola_user.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600 ">
            <span>🗓️</span><span>Kelola User</span>
          </a>
          <?php } ?>
          <a href="../Config/logout.php" class="flex items-center space-x-3 text-gray-600 hover:text-indigo-600 ">
            <span></span><span>Logout</span>
          </a>
        </nav>
      </div>
    </aside>

    
  </div>