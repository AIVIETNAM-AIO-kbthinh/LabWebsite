-- =========================================================
--  ISI LAB v3 - MySQL Database Schema
--  Charset: utf8mb4 (full Unicode + emoji support)
--  Engine : InnoDB (transactional, FK support)
-- =========================================================

CREATE DATABASE IF NOT EXISTS `isilab`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `isilab`;

-- ---------------------------------------------------------
-- 1. ADMIN USERS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
    `id`          INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `username`    VARCHAR(64)    NOT NULL,
    `email`       VARCHAR(150)   NOT NULL,
    `password`    VARCHAR(255)   NOT NULL,            -- bcrypt hash
    `full_name`   VARCHAR(150)   NULL,
    `role`        ENUM('super','editor') NOT NULL DEFAULT 'editor',
    `last_login`  DATETIME       NULL,
    `created_at`  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_admins_username` (`username`),
    UNIQUE KEY `uk_admins_email` (`email`)
) ENGINE=InnoDB;

-- Default account:  admin / isilab@2024  (CHANGE AFTER LOGIN!)
INSERT INTO `admins` (`username`,`email`,`password`,`full_name`,`role`) VALUES
('admin','admin@isilab.local',
 '$2y$10$t5X0zo4F1PZb/bBSFs.2fuafUdmUE//zKW4WC9kfFyeoqiSCZVdoC',
 'Lab Administrator','super');

-- ---------------------------------------------------------
-- 2. SITE SETTINGS (key/value store)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `key_name`    VARCHAR(80)    NOT NULL,
    `value`       TEXT           NULL,
    `updated_at`  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`key_name`)
) ENGINE=InnoDB;

INSERT INTO `settings` (`key_name`,`value`) VALUES
('site_name',        'ISI Lab'),
('site_tagline_en',  'Intelligent System and IoT Laboratory'),
('site_tagline_vi',  'Phòng thí nghiệm Hệ thống Thông minh và IoT'),
('contact_address_en','VNU International School, 144 Xuan Thuy, Cau Giay, Hanoi'),
('contact_address_vi','Khoa Quốc tế - ĐHQGHN, 144 Xuân Thủy, Cầu Giấy, Hà Nội'),
('contact_email',    'isilab@vnuis.edu.vn'),
('contact_phone',    '+84 24 3754 7461'),
('contact_hours_en', 'Mon - Fri: 8:00 - 17:00'),
('contact_hours_vi', 'Thứ 2 - Thứ 6: 8:00 - 17:00'),
('social_facebook',  '#'),
('social_youtube',   '#'),
('social_linkedin',  '#'),
('social_github',    '#'),
('stat_members',     '50'),
('stat_projects',    '30'),
('stat_publications','100'),
('stat_years',       '15');

-- ---------------------------------------------------------
-- 3. RESEARCH AREAS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `research_areas`;
CREATE TABLE `research_areas` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `slug`       VARCHAR(120)  NOT NULL,
    `icon`       VARCHAR(60)   NOT NULL DEFAULT 'fa-flask',
    `title_en`   VARCHAR(200)  NOT NULL,
    `title_vi`   VARCHAR(200)  NULL,
    `desc_en`    TEXT          NULL,
    `desc_vi`    TEXT          NULL,
    `sort_order` INT           NOT NULL DEFAULT 0,
    `is_active`  TINYINT(1)    NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_research_slug` (`slug`)
) ENGINE=InnoDB;

INSERT INTO `research_areas` (`slug`,`icon`,`title_en`,`title_vi`,`desc_en`,`desc_vi`,`sort_order`) VALUES
('ai','fa-brain','Artificial Intelligence','Trí tuệ nhân tạo',
 'Deep learning, computer vision, natural language processing, and intelligent decision-making systems for real-world applications.',
 'Học sâu, thị giác máy tính, xử lý ngôn ngữ tự nhiên và hệ thống ra quyết định thông minh cho các ứng dụng thực tế.',1),
('robotics','fa-robot','Robotics & Automation','Robot & Tự động hóa',
 'Designing intelligent robots, autonomous control systems, and industrial automation solutions for manufacturing and healthcare.',
 'Thiết kế robot thông minh, hệ thống điều khiển tự hành và giải pháp tự động hóa công nghiệp cho sản xuất và y tế.',2),
