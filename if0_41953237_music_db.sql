-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql105.infinityfree.com
-- Generation Time: May 26, 2026 at 03:07 AM
-- Server version: 11.4.11-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41953237_music_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievement`
--

CREATE TABLE `achievement` (
  `id_achievement` int(11) NOT NULL,
  `nama_achievement` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `kriteria_unlock` text DEFAULT NULL,
  `poin_bonus` int(11) NOT NULL DEFAULT 0,
  `rarity` enum('common','rare','epic','legendary') NOT NULL DEFAULT 'common',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `achievement`
--

INSERT INTO `achievement` (`id_achievement`, `nama_achievement`, `deskripsi`, `icon`, `kriteria_unlock`, `poin_bonus`, `rarity`, `created_at`) VALUES
(1, 'First Blood', 'Memainkan game pertama kali', NULL, NULL, 50, 'common', '2026-05-12 05:33:48'),
(2, 'Pemula Champion', 'Menyelesaikan level Pemula', NULL, NULL, 100, 'common', '2026-05-12 05:33:48'),
(3, 'Normal Conqueror', 'Menyelesaikan level Normal', NULL, NULL, 150, 'rare', '2026-05-12 05:33:48'),
(4, 'Hard Slayer', 'Menyelesaikan level Hard', NULL, NULL, 200, 'epic', '2026-05-12 05:33:48'),
(5, 'Expert Master', 'Menyelesaikan level Expert', NULL, NULL, 300, 'legendary', '2026-05-12 05:33:48'),
(6, 'Perfect Score', 'Menjawab semua benar dalam satu game', NULL, NULL, 250, 'epic', '2026-05-12 05:33:48'),
(7, 'Streak Master', '10 jawaban benar berturut-turut', NULL, NULL, 150, 'rare', '2026-05-12 05:33:48'),
(8, 'Music Buff', 'Memainkan 100 soal total', NULL, NULL, 200, 'rare', '2026-05-12 05:33:48'),
(9, 'Koin Sultan', 'Mengumpulkan 1000 koin', NULL, NULL, 500, 'legendary', '2026-05-12 05:33:48');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$IabFeBbkFxmqxtZwBdRHeuPLelvP7NnyBmlcK8gRfo8LB1wioVj3G', 'Administrator', 'admin@musicgame.com', '2026-05-15 21:54:23');

-- --------------------------------------------------------

--
-- Table structure for table `konfigurasi_game`
--

CREATE TABLE `konfigurasi_game` (
  `id` int(11) NOT NULL,
  `nama_setting` varchar(100) NOT NULL,
  `nilai` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `konfigurasi_game`
--

INSERT INTO `konfigurasi_game` (`id`, `nama_setting`, `nilai`, `deskripsi`, `updated_at`) VALUES
(1, 'poin_pemula', '10', 'Poin per jawaban benar level Pemula', NULL),
(2, 'poin_normal', '12', 'Poin per jawaban benar level Normal', NULL),
(3, 'poin_hard', '15', 'Poin per jawaban benar level Hard', NULL),
(4, 'poin_expert', '20', 'Poin per jawaban benar level Expert', NULL),
(5, 'timer_pemula', '45', 'Waktu per soal level Pemula (detik)', NULL),
(6, 'timer_normal', '30', 'Waktu per soal level Normal (detik)', NULL),
(7, 'timer_hard', '20', 'Waktu per soal level Hard (detik)', NULL),
(8, 'timer_expert', '15', 'Waktu per soal level Expert (detik)', NULL),
(9, 'soal_pemula', '8', 'Jumlah soal level Pemula', NULL),
(10, 'soal_normal', '10', 'Jumlah soal level Normal', NULL),
(11, 'soal_hard', '12', 'Jumlah soal level Hard', NULL),
(12, 'soal_expert', '15', 'Jumlah soal level Expert', NULL),
(13, 'maintenance_mode', '0', 'Mode maintenance (0=off, 1=on)', NULL),
(14, 'double_points_harga', '100', 'Harga power-up Double Points', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `level_progress`
--

CREATE TABLE `level_progress` (
  `id` int(11) NOT NULL,
  `id_pemain` int(11) NOT NULL,
  `level_name` varchar(50) NOT NULL,
  `best_score` int(11) NOT NULL DEFAULT 0,
  `best_streak` int(11) NOT NULL DEFAULT 0,
  `times_completed` int(11) NOT NULL DEFAULT 0,
  `last_played` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemain`
--

