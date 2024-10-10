-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 10, 2024 at 08:56 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan_indah`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

DROP TABLE IF EXISTS `buku`;
CREATE TABLE IF NOT EXISTS `buku` (
  `id_buku` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `penulis` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_terbit` int NOT NULL,
  `id_kategori` int NOT NULL,
  `img` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `pdf_file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('tersedia','kosong') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_buku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `id_kategori`, `img`, `pdf_file`, `status`) VALUES
(100, 'Lopisahi', 'Lotte', 'Gramedia', 2009, 1, 'orv.jpg', 'naskah_sandiwara_bahasa_jawa_horor.pdf', 'tersedia'),
(101, 'OPak Lama', 'Oda', 'Gramedia', 2020, 1, 'haia.jpg', 'naskah_sandiwara_bahasa_jawa_horor.pdf', 'tersedia'),
(111, 'Apalaj', 'dasd', 'Gramediad', 2007, 3, 'book.png', '', 'tersedia'),
(165, 'Under The Oak Tree', 'Hehe', 'Elex Komputindo', 2000, 2, 'haia.jpg', '', 'tersedia'),
(201, 'Hujan', 'Tere Liye', 'Gramedia', 2010, 2, 'hujan.jpg', '', 'tersedia'),
(202, 'ORV', 'Black Box', 'Lotte', 2010, 3, 'orv.jpg', '', 'tersedia'),
(203, 'Sejarah Dunia', 'Anonymous', 'Goblin', 2011, 1, 'sejarah.jpg', '', 'tersedia'),
(204, 'Kill The Villainess', 'Korea', 'Lotte', 2023, 2, 'kill.jpg', '', 'tersedia'),
(205, 'The Problematic Prince', 'Korea', 'Lotte', 2022, 3, 'problem.jpg', '', 'tersedia'),
(206, 'White Blood', 'Korea', 'Lotte', 2020, 1, 'white.jpg', '', 'tersedia'),
(210, 'Solo Leveling', 'Koreah', 'Gramedia', 2018, 3, 'solo.jpg\r\n', '', 'tersedia'),
(236, 'The Step-Mother Fairytale ', 'Potret', 'Gramedia', 2009, 3, 'step.jpg', '', 'tersedia'),
(275, 'Roxana', 'Korea', 'Gramedia', 2024, 2, 'roxana.jpg', '', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `kategoribuku_relasi`
--

DROP TABLE IF EXISTS `kategoribuku_relasi`;
CREATE TABLE IF NOT EXISTS `kategoribuku_relasi` (
  `id_katbuku` int NOT NULL AUTO_INCREMENT,
  `id_buku` int NOT NULL,
  `id_kategori` int NOT NULL,
  PRIMARY KEY (`id_katbuku`),
  KEY `id_kategori` (`id_kategori`),
  KEY `id_buku` (`id_buku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_buku`
--

DROP TABLE IF EXISTS `kategori_buku`;
CREATE TABLE IF NOT EXISTS `kategori_buku` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_buku`
--

INSERT INTO `kategori_buku` (`id_kategori`, `nama_kategori`) VALUES
(1, 'fiksi'),
(2, 'non fiksi'),
(3, 'sejarah'),
(4, 'biografi');

-- --------------------------------------------------------

--
-- Table structure for table `koleksi_pribadi`
--

DROP TABLE IF EXISTS `koleksi_pribadi`;
CREATE TABLE IF NOT EXISTS `koleksi_pribadi` (
  `id_koleksi` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_buku` int NOT NULL,
  PRIMARY KEY (`id_koleksi`),
  KEY `id_user` (`id_user`),
  KEY `id_buku` (`id_buku`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `koleksi_pribadi`
--

INSERT INTO `koleksi_pribadi` (`id_koleksi`, `id_user`, `id_buku`) VALUES
(1, 9, 201),
(2, 9, 100),
(3, 7, 100),
(4, 7, 100),
(5, 7, 101);

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id_peminjaman` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_buku` int NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_peminjaman`),
  KEY `id_user` (`id_user`),
  KEY `id_buku` (`id_buku`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `id_buku`, `tgl_pinjam`, `tgl_kembali`, `status`) VALUES
