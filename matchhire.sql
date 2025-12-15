-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Des 2025 pada 13.48
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
-- Database: `matchhire`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `pelamar_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `status` enum('Terkirim','Direview','Ditolak','Diterima') DEFAULT 'Terkirim',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `applications`
--

INSERT INTO `applications` (`application_id`, `pelamar_id`, `job_id`, `status`, `submitted_at`, `updated_at`) VALUES
(1, 1, 1, 'Diterima', '2025-12-14 11:54:51', '2025-12-14 12:06:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `perusahaan_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `industry` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jobs`
--

INSERT INTO `jobs` (`job_id`, `perusahaan_id`, `title`, `description`, `requirements`, `location`, `salary_range`, `status`, `created_at`, `updated_at`, `industry`) VALUES
(1, 2, 'IT Consultant', 'Membutuhkan pekerja giat IT yang sigap membantu pengawasa server', 'S1 Informatika', 'Jakarta Raya', '15.000.000', 'open', '2025-12-06 19:05:20', '2025-12-06 19:05:20', 'IT'),
(2, 2, 'Junior Software Engineer', 'Mencari pekerja yang giat untuk mengelola dan mengusahakan software penjualan traktor', 'S1 Sistem Informasi\r\nS1 Informatika', 'Surabaya', '7.000.000', 'open', '2025-12-06 19:06:29', '2025-12-06 19:06:29', 'IT');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `recipient_user_id` int(11) NOT NULL,
  `sender_user_id` int(11) DEFAULT NULL,
  `type` enum('status_update','new_application','application_withdrawn') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`notification_id`, `recipient_user_id`, `sender_user_id`, `type`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 2, 'status_update', 'Status lamaran Anda telah diperbarui menjadi: Diterima', 0, '2025-12-14 12:06:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelamar_profile`
--

CREATE TABLE `pelamar_profile` (
  `pelamar_id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `skills` text DEFAULT NULL,
  `preferred_job` varchar(255) DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelamar_profile`
--

INSERT INTO `pelamar_profile` (`pelamar_id`, `full_name`, `phone`, `address`, `birth_date`, `resume_path`, `about`, `updated_at`, `skills`, `preferred_job`, `experience`, `industry`) VALUES
(1, 'Anak Agung Ngurah Dharma Yudha', '081334706701', 'Perumahan Griya Mukti Sejahtera Blok F1, No.21, RT.06, Kel. Gunung Lingai, Kec. Sungai Pinang, Kota Samarinda 75119', '2004-06-23', 'uploads/resume/resume_1_1765047759.pdf', 'Pekerja Keras', '2025-12-06 19:02:39', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `perusahaan_profile`
--

CREATE TABLE `perusahaan_profile` (
  `perusahaan_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `perusahaan_profile`
--

INSERT INTO `perusahaan_profile` (`perusahaan_id`, `company_name`, `industry`, `company_address`, `website`, `description`, `logo_path`, `updated_at`) VALUES
(2, 'PT Trakindo Utama', 'Penyediaan Barang', 'Jl. Raya Cilandak KKO No.1 Lantai 10, RT.13/RW.5, Cilandak Tim., Ps. Minggu, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12560', 'trakindo.co.id', 'Trakindo (PT Trakindo Utama) adalah dealer resmi Caterpillar di Indonesia sejak 1970, menyediakan penjualan, penyewaan, suku cadang, dan layanan untuk alat berat seperti mesin konstruksi, pertambangan, serta genset;', NULL, '2025-12-06 18:59:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role` enum('pelamar','perusahaan') NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `role`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'pelamar', 'dhryudha@gmail.com', '$2y$10$qQiyI4.IpquM0Q09dekuXu7KbxJ0az.7t7oSdjmDzp3dvIkG0f6mW', '2025-12-06 18:48:18', '2025-12-06 18:48:18'),
(2, 'perusahaan', 'cakalika.fernandha@gmail.com', '$2y$10$SYet4w2.gEqQY/AD5xCS8OGaoUBwRg7iIwGVtc6qnzhF5w3rVVAiK', '2025-12-06 18:53:52', '2025-12-06 18:53:52'),
(3, 'pelamar', 'yudhacool1133@gmail.com', '$2y$10$U.VHnAZeGaL7voX9fWpf9OrXlKb5Q2JZNi/vmzwt2IyodajA7q2lq', '2025-12-06 18:54:22', '2025-12-06 18:54:22'),
(4, 'pelamar', 'discord@gmail.com', '$2y$10$IG9s7omERVAmXEtsBwtTeuKfvCLx/m7eiFw0tk5vW3y0uT4eQXBrW', '2025-12-06 19:01:20', '2025-12-06 19:01:20');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_log_user` (`user_id`);

--
-- Indeks untuk tabel `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `idx_app_pelamar` (`pelamar_id`),
  ADD KEY `idx_app_job` (`job_id`),
  ADD KEY `idx_app_status` (`status`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `idx_jobs_perusahaan` (`perusahaan_id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notif_recipient` (`recipient_user_id`),
  ADD KEY `fk_notif_sender` (`sender_user_id`);

--
-- Indeks untuk tabel `pelamar_profile`
--
ALTER TABLE `pelamar_profile`
  ADD PRIMARY KEY (`pelamar_id`);

--
-- Indeks untuk tabel `perusahaan_profile`
--
ALTER TABLE `perusahaan_profile`
  ADD PRIMARY KEY (`perusahaan_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_apps_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_apps_pelamar` FOREIGN KEY (`pelamar_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_jobs_perusahaan` FOREIGN KEY (`perusahaan_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notif_recipient` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notif_sender` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pelamar_profile`
--
ALTER TABLE `pelamar_profile`
  ADD CONSTRAINT `fk_pelamar_user` FOREIGN KEY (`pelamar_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `perusahaan_profile`
--
ALTER TABLE `perusahaan_profile`
  ADD CONSTRAINT `fk_perusahaan_user` FOREIGN KEY (`perusahaan_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
