-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 29 Bulan Mei 2023 pada 11.18
-- Versi server: 8.0.32-0ubuntu0.22.04.2
-- Versi PHP: 8.1.2-1ubuntu2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eraport`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `data_user` (IN `username` VARCHAR(50))  BEGIN
SELECT admin.nama_user,admin.id_role,role.nama as role FROM admin INNER JOIN role ON admin.id_role = role.id_role WHERE admin.username = username;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id_absen` int NOT NULL,
  `izin` int NOT NULL,
  `sakit` int NOT NULL,
  `alfa` int NOT NULL,
  `id_siswa` int NOT NULL,
  `id_semester` int NOT NULL,
  `id_guru` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_user` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` enum('L','P') COLLATE utf8mb4_general_ci NOT NULL,
  `id_role` int NOT NULL,
  `tanggal_regis` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_user`, `nama`, `foto`, `email`, `username`, `password`, `gender`, `id_role`, `tanggal_regis`) VALUES
(16, 'ahmad fauzan', '1684210085_ZB3uqq.jpg', 'fauzanguci2@gmail.com', 'fauzan123', 'd3a33788c755ebcef244fd61c04f408a', 'L', 1, '2023-05-16 04:08:05'),
(17, 'admin123', '1685277035_Screenshot (1).png', 'admin123@gmail.com', 'admin123', '0192023a7bbd73250516f069df18b500', 'L', 1, '2023-05-28 12:30:35');

--
-- Trigger `admin`
--
DELIMITER $$
CREATE TRIGGER `add_user` AFTER INSERT ON `admin` FOR EACH ROW BEGIN
INSERT INTO log_activity 
SET
id_user = NEW.id_user,
aktivitas = "Menambahkan data user";
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_user` AFTER DELETE ON `admin` FOR EACH ROW BEGIN
INSERT INTO log_activity
SET
id_user = OLD.id_user,
aktivitas = "Menghapus data user";
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `edit_user` AFTER UPDATE ON `admin` FOR EACH ROW BEGIN
INSERT INTO log_activity
SET
id_user = OLD.id_user,
aktivitas = "Mengubah data user";
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_ekskul`
--

CREATE TABLE `data_ekskul` (
  `id_data` int NOT NULL,
  `id_ekskul` int NOT NULL,
  `id_siswa` int NOT NULL,
  `nilai` int NOT NULL,
  `keterangan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_ekskul`
--

INSERT INTO `data_ekskul` (`id_data`, `id_ekskul`, `id_siswa`, `nilai`, `keterangan`) VALUES
(5, 11, 1765, 100, 'Sangat  bagusaaa'),
(6, 12, 1765, 90, 'Good');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ekskul`
--

