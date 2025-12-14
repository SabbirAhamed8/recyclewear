-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 12:18 PM
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
-- Database: `recyclewear`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetTotalRevenue` (IN `startDate` DATE, IN `endDate` DATE, OUT `total` DECIMAL(10,2))   BEGIN
    SELECT SUM(p.price) INTO total
    FROM Orders o
    JOIN Recycled_Product p ON o.product_id = p.product_id
    WHERE o.order_date BETWEEN startDate AND endDate
    AND o.status = 'Delivered';
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `email`, `password`, `address`, `phone`, `points`) VALUES
(1, 'Sabbir', 'driver@test.com', '$2y$10$54ueBdrK6gkAmaGzsBROM.Jm7hEhQ/qzdTu6T7SbhqXyIqjULS1HK', 'qwsa', '01010101010', 10),
(4, 'Sabbir', 'a@mail.com', '$2y$10$2bB8z/XD.Cb4tmlXP.6MreN.uOYCRX2fb/3ShUHo1BELxGlHFkvZe', 'dhaka', '12', 0),
(5, 'Sabbir', 'nayeem20038@gmail.com', '$2y$10$YlzifLTvaHDhB56Rot6A6.DiPvLjoVelKZ.12V4x4hFNTrl1yVq3.', 'dhaka', '01010101010', 10);

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`delivery_id`, `order_id`, `driver_id`, `status`, `date`) VALUES
(3, 3, 11, 'Delivered', '2025-12-14'),
(4, 4, 9, 'Delivered', '2025-12-14');

-- --------------------------------------------------------

--
-- Table structure for table `donation_sell_request`
--

CREATE TABLE `donation_sell_request` (
  `request_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `item_type` varchar(50) DEFAULT NULL,
  `condition_status` varchar(50) DEFAULT NULL,
  `request_type` enum('Donate','Sell') NOT NULL,
  `pickup_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `assigned_driver_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_sell_request`
--

INSERT INTO `donation_sell_request` (`request_id`, `customer_id`, `item_type`, `condition_status`, `request_type`, `pickup_date`, `status`, `assigned_driver_id`, `image`) VALUES
(1, 1, 'T-Shirt', 'Damaged', 'Sell', '2025-12-22', 'Picked Up', 1, NULL),
(2, 1, 'T-Shirt', 'Good', 'Donate', '2025-11-11', 'Scheduled', 10, 'uploads/1765568082_Screenshot 2025-12-12 190750.png'),
(3, 5, 'T-Shirt', 'Good', 'Donate', '2025-02-23', 'Picked Up', 9, 'uploads/1765698564_image.png');

--
-- Triggers `donation_sell_request`
--
DELIMITER $$
CREATE TRIGGER `after_request_update` AFTER UPDATE ON `donation_sell_request` FOR EACH ROW BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO System_Logs (action_type, description)
        VALUES ('Status Change', CONCAT('Request #', OLD.request_id, ' changed from ', OLD.status, ' to ', NEW.status));
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `award_points_on_pickup` AFTER UPDATE ON `donation_sell_request` FOR EACH ROW BEGIN
    IF NEW.status = 'Picked Up' AND OLD.status != 'Picked Up' THEN
        UPDATE Customer 
        SET points = points + 10 
        WHERE customer_id = NEW.customer_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `driver_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `availability` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`driver_id`, `name`, `email`, `password`, `contact`, `availability`) VALUES
