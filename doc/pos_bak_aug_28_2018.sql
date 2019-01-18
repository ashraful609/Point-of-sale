-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2018 at 07:40 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch_product`
--

CREATE TABLE `branch_product` (
  `branch_store_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `branch_store`
--

CREATE TABLE `branch_store` (
  `branch_store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(13) NOT NULL,
  `branch_manager_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `description`, `created`) VALUES
(1, 'Electronics', 'sdas asfsfsdg asg asdg d ', '2018-07-16 21:35:44'),
(2, 'Moneybag', 'Mens wallet and moneybag', '2018-07-19 00:00:18'),
(3, 'Restricted', 'This category should be used by the developer.', '2018-08-24 23:17:58');

-- --------------------------------------------------------

--
-- Table structure for table `company_info`
--

CREATE TABLE `company_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(80) NOT NULL,
  `zipcode` varchar(60) NOT NULL,
  `phone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_info`
--

INSERT INTO `company_info` (`id`, `name`, `address`, `city`, `country`, `zipcode`, `phone`) VALUES
(1, 'Nobabee Style', 'Khadiza palace&#44; Square Masterbari&#44;\r\nValuka', 'Mymensingh', 'Bangladesh', '2200', '01729019223');

-- --------------------------------------------------------

--
-- Table structure for table `cost`
--

CREATE TABLE `cost` (
  `cost_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `total_amount` float NOT NULL,
  `issue_date` datetime NOT NULL,
  `payment_status` varchar(150) NOT NULL,
  `due_amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coustomer`
--