CREATE TABLE `pemain` (
  `id_pemain` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT 'default.png',
  `total_skor` int(11) NOT NULL DEFAULT 0,
  `level_pemain` int(11) NOT NULL DEFAULT 1,
  `xp` int(11) NOT NULL DEFAULT 0,
  `koin` int(11) NOT NULL DEFAULT 0,
  `total_main` int(11) NOT NULL DEFAULT 0,
  `skor_tertinggi` int(11) NOT NULL DEFAULT 0,
  `unlocked_levels` text DEFAULT NULL,
  `status` enum('aktif','nonaktif','banned') NOT NULL DEFAULT 'aktif',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pemain`
--

INSERT INTO `pemain` (`id_pemain`, `username`, `password`, `nama_lengkap`, `email`, `avatar`, `total_skor`, `level_pemain`, `xp`, `koin`, `total_main`, `skor_tertinggi`, `unlocked_levels`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'player1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Player Satu', NULL, 'default.png', 0, 1, 0, 0, 0, 0, '[\"Pemula\"]', 'aktif', NULL, '2026-05-12 05:33:48', NULL),
(2, 'mufid', '$2y$12$7dr3CCpJXhz10GgqSB7FZetKKwbk24cDDY7.n/iZR1.0bI9chIfl2', 'mufid', 'mufidhusna251@gmail.com', 'default.png', 465, 3, 230, 2320, 6, 95, '[\"Pemula\",\"Normal\"]', 'aktif', '2026-05-26 02:30:54', '2026-05-12 05:35:27', '2026-05-26 02:30:54'),
(3, 'apabila', '$2y$12$b.2pVOkFLwldDr4UfcksPOKTvD2E8nxCoLB5XXTT8UoHSc1QZSjiO', 'apalbila', 'apabila1234@email.com', 'default.png', 549, 3, 271, 3670, 7, 111, '[\"Pemula\",\"Normal\",\"Hard\"]', 'aktif', '2026-05-17 16:33:21', '2026-05-17 13:55:25', '2026-05-17 16:33:21'),
(4, 'fatir', '$2y$12$Lhucjw4N51Fi81C..tQuz.b98GnzORCJvF2AoOn5TzuDc1Ef81LpG', 'ummi fatir', 'fatir69@email.com', 'default.png', 30, 1, 15, 15, 1, 30, '[\"Pemula\"]', 'aktif', '2026-05-18 03:33:15', '2026-05-18 03:33:07', '2026-05-18 03:39:03'),
(5, 'aril', '$2y$10$sciWsuRXZWfIW6Sv5p/cbumr6seQzDUVgCzrJEyo/hbrs9Emm./sK', 'kahfi', 'aril@email.com', 'default.png', 0, 1, 0, 0, 0, 0, '[\"Pemula\"]', 'aktif', NULL, '2026-05-18 11:02:08', NULL),
(6, 'cece', '$2y$10$HpHwYV7XV8KlsB..rz7EF.ZbqVHVcrvf0j2fziA1biXAcO1tpZtOy', 'inces', 'studywithmufid@gmail.com', 'default.png', 0, 1, 0, 0, 0, 0, '[\"Pemula\"]', 'aktif', NULL, '2026-05-18 11:03:59', NULL),
(7, 'iflese', '$2y$10$npFNr.mGmS0qbtGp0p91puzstIg99.H/qesXK6n/jocgoUXZzYCjm', 'syafira', 'syafira354313@gmail.com', 'default.png', 0, 1, 0, 0, 0, 0, '[\"Pemula\"]', 'aktif', NULL, '2026-05-18 11:11:20', NULL),
(8, 'madslicker', '$2y$10$2FE7vVsoNS3mIFI5UgxTk.fz8yXLNdG4Gni/JLtUtHZioayX4pN.G', 'Mads dittman mikkelsen', 'riellecter@gmail.com', 'default.png', 363, 2, 177, 800, 5, 95, '[\"Pemula\",\"Hard\",\"Expert\",\"Normal\"]', 'aktif', '2026-05-18 11:15:53', '2026-05-18 11:15:41', '2026-05-18 11:34:37'),
(9, '_chocolachies', '$2y$10$/bur8fa5kyV8tZl1BlnfDOX.TFzhR8q7WXM7Kl.R6rgslG/F9.qci', 'ichaa', 'sitikholisha354@gmail.com', 'default.png', 220, 2, 108, 325, 4, 65, '[\"Pemula\",\"Normal\",\"Hard\"]', 'aktif', '2026-05-18 11:16:49', '2026-05-18 11:16:22', '2026-05-18 11:32:06'),
(10, 'Agns', '$2y$10$SZ6YulSs86kFeMeSRShpDeZETn0nC5lPAnyP81YNi6mQXZ.524Ej6', 'Agnes', 'abielljeaa@gmail.com', 'default.png', 285, 2, 141, 1125, 7, 85, '[\"Pemula\",\"Normal\"]', 'aktif', '2026-05-25 18:15:55', '2026-05-18 11:26:16', '2026-05-25 18:15:55'),
(11, 'arstcu', '$2y$10$jxPI5rAakPqP0yVTIrqmh.y/DHhM/Ra/tYNwO3joQIVFFU1bo6yIS', 'arif stecu', 'arif@gmail.com', 'default.png', 0, 1, 0, 0, 0, 0, '[\"Pemula\"]', 'aktif', NULL, '2026-05-18 11:45:25', NULL),
(12, 'Zidan', '$2y$10$xcwxxagG4Ptk8AeL.2Dgt.l1YuMSOT8xAjlxx7F2jQHFD4tvjVkMa', 'Zidan ganteng', '', 'default.png', 65, 1, 32, 30, 1, 65, '[\"Pemula\",\"Normal\"]', 'aktif', '2026-05-18 11:46:16', '2026-05-18 11:46:06', '2026-05-18 11:50:19'),
(13, 'arif', '$2y$10$2NCtFYjnE2dwfIDjsbubXOAVDjszxNmqOm5rkiFvC8lsupS2QbLIO', 'arif stecu', '', 'default.png', 0, 1, 0, 0, 0, 0, '[\"Pemula\"]', 'aktif', '2026-05-18 11:46:30', '2026-05-18 11:46:20', '2026-05-18 11:46:30'),
(14, 'uwu', '$2y$10$TmKljuL4ntyZAKHnfM.yKO6P31tw1zOfgTiOWqjzS9vN69KWoYNRK', 'uwuwuwu', 'cobacob@gmail.com', 'default.png', 0, 1, 0, 0, 0, 0, '[\"Pemula\"]', 'aktif', NULL, '2026-05-18 11:55:03', NULL),
(15, 'arielgays123', '$2y$10$rHa7wKnbgUm.8wu3R.KYpuDCDA0fhR.0ms6ctqgmOJ9x408wM/VrO', 'M alil', 'arielgays123@gmail.com', 'default.png', 0, 1, 0, 0, 0, 0, '[\"Pemula\"]', 'aktif', '2026-05-18 13:15:58', '2026-05-18 12:06:19', '2026-05-19 01:01:42'),
(16, 'Kafhii', '$2y$10$dzipfo63ROiFiBjmAhHupO/DK5L3llYWo2SRpg3vr.RLLJ94Bwgo2', 'M alil', 'arielgays123@gmail.com', 'default.png', 225, 2, 110, 300, 4, 85, '[\"Pemula\",\"Hard\"]', 'aktif', '2026-05-19 08:25:55', '2026-05-18 12:07:27', '2026-05-19 08:25:55'),
(17, 'EtsuKagami', '$2y$10$X.A8ra11hXPZNUmwOd6WyuowhIhHWOexoLulv2erSEovFS1.Niq..', '', 'firmanmulyadi618@gmail.com', 'default.png', 65, 1, 32, 15, 1, 65, '[\"Pemula\"]', 'aktif', '2026-05-18 12:26:07', '2026-05-18 12:25:53', '2026-05-18 12:32:10'),
(18, 'Issei Jawa', '$2y$10$SHFphWCXWFOzZYsGa7vtneGqt9eztQ41oZKlBNg776SIWu8t3xMU2', 'Issei Hyoudou-Wicaksono', 'profissei13@gmail.com', 'default.png', 40, 1, 20, 20, 1, 40, '[\"Pemula\"]', 'aktif', '2026-05-18 12:46:00', '2026-05-18 12:45:45', '2026-05-18 12:51:44'),
(19, 'Gaa', '$2y$10$R/zl8Yyw.oHkHOweD49xoO6cpBMvi0iurk.Ro6xaNqeML7L47erEO', 'Gagaga', '', 'default.png', 40, 1, 20, 10, 1, 40, '[\"Pemula\"]', 'aktif', '2026-05-18 14:12:27', '2026-05-18 14:12:19', '2026-05-18 14:18:21'),
(20, 'Dimsum mentai hot6967', '$2y$10$sRWSZgGrIFkYvEARhhshZuqWNZfA7PYXFFRLruwvKM7gUvihbHIdy', 'Dimas', 'chandradimasw@gmail.com', 'default.png', 90, 1, 45, 45, 2, 60, '[\"Pemula\"]', 'aktif', '2026-05-18 14:15:30', '2026-05-18 14:15:21', '2026-05-18 14:23:24'),
(21, 'anto', '$2y$10$8AZoCZz4EjToLNv88A.oo.FNfFHo0sbQHZ0llmO1JjOvTD7fIpylS', 'anto kewer', 'kewer@email.com', 'default.png', 0, 1, 0, 0, 0, 0, '[\"Pemula\"]', 'aktif', '2026-05-19 03:49:48', '2026-05-19 03:45:59', '2026-05-19 03:49:48');

-- --------------------------------------------------------

--
-- Table structure for table `pemain_achievement`
--

CREATE TABLE `pemain_achievement` (
  `id` int(11) NOT NULL,
  `id_pemain` int(11) NOT NULL,
  `id_achievement` int(11) NOT NULL,
  `unlocked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skor`
--

CREATE TABLE `skor` (
  `id_skor` int(11) NOT NULL,
  `id_pemain` int(11) NOT NULL,
  `skor` int(11) NOT NULL DEFAULT 0,
  `jumlah_benar` int(11) NOT NULL DEFAULT 0,
  `jumlah_salah` int(11) NOT NULL DEFAULT 0,
  `streak_terpanjang` int(11) NOT NULL DEFAULT 0,
  `waktu_tempuh` int(11) NOT NULL DEFAULT 0,
  `xp_didapat` int(11) NOT NULL DEFAULT 0,
  `koin_didapat` int(11) NOT NULL DEFAULT 0,
  `mode_permainan` varchar(50) DEFAULT NULL,
  `level_cleared` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal_main` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skor`
--

INSERT INTO `skor` (`id_skor`, `id_pemain`, `skor`, `jumlah_benar`, `jumlah_salah`, `streak_terpanjang`, `waktu_tempuh`, `xp_didapat`, `koin_didapat`, `mode_permainan`, `level_cleared`, `tanggal_main`) VALUES
(1, 2, 95, 8, 0, 8, 0, 47, 40, 'Pemula', 0, '2026-05-15 22:24:33'),
(2, 2, 95, 8, 0, 8, 0, 47, 80, 'Pemula', 0, '2026-05-16 09:33:20'),
(3, 2, 65, 6, 2, 4, 0, 32, 150, 'Pemula', 0, '2026-05-17 02:21:01'),
(4, 2, 30, 3, 5, 1, 0, 15, 285, 'Pemula', 0, '2026-05-17 03:27:52'),
(5, 2, 95, 8, 0, 8, 0, 47, 595, 'Pemula', 0, '2026-05-17 04:35:04'),
(6, 3, 75, 7, 2, 4, 0, 37, 35, 'Pemula', 0, '2026-05-17 13:59:19'),
(7, 3, 24, 2, 7, 1, 0, 12, 45, 'Normal', 0, '2026-05-17 14:31:15'),
(8, 3, 95, 8, 1, 8, 0, 47, 120, 'Pemula', 0, '2026-05-17 14:42:29'),
(9, 3, 75, 6, 2, 5, 0, 37, 230, 'Pemula', 0, '2026-05-17 15:00:54'),
(10, 3, 75, 6, 2, 6, 0, 37, 460, 'Pemula', 0, '2026-05-17 15:25:23'),
(11, 3, 94, 7, 1, 4, 0, 46, 925, 'Normal', 0, '2026-05-17 15:28:40'),
(12, 3, 111, 8, 0, 8, 0, 55, 1855, 'Normal', 0, '2026-05-17 15:31:40'),
(13, 4, 30, 3, 5, 1, 0, 15, 15, 'Pemula', 0, '2026-05-18 03:39:03'),
(14, 8, 70, 6, 2, 3, 0, 34, 30, 'Pemula', 0, '2026-05-18 11:20:55'),
(15, 9, 50, 5, 3, 2, 0, 25, 25, 'Pemula', 0, '2026-05-18 11:21:37'),
(16, 8, 53, 4, 4, 3, 0, 26, 50, 'Normal', 0, '2026-05-18 11:25:21'),
(17, 9, 45, 3, 3, 2, 0, 21, 40, 'Hard', 0, '2026-05-18 11:25:23'),
(18, 8, 60, 4, 2, 2, 0, 28, 100, 'Hard', 0, '2026-05-18 11:28:54'),
(19, 9, 65, 5, 3, 3, 0, 32, 90, 'Normal', 0, '2026-05-18 11:30:09'),
(20, 8, 85, 4, 0, 4, 0, 42, 200, 'Expert', 0, '2026-05-18 11:30:41'),
(21, 9, 60, 3, 1, 2, 0, 30, 170, 'Expert', 0, '2026-05-18 11:32:06'),
(22, 8, 95, 8, 0, 8, 0, 47, 420, 'Pemula', 0, '2026-05-18 11:34:37'),
(23, 12, 65, 6, 2, 4, 0, 32, 30, 'Pemula', 0, '2026-05-18 11:50:19'),
(24, 16, 45, 4, 4, 3, 0, 22, 20, 'Pemula', 0, '2026-05-18 12:12:12'),
(25, 16, 65, 5, 3, 3, 0, 32, 45, 'Normal', 0, '2026-05-18 12:16:22'),
(26, 16, 30, 2, 4, 1, 0, 14, 75, 'Hard', 0, '2026-05-18 12:19:26'),
(27, 16, 85, 4, 0, 4, 0, 42, 160, 'Expert', 0, '2026-05-18 12:21:08'),
(28, 17, 65, 3, 1, 3, 0, 32, 15, 'Expert', 0, '2026-05-18 12:32:10'),
(29, 2, 85, 4, 0, 4, 0, 42, 1170, 'Expert', 0, '2026-05-18 12:38:19'),
(30, 18, 40, 4, 4, 2, 0, 20, 20, 'Pemula', 0, '2026-05-18 12:51:44'),
(31, 19, 40, 2, 2, 2, 0, 20, 10, 'Expert', 0, '2026-05-18 14:18:21'),
(32, 20, 30, 3, 5, 1, 0, 15, 15, 'Pemula', 0, '2026-05-18 14:21:32'),
(33, 20, 60, 3, 1, 2, 0, 30, 30, 'Expert', 0, '2026-05-18 14:23:24'),
(34, 10, 40, 4, 4, 2, 0, 20, 20, 'Pemula', 0, '2026-05-19 01:56:25'),
(35, 10, 65, 3, 1, 3, 0, 32, 35, 'Expert', 0, '2026-05-19 01:58:17'),
(36, 10, 85, 4, 0, 4, 0, 42, 75, 'Expert', 0, '2026-05-19 01:59:43'),
(37, 10, 85, 4, 0, 4, 0, 42, 150, 'Expert', 0, '2026-05-19 02:01:41'),
(38, 10, 0, 0, 8, 0, 0, 0, 0, 'Pemula', 0, '2026-05-19 02:08:19'),
(39, 10, 0, 0, 8, 0, 0, 0, 280, 'Pemula', 0, '2026-05-19 02:17:15'),
(40, 10, 10, 1, 7, 1, 0, 5, 565, 'Pemula', 0, '2026-05-19 02:24:50');

-- --------------------------------------------------------

--
-- Table structure for table `soal`
--

CREATE TABLE `soal` (
  `id_soal` int(11) NOT NULL,
  `nama_file_audio_preview` varchar(255) DEFAULT NULL,
  `nama_file_audio_full` varchar(255) DEFAULT NULL,
  `durasi_audio` int(11) NOT NULL DEFAULT 0,
  `lirik_awal` text DEFAULT NULL,
  `lirik_lanjutan` text DEFAULT NULL,
  `pilihan_a` varchar(500) NOT NULL,
  `pilihan_b` varchar(500) NOT NULL,
  `pilihan_c` varchar(500) NOT NULL,
  `pilihan_d` varchar(500) NOT NULL,
  `jawaban_benar` enum('A','B','C','D') NOT NULL,
  `genre_musik` varchar(50) DEFAULT NULL,
  `artis` varchar(100) DEFAULT NULL,
  `tahun_rilis` year(4) DEFAULT NULL,
  `level_kesulitan` enum('mudah','sedang','sulit','expert') NOT NULL DEFAULT 'sedang',
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `soal`
--

INSERT INTO `soal` (`id_soal`, `nama_file_audio_preview`, `nama_file_audio_full`, `durasi_audio`, `lirik_awal`, `lirik_lanjutan`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `jawaban_benar`, `genre_musik`, `artis`, `tahun_rilis`, `level_kesulitan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(48, '6a07a1ef74089_preview.mp3', '6a07a1ef79c56_full.mp3', 0, 'Lately you\'ve been busy, wondering if you miss me\r\nWhy did you go against me? I just wanna know\r\nHow come you act so different? Just talk to me I\'ll listen\r\nAll the love I\'m giving, don\'t act like you don\'t know', 'I was out there on the road, life out of control\r\nShe became a victim to my busy schedule\r\nAnd I know that it\'s not fair, that don\'t mean that I don\'t care\r\nThis one\'s dedicated to the girl out there', 'I was out there on the road, life out of control', ' When I know you probably think it\'s a lie', 'I know I told you last time was the last time', 'How could you pull the plug and let me flatline?', 'A', 'RnB', 'Justin Bieber', 2013, 'mudah', 1, '2026-05-15 22:45:03', NULL),
(50, '6a07e0d0ef0a9_preview.mp3', '6a07e0d100619_full.mp3', 0, 'Now, you\'re the inspiration of this precious song\r\nAnd I just wanna see your face light up since you put me on\r\nSo now, I say goodbye to the old me, it\'s already gone', 'And I can\'t wait, wait, wait, wait, wait to get you home\r\nJust to let you know, you are', 'You are, you are the love of my life', 'My mirror staring back at me, staring back at me', 'And I can\'t wait, wait, wait, wait, wait to get you home Just to let you know, you are', 'And now it\'s clear as this promise', 'C', 'RnB', 'Justin Timberlake', 2013, 'sedang', 1, '2026-05-16 03:13:21', NULL),
(51, '6a07e1ca054cb_preview.mp3', '6a07e1ca0bd59_full.mp3', 0, 'Ever since I was a kid, I been legit (Jit, ooh, nah)\r\nIf I was you, I would cut up my wrist (Dumb bit\')\r\nXO tatted all over her body, yeah (Yeah)\r\nShe just wanna roll and I don\'t mind it, yeah', '', 'It don\'t matter what they say, I\'m timeless, yeah (Schyeah)', 'You should let her go, she wanna be it (Oh, yeah)', 'Them drugs finna hit, I\'m feelin\' ill', 'Ever since I was a jit, I been legit (Ooh, nah, uh)', 'D', 'Pop', 'The Weeknd & Playboi Carti', 2024, 'mudah', 1, '2026-05-16 03:17:30', NULL),
(53, '6a07e6195a6a8_preview.mp3', '6a07e61961771_full.mp3', 0, '(Ooh)\r\nWho am I gonna call?\r\n(Ooh)\r\nWho\'s gonna catch me when I\r\n(Ooh)\r\nWho\'s the one you\'re sleeping with?\r\n(Ooh-ooh-ooh-ooh)', 'Well, don\'t you sit in front of me ', 'And this time, baby', 'And I wish I could say the same for you', 'Well, don\'t you sit in front of me ', 'We thought we should try it again', 'C', 'Pop', 'The Neighbourhood', 2015, 'mudah', 1, '2026-05-16 03:35:53', NULL),
(54, '6a07ef6e4b7ce_preview.mp3', '6a07ef6e519eb_full.mp3', 0, 'You got that long hair, slicked back, white t-shirt\r\nAnd I got that good girl faith and a tight little skirt\r\nAnd when we go crashing down, we come back every time\r\n\'Cause we never go out of style (we never go, we never go)\r\nWe never go out of style', 'Take me home\r\nJust take me home\r\nYeah, just take me home\r\n(Out of style)', 'Oh, you got that James Dean daydream look in your eye', 'Take me home Just take me home Yeah, just take me home (Out of style)', 'I say, \"I heard, oh That you\'ve been out and about with some other girl', 'So it goes He can\'t keep his wild eyes on the road, mm, mm', 'B', 'Pop', 'Taylor Swift', 2024, 'sedang', 1, '2026-05-16 04:15:42', NULL),
(55, '6a07f13cf07c3_preview.mp3', '6a07f13d02d6a_full.mp3', 0, 'We gonna party like it\'s 3012 tonight\r\nI wanna show you all the finer things in life\r\nSo just forget about the world, be young tonight\r\nI\'m coming for ya, I\'m coming for ya', 'Cause all I need is a beauty and a beat\r\nWho can make my life complete', 'Body rock, girl, I can feel your body rock', 'It\'s all by you, when the music makes you move Baby do it like you do', 'What you got, a billion could\'ve never bought', 'Cause all I need is a beauty and a beat Who can make my life complete', 'D', 'RnB', 'Justin Bieber', 2012, 'mudah', 1, '2026-05-16 04:23:25', NULL),
(56, '6a07f3c14631b_preview.mp3', '6a07f3c14951d_full.mp3', 0, 'I never wanna play the same old part\r\nKeep you in the dark (keep you in the dark)\r\nNow let me show you the shape of my heart', 'Looking back on the things I\'ve done\r\nI was trying to be someone (trying to be someone)\r\nPlayed my part, kept you in the dark\r\nNow let me show you the shape of my heart', 'Looking back on the things I\'ve done', 'But to show you the shape of my heart', 'You can save me from the man I\'ve become', 'I\'m here with my confession', 'A', 'Pop', 'Backstreet Boys', 2000, 'mudah', 1, '2026-05-16 04:34:09', NULL),
(57, '6a07f52e66dcb_preview.mp3', '6a07f52e6a3a5_full.mp3', 0, 'People always told me, \"Be careful of what you do\r\nAnd don\'t go around breaking young girls\' hearts\" (hee-hee)\r\nAnd mother always told me, \"Be careful of who you love\r\nAnd be careful of what you do (oh-oh)\r\n\'Cause the lie becomes the truth\" (oh-oh), hey-ey', 'Billie Jean is not my lover, uh\r\nShe\'s just a girl who claims that I am the one (oh, baby, oh, no)\r\nBut the kid is not my son (whoo)', 'She says I am the one (oh, baby)', 'Billie Jean is not my lover, uh', 'Just remember to always think twice', 'For 40 days and for 40 nights, I was on her side', 'B', 'RnB', 'Michael Jackson', 1982, 'expert', 1, '2026-05-16 04:40:14', NULL),
(58, '6a07f9b0d8ee7_preview.mp3', '6a07f9b0df1da_full.mp3', 0, 'People always told me, \"Be careful of what you do\r\nAnd don\'t go around breaking young girls\' hearts\" (hee-hee)\r\nAnd mother always told me, \"Be careful of who you love\r\nAnd be careful of what you do (oh-oh)\r\n\'Cause the lie becomes the truth\" (oh-oh), hey-ey', 'Billie Jean is not my lover, uh\r\nShe\'s just a girl who claims that I am the one (oh, baby, oh, no)\r\nBut the kid is not my son (whoo)', 'She says I am the one (oh, baby)', 'Billie Jean is not my lover, uh', 'Just remember to always think twice', 'For 40 days and for 40 nights, I was on her side', 'B', 'RnB', 'Michael Jackson', 1982, 'expert', 1, '2026-05-16 04:59:28', NULL),
(59, '6a07fac8813a7_preview.mp3', '6a07fac8849ac_full.mp3', 0, 'Did you think that I wouldn\'t see you out at the movies?\r\nWhat you doin\' to me? You\'re taking him where we used to go\r\nNow if you\'re trying to break my heart\r\nIt\'s workin\', \'cause you know that', 'That should be me, holdin\' your hand\r\nThat should be me, makin\' you laugh\r\nThat should be me, this is so sad\r\nThat should be me, that should be me', 'That should be me, makin\' you laugh', 'That should be me, holdin\' your hand', 'That should be me, that should be me', 'That should be me, this is so sad', 'B', 'Pop', 'Justin Bieber', 2010, 'mudah', 1, '2026-05-16 05:04:08', '2026-05-17 13:50:53'),
(60, '6a0985d360451_preview.mp3', '6a0985d369a0a_full.mp3', 0, 'i\'m young and i love to be young \r\nAnd i\'m free and i love to be free\r\nTo live my life the way I want\r\nTo say and do whatever I please', 'Hey, hey (you dont\'t own me)', 'Oh, no, no (you dont\'t own me)', 'Hey, hey (you dont\'t own me)', 'Don\'t don\'t', 'you dont\'t own me', 'B', 'Pop', 'SAYGRECE, G-Eazy', 2015, 'sedang', 1, '2026-05-17 09:09:39', NULL),
(61, '6a0988a713bb7_preview.mp3', '6a0988a71b50d_full.mp3', 0, 'Deixa a timidez de lado puxa a amiga e vem dançar\r\nMostra tudo o que \'cê sabe o Guuga vai analisar \r\nDeixa a timidez de lado puxa a amiga e vem dançar\r\nMostra tudo o que \'cê sabe que o Livinho vai te olhar', 'Pode, pode, pode sentar \r\nPode, pode, pode sentar \r\nPode, pode, pode sentar \r\nE se tiver muito excitada, \r\npode dançar pelada Se tiver muito excitada, \r\npode dançar pelada \r\nEu \'to vidrado em você\r\n', 'Deixa a timidez de lado puxa a amiga e vem dançar', 'Balança a bunda agora!', 'Pode, pode, pode sentar', 'Mostra tudo o que \'cê sabe o Guuga vai analisar ', 'C', 'HipHop', 'DJ Guuga, MC Livinho', 2019, 'expert', 1, '2026-05-17 09:21:43', NULL),
(62, '6a098a0844896_preview.mp3', '6a098a084c656_full.mp3', 0, 'move that body let me se just do\r\ngirl you magnificent \r\nno lie', 'feel your eyes\r\nthey all over me\r\ndon\'t be shy\r\ntake control of me\r\nit\'s the vibe it\'s gonna be lit tonight\r\nno lie', 'Move so hypnotic', 'Hypnotic, the way you move', 'no lie', 'feel your eyes', 'D', 'Pop', 'Sean Paul, Dua Lipa', 2016, 'sedang', 1, '2026-05-17 09:27:36', NULL),
(63, '6a098b186059d_preview.mp3', '6a098b186a2c6_full.mp3', 0, 'For what is a man, what has he got?\r\nIf not himself then he has naught\r\nNot to say the things that he truly feels\r\nAnd not the words of someone who kneels', 'Let the record shows I took all the blows and did it my way', 'Let the record shows I took all the blows and did it my way', 'I faced it all and I stood tall and did it my way', 'I ate it up and spit it out', 'Yes, there were times I\'m sure you knew', 'A', 'Jazz', 'Frank Sinatra, Luciano Pavarotti', 1969, 'sulit', 1, '2026-05-17 09:32:08', NULL),
(64, '6a098c5c0830d_preview.mp3', '6a098c5c0f77a_full.mp3', 0, 'Ku jenuh mendengar suaramu\r\nKu jenuh dengar celotehmu\r\nJungkir balik mencintaimu\r\nTiada lagi maaf bagimu', 'Direject direject direject aja\r\nDireject direject direject aja\r\nPacar yang tak setia, usah ditanggapin\r\nDimaafin malah nyakitin', 'Tak tertipu suara palsumu', 'Jangan nomor baru kau hubungi aku', 'Direject direject direject aja', 'Kamu calling-calling aku lagi pusing', 'C', 'Dangdut', 'Jenita Janet', 2013, 'sedang', 1, '2026-05-17 09:37:32', NULL),
(65, '6a098d68b8015_preview.mp3', '6a098d68c0183_full.mp3', 0, 'Kamu tak memperhatikanku\r\nRindunya hatiku\r\nRindu, ingin bertemu\r\nKu ingin dirimu\r\nS\'lalu di sisiku', 'Aku meriang, aku meriang\r\nAku meriang, merindukan kasih sayang\r\nAku meriang, aku meriang\r\nAku meriang, aku butuh perhatian', 'Aku merica, aku merica', 'Aku meriang, aku meriang', 'Aku merinding, aku merinding', 'Aku menggigil, aku menggigil', 'B', 'Dangdut', 'Cita Citata', 2015, 'sedang', 1, '2026-05-17 09:42:00', NULL),
(66, '6a098f871de33_preview.mp3', '6a098f8726421_full.mp3', 0, 'Don\'t wanna wake up one day wishing that we\'d done more\r\nI wanna live fast and never look back, that\'s what we\'re here for\r\nDon\'t wanna wake up one day wondering, where\'d it all go?\r\nCause we\'ll be home before we know\r\nI wanna hear you sing it', 'Hey, mama, don\'t stress your mind\r\nWe ain\'t coming home tonight\r\nHey, mom, we gonna be alright\r\nDry those eyes\r\nWe\'ll be back in the morning when the sun starts to rise\r\nSo mama, don\'t stress your mind\r\nSo mama, don\'t stress your mind\r\nMama, mama, mama, hey', '\'Cause I got the keys, baby', 'I got the keys to the universe', 'Hey, mama, don\'t stress your mind', 'Where should we run to?', 'C', 'Pop', 'Jonas Blue, William Singe', 2017, 'sedang', 1, '2026-05-17 09:51:03', NULL),
(67, '6a09920d04b35_preview.mp3', '6a09920d0ce23_full.mp3', 0, 'ne modeun ge nae mam-e dallabut-eobeolyeo, boy\r\n\r\nWe\'re magnetized, injeonghalge\r\n\r\nThis time, I want', 'You, you, you, you, like it\'s magnetic\r\nYou, you, you, you, you, you, you, you, super ikkeulim\r\nYou, you, you, you, like it\'s magnetic\r\nYou, you, you, you, you, you, you, you, super ikkeulim', 'Bae, bae, bae, bae, bae, bae, bae, bae, bae', 'Dash-da-da, dash-da-da, dash-da, like it\'s magnetic', 'You, you, you, you, like it\'s magnetic', 'jeongbandae gat-a our type, neon J, nan wanjeon P', 'C', 'Pop', 'ILLIT', 2024, 'sedang', 1, '2026-05-17 10:01:49', NULL),
(68, '6a09932595153_preview.mp3', '6a0993259c1ae_full.mp3', 0, 'Dasar kau keong racun\r\nBaru kenal eh ngajak tidur\r\nNgomong nggak sopan santun\r\nKau anggap aku ayam kampung\r\nKau rayu diriku\r\nKau goda diriku\r\nKau colek diriku', 'Eh ku takut sekali\r\ntanpa basa basi kau ngajak happy happy\r\nEh kau tak tahu malu\r\nTanpa basa basi kau ngajak happy happy', 'Mulut kumat kemot', 'Mentang-mentang kau kaya', 'Ngajak check-in dan santai', 'Eh ku takut sekali', 'D', 'Dangdut', 'Lissa', 2010, 'mudah', 1, '2026-05-17 10:06:29', '2026-05-17 13:49:58'),
(69, '6a09957527d3c_preview.mp3', '6a099575315a4_full.mp3', 0, 'Go on now, go, walk out the door\r\nJust turn around now, \'cause you\'re not welcome anymore\r\nWeren\'t you the one who tried to hurt me with goodbye?\r\nDid you think I\'d crumble? Did you think I\'d lay down and die?', 'Oh no, not I, I will survive\r\nOh, as long as I know how to love, I know I\'ll stay alive\r\nI\'ve got all my life to live, and I\'ve got all my love to give\r\nI will survive, I will survive, hey-hey', 'And I spent oh so many nights', 'Oh no, not I, I will survive', 'It took all the strength I had,', 'Kept tryin\' hard to mend the pieces of my broken heart', 'B', 'Pop', 'Demi Lovato', 2016, 'sulit', 1, '2026-05-17 10:16:21', NULL),
(70, '6a0997387ade4_preview.mp3', '6a09973883b84_full.mp3', 0, 'Deiteu naenae jellisyujeu\r\nI got Suede on my vinyl Naui donghwachaek', 'I\'m not cute anymore (Uh-uh)\r\nI\'m not cute anymore (Uh-uh)\r\n', 'I\'m not cute anymore (Uh-uh) I\'m not cute anymore (Uh-uh)', '\'Cause I\'m not cute anymore', 'I got Suede on my vinyl, ', 'no keyring, no hand mirror', 'A', 'KPop', 'ILLIT', 2025, 'mudah', 1, '2026-05-17 10:23:52', '2026-05-17 13:49:03'),
(71, '6a099867f3413_preview.mp3', '6a09986807416_full.mp3', 0, 'Lord, I can\'t change\r\nWon\'t you fly high,', ' free bird, yeah', 'Things just couldn\'t be the same', ' free bird, yeah', 'If I stay here with you, girl', 'Oh, oh, oh, oh', 'B', 'Rock', 'Lynyrd Skynyrd', 1973, 'sulit', 1, '2026-05-17 10:28:56', NULL),
(72, '6a09b30b39fcf_preview.mp3', '6a09b30b447b6_full.mp3', 0, 'I\'ma blow this, blow this, oh, blow this, blow this\r\nI\'ma blow this, blow this, oh, blow this house, house down', 'Dishes, breakin\' dishes, breakin\' dishes\r\nI\'m breakin\' dishes up in here, all night (uh-huh)\r\nI ain\'t gon\' stop until I see police lights (uh-huh)\r\nI\'ma fight a man', 'Dishes, breakin\' dishes, breakin\' dishes', 'A man, a man, a ma-e-a-a-an', '(Ah) if you don\'t come, I\'ma huff and puff', '(Ah) I don\'t know who you think I am (I don\'t know who you think I am)', 'A', 'RnB', 'Rihanna', 2007, 'sedang', 1, '2026-05-17 12:22:35', NULL),
(73, '6a09b4e45092f_preview.mp3', '6a09b4e46a8f7_full.mp3', 0, 'Bye-bye\r\nYeah, boy, bye\r\nIt\'s over, it\'s over, oh, yeah', 'This ain\'t the first time I\'ve been hostage to these tears\r\nI can\'t believe I\'m finally movin\' through my fears\r\nAt least I know how hard we tried, both you and me\r\nDidn\'t we? Didn\'t we?', 'So I grab my stuff', 'Courtney just pulled up in the driveway', 'This ain\'t the first time I\'ve been hostage to these tears', 'Bye-bye', 'C', 'Pop', 'Altare', 2025, 'sulit', 1, '2026-05-17 12:30:28', '2026-05-17 13:51:45'),
(74, '6a09b6009fdf1_preview.mp3', '6a09b600a947d_full.mp3', 0, 'For every kiss you give me\r\nI\'ll give you three\r\nOh, since the day I saw you\r\nI have been waiting for you\r\nYou know I will adore you\r\n\'Til eternity', 'So won\'t you, please (Be my, be my baby)\r\nBe my little baby? (My one and only baby)\r\nSay you\'ll be my darling (Be my, be my baby)\r\nBe my baby, now (My one and only baby)\r\nWhoa-oh-oh-oh-oh', 'Be my baby, now (My one and only baby)', 'So, come on and be (Be my, be my baby)', 'Oh-oh-oh-oh', 'So won\'t you, please (Be my, be my baby)', 'D', 'RnB', 'The Ronettes', 1963, 'mudah', 1, '2026-05-17 12:35:12', '2026-05-17 13:50:21'),
(75, '6a09b7909a304_preview.mp3', '6a09b790a10f4_full.mp3', 0, '--', 'ada berondong tua\r\ntebar tebar pesona\r\nsukanya daun muda\r\ndia lupa usia', 'Cartel', 'Berondong tua', 'Pemersatu bangsa', 'Sahara', 'B', 'Dangdut', 'Siti Badriah', 2014, 'sulit', 1, '2026-05-17 12:41:52', NULL),
(76, '6a09b862de16b_preview.mp3', '6a09b862e6396_full.mp3', 0, 'B.I.G yea we bang like this modu da gati\r\n\r\nChong majeun geotcheoreom', 'Bang! Bang! Bang!\r\nBang! Bang! Bang!\r\nPpangya ppangya ppangya\r\nBang! Bang! Bang!\r\nBang! Bang! Bang!\r\nPpangya ppangya ppangya\r\n', 'Bang! Bang! Bang!', 'Neol michige hago sipeo', 'Nan bureul jilleo', 'Simjangeul taewo', 'A', 'KPop', 'BigBang', 2015, 'sulit', 1, '2026-05-17 12:45:22', NULL),
(77, '6a09baa96d7b6_preview.mp3', '6a09baa97879d_full.mp3', 0, 'A-a-aşkım, çok pardon-don\r\nA-a-aşkım, çok pardon-don\r\nBütün kızlar bende var\r\nBende para çok çok var (A-A-A—)\r\nBurnumda hep to****lar var', 'Var, var, var, var, var, var, var\r\nTamponu yere bastırdım\r\nHemen tesisat yaptırdım\r\nKıza kendimi kaptırdım\r\nKıza kendimi kaptırdım', 'sarılara biteriz', 'Var, var, var, var, var, var, var', 'A-a-aşkım, çok pardon-don', 'Lvbel C5 iyi ki var', 'B', 'HipHop', 'AKDO, Lvlbel C5', 2025, 'expert', 1, '2026-05-17 12:55:05', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievement`
--
ALTER TABLE `achievement`
  ADD PRIMARY KEY (`id_achievement`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `konfigurasi_game`
--
ALTER TABLE `konfigurasi_game`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_setting` (`nama_setting`);

--
-- Indexes for table `level_progress`
--
ALTER TABLE `level_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pemain_level` (`id_pemain`,`level_name`);

--
-- Indexes for table `pemain`
--
ALTER TABLE `pemain`
  ADD PRIMARY KEY (`id_pemain`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_skor_tertinggi` (`skor_tertinggi`);

--
-- Indexes for table `pemain_achievement`
--
ALTER TABLE `pemain_achievement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_achievement` (`id_achievement`),
  ADD KEY `idx_pemain_achievement` (`id_pemain`,`id_achievement`);

--
-- Indexes for table `skor`
--
ALTER TABLE `skor`
  ADD PRIMARY KEY (`id_skor`),
  ADD KEY `idx_pemain` (`id_pemain`),
  ADD KEY `idx_tanggal` (`tanggal_main`);

--
-- Indexes for table `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id_soal`),
  ADD KEY `idx_kesulitan` (`level_kesulitan`),
  ADD KEY `idx_status` (`status_aktif`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievement`
--
ALTER TABLE `achievement`
  MODIFY `id_achievement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `konfigurasi_game`
--
ALTER TABLE `konfigurasi_game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `level_progress`
--
ALTER TABLE `level_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemain`
--
ALTER TABLE `pemain`
  MODIFY `id_pemain` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pemain_achievement`
--
ALTER TABLE `pemain_achievement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skor`
--
ALTER TABLE `skor`
  MODIFY `id_skor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `level_progress`
--
ALTER TABLE `level_progress`
  ADD CONSTRAINT `level_progress_ibfk_1` FOREIGN KEY (`id_pemain`) REFERENCES `pemain` (`id_pemain`) ON DELETE CASCADE;

--
-- Constraints for table `pemain_achievement`
--
ALTER TABLE `pemain_achievement`
  ADD CONSTRAINT `pemain_achievement_ibfk_1` FOREIGN KEY (`id_pemain`) REFERENCES `pemain` (`id_pemain`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemain_achievement_ibfk_2` FOREIGN KEY (`id_achievement`) REFERENCES `achievement` (`id_achievement`) ON DELETE CASCADE;

--
-- Constraints for table `skor`
--
ALTER TABLE `skor`
  ADD CONSTRAINT `skor_ibfk_1` FOREIGN KEY (`id_pemain`) REFERENCES `pemain` (`id_pemain`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