(9, 'Sabbir', 'sabbir@recyclewear.com', '$2y$10$2Q8iCQ8/ByCp9EdL3NEQQubs5IBmKv05Fg54tAhUvuacwUm0WPqQG', '10101', 1),
(10, 'Samin', 'samin@gmail.com', '$2y$10$e5LQBT2UYe4YB03sS8ele.G4ZwDJiPfdGY/uy326uqfbYMqfa6Id2', '10101', 0),
(11, 'rasel', 'rasel@gmail.com', '$2y$10$SJBXlKbDNimlRmB8lEeVQeRojFE9AR3vB0QegHII57LMX/D83BC7u', '10101', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `full_sales_report`
-- (See below for the actual view)
--
CREATE TABLE `full_sales_report` (
`order_id` int(11)
,`customer_name` varchar(100)
,`email` varchar(100)
,`product_name` varchar(100)
,`price` decimal(10,2)
,`order_date` datetime
,`driver_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Processing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `product_id`, `quantity`, `order_date`, `status`) VALUES
(1, 1, 3, 1, '2025-11-27 01:33:40', 'Delivered'),
(2, 4, 4, 1, '2025-12-13 01:46:31', 'Delivered'),
(3, 4, 2, 1, '2025-12-13 01:48:55', 'Delivered'),
(4, 5, 6, 1, '2025-12-14 13:48:42', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `recycled_product`
--

CREATE TABLE `recycled_product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Available',
  `request_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recycled_product`
--

INSERT INTO `recycled_product` (`product_id`, `product_name`, `description`, `category`, `price`, `status`, `request_id`, `image`) VALUES
(2, 'T-shirt', 'Good', 'Cloth', 20.00, 'Sold', NULL, 'uploads/1764185561_bmw.jpg'),
(3, 'T-shirt', 'Good', 'Cloth', 40.00, 'Sold', NULL, 'uploads/1764185583_bmw.jpg'),
(4, 'T-shirt', 'Good', 'Cloth', 40.00, 'Sold', NULL, 'uploads/1765568526_Screenshot 2025-12-12 190750.png'),
(5, 'T-shirt', 'Good', 'Cloth', 10.00, 'Available', NULL, 'uploads/1765696970_robot.png'),
(6, 'T-shirt', 'Good', 'Cloth', 10.00, 'Sold', NULL, 'uploads/1765697258_robot.png');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `log_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`log_id`, `action_type`, `description`, `log_date`) VALUES
(1, 'Status Change', 'Request #2 changed from Pending to Scheduled', '2025-12-13 01:39:55'),
(2, 'Status Change', 'Request #2 changed from Scheduled to Picked Up', '2025-12-13 01:40:40'),
(3, 'Status Change', 'Request #2 changed from Picked Up to Pending', '2025-12-14 13:31:55'),
(4, 'Status Change', 'Request #3 changed from Pending to Scheduled', '2025-12-14 13:51:12'),
(5, 'Status Change', 'Request #2 changed from Pending to Scheduled', '2025-12-14 13:51:25'),
(6, 'Status Change', 'Request #3 changed from Scheduled to Picked Up', '2025-12-14 13:53:28');

-- --------------------------------------------------------

--
-- Structure for view `full_sales_report`
--
DROP TABLE IF EXISTS `full_sales_report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `full_sales_report`  AS SELECT `o`.`order_id` AS `order_id`, `c`.`name` AS `customer_name`, `c`.`email` AS `email`, `p`.`product_name` AS `product_name`, `p`.`price` AS `price`, `o`.`order_date` AS `order_date`, `d`.`name` AS `driver_name` FROM ((((`orders` `o` join `customer` `c` on(`o`.`customer_id` = `c`.`customer_id`)) join `recycled_product` `p` on(`o`.`product_id` = `p`.`product_id`)) left join `delivery` `del` on(`o`.`order_id` = `del`.`order_id`)) left join `driver` `d` on(`del`.`driver_id` = `d`.`driver_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `donation_sell_request`
--
ALTER TABLE `donation_sell_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`driver_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `recycled_product`
--
ALTER TABLE `recycled_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donation_sell_request`
--
ALTER TABLE `donation_sell_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recycled_product`
--
ALTER TABLE `recycled_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `delivery_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`);

--
-- Constraints for table `donation_sell_request`
--
ALTER TABLE `donation_sell_request`
  ADD CONSTRAINT `donation_sell_request_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `recycled_product` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
