-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 13, 2026 lúc 10:38 AM
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
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percent` int(11) NOT NULL CHECK (`discount_percent` > 0 and `discount_percent` <= 100),
  `min_order_value` decimal(10,2) DEFAULT 0.00,
  `expiration_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_percent`, `min_order_value`, `expiration_date`, `created_at`) VALUES
(1, 'GIAM10', 10, 200000.00, '2030-12-31', '2026-01-13 08:13:29'),
(2, 'GIAM5', 5, 50000.00, '2030-12-31', '2026-01-13 08:13:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `order_id`, `rating`, `comment`, `created_at`) VALUES
(1, 6, 1, 5, 'kem rất ngon nha mọi người', '2026-01-10 14:53:34'),
(2, 2, 6, 5, 'Chất lượng tuyệt vời, vị dâu tây chua chua ngọt ngọt ăn rất cuốn. Đặc biệt là kem không quá ngọt gắt, phù hợp với người đang ăn kiêng như mình. Đóng gói rất cẩn thận, 5 sao!', '2026-01-11 08:37:54'),
(3, 8, 9, 5, 'Kem ổi hồng cực phẩm: Thơm đậm vị ổi xá lị, ngọt dịu không gắt, hậu vị thanh mát rất sảng khoái. Đáng thử! 9/10.', '2026-01-13 09:29:46'),
(4, 9, 10, 4, 'Vỏ ngoài mỏng dẻo, nhân kem bên trong béo ngậy, ngọt thanh. Team mê đồ dẻo nhất định không được bỏ qua món này nha! ✨', '2026-01-13 09:33:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `status` enum('Đang xử lý','Đang giao','Hoàn thành','Đã hủy') DEFAULT 'Đang xử lý',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `email`, `address`, `total_price`, `status`, `created_at`) VALUES
(1, 6, 'nguyen vi dat', 'vidat112296@gmail.com', 'hcm', 2024000.00, 'Hoàn thành', '2025-12-24 14:36:24'),
(2, 1, 'admin', 'admin@gmail.com', 'hcm', 29000.00, 'Hoàn thành', '2025-12-30 16:15:43'),
(3, 1, 'admin', 'vidat112296@gmail.com', 'hcm', 27000.00, 'Hoàn thành', '2026-01-06 14:35:50'),
(4, 1, 'admin', 'admin@gmail.com', 'hanoi', 99800.00, 'Hoàn thành', '2026-01-06 14:54:18'),
(5, 6, 'NVD', 'vidat112296@gmail.com', 'hcm\\r\\n', 108000.00, 'Hoàn thành', '2026-01-06 16:09:48'),
(6, 2, 'abc', 'abc@gmail.com', 'đà nẵng ', 222000.00, 'Hoàn thành', '2026-01-11 15:31:00'),
(7, 6, 'NVD', 'vidat112296@gmail.com', 'Nha Trang', 220000.00, 'Hoàn thành', '2026-01-13 13:39:58'),
(8, 1, 'admin', 'admin@gmail.com', 'Hà Giang', 22000.00, 'Hoàn thành', '2026-01-13 14:27:03'),
(9, 8, 'Desmond', 'desmond@gmail.com', 'Thành Phố Hồ Chí Minh', 110000.00, 'Hoàn thành', '2026-01-13 16:27:59'),
(10, 9, 'Rose', 'Rose@gmail.com', 'Đà Lạt', 50000.00, 'Hoàn thành', '2026-01-13 16:31:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `price`, `quantity`) VALUES
(1, 5, 3, 29000.00, 2),
(2, 5, 2, 25000.00, 2),
(3, 6, 3, 29000.00, 3),
(4, 6, 7, 27000.00, 5),
(5, 7, 12, 22000.00, 10),
(6, 8, 14, 22000.00, 1),
(7, 9, 12, 22000.00, 5),
(8, 10, 10, 50000.00, 1);

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
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `quantity`, `description`, `category`, `status`) VALUES
(1, 'Kem Matcha', 29000, 'kemmatcha.jpg', 6, 'Kem matcha mang sắc xanh dịu mát, thơm hương trà xanh thanh khiết, vị đắng nhẹ tinh tế hòa cùng lớp kem béo mịn tan chậm trên đầu lưỡi. Một lựa chọn nhẹ nhàng, sang trọng dành cho những tâm hồn yêu sự tinh giản và thư thái.', 'Kem ốc quế ', 1),
(2, 'Kem Dừa', 25000, 'kemdua.png', 3, 'Kem dừa trắng mịn, béo ngậy vừa đủ, lan tỏa hương dừa tươi tự nhiên đầy cuốn hút. Mỗi miếng kem là cảm giác mát lạnh, ngọt ngào như một chuyến du lịch nhỏ đến miền nhiệt đới yên bình.', 'Kem ốc quế ', 1),
(3, 'Kem Dâu', 29000, 'kemdau.jpg', 3, 'Vị dâu chua ngọt dễ thương, tươi tắn như những quả dâu chín mọng, kết hợp cùng kem mềm mịn và sắc hồng ngọt ngào. Mang đến cảm giác trẻ trung, đáng yêu, khiến ai cũng muốn mỉm cười khi thưởng thức.', 'Kem ốc quế', 1),
(4, 'Kem Sầu Riêng', 39000, 'kem.jpg', 10, 'Kem sầu riêng đậm vị, béo thơm nồng nàn, chuẩn hương sầu riêng chín vàng hấp dẫn. Lớp kem mềm mịn tan nhanh, để lại dư vị béo ngậy khó quên – một “món cưng” dành riêng cho những tín đồ sầu riêng chính hiệu.', 'Kem ốc quế', 1),
(5, 'Kem Tươi ', 29900, 'kemtuoivani.png', 50, 'Kem Vani – Vị Ngon Thuần Khiết\r\nVani không chỉ là một hương vị, mà là một chuẩn mực của sự tinh tế. Được chế biến từ những hạt Vani nguyên bản hòa quyện cùng dòng sữa tươi thượng hạng, mỗi muỗng kem mang đến cảm giác mướt mịn, tan chảy với vị ngọt thanh tao và hương thơm dịu nhẹ, nồng nàn. Không cầu kỳ, không phô trương, Vani chính là lựa chọn hoàn hảo để vỗ về vị giác và đánh thức những cảm xúc nguyên bản nhất.', 'kemtuoi', 1),
(6, 'Kem Tươi Dâu', 30000, 'kemtuoidau.png', 12, 'Kem Tươi Dâu (Strawberry): Mang sắc hồng ngọt ngào và hương thơm trái cây tự nhiên. Vị chua chua ngọt ngọt đầy kích thích giúp cân bằng vị giác, không gây cảm giác ngấy, mang lại trải nghiệm tươi mới và trẻ trung.', 'kemtuoi', 1),
(7, 'Kem Tươi Matcha', 27000, 'kemtuoimatcha.png', 25, 'Kem Tươi Matcha (Trà Xanh): Sự kết hợp hoàn hảo giữa vị chát nhẹ đặc trưng của bột Matcha Nhật Bản và sự ngọt dịu của kem sữa. Màu xanh mướt mắt cùng hương thơm thanh khiết giúp tinh thần sảng khoái, giải nhiệt tức thì trong những ngày nắng nóng.', 'kemtuoi', 1),
(8, 'Kem Tươi Socola', 40000, 'kemtuoisocola.png', 10, 'Kem Tươi Socola (Chocolate): Mang hương vị đậm đà, quyến rũ từ những dòng cacao nguyên chất. Vị đắng nhẹ tinh tế hòa quyện cùng độ béo ngậy của sữa tươi, tạo nên một cảm giác mịn màng, tan chảy ngay đầu lưỡi. Đây là lựa chọn \"bất bại\" cho mọi lứa tuổi.', 'kemtuoi', 1),
(10, 'Kem Mochi Mơ Tây', 50000, 'kem-mochi-mo-tay.png', 99, 'Mochi Mơ Tây: Lựa chọn hoàn hảo cho những ai thích vị chua dịu tinh tế. Hương thơm đặc trưng của mơ tây hòa quyện cùng vị béo ngậy của kem sữa, mang lại trải nghiệm mới lạ, kích thích vị giác và không gây cảm giác ngấy.\r\n', 'Mochi', 1),
(11, 'Kem Mochi Việt Quốc', 50000, 'kem-mochi-viet-quat.png', 100, 'Mochi Việt Quất: Một sự bùng nổ của sắc tím mộng mơ. Lớp vỏ dẻo dai bao bọc lấy nhân kem Việt Quất ngọt thanh, chua nhẹ. Từng miếng kem tan chảy mang theo hương thơm tự nhiên của quả mọng, tạo cảm giác thanh mát và cực kỳ sảng khoái.', 'mochi', 1),
(12, 'Kem Que Ổi', 22000, 'kemqueoi.png', 85, 'Kem Que Ổi Hồng: Mang hương vị nhiệt đới đặc trưng, thơm nồng nàn và ngọt thanh. Cảm giác mát lạnh tan nhanh, để lại hậu vị tươi mới như đang thưởng thức một trái ổi chín cây chính hiệu.', 'kemque', 1),
(13, 'Kem Que Mè Đen', 22000, 'kemquemeden.png', 100, 'Kem Que Mè Đen: Sự kết hợp hoàn hảo giữa vị bùi béo của mè đen rang thơm và kem sữa mịn màng. Không chỉ ngon miệng mà còn mang cảm giác thanh nhẹ, bổ dưỡng, rất được lòng người yêu thích vị truyền thống.', 'kemque ', 1),
(14, 'Kem Que Sầu Riêng', 22000, 'kemquesaurieng.png', 99, 'Kem Que Sầu Riêng: \"Vua của các loại trái cây\" nay có phiên bản kem que béo ngậy. Hương thơm đặc trưng, đậm đặc và quyện cùng vị sữa, dành riêng cho những tín đồ say đắm sự ngọt ngào, nồng nàn.', 'kemque', 1),
(15, 'Kem Que Matcha', 22000, 'kemquexanh.png', 100, 'Kem Que Matcha: Đậm chất Nhật Bản với bột trà xanh nguyên chất. Vị hơi đắng nhẹ tinh tế ở đầu lưỡi, quyện cùng độ béo của sữa, tạo nên sự cân bằng hoàn hảo, giúp giải nhiệt và thư giãn tức thì.', 'kemque', 1);

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
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `title`, `full_name`, `email`, `password`, `dob`, `created_at`, `role`, `status`, `otp_code`, `otp_expiry`) VALUES
(1, NULL, 'admin', 'admin@gmail.com', 'admin', NULL, '2025-12-18 02:04:34', 1, 1, NULL, NULL),
(2, NULL, 'abc', 'abc@gmail.com', '12345', NULL, '2025-12-23 06:38:35', 0, 1, NULL, NULL),
(6, NULL, 'NVD', 'vidat112296@gmail.com', 'vidat', NULL, '2025-12-23 06:49:35', 0, 1, NULL, NULL),
(7, NULL, 'VI DAT', 'dat@gmail.com', '123', NULL, '2026-01-06 07:41:18', 0, 1, NULL, NULL),
(8, NULL, 'Desmond', 'desmond@gmail.com', '$2y$10$yma6oTgVyWD2dJVg9/OJ5ecngSlS0LCe.8iCOevV2zJvdHp0FT4Dm', NULL, '2026-01-13 09:26:46', 0, 1, NULL, NULL),
(9, NULL, 'Rose', 'Rose@gmail.com', '$2y$10$YtxLSxb2LqfgHrQOIvVIG.jX/Mx3p4sC/Gzf3iSNzC2PnK2U8zIWe', NULL, '2026-01-13 09:30:44', 0, 1, NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
