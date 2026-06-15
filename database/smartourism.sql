CREATE DATABASE IF NOT EXISTS sql_smartourism_mediapatner_com CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sql_smartourism_mediapatner_com;

DROP TABLE IF EXISTS tb_destinasi;
DROP TABLE IF EXISTS tb_users;

CREATE TABLE tb_destinasi (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama_destinasi VARCHAR(200) NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  daya_tarik TINYINT UNSIGNED NOT NULL,
  aksesibilitas TINYINT UNSIGNED NOT NULL,
  fasilitas TINYINT UNSIGNED NOT NULL,
  sarana TINYINT UNSIGNED NOT NULL,
  ulasan DECIMAL(3,1) NOT NULL,
  jumlah_pengunjung INT UNSIGNED NOT NULL,
  rating DECIMAL(3,1) NOT NULL,
  latitude DECIMAL(10,7) NOT NULL,
  longitude DECIMAL(10,7) NOT NULL,
  status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tb_users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL,
  role ENUM('admin', 'petugas') NOT NULL DEFAULT 'admin',
  status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tb_destinasi
  (id, nama_destinasi, kategori, daya_tarik, aksesibilitas, fasilitas, sarana, ulasan, jumlah_pengunjung, rating, latitude, longitude, status)
VALUES
  (1, 'Candi Borobudur', 'Budaya', 9, 9, 8, 9, 4.8, 850000, 4.8, -7.6079000, 110.2038000, 'aktif'),
  (2, 'Candi Mendut', 'Budaya', 8, 8, 7, 7, 4.5, 180000, 4.5, -7.5992000, 110.2278000, 'aktif'),
  (3, 'Candi Pawon', 'Budaya', 7, 7, 6, 6, 4.2, 95000, 4.2, -7.6035000, 110.2196000, 'aktif'),
  (4, 'Ketep Pass', 'Alam', 9, 7, 8, 7, 4.6, 320000, 4.6, -7.5258000, 110.3453000, 'aktif'),
  (5, 'Punthuk Setumbu', 'Alam', 9, 6, 7, 6, 4.7, 210000, 4.7, -7.6226000, 110.1875000, 'aktif'),
  (6, 'Air Terjun Kedung Kayang', 'Alam', 8, 5, 5, 5, 4.3, 75000, 4.3, -7.5589000, 110.4012000, 'aktif'),
  (7, 'Desa Wisata Candirejo', 'Desa Wisata', 7, 7, 6, 6, 4.4, 45000, 4.4, -7.6011000, 110.2156000, 'aktif'),
  (8, 'Desa Wisata Ngargogondo', 'Desa Wisata', 6, 6, 5, 5, 4.1, 32000, 4.1, -7.5943000, 110.2489000, 'aktif'),
  (9, 'Masjid Agung Magelang', 'Religi', 7, 9, 8, 8, 4.5, 280000, 4.5, -7.4712000, 110.2177000, 'aktif'),
  (10, 'Makam Kyai Raden Santri', 'Religi', 6, 7, 5, 5, 4.0, 120000, 4.0, -7.5218000, 110.2134000, 'aktif'),
  (11, 'Taman Kyai Langgeng', 'Taman', 8, 9, 8, 8, 4.4, 350000, 4.4, -7.4706000, 110.2123000, 'aktif'),
  (12, 'Kebun Teh Ngluwar', 'Alam', 7, 6, 5, 5, 4.2, 55000, 4.2, -7.4983000, 110.3512000, 'aktif'),
  (13, 'Gunung Merbabu (basecamp)', 'Alam', 9, 5, 5, 4, 4.6, 42000, 4.6, -7.5031000, 110.4198000, 'aktif'),
  (14, 'Sunrise Puthuk Mongkrong', 'Alam', 8, 5, 4, 4, 4.5, 28000, 4.5, -7.6134000, 110.1923000, 'aktif'),
  (15, 'Museum Diponegoro', 'Budaya', 6, 8, 7, 7, 4.1, 85000, 4.1, -7.4695000, 110.2195000, 'aktif');

INSERT INTO tb_users (id, username, password_hash, nama_lengkap, role, status)
VALUES (1, 'admin', '$2y$10$5leqFdZWrctcXMNgDQYXoe/k3jQgu8y2jx4qzkAXMQJFYazxcsnUi', 'Administrator', 'admin', 'aktif');