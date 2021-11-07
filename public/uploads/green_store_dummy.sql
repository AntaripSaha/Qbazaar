
INSERT INTO `categories` (`id`, `slug`, `icon`, `image`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'food', '<i class=\"icofont-food-basket\"></i>', '162211069760af71e9a0a6c.png', NULL, 1, '2021-05-24 09:50:15', '2021-07-15 12:25:06'),
(5, 'fruits-vegetables', '', 'default-cat.png', 4, 1, '2021-05-24 10:26:30', '2021-05-27 09:50:18'),
(6, 'beauty-health', '<i class=\"icofont-muscle-weight\"></i>', '162211077360af72357aa52.png', NULL, 1, '2021-05-25 14:38:12', '2021-07-15 12:24:21'),
(7, 'baby-care', '<i class=\"icofont-baby-food\"></i>', '162211081060af725a7788c.png', NULL, 1, '2021-05-25 14:57:59', '2021-07-15 12:23:28'),
(8, 'hygiene', '<i class=\"icofont-laboratory\"></i>', '162211084560af727d1fde5.png', NULL, 1, '2021-05-26 14:17:04', '2021-07-15 12:22:58'),
(9, 'home-cleaning', '<i class=\"icofont-water-bottle\"></i>', '162211088360af72a36252e.png', NULL, 1, '2021-05-26 14:29:41', '2021-07-15 12:21:23'),
(11, 'medicine', '<i class=\"icofont-medicine\"></i>', '162211092260af72cab9a9e.png', NULL, 1, '2021-05-26 16:04:29', '2021-07-15 12:13:52'),
(12, 'breakfast', '', 'default-cat.png', 4, 1, '2021-05-27 09:05:24', '2021-05-27 09:50:53'),
(13, 'health-care', '', 'default-cat.png', 6, 1, '2021-05-27 09:05:54', '2021-05-27 09:05:54'),
(14, 'office-products', '<i class=\"icofont-ui-office\"></i>', '162211111160af73870b8bd.png', NULL, 1, '2021-05-27 10:25:11', '2021-07-15 12:13:25'),
(15, 'kitchen-appliances', '<i class\="icofont-spoon-and-fork\"></i>', '16279067076107e29307a93.png', NULL, 1, '2021-05-27 10:27:09', '2021-07-15 12:13:06');



INSERT INTO `category_translations` (`id`, `category_id`, `locale`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 4, 'English', 'Food', 'All Types Of Food', '2021-05-24 10:23:15', '2021-05-24 10:23:15'),
(2, 5, 'English', 'Fruits & Vegetables', 'Fruits & Vegetables Items', '2021-05-24 10:26:30', '2021-05-24 16:21:02'),
(3, 6, 'English', 'Beauty & Health', '', '2021-05-25 14:38:12', '2021-05-25 14:38:12'),
(4, 7, 'English', 'Baby Care', '', '2021-05-25 14:57:59', '2021-05-25 14:57:59'),
(5, 8, 'English', 'Hygiene', '', '2021-05-26 14:17:04', '2021-05-26 14:17:04'),
(6, 9, 'English', 'Home & Cleaning', '', '2021-05-26 14:29:41', '2021-05-26 14:29:41'),
(7, 11, 'English', 'Medicine', '', '2021-05-26 16:04:29', '2021-05-26 16:04:29'),
(8, 12, 'English', 'Breakfast', '', '2021-05-27 09:05:24', '2021-05-27 09:05:24'),
(9, 13, 'English', 'Health Care', '', '2021-05-27 09:05:54', '2021-05-27 09:05:54'),
(10, 14, 'English', 'Office Products', '', '2021-05-27 10:25:11', '2021-05-27 10:25:11'),
(11, 15, 'English', 'Kitchen Appliances', '', '2021-05-27 10:27:09', '2021-05-27 10:27:09');