('iot','fa-network-wired','Internet of Things','Internet vạn vật',
 'Smart sensors, connected devices, edge computing, and IoT platforms for smart cities, agriculture, and industry 4.0.',
 'Cảm biến thông minh, thiết bị kết nối, edge computing và nền tảng IoT cho thành phố thông minh, nông nghiệp và công nghiệp 4.0.',3),
('uav','fa-helicopter','UAV & Drone Systems','Hệ thống UAV & Drone',
 'Flight control systems, drone navigation, swarm intelligence, and aerial surveillance for various civilian applications.',
 'Hệ thống điều khiển bay, dẫn đường drone, trí tuệ bầy đàn và giám sát trên không cho các ứng dụng dân sự.',4),
('embedded','fa-microchip','Embedded Systems','Hệ thống nhúng',
 'Microcontroller programming, FPGA design, real-time operating systems, and hardware-software co-design.',
 'Lập trình vi điều khiển, thiết kế FPGA, hệ điều hành thời gian thực và đồng thiết kế phần cứng-phần mềm.',5),
('cybersecurity','fa-shield-halved','Cybersecurity','An ninh mạng',
 'Network security, secure IoT protocols, cryptography, and defense systems against cyber threats.',
 'An ninh mạng, giao thức IoT an toàn, mật mã học và hệ thống phòng thủ chống lại các mối đe dọa mạng.',6);