(1, 1, 165, '2024-09-19', '0000-00-00', 1),
(2, 2, 201, '2024-09-19', '2024-09-26', 1),
(3, 9, 275, '2024-09-19', '2024-09-26', 1),
(4, 2, 210, '2024-09-19', '2024-09-26', 1),
(5, 7, 206, '2024-09-19', '2024-09-26', 1),
(6, 8, 203, '2024-09-19', '2024-09-26', 1),
(7, 1, 202, '2024-09-19', '2024-09-26', 1),
(8, 8, 165, '2024-09-19', '2024-09-26', 1),
(9, 8, 165, '2024-09-19', '2024-09-26', 1),
(10, 1, 201, '2024-09-19', '2024-09-26', 1),
(11, 9, 202, '2024-09-19', '2024-09-26', 1),
(12, 8, 165, '2024-09-19', '2024-09-26', 1),
(13, 8, 165, '2024-09-19', '2024-09-26', 1),
(14, 8, 165, '2024-09-19', '2024-09-26', 1),
(15, 2, 165, '2024-09-19', '2024-09-26', 1),
(16, 11, 165, '2024-09-19', '2024-09-26', 1),
(17, 8, 203, '2024-09-20', '2024-09-27', 1),
(18, 1, 165, '2024-09-20', '2024-09-27', 1),
(19, 8, 165, '2024-09-20', '2024-09-27', 1),
(20, 1, 165, '2024-09-20', '2024-09-27', 1),
(21, 1, 165, '2024-09-20', '2024-09-27', 1),
(22, 1, 165, '2024-10-02', '2024-10-09', 1),
(23, 8, 165, '2024-10-02', '2024-10-09', 1),
(24, 1, 204, '2024-10-02', '2024-10-09', 1),
(25, 8, 204, '2024-10-02', '2024-10-09', 1),
(26, 5, 204, '2024-10-02', '2024-10-09', 1),
(27, 6, 204, '2024-10-02', '2024-10-09', 1),
(28, 7, 204, '2024-10-02', '2024-10-09', 1),
(29, 1, 165, '2024-10-02', '2024-10-09', 1),
(30, 2, 1, '2024-10-04', '2024-10-11', 1),
(31, 7, 210, '2024-10-08', '2024-10-15', 1),
(32, 1, 100, '2024-10-09', '2024-10-16', 1),
(33, 7, 111, '2024-10-09', '2024-10-16', 1),
(34, 7, 100, '2024-10-09', '2024-10-16', 1),
(35, 1, 101, '2024-10-09', '2024-10-16', 1),
(36, 7, 165, '2024-10-09', '2024-10-16', 1),
(37, 7, 204, '2024-10-09', '2024-10-12', 1),
(38, 7, 204, '2024-10-09', '2024-10-12', 1),
(39, 9, 275, '2024-10-09', '2024-10-12', 1),
(40, 9, 100, '2024-10-09', '2024-10-12', 1),
(41, 9, 201, '2024-10-10', '2024-10-13', 1),
(42, 9, 111, '2024-10-10', '2024-10-13', 1),
(43, 7, 101, '2024-10-10', '2024-10-13', 1),
(44, 9, 100, '2024-10-10', '2024-10-13', 1),
(45, 9, 100, '2024-10-10', '2024-10-13', 1),
(46, 7, 100, '2024-10-10', '2024-10-13', 1),
(47, 7, 100, '2024-10-10', '2024-10-13', 1),
(48, 7, 101, '2024-10-10', '2024-10-13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ulasan_buku`
--

DROP TABLE IF EXISTS `ulasan_buku`;
CREATE TABLE IF NOT EXISTS `ulasan_buku` (
  `id_ulasan` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_buku` int NOT NULL,
  `ulasan` text COLLATE utf8mb4_general_ci NOT NULL,
  `rating` int NOT NULL,
  PRIMARY KEY (`id_ulasan`),
  KEY `id_user` (`id_user`),
  KEY `id_buku` (`id_buku`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ulasan_buku`
--

INSERT INTO `ulasan_buku` (`id_ulasan`, `id_user`, `id_buku`, `ulasan`, `rating`) VALUES
(2, 1, 165, 'bagus seklai karena ini bagus', 10),
(3, 8, 201, 'Wow keren', 8),
(4, 9, 202, 'kerenn', 7),
(7, 12, 206, 'sangat bagus sangat rekomen wajib baca gusy', 10),
(10, 9, 201, 'penulsinya problematik', 0),
(13, 9, 201, 'bagus ya', 4);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `alamat`, `email`, `username`, `password`, `role`) VALUES
(1, 'Nur Indah Dewi Kusuma Wardani', 'Tagalrejo', 'mawar@gmail.com', 'mawar', '81dc9bdb52d04dc20036dbd8313ed055', 'admin'),
(2, 'Langit Biru', 'Pasawahan', 'langit@gmail.com', 'Langit', '5f4dcc3b5aa765d61d8327deb882cf99', 'user'),
(3, 'Nusa Pas', 'Lalaland', 'nusa@gmail.com', 'Nusa', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(4, 'Laras Puri', 'Galagher', 'laras@gmail.com', 'Laras', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(5, 'Sekar Kembang', 'Pasawahan', 'sekar@gmail.com', 'sekar', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(6, 'Kaila Lala', 'Galagher', 'lala@gmail.com', 'Lala', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(7, 'Kuniganing', 'Hanji', 'kuni@gmail.com', 'Kuni', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(8, 'Aspasya Salsabila', 'Pakis', 'pasya@gmail.com', 'pasya', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(9, 'Anao Tier', 'Olycuss', 'anao@gmail.com', 'ana', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(10, 'Kencana Jani', 'Pallor', 'jani@gmail.com', 'jani', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(11, 'Miko Kamila', 'Jakata', 'miko@gmail.com', 'miko', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(12, 'salma', 'magelang', 'zaskiasalma@gmail.com', 'zalma', '827ccb0eea8a706c4c34a16891f84e7b', 'user'),
(13, 'Opak', 'Tegalrejo', 'opak@gmail.com', 'opak', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(14, '', '', '', 'mawar', '81dc9bdb52d04dc20036dbd8313ed055', ''),
(15, '', '', '', 'mawar', '81dc9bdb52d04dc20036dbd8313ed055', '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kategoribuku_relasi`
--
ALTER TABLE `kategoribuku_relasi`
  ADD CONSTRAINT `kategoribuku_relasi_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_buku` (`id_kategori`),
  ADD CONSTRAINT `kategoribuku_relasi_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`);

--
-- Constraints for table `koleksi_pribadi`
--
ALTER TABLE `koleksi_pribadi`
  ADD CONSTRAINT `koleksi_pribadi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `koleksi_pribadi_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `ulasan_buku`
--
ALTER TABLE `ulasan_buku`
  ADD CONSTRAINT `ulasan_buku_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `ulasan_buku_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