CREATE TABLE `coustomer` (
  `coustomer_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `email` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coustomer`
--

INSERT INTO `coustomer` (`coustomer_id`, `name`, `phone`, `email`) VALUES
(1, 'Customer', 'None', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `discount_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discount_product`
--

CREATE TABLE `discount_product` (
  `product_id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `product_id` int(11) NOT NULL,
  `supply_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `discount` float NOT NULL,
  `coustomer_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `total_amount` varchar(45) NOT NULL,
  `payment_type` varchar(60) NOT NULL,
  `user_used_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `discount`, `coustomer_id`, `status`, `created`, `total_amount`, `payment_type`, `user_used_id`) VALUES
(1, 0, 1, 1, '2018-08-28 23:37:55', '32000', 'cash', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total` float NOT NULL,
  `remark` text NOT NULL,
  `discount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`order_id`, `product_id`, `quantity`, `sub_total`, `remark`, `discount`) VALUES
(1, 5, 2, 16000, 'None', 0);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `permission_id` int(11) NOT NULL,
  `permission` varchar(60) NOT NULL,
  `permission_description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `unit_price` float NOT NULL,
  `sale_price` float NOT NULL,
  `active` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `edited` datetime NOT NULL,
  `category_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `title`, `description`, `unit_price`, `sale_price`, `active`, `created`, `edited`, `category_id`, `image_url`) VALUES
(1, 'Gucci', 'Export quality moneybag', 700, 995, 1, '2018-07-19 00:01:15', '2018-07-20 12:15:28', 2, 'images/default_product_image.jpg'),
(3, 'Gucci', 'Gucci smart watch', 10000, 12500, 0, '2018-07-20 12:21:48', '2018-07-20 12:21:48', 1, 'images/default_product_image.jpg'),
(4, 'Gucci', 'sfsdf', 121, 1212, 1, '2018-07-20 12:22:07', '2018-07-20 12:22:07', 1, 'images/default_product_image.jpg'),
(5, 'Samsung Monitor', 'Samsung 22inch LED monitor', 5000, 8000, 1, '2018-07-20 23:51:40', '2018-07-20 23:51:40', 1, 'images/default_product_image.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_variant`
--

CREATE TABLE `product_variant` (
  `product_variant_id` int(11) NOT NULL,
  `size` varchar(45) NOT NULL,
  `color` varchar(45) NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_variant`
--

INSERT INTO `product_variant` (`product_variant_id`, `size`, `color`, `quantity`, `product_id`) VALUES
(1, 'None', 'Black', 10, 1),
(3, 'None', 'Black', 10, 3),
(4, 'None', 'None', 10, 4),
(5, '22', 'None', 9991, 5);

-- --------------------------------------------------------

--
-- Table structure for table `returned_order`
--

CREATE TABLE `returned_order` (
  `returned_order_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `returned_date` datetime NOT NULL,
  `total_returned_amount` float NOT NULL,
  `total_returned_charge` float NOT NULL,
  `remark` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `returned_order`
--

INSERT INTO `returned_order` (`returned_order_id`, `order_id`, `returned_date`, `total_returned_amount`, `total_returned_charge`, `remark`) VALUES
(1, 1, '2018-08-28 23:38:12', 0, 0, 'dsdfsd sdf ');

-- --------------------------------------------------------

--
-- Table structure for table `returned_product`
--

CREATE TABLE `returned_product` (
  `product_id` int(11) NOT NULL,
  `returned_quantity` int(11) NOT NULL,
  `returned_amount` float NOT NULL,
  `returned_charge` float NOT NULL,
  `returned_order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `returned_product`
--

INSERT INTO `returned_product` (`product_id`, `returned_quantity`, `returned_amount`, `returned_charge`, `returned_order_id`) VALUES
(5, 2, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sell_log`
--

CREATE TABLE `sell_log` (
  `id` int(11) NOT NULL,
  `user_first_name` varchar(255) NOT NULL,
  `user_last_name` varchar(255) NOT NULL,
  `user_phone` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `user_group` varchar(60) NOT NULL,
  `discount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sell_log`
--

INSERT INTO `sell_log` (`id`, `user_first_name`, `user_last_name`, `user_phone`, `date`, `user_group`, `discount`) VALUES
(1, 'Asif', 'Hassan', '0165586545', '2018-08-28 23:37:55', '1', 0),
(2, 'Asif', 'Hassan', '0165586545', '2018-08-28 23:38:12', '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sell_log_detail`
--

CREATE TABLE `sell_log_detail` (
  `id` int(11) NOT NULL,
  `sell_log_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `selling_price` float NOT NULL,
  `buying_price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_category` varchar(160) NOT NULL,
  `discount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sell_log_detail`
--

INSERT INTO `sell_log_detail` (`id`, `sell_log_id`, `product_name`, `selling_price`, `buying_price`, `quantity`, `product_category`, `discount`) VALUES
(1, 1, 'Samsung Monitor', 8000, 5000, 4, '1', 0),
(2, 2, 'returned product', -8000, -5000, 2, '3', 0);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supply`
--

CREATE TABLE `supply` (
  `supply_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `payment_status` varchar(160) NOT NULL,
  `supply_status` varchar(160) NOT NULL,
  `supply_order_time` datetime NOT NULL,
  `deliver_time` datetime NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `cost_cost_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `user_status` int(3) NOT NULL,
  `last_login` datetime NOT NULL,
  `user_detail_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `user_status`, `last_login`, `user_detail_id`) VALUES
(1, 'asif', '5f4dcc3b5aa765d61d8327deb882cf99', 1, '2018-08-28 23:10:11', 1),
(2, 'tuhin', 'e10adc3949ba59abbe56e057f20f883e', 1, '2018-07-19 22:58:29', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user-detail_user_group`
--

CREATE TABLE `user-detail_user_group` (
  `user_detail_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_detail`
--

CREATE TABLE `user_detail` (
  `user_detail_id` int(11) NOT NULL,
  `verification_code` varchar(60) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `email` varchar(80) NOT NULL,
  `registered` datetime NOT NULL,
  `phone` varchar(45) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_detail`
--

INSERT INTO `user_detail` (`user_detail_id`, `verification_code`, `first_name`, `last_name`, `email`, `registered`, `phone`, `address`) VALUES
(1, 'None', 'Asif', 'Hassan', 'asif@mail.com', '2018-07-18 00:29:20', '0165586545', 'Dhaka, Bangladesh'),
(2, 'None', 'Tuhin', 'Khan', 't@g.c', '2018-07-18 00:56:08', '0173707050', 'France');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `user_group_id` int(11) NOT NULL,
  `user_group` varchar(80) NOT NULL,
  `group_description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`user_group_id`, `user_group`, `group_description`) VALUES
(1, 'admin', 'All privileges are allowed');

-- --------------------------------------------------------

--
-- Table structure for table `user_group_permission`
--

CREATE TABLE `user_group_permission` (
  `user_group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch_product`
--
ALTER TABLE `branch_product`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `branch_store_id` (`branch_store_id`);

--
-- Indexes for table `branch_store`
--
ALTER TABLE `branch_store`
  ADD PRIMARY KEY (`branch_store_id`),
  ADD KEY `branch_manager_user_id` (`branch_manager_user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cost`
--
ALTER TABLE `cost`
  ADD PRIMARY KEY (`cost_id`);

--
-- Indexes for table `coustomer`
--
ALTER TABLE `coustomer`
  ADD PRIMARY KEY (`coustomer_id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `discount_product`
--
ALTER TABLE `discount_product`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `discount_id` (`discount_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `supply_id` (`supply_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `coustomer_id` (`coustomer_id`),
  ADD KEY `user_user_id` (`user_used_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD PRIMARY KEY (`product_variant_id`),
  ADD KEY `fk_product_id` (`product_id`) USING BTREE;

--
-- Indexes for table `returned_order`
--
ALTER TABLE `returned_order`
  ADD PRIMARY KEY (`returned_order_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `returned_product`
--
ALTER TABLE `returned_product`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `returned_order_id` (`returned_order_id`);

--
-- Indexes for table `sell_log`
--
ALTER TABLE `sell_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sell_log_detail`
--
ALTER TABLE `sell_log_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sell_log_id` (`sell_log_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `supply`
--
ALTER TABLE `supply`
  ADD PRIMARY KEY (`supply_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `cost_cost_id` (`cost_cost_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_detail_id` (`user_detail_id`);

--
-- Indexes for table `user-detail_user_group`
--
ALTER TABLE `user-detail_user_group`
  ADD KEY `user_detail_id` (`user_detail_id`),
  ADD KEY `user_group_id` (`user_group_id`);

--
-- Indexes for table `user_detail`
--
ALTER TABLE `user_detail`
  ADD PRIMARY KEY (`user_detail_id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`user_group_id`);

--
-- Indexes for table `user_group_permission`
--
ALTER TABLE `user_group_permission`
  ADD KEY `user_group_id` (`user_group_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch_store`
--
ALTER TABLE `branch_store`
  MODIFY `branch_store_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_info`
--
ALTER TABLE `company_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cost`
--
ALTER TABLE `cost`
  MODIFY `cost_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coustomer`
--
ALTER TABLE `coustomer`
  MODIFY `coustomer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_variant`
--
ALTER TABLE `product_variant`
  MODIFY `product_variant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `returned_order`
--
ALTER TABLE `returned_order`
  MODIFY `returned_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `returned_product`
--
ALTER TABLE `returned_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sell_log`
--
ALTER TABLE `sell_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sell_log_detail`
--
ALTER TABLE `sell_log_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supply`
--
ALTER TABLE `supply`
  MODIFY `supply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_detail`
--
ALTER TABLE `user_detail`
  MODIFY `user_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `user_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branch_product`
--
ALTER TABLE `branch_product`
  ADD CONSTRAINT `fk_branch_store_id_bs` FOREIGN KEY (`branch_store_id`) REFERENCES `branch_store` (`branch_store_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_id_produ` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `branch_store`
--
ALTER TABLE `branch_store`
  ADD CONSTRAINT `fk_user_id_ur` FOREIGN KEY (`branch_manager_user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `discount_product`
--
ALTER TABLE `discount_product`
  ADD CONSTRAINT `fk_discount_id_d` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`discount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_id_po` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_product_id_prod` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_supply_id_s` FOREIGN KEY (`supply_id`) REFERENCES `supply` (`supply_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_coustomer_id_c` FOREIGN KEY (`coustomer_id`) REFERENCES `coustomer` (`coustomer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_user_id_u` FOREIGN KEY (`user_used_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `fk_order_id_o` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_id_p` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `category_id_cat` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD CONSTRAINT `product_var_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `returned_order`
--
ALTER TABLE `returned_order`
  ADD CONSTRAINT `fk_order_id_or` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `returned_product`
--
ALTER TABLE `returned_product`
  ADD CONSTRAINT `fk_product_id_pro` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_returned_order_id` FOREIGN KEY (`returned_order_id`) REFERENCES `returned_order` (`returned_order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sell_log_detail`
--
ALTER TABLE `sell_log_detail`
  ADD CONSTRAINT `id_sl` FOREIGN KEY (`sell_log_id`) REFERENCES `sell_log` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `supply`
--
ALTER TABLE `supply`
  ADD CONSTRAINT `fk_cost_cost_id_c` FOREIGN KEY (`cost_cost_id`) REFERENCES `cost` (`cost_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_supplier_id_su` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_detail_id` FOREIGN KEY (`user_detail_id`) REFERENCES `user_detail` (`user_detail_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user-detail_user_group`
--
ALTER TABLE `user-detail_user_group`
  ADD CONSTRAINT `fk_user_detail_id_dtgrp` FOREIGN KEY (`user_detail_id`) REFERENCES `user_detail` (`user_detail_id`),
  ADD CONSTRAINT `fk_user_group_id` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`user_group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_group_permission`
--
ALTER TABLE `user_group_permission`
  ADD CONSTRAINT `fk_permission_id_p` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`permission_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_group_id_ug` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`user_group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