-- ---------------------------------------------------------
-- 4. MEMBERS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `slug`       VARCHAR(160)  NOT NULL,
    `name`       VARCHAR(160)  NOT NULL,
    `role`       ENUM('leader','lecturer','student','alumni') NOT NULL DEFAULT 'student',
    `title_en`   VARCHAR(200)  NULL,
    `title_vi`   VARCHAR(200)  NULL,
    `desc_en`    TEXT          NULL,
    `desc_vi`    TEXT          NULL,
    `image`      VARCHAR(500)  NULL,
    `email`      VARCHAR(150)  NULL,
    `scholar`    VARCHAR(500)  NULL,
    `linkedin`   VARCHAR(500)  NULL,
    `github`     VARCHAR(500)  NULL,
    `sort_order` INT           NOT NULL DEFAULT 0,
    `is_active`  TINYINT(1)    NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_member_slug` (`slug`),
    KEY `idx_member_role` (`role`)
) ENGINE=InnoDB;

INSERT INTO `members`
(`slug`,`name`,`role`,`title_en`,`title_vi`,`desc_en`,`desc_vi`,`image`,`email`,`scholar`,`linkedin`,`sort_order`) VALUES
('khoa-nguyen-dang','Dr. Khoa Nguyen Dang','leader','Lab Leader','Trưởng phòng Lab',
 'Ph.D. in Aerospace Engineering from University of Ulsan, Korea (2018). Research interests: modeling systems, control systems, UAV, robotics.',
 'Tiến sĩ Kỹ thuật Hàng không Vũ trụ từ Đại học Ulsan, Hàn Quốc (2018). Hướng nghiên cứu: hệ thống mô hình, hệ thống điều khiển, UAV, robot.',
 'https://overwhelming-blue-nefdwamhj6.edgeone.app/khoand-96x96.jpg',
 'khoand@vnuis.edu.vn',
 'https://scholar.google.com/citations?user=iV3j9P8AAAAJ','#',1),
('tung-bui-thanh','Tung Bui Thanh','lecturer','Senior Lecturer','Giảng viên chính',
 'Master in Industrial Automation from HUST (2008). 15+ years experience in automation and control systems.',
 'Thạc sĩ Tự động hóa Công nghiệp từ ĐH Bách khoa Hà Nội (2008). Hơn 15 năm kinh nghiệm trong lĩnh vực tự động hóa và điều khiển.',
 'https://assistant-jade-bz3ubniaye.edgeone.app/tung-96x96.jpg',
 'tungbt@vnuis.edu.vn','#','#',2),
('pham-hai-yen','Pham Hai Yen','lecturer','Lecturer','Giảng viên',
 'Master in Industrial Automation from HUST (2008). Research in IoT and embedded systems.',
 'Thạc sĩ Tự động hóa Công nghiệp từ ĐH Bách khoa Hà Nội (2008). Nghiên cứu IoT và hệ thống nhúng.',
 'https://aware-lavender-gvagcavmqy.edgeone.app/yen-96x96.jpg',
 'yenph@vnuis.edu.vn','#','#',3),
('pham-tuan-minh','Pham Tuan Minh','student','Student - AAI','Sinh viên - AAI',
 'Student at International School studying Automation and Informatics. Passionate about AI in manufacturing and healthcare.',
 'Sinh viên Khoa Quốc tế ngành Tự động hóa và Tin học. Đam mê AI trong sản xuất và y tế.',
 'https://i.pravatar.cc/400?img=15','minhphamnm@gmail.com',NULL,NULL,4),
('do-thuy-trang','Do Thuy Trang','student','Student - AAI','Sinh viên - AAI',
 'Student in Automation and Informatics at VNU International School.',
 'Sinh viên Tự động hóa và Tin học tại Khoa Quốc tế - ĐHQGHN.',
 'https://i.pravatar.cc/400?img=49','trang.dt@gmail.com',NULL,NULL,5),
('nguyen-khanh-ly','Nguyen Khanh Ly','student','AAI Student','Sinh viên AAI',
 'AAI Student passionate about AI and IoT applications.',
 'Sinh viên AAI đam mê các ứng dụng AI và IoT.',
 'https://i.pravatar.cc/400?img=44',NULL,NULL,NULL,6),
('nguyen-thi-anh-thu','Nguyen Thi Anh Thu','student','Student - AAI','Sinh viên - AAI',
 'Programming, circuit design, and simulation experience. Interests: IoT, embedded systems, automation.',
 'Có kinh nghiệm lập trình, thiết kế mạch và mô phỏng. Quan tâm: IoT, hệ thống nhúng, tự động hóa.',
 'https://i.pravatar.cc/400?img=45','ntathu233@gmail.com',NULL,NULL,7),
('nguyen-thanh-danh','Nguyen Thanh Danh','student','Student - AAI','Sinh viên - AAI',
 'Strong passion for IoT and AI. Aiming to apply technologies to real-world problems.',
 'Đam mê IoT và AI. Mục tiêu ứng dụng công nghệ vào các vấn đề thực tế.',
 'https://i.pravatar.cc/400?img=11','danhthanhnguyen14@gmail.com',NULL,NULL,8),
('nguyen-duy-hoang','Nguyen Duy Hoang','student','Cybersecurity Student','Sinh viên An ninh mạng',
 '2nd-year Swinburne University student in Cybersecurity. Web development & network security enthusiast.',
 'Sinh viên năm 2 Đại học Swinburne ngành An ninh mạng. Đam mê web development và bảo mật mạng.',
 'https://i.pravatar.cc/400?img=14','hoangnguyenduy356@gmail.com',NULL,NULL,9);

-- ---------------------------------------------------------
-- 5. PROJECTS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `slug`       VARCHAR(180)  NOT NULL,
    `title_en`   VARCHAR(220)  NOT NULL,
    `title_vi`   VARCHAR(220)  NULL,
    `desc_en`    TEXT          NULL,
    `desc_vi`    TEXT          NULL,
    `image`      VARCHAR(500)  NULL,
    `tag`        VARCHAR(60)   NULL,
    `year`       SMALLINT      NULL,
    `status_en`  VARCHAR(80)   NULL,
    `status_vi`  VARCHAR(80)   NULL,
    `link`       VARCHAR(500)  NULL,
    `is_featured` TINYINT(1)   NOT NULL DEFAULT 0,
    `is_active`  TINYINT(1)    NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_project_slug` (`slug`),
    KEY `idx_project_tag` (`tag`),
    KEY `idx_project_year` (`year`)
) ENGINE=InnoDB;

