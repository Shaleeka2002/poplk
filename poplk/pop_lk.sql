-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 04:04 PM
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
-- Database: `pop_lk`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_slug` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_slug`) VALUES
(1, 'KDrama', 'kdrama'),
(2, 'CDrama', 'cdrama'),
(3, 'JDrama', 'jdrama'),
(4, 'Others', 'others');

-- --------------------------------------------------------

--
-- Table structure for table `dramas`
--

CREATE TABLE `dramas` (
  `drama_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_sinhala` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL,
  `imdb_rating` decimal(3,1) DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT 0,
  `poster_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dramas`
--

INSERT INTO `dramas` (`drama_id`, `title`, `title_sinhala`, `category_id`, `genre`, `description`, `release_year`, `imdb_rating`, `is_new`, `poster_url`, `created_at`, `updated_at`) VALUES
(1, 'Mr Plankton', 'ප්ලැන්ක්ටන් මහතා', 1, 'Drama', 'අවාසනාවන්ත මිනිසෙක් සහ ඔහුගේ හිටපු, අවාසනාවන්තම මනාලිය, ඔහුගේ ජීවිතයේ අවසාන ගමනේදී එකිනෙකා සමඟ යාමට බල කෙරෙයි.', NULL, 6.5, 1, 'images/10 New Korean Dramas Coming Out In November 2024 To Add To Your Watch List.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(2, 'All of Us are Dead', NULL, 1, 'Horror, Thriller, Action, Adventure', 'සිරවී සිටින සිසුන් zombie වෛරස් පැතිරීම සඳහා බිම් ශුන්‍ය වී ඇති ඔවුන්ගේ උසස් පාසලෙන් ගැලවිය යුතුය.', NULL, 7.6, 1, 'https://i.pinimg.com/736x/96/83/a8/9683a8f98b4dc21687e348c3aa68defd.jpg', '2025-03-25 16:54:05', '2025-03-26 14:59:38'),
(3, 'Hidden Love', NULL, 2, 'Romantic', 'Sang Zhi ඇගේ සහෝදරයාගේ මිතුරෙකු වන Duan Jia Xu සමඟ ආදරයෙන් බැඳී සිටින අතර, ඇය එම විශ්ව විද්‍යාලයට යාමට පටන් ගත් පසු ඔවුන් වඩාත් සමීප වේ.', NULL, 6.5, 0, 'images/hidden-love.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(4, 'The Queen Who Crown', NULL, 1, 'Historical', NULL, NULL, 7.7, 0, 'https://i.pinimg.com/474x/84/08/b9/8408b9f89aef0d6230ebdf15bef96401.jpg', '2025-03-25 16:54:05', '2025-03-26 15:01:20'),
(5, 'True Beauty', NULL, 1, 'Mystery', NULL, NULL, 6.5, 0, 'images/True beauty.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(6, 'Love Next Door', NULL, 1, 'Mystery', NULL, NULL, 6.5, 0, 'images/Love Next Door KDrama Poster.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(7, 'My Demon', NULL, 1, 'Mystery', NULL, NULL, 6.5, 0, 'images/images (1).jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(8, 'Goblin', NULL, 1, 'Mystery', NULL, NULL, 6.5, 0, 'images/Goblin.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(9, 'Squid Game Season 2', NULL, 1, 'Mystery', NULL, NULL, 6.5, 0, 'images/images (5).jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(10, 'A Business Proposal', NULL, 1, 'Romantic', NULL, NULL, 7.5, 0, 'images/a buissness proposal.jpg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(11, 'Extraordinary You', NULL, 1, 'Teen', NULL, NULL, 5.5, 0, 'images/extraordinary you.jpg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(12, 'The K2', NULL, 1, 'Mystery', NULL, NULL, 6.5, 0, 'images/The K2.jpg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(13, 'The Tale of Nokdu', NULL, 1, 'History', NULL, NULL, 4.5, 0, 'images/The tale of nokdu.jpg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(14, 'Welcome to Samdal-ri', NULL, 1, 'Romantic', NULL, NULL, 6.5, 0, 'images/welcome to samdal-ri.jpg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(15, 'Ski into Love', NULL, 2, 'Love', NULL, NULL, 6.5, 1, 'images/ski-into-love.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(16, 'Hidden Love', NULL, 2, 'Romantic', NULL, NULL, 6.5, 0, 'images/{ 💌 } • 𝗛𝘪𝘥𝘥𝘦𝘯 𝗟𝘰𝘷𝘦 __ 偷偷藏不住.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(17, 'Unforgettable Love', NULL, 2, 'Comedy', NULL, NULL, 6.5, 0, 'images/Unforgettable Love.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(18, 'Eat Run Love', NULL, 2, 'Love', NULL, NULL, 6.5, 1, 'images/Put your head on my shoulder.jpg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(19, 'Put Your Head On My Shoulder', NULL, 2, 'Romantic', NULL, NULL, 5.2, 0, 'images/You are my glory cdrama poster.jpg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(20, 'You Are My Glory', NULL, 2, 'Romantic', NULL, NULL, 4.5, 0, 'images/dr-chocolate.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(21, 'Dr Chocolate', NULL, 3, 'Care', NULL, NULL, 6.5, 0, 'images/my happy marriage.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(22, 'My Happy Marriage', NULL, 3, 'Action', NULL, NULL, 6.5, 0, 'images/Our Secret Diary 2023 _ Наш тайный дневник 2023.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(23, 'Our Secret Diary', NULL, 3, 'Romance', NULL, NULL, 6.5, 0, 'images/Lovesick Ellie.jpeg', '2025-03-25 16:54:05', '2025-03-25 17:47:35'),
(24, 'Lovesick Ellie', NULL, 3, 'Thriller', NULL, NULL, 6.5, 0, 'images/placeholder_24.jpg', '2025-03-25 16:54:05', '2025-03-25 17:41:57'),
(25, '4 Minutes', NULL, 4, '', NULL, NULL, 6.5, 0, 'images/placeholder_25.jpg', '2025-03-25 16:54:05', '2025-03-25 17:41:57');

-- --------------------------------------------------------

--
-- Table structure for table `slideshow`
--

CREATE TABLE `slideshow` (
  `slide_id` int(11) NOT NULL,
  `drama_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `button_text` varchar(50) DEFAULT 'Watch Now',
  `button_link` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slideshow`
--

INSERT INTO `slideshow` (`slide_id`, `drama_id`, `title`, `description`, `video_url`, `button_text`, `button_link`, `display_order`, `is_active`) VALUES
(1, 1, 'Mr Plankton', 'අවාසනාවන්ත මිනිසෙක් සහ ඔහුගේ හිටපු, අවාසනාවන්තම මනාලිය, ඔහුගේ ජීවිතයේ අවසාන ගමනේදී එකිනෙකා සමඟ යාමට බල කෙරෙයි.', 'videos/Mr. Plankton _ Official Trailer _ Netflix EN-SUB (1080p).mp4', 'Watch Now', 'MrPlankton.html', 1, 1),
(2, 2, 'All of Us Are Dead', 'සිරවී සිටින සිසුන් zombie වෛරස් පැතිරීම සඳහා බිම් ශුන්‍ය වී ඇති ඔවුන්ගේ උසස් පාසලෙන් ගැලවිය යුතුය.', 'videos/All of Us Are Dead _ Official Trailer _ Netflix (1080p).mp4', 'Watch Now', 'MrPlankton.html', 2, 1),
(3, 3, 'Hidden Love', 'Sang Zhi ඇගේ සහෝදරයාගේ මිතුරෙකු වන Duan Jia Xu සමඟ ආදරයෙන් බැඳී සිටින අතර, ඇය එම විශ්ව විද්‍යාලයට යාමට පටන් ගත් පසු ඔවුන් වඩාත් සමීප වේ.', 'videos/hidden love.mp4', 'Watch Now', 'MrPlankton.html', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `drama_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `video_id` int(11) NOT NULL,
  `drama_id` int(11) NOT NULL,
  `video_type` enum('trailer','episode','teaser','clip') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) NOT NULL,
  `episode_number` int(11) DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`video_id`, `drama_id`, `video_type`, `title`, `video_url`, `episode_number`, `duration`, `created_at`) VALUES
(1, 1, 'trailer', 'Official Trailer', 'videos/Mr. Plankton _ Official Trailer _ Netflix EN-SUB (1080p).mp4', NULL, NULL, '2025-03-25 16:54:05'),
(2, 2, 'trailer', 'Official Trailer', 'videos/All of Us Are Dead _ Official Trailer _ Netflix (1080p).mp4', NULL, NULL, '2025-03-25 16:54:05'),
(3, 3, 'trailer', 'Official Trailer', 'videos/hidden love.mp4', NULL, NULL, '2025-03-25 16:54:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `dramas`
--
ALTER TABLE `dramas`
  ADD PRIMARY KEY (`drama_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `slideshow`
--
ALTER TABLE `slideshow`
  ADD PRIMARY KEY (`slide_id`),
  ADD KEY `drama_id` (`drama_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`drama_id`),
  ADD KEY `drama_id` (`drama_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `drama_id` (`drama_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dramas`
--
ALTER TABLE `dramas`
  MODIFY `drama_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `slideshow`
--
ALTER TABLE `slideshow`
  MODIFY `slide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dramas`
--
ALTER TABLE `dramas`
  ADD CONSTRAINT `dramas_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `slideshow`
--
ALTER TABLE `slideshow`
  ADD CONSTRAINT `slideshow_ibfk_1` FOREIGN KEY (`drama_id`) REFERENCES `dramas` (`drama_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_favorites_ibfk_2` FOREIGN KEY (`drama_id`) REFERENCES `dramas` (`drama_id`) ON DELETE CASCADE;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`drama_id`) REFERENCES `dramas` (`drama_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
