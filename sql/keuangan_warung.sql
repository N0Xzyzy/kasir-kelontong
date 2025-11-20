-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Nov 2025 pada 04.47
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `keuangan_warung`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `stok` int(11) NOT NULL,
  `kategori` int(100) DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `stok`, `kategori`, `harga`, `status`) VALUES
(2, 'Gula 1kg', 959, NULL, 10000.00, 'nonaktif'),
(3, 'sego goreng', 12, NULL, 3000.00, 'aktif'),
(4, 'mi goreang', 122, NULL, 4000.00, 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_jual` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_barang`, `jumlah`, `harga_jual`, `subtotal`) VALUES
(1, 10, 2, 1, 10000.00, 10000.00),
(2, 10, 3, 1, 3000.00, 3000.00),
(3, 10, 4, 1, 4000.00, 4000.00),
(4, 11, 3, 2, 3000.00, 6000.00),
(5, 12, 4, 1, 4000.00, 4000.00),
(6, 12, 3, 1, 3000.00, 3000.00),
(7, 13, 3, 1, 3000.00, 3000.00),
(8, 13, 4, 1, 4000.00, 4000.00),
(9, 14, 3, 1, 3000.00, 3000.00),
(10, 15, 3, 1, 3000.00, 3000.00),
(11, 16, 3, 1, 3000.00, 4500.00),
(12, 17, 3, 1, 3000.00, 4500.00),
(13, 18, 3, 0, 3000.00, 1500.00),
(14, 19, 3, 3, 3000.00, 10500.00),
(15, 20, 4, 5, 4000.00, 20000.00),
(16, 21, 4, 1, 4000.00, 4000.00),
(17, 21, 3, 1, 3000.00, 3000.00),
(18, 22, 3, 1, 3000.00, 3000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hutang_pelanggan`
--

CREATE TABLE `hutang_pelanggan` (
  `id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `jumlah_hutang` decimal(12,2) NOT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `status` enum('lunas','belum') DEFAULT 'belum',
  `kontak` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hutang_pelanggan`
--

INSERT INTO `hutang_pelanggan` (`id`, `nama_pelanggan`, `id_transaksi`, `jumlah_hutang`, `tanggal_jatuh_tempo`, `status`, `kontak`, `catatan`) VALUES
(1, 'Sujarwo Tejo', 10, 17000.00, '2025-08-27', 'belum', '0891231823', 'pak jarwo utangnya belom lunas lho rek');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(100) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_keuangan`
--

CREATE TABLE `laporan_keuangan` (
  `id_laporan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `total_pemasukan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pengeluaran` decimal(12,2) NOT NULL DEFAULT 0.00,
  `laba` decimal(12,2) NOT NULL DEFAULT 0.00,
  `jumlah_transaksi` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` decimal(12,2) NOT NULL,
  `sumber` enum('transaksi','pelunasan_hutang','lainnya') NOT NULL,
  `id_sumber` int(11) DEFAULT NULL,
  `id_laporan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pemasukan`
--

INSERT INTO `pemasukan` (`id`, `tanggal`, `jumlah`, `sumber`, `id_sumber`, `id_laporan`) VALUES
(3, '2025-08-26', 10500.00, 'transaksi', 19, NULL),
(4, '2025-08-27', 20000.00, 'transaksi', 20, NULL),
(5, '2025-08-27', 7000.00, 'transaksi', 21, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kategori` enum('Belanja','Operasional','Lain-lain') NOT NULL,
  `keperluan` varchar(255) NOT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `total` decimal(12,2) NOT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `id_laporan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `total_transaksi` decimal(12,2) NOT NULL,
  `metode_pembayaran` enum('Tunai','Hutang') NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal`, `total_transaksi`, `metode_pembayaran`, `id_user`) VALUES
(10, '2025-08-20', 17000.00, 'Hutang', 2),
(11, '2025-08-26', 6000.00, 'Tunai', 2),
(12, '2025-08-26', 7000.00, 'Tunai', 2),
(13, '2025-08-26', 7000.00, 'Tunai', 2),
(14, '2025-08-26', 3000.00, 'Tunai', 2),
(15, '2025-08-26', 3000.00, 'Tunai', 2),
(16, '2025-08-26', 4500.00, 'Tunai', 2),
(17, '2025-08-26', 4500.00, 'Tunai', 2),
(18, '2025-08-26', 1500.00, 'Tunai', 2),
(19, '2025-08-26', 10500.00, 'Tunai', 2),
(20, '2025-08-27', 20000.00, 'Tunai', 2),
(21, '2025-08-27', 7000.00, 'Tunai', 2),
(22, '2025-08-27', 3000.00, 'Hutang', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','operator','kasir') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`) VALUES
(2, 'Owner', '5be057accb25758101fa5eadbbd79503', 'owner'),
(31, 'owner3', '202cb962ac59075b964b07152d234b70', 'owner');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `fk_kategori` (`kategori`);

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `hutang_pelanggan`
--
ALTER TABLE `hutang_pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD UNIQUE KEY `tanggal` (`tanggal`),
  ADD UNIQUE KEY `unique_tanggal` (`tanggal`);

--
-- Indeks untuk tabel `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pemasukan_transaksi` (`id_sumber`),
  ADD KEY `fk_pemasukan_laporan` (`id_laporan`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `fk_pengeluaran_laporan` (`tanggal`),
  ADD KEY `fk_pengeluaran_laporan2` (`id_laporan`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `hutang_pelanggan`
--
ALTER TABLE `hutang_pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_kategori` FOREIGN KEY (`kategori`) REFERENCES `kategori` (`id`);

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Ketidakleluasaan untuk tabel `hutang_pelanggan`
--
ALTER TABLE `hutang_pelanggan`
  ADD CONSTRAINT `hutang_pelanggan_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`);

--
-- Ketidakleluasaan untuk tabel `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `fk_pemasukan_laporan` FOREIGN KEY (`id_laporan`) REFERENCES `laporan_keuangan` (`id_laporan`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pemasukan_transaksi` FOREIGN KEY (`id_sumber`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `fk_pengeluaran_laporan2` FOREIGN KEY (`id_laporan`) REFERENCES `laporan_keuangan` (`id_laporan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
