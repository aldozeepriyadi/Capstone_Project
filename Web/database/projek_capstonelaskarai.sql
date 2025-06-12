-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Jun 2025 pada 06.27
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
-- Database: `projek_capstonelaskarai`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_topics`
--

CREATE TABLE `chat_topics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `chat_topics`
--

INSERT INTO `chat_topics` (`id`, `user_id`, `title`, `created_at`) VALUES
(9, 3, 'halo', '2025-06-06 02:36:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sender` enum('user','bot') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `messages`
--

INSERT INTO `messages` (`id`, `topic_id`, `user_id`, `sender`, `message`, `timestamp`) VALUES
(32, 9, 3, 'user', 'halo', '2025-06-06 02:36:40'),
(33, 9, 3, 'bot', 'Halo juga! Ada yang bisa saya bantu hari ini?\n', '2025-06-06 02:36:40'),
(34, 9, 3, 'user', 'kamu gimana', '2025-06-06 02:36:49'),
(35, 9, 3, 'bot', 'Sebagai model bahasa besar, saya tidak memiliki perasaan atau kondisi pribadi seperti manusia. Saya selalu siap membantu Anda dengan pertanyaan atau tugas apa pun yang Anda miliki. Jadi, saya \"baik-baik saja\" dalam artian saya berfungsi dengan baik dan siap membantu Anda.\n\nAda hal spesifik yang ingin Anda tanyakan atau butuhkan bantuan dari saya?\n', '2025-06-06 02:36:49'),
(36, 9, 3, 'user', 'halo', '2025-06-06 02:36:56'),
(37, 9, 3, 'bot', 'Halo juga! Ada yang bisa saya bantu?\n', '2025-06-06 02:36:56'),
(60, 9, 3, 'user', 'coba', '2025-06-06 11:27:05'),
(61, 9, 3, 'bot', 'Halo! Ada yang bisa saya bantu?', '2025-06-06 11:27:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`) VALUES
(3, 'Rizki Wahyu', 'rizkiwnf', 'rizkiwahyuproject@gmail.com', '$2y$10$RVvjd3/hDQ9rti3xp8vQCewSjlrAYWMEVdxMh/m.U8xXVbSQrVQ22', 'user'),
(4, 'rizki', 'rizki', 'rizki@gmail.com', '$2y$10$NduS5Egja1k7jGYvYZ93W.8tG917SWIHcLH5ZNInuPDCXW2DH4URO', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `chat_topics`
--
ALTER TABLE `chat_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `chat_topics`
--
ALTER TABLE `chat_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `chat_topics`
--
ALTER TABLE `chat_topics`
  ADD CONSTRAINT `chat_topics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `chat_topics` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