INSERT INTO `projects`
(`slug`,`title_en`,`title_vi`,`desc_en`,`desc_vi`,`image`,`tag`,`year`,`status_en`,`status_vi`,`link`,`is_featured`) VALUES
('smart-uav-surveillance','Smart UAV Surveillance System','Hệ thống giám sát UAV thông minh',
 'Autonomous drone with computer vision for real-time monitoring and threat detection in urban environments.',
 'Drone tự hành với thị giác máy tính để giám sát thời gian thực và phát hiện mối đe dọa trong môi trường đô thị.',
 'https://images.unsplash.com/photo-1473968512647-3e447244af8f?w=800&h=600&fit=crop',
 'UAV',2024,'In Progress','Đang thực hiện','#',1),
('iot-smart-agriculture','IoT-based Smart Agriculture','Nông nghiệp thông minh dựa trên IoT',
 'Sensor network for monitoring soil moisture, temperature, and automated irrigation control.',
 'Mạng cảm biến giám sát độ ẩm đất, nhiệt độ và điều khiển tưới tiêu tự động.',
 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&h=600&fit=crop',
 'IoT',2024,'Completed','Hoàn thành','#',1),
('ai-medical-diagnosis','AI Medical Diagnosis Assistant','Trợ lý chẩn đoán y tế AI',
 'Deep learning model for assisting doctors in early detection of medical conditions from imaging data.',
 'Mô hình học sâu hỗ trợ bác sĩ phát hiện sớm các tình trạng y tế từ dữ liệu hình ảnh.',
 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=800&h=600&fit=crop',
 'AI',2024,'In Progress','Đang thực hiện','#',1),
('autonomous-robot-nav','Autonomous Robot Navigation','Điều hướng robot tự hành',
 'SLAM-based navigation system for indoor mobile robots with obstacle avoidance.',
 'Hệ thống điều hướng dựa trên SLAM cho robot di động trong nhà với khả năng tránh chướng ngại vật.',
 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&h=600&fit=crop',
 'Robotics',2023,'Completed','Hoàn thành','#',0),
('smart-home-automation','Smart Home Automation','Nhà thông minh tự động',
 'IoT-based home control system with voice recognition and energy optimization.',
 'Hệ thống điều khiển nhà dựa trên IoT với nhận diện giọng nói và tối ưu năng lượng.',
 'https://images.unsplash.com/photo-1558002038-1055907df827?w=800&h=600&fit=crop',
 'IoT',2023,'Completed','Hoàn thành','#',0),
('dl-cybersecurity','Deep Learning for Cybersecurity','Học sâu cho an ninh mạng',
 'Anomaly detection in network traffic using neural networks for threat prevention.',
 'Phát hiện bất thường trong lưu lượng mạng bằng mạng neural để phòng chống mối đe dọa.',
 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&h=600&fit=crop',
 'AI',2024,'In Progress','Đang thực hiện','#',0);

-- ---------------------------------------------------------
-- 6. PUBLICATIONS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `publications`;
CREATE TABLE `publications` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `year`       SMALLINT      NOT NULL,
    `title`      VARCHAR(400)  NOT NULL,
    `authors`    VARCHAR(400)  NULL,
    `venue`      VARCHAR(300)  NULL,
    `link`       VARCHAR(500)  NULL,
    `bibtex`     TEXT          NULL,
    `is_active`  TINYINT(1)    NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_pub_year` (`year`)
) ENGINE=InnoDB;

INSERT INTO `publications` (`year`,`title`,`authors`,`venue`,`link`) VALUES
(2024,'Adaptive Control of Quadrotor UAV with Disturbance Rejection',
 'K. N. Dang, T. B. Thanh, P. H. Yen',
 'IEEE Transactions on Aerospace and Electronic Systems','#'),
(2024,'Deep Reinforcement Learning for Autonomous Drone Navigation',
 'K. N. Dang, et al.',
 'International Conference on Robotics and Automation (ICRA)','#'),
(2023,'IoT-based Smart Greenhouse Monitoring System',
 'P. H. Yen, T. B. Thanh',
 'Journal of Intelligent & Fuzzy Systems','#'),
(2023,'Machine Learning Approach to Network Intrusion Detection',
 'N. D. Hoang, K. N. Dang',
 'Computers & Security Journal','#'),
(2022,'Modeling and Control of Multi-rotor UAV Systems',
 'K. N. Dang',
 'Aerospace Science and Technology','#');

-- ---------------------------------------------------------
-- 7. NEWS
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `slug`       VARCHAR(220)  NOT NULL,
    `title_en`   VARCHAR(300)  NOT NULL,
    `title_vi`   VARCHAR(300)  NULL,
    `excerpt_en` TEXT          NULL,
    `excerpt_vi` TEXT          NULL,
    `content_en` LONGTEXT      NULL,
    `content_vi` LONGTEXT      NULL,
    `image`      VARCHAR(500)  NULL,
    `date`       DATE          NOT NULL,
    `is_active`  TINYINT(1)    NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_news_slug` (`slug`),
    KEY `idx_news_date` (`date`)
) ENGINE=InnoDB;

