DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('admin','user','client') NOT NULL,
  `job_title` varchar(100) NOT NULL DEFAULT 'Employee',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `email`, `password`, `created_at`, `role`, `job_title`) VALUES
(1, 'admin', 'Admin', 'User', 'admin@example.com', '$2y$10$EPRA/J5JWqcDXAS/fQuHVO6jmhKW/Ehzfs6QPDFC0Jg9MCxdO2EmS', '2025-02-11 16:13:05', 'admin', 'Supervisor'),
(2, 'john', 'John', 'Doe', 'johndoe@example.com', '$2y$10$RzLgTYWkdf17jqPd7CixS.GNxKkEra.QurrZHXkGyJNh1rOpweVKa', '2025-02-11 21:06:23', 'user', 'Employee'),
(3, 'jane', 'jane', 'Doe', 'janedoe@example.com', '$2y$10$zGjgSqikhUa1GhgpWtiVX.uIjJLdfQmktL9fAtAZxrVffZ0F0AZXe', '2025-02-11 21:06:23', 'user', 'Employee'),
(4, 'james', 'James', 'Doe', 'jamesdoe@example.com', '$2y$10$RzLgTYWkdf17jqPd7CixS.GNxKkEra.QurrZHXkGyJNh1rOpweVKa', '2025-02-11 21:06:23', 'client', 'Client');
COMMIT;

DROP TABLE IF EXISTS `available_permits`;
CREATE TABLE IF NOT EXISTS `available_permits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permit_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `available_permits`
--

INSERT INTO `available_permits` (`id`, `permit_name`, `price`, `description`) VALUES
(1, 'Fishing Permit', 20.00, 'A permit that allows fishing in designated public areas for one year.'),
(2, 'Hiking Permit', 10.00, 'A permit that grants access to hike in restricted trails for a year.'),
(3, 'Camping Permit', 35.00, 'A permit that grants access to camp in designated areas for one year.');
COMMIT;

DROP TABLE IF EXISTS `supervisors`;
CREATE TABLE IF NOT EXISTS `supervisors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `supervisor_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`,`supervisor_id`),
  KEY `supervisor_id` (`supervisor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supervisors`
--

INSERT INTO `supervisors` (`id`, `employee_id`, `supervisor_id`) VALUES
(1, 3, 1),
(2, 3, 2);
COMMIT;

DROP TABLE IF EXISTS `employee_courses`;
CREATE TABLE IF NOT EXISTS `employee_courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `course_id` int NOT NULL,
  `progress` enum('Not Started','In Progress','Completed') DEFAULT 'Not Started',
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `course_id` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_courses`
--

INSERT INTO `employee_courses` (`id`, `employee_id`, `course_id`, `progress`) VALUES
(1, 2, 1, 'Completed');
COMMIT;

DROP TABLE IF EXISTS `permits`;
CREATE TABLE IF NOT EXISTS `permits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `permit_id` int NOT NULL,
  `assigned_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permits`
--

INSERT INTO `permits` (`id`, `user_id`, `permit_id`, `assigned_date`, `expiration_date`) VALUES
(1, 1, 2, '2025-02-13', '2026-02-13');
COMMIT;

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) NOT NULL,
  `training_video_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `training_video_link`) VALUES
(1, 'Test Course', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&pp=ygUXbmV2ZXIgZ29ubmEgZ2l2ZSB5b3UgdXA%3D');
COMMIT;
