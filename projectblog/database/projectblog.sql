-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 07, 2026 at 02:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectblog`
--

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE `artikel` (
  `id_artikel` int(11) NOT NULL,
  `judul` varchar(200) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `gambar` varchar(200) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `status` enum('draft','publish') DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artikel`
--

INSERT INTO `artikel` (`id_artikel`, `judul`, `isi`, `tanggal`, `id_user`, `id_kategori`, `gambar`, `penulis`, `status`) VALUES
(3, 'Pengalaman Soobin TXT di Filipina: Tarif Taksi Naik Saat Liburan, Fans Ikut Geram', 'Leader dari grup K-Pop TXT, yaitu Soobin TXT, baru-baru ini menjadi sorotan setelah membagikan pengalamannya saat berlibur di Filipina.\r\n\r\nDalam cerita yang ia bagikan kepada penggemar, Soobin mengaku mengalami kejadian yang cukup tidak menyenangkan saat menggunakan taksi. Ia menyebut bahwa tarif yang dikenakan kepadanya terasa jauh lebih mahal dari biasanya, terutama karena momen liburan yang membuat harga menjadi tidak wajar.\r\n\r\nKejadian ini langsung menarik perhatian para penggemar (MOA). Banyak yang merasa kesal dan menyayangkan tindakan oknum sopir taksi yang memanfaatkan situasi liburan untuk menaikkan harga, apalagi kepada wisatawan asing.\r\n\r\nBeberapa penggemar juga mengungkapkan bahwa praktik seperti ini memang kadang terjadi di berbagai destinasi wisata, terutama saat musim liburan di mana permintaan transportasi meningkat tajam. Hal ini sering dimanfaatkan oleh pihak tertentu untuk mendapatkan keuntungan lebih.\r\n\r\nMeski mengalami kejadian tersebut, Soobin tetap menanggapinya dengan santai dan tidak menunjukkan kemarahan berlebihan. Sikapnya yang tenang justru membuat banyak fans semakin kagum dengan kepribadiannya yang dewasa dan bijak.\r\n\r\nKejadian ini juga menjadi pengingat bagi para wisatawan untuk selalu berhati-hati saat bepergian ke luar negeri, terutama dalam menggunakan transportasi umum. Disarankan untuk menggunakan aplikasi transportasi resmi atau memastikan tarif sebelum memulai perjalanan.\r\n\r\nDi sisi lain, para penggemar berharap pengalaman kurang menyenangkan ini tidak mengurangi kesan Soobin terhadap Filipina, yang dikenal sebagai salah satu negara dengan penggemar K-Pop terbesar dan paling antusias di dunia.', '2026-04-06', NULL, 3, '1775485895_soobin.jpeg', '', 'draft');

-- --------------------------------------------------------

--
-- Table structure for table `artikel_tag`
--

CREATE TABLE `artikel_tag` (
  `id_artikel` int(11) DEFAULT NULL,
  `id_tag` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artikel_tag`
--

INSERT INTO `artikel_tag` (`id_artikel`, `id_tag`) VALUES
(3, 1),
(4, 2),
(5, 2),
(6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(3, 'Hiburan'),
(7, 'Olahraga'),
(10, 'Kpop');

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `id_komentar` int(11) NOT NULL,
  `id_artikel` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `komentar` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = Pending, 1 = Approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`id_komentar`, `id_artikel`, `nama`, `komentar`, `tanggal`, `status`) VALUES
(3, 2, 'admin', 'eee', '2026-04-06', 1),
(4, 3, 'admin', 'Berani bgt supir taksinya...', '2026-04-06', 1),
(5, 3, 'admin', '@txt wah parah nih min!', '2026-04-06', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id_tag` int(11) NOT NULL,
  `nama_tag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id_tag`, `nama_tag`) VALUES
(1, '@txt'),
(2, '@nct');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `role` enum('admin','penulis','user') DEFAULT 'user',
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama`, `role`, `email`) VALUES
(1, 'admin', '123456', 'Dewi', 'admin', 'wiwi@gmail.com'),
(2, 'zaza', '$2y$10$xs5uITc3KLHOjjhro5HDCe.PxmJFxmNF0iO8VGXMT8ujuo1cvHXPW', 'Zahira', 'penulis', 'ichil@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id_artikel`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id_komentar`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id_tag`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id_artikel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id_komentar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id_tag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