CREATE TABLE `ekskul` (
  `id_ekskul` int NOT NULL,
  `nama_ekskul` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_guru` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ekskul`
--

INSERT INTO `ekskul` (`id_ekskul`, `nama_ekskul`, `id_guru`) VALUES
(11, 'Basket', 145),
(12, 'Baket', 144);

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id_guru` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_mapel` int NOT NULL,
  `id_role` int NOT NULL,
  `gender` enum('L','P') COLLATE utf8mb4_general_ci NOT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu') COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_regis` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `no_telp` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `nama`, `foto`, `nip`, `email`, `password`, `id_mapel`, `id_role`, `gender`, `agama`, `tanggal_regis`, `no_telp`, `alamat`) VALUES
(143, 'bu Astri', '../../../asset/img/user.png', '1977120256789210', 'guru@gmail.com', '@SMKN2_4pMQg', 35, 2, 'P', 'Islam', '2023-05-16 04:10:57', '6289376431', 'Cimahi'),
(144, 'Ahmad Fauzan', '../../../asset/img/guru/1684210763_pxfuel.jpg', '232320480280434', 'fauzanguci2@gmail.com', '@SMKN2_bKllQ', 37, 2, 'L', 'Islam', '2023-05-16 04:19:23', '6238768324234', 'Cimahi'),
(145, 'Bu gigin', '../../../asset/img/guru/1684210827_ZB3uqq.jpg', '3213387923374928', 'b@a', '@SMKN2_PYnuG', 36, 2, 'P', 'Islam', '2023-05-16 04:20:27', '6236234287443', 'Kamarung');

-- --------------------------------------------------------

--
-- Struktur dari tabel `informasi_sekolah`
--

CREATE TABLE `informasi_sekolah` (
  `id_info` int NOT NULL,
  `nama_sekolah` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_sekolah` text COLLATE utf8mb4_general_ci NOT NULL,
  `notelp_sekolah` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `email_sekolah` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `website_sekolah` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kepala_sekolah` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `informasi_sekolah`
--

INSERT INTO `informasi_sekolah` (`id_info`, `nama_sekolah`, `alamat_sekolah`, `notelp_sekolah`, `email_sekolah`, `website_sekolah`, `kepala_sekolah`, `nip`) VALUES
(1, 'SMK NEGERI 2 CIMAHI', 'Jalan Kamarung No.69, RT.2/RW.5, Citeureup, Cimahi Utara, Citeureup, Kec. Cimahi Utara, Kota Cimahi, Jawa Barat 40512', '089605201376', 'info@smkn2cmi.sch.id', 'https://smkn2cmi.sch.id/', 'Agus Priyatmono Nugroho', '1977120256789210');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int NOT NULL,
  `jurusan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `jurusan`) VALUES
(3, 'Animasi'),
(4, 'Multimedia'),
(5, 'RPL'),
(6, 'Kimia Industri'),
(7, 'Teknik Permesinan'),
(8, 'Mekatronika');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL,
  `tingkatan` enum('10','11','12') COLLATE utf8mb4_general_ci NOT NULL,
  `id_jurusan` int NOT NULL,
  `nama_kelas` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `wali_kelas` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `tingkatan`, `id_jurusan`, `nama_kelas`, `wali_kelas`) VALUES
(684, '11', 5, 'RPL  A', 144),
(685, '11', 5, 'A', 145),
(686, '11', 5, 'B', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_activity`
--

CREATE TABLE `log_activity` (
  `id_log` int NOT NULL,
  `id_user` int NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `aktivitas` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_activity`
--

INSERT INTO `log_activity` (`id_log`, `id_user`, `tanggal`, `aktivitas`) VALUES
(1, 5, '2023-03-14 07:27:06', 'Menambahkan data user'),
(2, 5, '2023-03-14 07:31:54', 'Mengubah data user'),
(3, 2, '2023-03-14 07:32:07', 'Mengubah data user'),
(4, 6, '2023-03-14 07:34:47', 'Menambahkan data user'),
(5, 10, '2023-03-14 07:35:55', 'Menambahkan data user'),
(6, 10, '2023-03-14 07:38:25', 'Menghapus data user'),
(7, 1, '2023-03-15 13:08:52', 'Menambahkan data user'),
(8, 1, '2023-03-15 14:09:25', 'Mengubah data user'),
(9, 1, '2023-03-15 14:09:44', 'Mengubah data user'),
(10, 1, '2023-03-15 14:33:53', 'Mengubah data user'),
(11, 2, '2023-03-16 11:06:49', 'Menambahkan data user'),
(12, 1, '2023-03-16 11:07:55', 'Mengubah data user'),
(13, 2, '2023-03-19 01:29:43', 'Mengubah data user'),
(14, 3, '2023-03-20 01:30:25', 'Menambahkan data user'),
(15, 1, '2023-04-12 12:20:03', 'Mengubah data user'),
(16, 2, '2023-04-12 12:20:06', 'Mengubah data user'),
(17, 3, '2023-04-12 12:20:08', 'Mengubah data user'),
(18, 1, '2023-04-12 12:24:16', 'Mengubah data user'),
(19, 4, '2023-04-13 12:31:37', 'Menambahkan data user'),
(20, 5, '2023-04-13 12:49:06', 'Menambahkan data user'),
(21, 6, '2023-04-13 12:49:16', 'Menambahkan data user'),
(22, 7, '2023-04-13 13:06:10', 'Menambahkan data user'),
(23, 8, '2023-04-13 13:06:32', 'Menambahkan data user'),
(24, 1, '2023-04-13 13:06:51', 'Menghapus data user'),
(25, 2, '2023-04-13 13:06:52', 'Menghapus data user'),
(26, 3, '2023-04-13 13:06:52', 'Menghapus data user'),
(27, 4, '2023-04-13 13:06:52', 'Menghapus data user'),
(28, 5, '2023-04-13 13:06:52', 'Menghapus data user'),
(29, 6, '2023-04-13 13:06:52', 'Menghapus data user'),
(30, 7, '2023-04-13 13:06:52', 'Menghapus data user'),
(31, 8, '2023-04-13 13:06:52', 'Menghapus data user'),
(32, 9, '2023-04-13 13:08:18', 'Menambahkan data user'),
(33, 9, '2023-04-25 03:33:13', 'Mengubah data user'),
(34, 9, '2023-04-25 03:34:44', 'Mengubah data user'),
(35, 9, '2023-04-25 03:35:13', 'Mengubah data user'),
(36, 10, '2023-04-28 07:54:55', 'Menambahkan data user'),
(37, 11, '2023-05-15 01:29:53', 'Menambahkan data user'),
(38, 9, '2023-05-15 14:28:59', 'Menghapus data user'),
(39, 10, '2023-05-15 14:28:59', 'Menghapus data user'),
(40, 11, '2023-05-15 14:29:00', 'Menghapus data user'),
(41, 12, '2023-05-15 14:29:41', 'Menambahkan data user'),
(42, 13, '2023-05-16 02:37:23', 'Menambahkan data user'),
(43, 13, '2023-05-16 02:38:11', 'Menghapus data user'),
(44, 14, '2023-05-16 02:40:54', 'Menambahkan data user'),
(45, 12, '2023-05-16 03:14:20', 'Menghapus data user'),
(46, 14, '2023-05-16 03:14:20', 'Menghapus data user'),
(47, 15, '2023-05-16 03:58:13', 'Menambahkan data user'),
(48, 15, '2023-05-16 03:58:57', 'Menghapus data user'),
(49, 16, '2023-05-16 04:08:05', 'Menambahkan data user'),
(50, 17, '2023-05-28 12:30:35', 'Menambahkan data user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int NOT NULL,
  `mapel` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` enum('umum','kejuruan') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `mapel`, `jenis`) VALUES
(35, 'Matematika', 'kejuruan'),
(36, 'Produktif', 'kejuruan'),
(37, 'Bahasa Indonesia', 'umum'),
(38, 'Bahasa Inggris', 'kejuruan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int NOT NULL,
  `id_guru` int DEFAULT NULL,
  `id_siswa` int NOT NULL,
  `id_semester` int NOT NULL,
  `nilai_harian` double NOT NULL,
  `nilai_uts` double NOT NULL,
  `nilai_uas` int NOT NULL,
  `nilai_pengetahuan` double NOT NULL,
  `grade_pengetahuan` varchar(1) COLLATE utf8mb4_general_ci NOT NULL,
  `desk_pengetahuan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_keterampilan` double NOT NULL,
  `grade_ket` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `desk_ket` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_ukk` int DEFAULT NULL,
  `desk_ukk` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id_role` int NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id_role`, `role`) VALUES
(1, 'Admin'),
(2, 'Guru'),
(3, 'Siswa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `semester`
--

CREATE TABLE `semester` (
  `id_semester` int NOT NULL,
  `semester` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `semester`
--

INSERT INTO `semester` (`id_semester`, `semester`) VALUES
(1, 'Ganjil'),
(2, 'Genap');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nis` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_kelas` int NOT NULL,
  `id_role` int NOT NULL,
  `gender` enum('L','P') COLLATE utf8mb4_general_ci NOT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu') COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_regis` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `no_telp` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL,
  `nama_ortu` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Aktif','Nonaktif') COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_ajaran` varchar(10) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `nis`, `foto`, `email`, `password`, `id_kelas`, `id_role`, `gender`, `agama`, `tanggal_regis`, `no_telp`, `alamat`, `nama_ortu`, `status`, `tahun_ajaran`) VALUES
(1764, 'ADINDA MAYA FITRIANI', '102105231', '../../../asset/img/user.png', '102105231@gmail.com', '@SMKN2_1ggfW', 685, 3, 'P', 'Islam', '2023-05-28 14:45:34', '123', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1765, 'AHMAD FAUZAN', '102105232', '../../../asset/img/user.png', '102105232@gmail.com', '@SMKN2_3VQaf', 685, 3, 'L', 'Islam', '2023-05-28 14:45:34', '124', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1766, 'AHMAD ZULFIKAR RAMDZI', '102105233', '../../../asset/img/user.png', '102105233@gmail.com', '@SMKN2_Zulte', 685, 3, 'L', 'Islam', '2023-05-28 14:45:34', '125', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1767, 'AMELIA DEVINA AGUSTIANA', '102105234', '../../../asset/img/user.png', '102105234@gmail.com', '@SMKN2_Dc91F', 685, 3, 'P', 'Islam', '2023-05-28 14:45:34', '126', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1768, 'ANDIKA HIKMAH KUSNANDAR', '102105235', '../../../asset/img/user.png', '102105235@gmail.com', '@SMKN2_AfcaR', 685, 3, 'L', 'Islam', '2023-05-28 14:45:34', '127', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1769, 'ANDINI PUTRI YANI', '102105236', '../../../asset/img/user.png', '102105236@gmail.com', '@SMKN2_m8JCz', 685, 3, 'P', 'Islam', '2023-05-28 14:45:34', '128', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1770, 'BISMI KHAIRANI', '102105237', '../../../asset/img/user.png', '102105237@gmail.com', '@SMKN2_xvtgC', 685, 3, 'P', 'Islam', '2023-05-28 14:45:34', '129', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1771, 'CAHYADI SUHERMAN', '102105238', '../../../asset/img/user.png', '102105238@gmail.com', '@SMKN2_hdH71', 685, 3, 'L', 'Islam', '2023-05-28 14:45:34', '130', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1772, 'DADAN MAULANA', '102105239', '../../../asset/img/user.png', '102105239@gmail.com', '@SMKN2_6C32m', 685, 3, 'L', 'Islam', '2023-05-28 14:45:34', '131', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1773, 'DAVIN PUTRA PRATAMA', '102105240', '../../../asset/img/user.png', '102105240@gmail.com', '@SMKN2_4fEA2', 685, 3, 'L', 'Islam', '2023-05-28 14:45:35', '132', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1774, 'DIKI SEPTIANSYAH', '102105241', '../../../asset/img/user.png', '102105241@gmail.com', '@SMKN2_OpZwy', 685, 3, 'L', 'Islam', '2023-05-28 14:45:35', '133', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1775, 'FAHREZAN FATIH DWI SUSILA', '102105242', '../../../asset/img/user.png', '102105242@gmail.com', '@SMKN2_nns9w', 685, 3, 'L', 'Islam', '2023-05-28 14:45:35', '134', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1776, 'INDRIANTI NABILA PRATAMA', '102105243', '../../../asset/img/user.png', '102105243@gmail.com', '@SMKN2_cxCf9', 686, 3, 'P', 'Islam', '2023-05-28 14:45:35', '135', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1777, 'JANUAR IRHAM SYAHID', '102105244', '../../../asset/img/user.png', '102105244@gmail.com', '@SMKN2_st6ti', 686, 3, 'L', 'Islam', '2023-05-28 14:45:35', '136', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1778, 'KAYLA IRISYA PUTRI', '102105245', '../../../asset/img/user.png', '102105245@gmail.com', '@SMKN2_ihSdN', 686, 3, 'P', 'Islam', '2023-05-28 14:45:35', '137', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1779, 'KEYSHA EGA MAGANI', '102105246', '../../../asset/img/user.png', '102105246@gmail.com', '@SMKN2_EjqlX', 686, 3, 'P', 'Islam', '2023-05-28 14:45:35', '138', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1780, 'KHALIFAH ALKALIMAH MUHAMMAD', '102105247', '../../../asset/img/user.png', '102105247@gmail.com', '@SMKN2_MWOCd', 686, 3, 'L', 'Islam', '2023-05-28 14:45:35', '139', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1781, 'MAYANG PUTRI MUTIARA', '102105248', '../../../asset/img/user.png', '102105248@gmail.com', '@SMKN2_ptNqr', 686, 3, 'P', 'Islam', '2023-05-28 14:45:35', '140', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1782, 'MIA CRISTIANI BR MANIK', '102105249', '../../../asset/img/user.png', '102105249@gmail.com', '@SMKN2_Y2oJY', 686, 3, 'P', 'Kristen', '2023-05-28 14:45:35', '141', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1783, 'MUAMMARY DZAKY ZAIDAN', '102105250', '../../../asset/img/user.png', '102105250@gmail.com', '@SMKN2_yRuFb', 686, 3, 'L', 'Islam', '2023-05-28 14:45:35', '142', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1784, 'MUHAMMAD BINTANG RENJANA KAARHI', '102105251', '../../../asset/img/user.png', '102105251@gmail.com', '@SMKN2_e6tE0', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '143', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1785, 'MUHAMMAD RIZKY PRATAMA', '102105252', '../../../asset/img/user.png', '102105252@gmail.com', '@SMKN2_g6jyf', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '144', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1786, 'MUHAMMAD DINO ANSHARI', '102105253', '../../../asset/img/user.png', '102105253@gmail.com', '@SMKN2_xZ8mO', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '145', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1787, 'MUHAMMAD FADLAN ABYAN SAHARA', '102105254', '../../../asset/img/user.png', '102105254@gmail.com', '@SMKN2_y8HvC', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '146', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1788, 'MUHAMMAD IQBAL PRATAMA', '102105255', '../../../asset/img/user.png', '102105255@gmail.com', '@SMKN2_UhKKA', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '147', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1789, 'MUHAMMAD RAFLY SYAH ALAM', '102105256', '../../../asset/img/user.png', '102105256@gmail.com', '@SMKN2_eijFk', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '148', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1790, 'MUHAMMAD RIZZA REINATA H', '102105257', '../../../asset/img/user.png', '102105257@gmail.com', '@SMKN2_bI3lj', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '149', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1791, 'NOVIANTI SAFITRI', '102105258', '../../../asset/img/user.png', '102105258@gmail.com', '@SMKN2_NJV0o', 686, 3, 'P', 'Islam', '2023-05-28 14:45:36', '150', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1792, 'NUR SELA FEBRIANI', '102105259', '../../../asset/img/user.png', '102105259@gmail.com', '@SMKN2_Q6yEs', 686, 3, 'P', 'Islam', '2023-05-28 14:45:36', '151', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1793, 'RIKI ABDUL AZIZ', '102105260', '../../../asset/img/user.png', '102105260@gmail.com', '@SMKN2_K7zCY', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '152', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1794, 'RIO YUDANTO ARYO SENO', '102105261', '../../../asset/img/user.png', '102105261@gmail.com', '@SMKN2_9dRfq', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '153', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1795, 'RIZKYA GUSNALDY KALIA', '102105262', '../../../asset/img/user.png', '102105262@gmail.com', '@SMKN2_waYEp', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '154', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1796, 'SATRIO ZAKY FADHLUR RAHMAN', '102105263', '../../../asset/img/user.png', '102105263@gmail.com', '@SMKN2_VCmlP', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '155', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1797, 'SYIFA PUTRI WINANDI', '102105264', '../../../asset/img/user.png', '102105264@gmail.com', '@SMKN2_kgpmt', 686, 3, 'P', 'Islam', '2023-05-28 14:45:36', '156', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1798, 'TRIGUSMAETA AHMAD FADILAH', '102105265', '../../../asset/img/user.png', '102105265@gmail.com', '@SMKN2_jhYjA', 686, 3, 'L', 'Islam', '2023-05-28 14:45:36', '157', 'alamat', 'ortu', 'Aktif', '2023-2024'),
(1799, 'WANDI YUSUF SOPIAN', '102105266', '../../../asset/img/user.png', '102105266@gmail.com', '@SMKN2_WJjwi', 686, 3, 'L', 'Islam', '2023-05-28 14:45:37', '158', 'alamat', 'ortu', 'Aktif', '2023-2024');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absen`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_semester` (`id_semester`);

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_role` (`id_role`);

--
-- Indeks untuk tabel `data_ekskul`
--
ALTER TABLE `data_ekskul`
  ADD PRIMARY KEY (`id_data`),
  ADD KEY `id_ekskul` (`id_ekskul`,`id_siswa`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `ekskul`
--
ALTER TABLE `ekskul`
  ADD PRIMARY KEY (`id_ekskul`),
  ADD KEY `id_guru_2` (`id_guru`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `id_mapel` (`id_mapel`,`id_role`),
  ADD KEY `id_role` (`id_role`);

--
-- Indeks untuk tabel `informasi_sekolah`
--
ALTER TABLE `informasi_sekolah`
  ADD PRIMARY KEY (`id_info`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD UNIQUE KEY `tingkatan` (`tingkatan`,`id_jurusan`,`nama_kelas`),
  ADD KEY `id_jurusan` (`id_jurusan`,`wali_kelas`),
  ADD KEY `wali_kelas` (`wali_kelas`);

--
-- Indeks untuk tabel `log_activity`
--
ALTER TABLE `log_activity`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `id_guru` (`id_guru`,`id_siswa`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_semester` (`id_semester`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indeks untuk tabel `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id_semester`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `id_role` (`id_role`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_role_2` (`id_role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `data_ekskul`
--
ALTER TABLE `data_ekskul`
  MODIFY `id_data` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `ekskul`
--
ALTER TABLE `ekskul`
  MODIFY `id_ekskul` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT untuk tabel `informasi_sekolah`
--
ALTER TABLE `informasi_sekolah`
  MODIFY `id_info` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=687;

--
-- AUTO_INCREMENT untuk tabel `log_activity`
--
ALTER TABLE `log_activity`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `semester`
--
ALTER TABLE `semester`
  MODIFY `id_semester` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1800;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `absensi_ibfk_3` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `absensi_ibfk_5` FOREIGN KEY (`id_semester`) REFERENCES `semester` (`id_semester`);

--
-- Ketidakleluasaan untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);

--
-- Ketidakleluasaan untuk tabel `data_ekskul`
--
ALTER TABLE `data_ekskul`
  ADD CONSTRAINT `data_ekskul_ibfk_1` FOREIGN KEY (`id_ekskul`) REFERENCES `ekskul` (`id_ekskul`),
  ADD CONSTRAINT `data_ekskul_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);

--
-- Ketidakleluasaan untuk tabel `ekskul`
--
ALTER TABLE `ekskul`
  ADD CONSTRAINT `ekskul_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`);

--
-- Ketidakleluasaan untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_2` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`),
  ADD CONSTRAINT `guru_ibfk_3` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id_mapel`);

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`),
  ADD CONSTRAINT `kelas_ibfk_2` FOREIGN KEY (`wali_kelas`) REFERENCES `guru` (`id_guru`);

--
-- Ketidakleluasaan untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `nilai_ibfk_4` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `nilai_ibfk_5` FOREIGN KEY (`id_semester`) REFERENCES `semester` (`id_semester`);

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`),
  ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
