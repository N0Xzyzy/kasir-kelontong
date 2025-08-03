CREATE DATABASE keuangan_warung;
USE keuangan_warung;

CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('owner', 'operator', 'kasir') NOT NULL
);

CREATE TABLE barang (
  id_barang INT AUTO_INCREMENT PRIMARY KEY,
  nama_barang VARCHAR(100) NOT NULL,
  stok INT NOT NULL,
  harga_beli DECIMAL(12,2) NOT NULL,
  harga_jual DECIMAL(12,2) NOT NULL
);

CREATE TABLE transaksi (
  id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  id_barang INT NOT NULL,
  jumlah INT NOT NULL,
  harga_jual DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  total_transaksi DECIMAL(12,2) NOT NULL,
  metode_pembayaran ENUM('Tunai', 'Hutang') NOT NULL,
  id_user INT,
  FOREIGN KEY (id_barang) REFERENCES barang(id_barang),
  FOREIGN KEY (id_user) REFERENCES users(id_user)
);

CREATE TABLE hutang_pelanggan (
  id_hutang INT AUTO_INCREMENT PRIMARY KEY,
  id_transaksi INT NOT NULL,
  nama_pelanggan VARCHAR(100) NOT NULL,
  jumlah_hutang DECIMAL(12,2) NOT NULL,
  status ENUM('Belum Lunas', 'Lunas') NOT NULL DEFAULT 'Belum Lunas',
  tanggal DATE NOT NULL,
  FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi)
);

CREATE TABLE pengeluaran (
  id_pengeluaran INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  deskripsi VARCHAR(255) NOT NULL,
  jumlah DECIMAL(12,2) NOT NULL,
  id_user INT,
  FOREIGN KEY (id_user) REFERENCES users(id_user)
);

CREATE TABLE laporan_keuangan (
  id_laporan INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  pemasukan DECIMAL(12,2) NOT NULL,
  pengeluaran DECIMAL(12,2) NOT NULL,
  laba DECIMAL(12,2) NOT NULL
);