INSERT INTO `news` (`slug`,`title_en`,`title_vi`,`excerpt_en`,`excerpt_vi`,`image`,`date`) VALUES
('innovation-award-2024',
 'ISI Lab Wins National Innovation Award 2024',
 'ISI Lab giành giải thưởng Sáng tạo Quốc gia 2024',
 'Our team has been honored with the National Innovation Award for outstanding contributions to AI and IoT research.',
 'Đội ngũ của chúng tôi đã được vinh danh với Giải thưởng Sáng tạo Quốc gia cho những đóng góp xuất sắc cho nghiên cứu AI và IoT.',
 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=800&h=600&fit=crop','2024-12-15'),
('new-lab-equipment',
 'New Research Lab Equipment Arrives',
 'Thiết bị nghiên cứu mới về phòng Lab',
 'State-of-the-art robotics and IoT testing equipment now available for student projects and research.',
 'Thiết bị robot và kiểm thử IoT hiện đại đã sẵn sàng cho các dự án sinh viên và nghiên cứu.',
 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=600&fit=crop','2024-11-20'),
('annual-tech-showcase-2024',
 'Annual Tech Showcase 2024',
 'Triển lãm công nghệ thường niên 2024',
 'Join us for the annual showcase featuring student projects in AI, IoT, robotics, and UAV systems.',
 'Tham gia triển lãm thường niên với các dự án sinh viên về AI, IoT, robot và UAV.',
 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop','2024-10-10');

-- ---------------------------------------------------------
-- 8. GALLERY
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `image`      VARCHAR(500)  NOT NULL,
    `caption`    VARCHAR(255)  NULL,
    `sort_order` INT           NOT NULL DEFAULT 0,
    `is_active`  TINYINT(1)    NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `gallery` (`image`,`caption`,`sort_order`) VALUES
('https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&h=600&fit=crop','Lab Equipment',1),
('https://images.unsplash.com/photo-1581090464777-f3220bbe1b8b?w=600&h=600&fit=crop','Research Session',2),
('https://images.unsplash.com/photo-1573164574572-cb89e39749b4?w=600&h=600&fit=crop','Team Meeting',3),
('https://images.unsplash.com/photo-1567446537708-ac4aa75c9c28?w=600&h=600&fit=crop','Drone Testing',4),
('https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=600&h=600&fit=crop','Robotics Workshop',5),
('https://images.unsplash.com/photo-1581092921461-eab62e97a780?w=600&h=600&fit=crop','IoT Project',6),
('https://images.unsplash.com/photo-1551434678-e076c223a692?w=600&h=600&fit=crop','Coding Sprint',7),
('https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=600&h=600&fit=crop','Conference',8);

-- ---------------------------------------------------------
-- 9. CONTACT MESSAGES  (form submissions)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(150)  NOT NULL,
    `email`      VARCHAR(150)  NOT NULL,
    `subject`    VARCHAR(220)  NOT NULL,
    `message`    TEXT          NOT NULL,
    `ip`         VARCHAR(45)   NULL,
    `user_agent` VARCHAR(255)  NULL,
    `is_read`    TINYINT(1)    NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_msg_isread` (`is_read`),
    KEY `idx_msg_created` (`created_at`)
) ENGINE=InnoDB;
