-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 18, 2026 lúc 03:51 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(16, 1, 12, 1),
(17, 1, 5, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Kem Ốc Quế', '', '2026-01-18 07:58:58'),
(2, 'Kem Tươi', '', '2026-01-18 12:35:35'),
(3, 'Kem Que', '', '2026-01-18 12:35:39'),
(4, 'Kem Mochi', '', '2026-01-18 12:35:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT NULL,
  `min_order_value` decimal(10,2) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_percent`, `min_order_value`, `expiration_date`, `created_at`) VALUES
(1, 'hui-kfg-ilo', 10, 200000.00, '2030-12-31', '2026-01-13 01:13:29'),
(2, 'kbn-opl-iut', 15, 300000.00, '2030-12-31', '2026-01-18 13:53:57'),
(3, 'fdw-qtl-oop', 20, 400000.00, '2030-12-31', '2026-01-18 13:54:37'),
(4, 'gvc-xyz-asd', 30, 500000.00, '2030-12-31', '2026-01-18 13:55:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `order_id`, `rating`, `comment`, `created_at`) VALUES
(1, 6, 1, 5, 'kem rất ngon', '2026-01-10 07:53:34'),
(2, 8, 6, 5, 'Kem ở đây cực kỳ mịn và mướt, ăn vào là tan ngay trên đầu lưỡi chứ không bị lạo xạo đá như những chỗ khác. Độ ngọt vừa phải, ăn xong vẫn cảm thấy thanh cổ họng chứ không bị gắt.', '2026-01-18 12:56:05'),
(3, 10, 4, 5, 'Điểm mình thích nhất là hương vị rất thật. Kem dâu thì thơm mùi dâu tươi, sầu riêng thì béo ngậy đúng chất, cảm nhận rõ được nguyên liệu tự nhiên chứ không phải dùng toàn phẩm màu hay hương liệu.', '2026-01-18 12:56:35'),
(4, 9, 7, 4, 'Cực kết vỏ ốc quế ở đây, giòn rụm và thơm mùi bơ. Ăn kem xong mà vẫn còn thèm cái vỏ quế, kết hợp với kem tươi lạnh lạnh đúng là cực phẩm!', '2026-01-18 12:57:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `status` enum('Đang xử lý','Đang giao','Hoàn thành','Đã hủy') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `email`, `address`, `total_price`, `status`, `created_at`) VALUES
(1, 6, 'nguyen vi dat', 'vidat112296@gmail.com', 'hcm', 2024000.00, 'Hoàn thành', '2025-12-24 14:36:24'),
(2, 1, 'admin', 'admin@gmail.com', 'hcm', 29000.00, 'Hoàn thành', '2025-12-30 16:15:43'),
(3, 1, 'admin', 'admin@gmail.com', 'HCM', 110000.00, 'Hoàn thành', '2026-01-18 19:47:18'),
(4, 10, 'Damian', 'Damian@gmail.com', 'Đà Lạt', 104000.00, 'Hoàn thành', '2026-01-18 19:50:52'),
(5, 6, 'NVD', 'vidat112296@gmail.com', 'An Giang', 537000.00, 'Hoàn thành', '2026-01-18 19:51:37'),
(6, 8, 'Desmond', 'desmond@gmail.com', 'Đà Nẵng', 252000.00, 'Hoàn thành', '2026-01-18 19:52:35'),
(7, 9, 'Rose', 'Rose@gmail.com', 'Nha Trang', 166000.00, 'Hoàn thành', '2026-01-18 19:53:46'),
(8, 6, 'NVD', 'vidat112296@gmail.com', 'An Giang', 220000.00, 'Đang xử lý', '2026-01-18 21:24:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `price`, `quantity`) VALUES
(1, 1, 3, 29000.00, 2),
(2, 2, 2, 25000.00, 2),
(3, 3, 13, 22000.00, 5),
(4, 4, 9, 40000.00, 1),
(5, 4, 14, 20000.00, 1),
(6, 4, 13, 22000.00, 2),
(7, 5, 2, 25000.00, 1),
(8, 5, 10, 40000.00, 1),
(9, 5, 5, 32000.00, 1),
(10, 5, 8, 40000.00, 11),
(11, 6, 11, 20000.00, 1),
(12, 6, 8, 40000.00, 5),
(13, 6, 6, 32000.00, 1),
(14, 7, 7, 32000.00, 2),
(15, 7, 13, 22000.00, 2),
(16, 7, 3, 29000.00, 2),
(17, 8, 13, 22000.00, 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` text DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `quantity`, `description`, `category`, `status`, `category_id`) VALUES
(1, 'Kem Ốc Quế Matcha', 29000, 'kemmatcha.jpg', 100, 'Kem Matcha - \"Tinh Túy Trà Xanh\": Dành cho những ai yêu thích sự thanh tao, kem Matcha sở hữu vị chát nhẹ đặc trưng của bột trà xanh Nhật Bản cao cấp. Hậu vị ngọt dịu cùng mùi thơm cỏ cây thư thái giúp bạn giải tỏa căng thẳng ngay lập tức, là lựa chọn \"healthy\" cho một buổi chiều nhẹ nhàng.', 'Kem ốc quế', 1, 1),
(2, 'Kem Ốc Quế Dừa', 25000, 'kemdua.png', 99, 'Dừa Non - \"Thơm Bùi Truyền Thống\": Được chế biến từ cốt dừa nguyên chất, kem dừa mang đến vị béo thanh khiết, không hề ngấy. Điểm nhấn là hương thơm thoang thoảng của dừa tươi, gợi nhớ hương vị quê hương mộc mạc nhưng đầy tinh tế, ăn cùng vỏ quế giòn rụm cực kỳ \"cuốn\".', 'Kem ốc quế', 1, 1),
(3, 'Kem Ốc Quế Dâu', 29000, 'kemdau.jpg', 98, 'Dâu Tây - \"Ngọt Ngào Mọng Nước\": Mang sắc hồng bắt mắt và hương thơm dịu nhẹ, kem dâu tây là sự cân bằng tuyệt vời giữa vị chua thanh và ngọt hậu. Mỗi miếng kem như gói gọn sự tươi mát của những quả dâu mọng, cực kỳ phù hợp để giải nhiệt và khơi dậy cảm hứng.', 'Kem ốc quế', 1, 1),
(4, 'Kem Ốc Quế Sầu Riêng', 39000, 'kem.jpg', 100, 'Sầu Riêng - \"Đệ Nhất Béo Ngậy\": Sự kết hợp hoàn hảo giữa kem tươi mịn màng và hương thơm nồng nàn đặc trưng của sầu riêng chín cây. Vị ngọt đậm đà, béo ngậy tan chậm trong miệng, mang đến trải nghiệm khó cưỡng cho những \"tín đồ\" trung thành của loại trái cây vùng nhiệt đới này.', 'Kem ốc quế', 1, 1),
(5, 'Kem Tươi Vani', 32000, 'kemtuoivani.png', 99, 'Vani: Hương vị cổ điển vượt thời gian với độ béo nhẹ, thơm mùi thơm tinh tế của hạt vani, mang lại cảm giác ngọt ngào và dễ chịu.', NULL, 1, 2),
(6, 'Kem Tươi Socola', 32000, 'kemtuoisocola.png', 99, 'Socola: Đậm đà và lôi cuốn với vị đắng nhẹ tinh tế hòa quyện cùng độ béo ngậy, là lựa chọn số một cho những người yêu thích sự nồng nàn.', NULL, 1, 2),
(7, 'Kem Tươi Dâu', 32000, 'kemtuoidau.png', 98, 'Dâu: Sắc hồng xinh xắn đi kèm vị chua ngọt tự nhiên, tạo nên một sự bùng nổ tươi mát và đầy năng lượng cho vị giác.', NULL, 1, 2),
(8, 'Kem Tươi Vani Mix Matcha', 40000, 'kemtuoitraxanhvavanni.png', 84, 'Sự kết hợp đầy nghệ thuật giữa sắc trắng tinh khôi của Vani và màu xanh dịu mát của Matcha. Hai dòng kem xoắn quyện vào nhau không chỉ tạo nên vẻ ngoài bắt mắt mà còn mang đến tầng hương vị đa dạng: vị béo ngậy, ngọt ngào của Vani làm dịu đi cái chát nhẹ, thanh tao của trà xanh Nhật Bản.', NULL, 1, 2),
(9, 'Kem Mochi Mơ Tây', 40000, 'kem-mochi-mo-tay.png', 99, 'Mơ Tây: Sự kết hợp thú vị giữa lớp vỏ mochi dẻo mềm và nhân kem mơ tây có vị chua đặc trưng, thanh tao và lạ miệng.', NULL, 1, 4),
(10, 'Kem Mochi Việt Quốc', 40000, 'kem-mochi-viet-quat.png', 99, 'Việt Quất: Màu tím đặc trưng từ trái việt quất tươi, vị kem ngọt thanh hòa quyện cùng lớp vỏ dai dai, tạo nên trải nghiệm ẩm thực đầy thú vị.', NULL, 1, 4),
(11, 'Kem Que Mè Đen', 20000, 'kemquemeden.png', 99, 'Mè Đen: Hương vị truyền thống đầy bổ dưỡng với mùi mè rang thơm lừng, bùi bùi, là sự lựa chọn hoàn hảo cho những ai thích vị mộc mạc.', NULL, 1, 3),
(12, 'Kem Que Sầu Riêng', 20000, 'kemquesaurieng.png', 100, 'Sầu Riêng: \"Vua của các loại kem que\" với hương sầu riêng nồng nàn, đậm đà, mang đến cảm giác béo ngậy đặc trưng không thể trộn lẫn.', NULL, 1, 3),
(13, 'Kem Que Ổi Hồng', 22000, 'kemqueoi.png', 81, 'Ổi Hồng: Mang đậm hơi thở nhiệt đới với hương thơm quyến rũ của ổi xá lị, vị ngọt dịu và sảng khoái như vừa thưởng thức một trái ổi tươi.', NULL, 1, 3),
(14, 'Kem Que Matcha', 20000, 'kemquexanh.png', 99, 'Trà Xanh: Phiên bản kem que cô đọng hương vị trà xanh đậm nét, mát lạnh tê lưỡi, giúp xua tan cái nóng ngay tức thì.', NULL, 1, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `title` varchar(10) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` int(1) DEFAULT 0,
  `status` int(11) DEFAULT 1,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `title`, `full_name`, `email`, `password`, `dob`, `created_at`, `role`, `status`, `otp_code`, `otp_expiry`, `deleted_at`) VALUES
(1, NULL, 'admin', 'admin@gmail.com', 'admin', NULL, '2025-12-17 19:04:34', 1, 1, NULL, NULL, NULL),
(2, NULL, 'abc', 'abc@gmail.com', '12345', NULL, '2025-12-22 23:38:35', 0, 1, NULL, NULL, NULL),
(6, NULL, 'NVD', 'vidat112296@gmail.com', 'vidat', NULL, '2025-12-22 23:49:35', 0, 1, NULL, NULL, NULL),
(7, NULL, 'VI DAT', 'dat@gmail.com', '123', NULL, '2026-01-06 00:41:18', 0, 1, NULL, NULL, '2026-01-18 13:32:56'),
(8, NULL, 'Desmond', 'desmond@gmail.com', '$2y$10$yma6oTgVyWD2dJVg9/OJ5ecngSlS0LCe.8iCOevV2zJvdHp0FT4Dm', NULL, '2026-01-13 02:26:46', 0, 1, NULL, NULL, NULL),
(9, NULL, 'Rose', 'Rose@gmail.com', '$2y$10$YtxLSxb2LqfgHrQOIvVIG.jX/Mx3p4sC/Gzf3iSNzC2PnK2U8zIWe', NULL, '2026-01-13 02:30:44', 0, 1, NULL, NULL, NULL),
(10, NULL, 'Damian', 'Damian@gmail.com', '$2y$10$1W8kwmiGwaEdRG3zCbFd9uEeNCuRV6Wpvx43as9/6Y4kSTAq/DjPO', NULL, '2026-01-18 12:49:58', 0, 0, NULL, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
