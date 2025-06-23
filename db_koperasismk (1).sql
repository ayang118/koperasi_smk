-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS db_koperasismk;
USE db_koperasismk;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL
);

-- Tabel products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255) NOT NULL
);

-- Tabel cart
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Tabel orders
CREATE TABLE IF NOT EXISTS orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    buyer_name VARCHAR(100) NOT NULL,
    class_name VARCHAR(50) NOT NULL,
    buyer_email VARCHAR(100) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transfer_method VARCHAR(50) DEFAULT NULL,
    payment_proof VARCHAR(255) DEFAULT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel order_items
CREATE TABLE IF NOT EXISTS order_items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    order_id INT(11) NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Insert admin dan user
INSERT INTO users (email, password, role) VALUES
('admin@koperasi.com', '$2y$10$abcdefghijklmnopqrstuv', 'admin'),
('user@koperasi.com', '$2y$10$abcdefghijklmnopqrstuv', 'user');

-- Insert contoh produk
INSERT INTO products (name, description, price, stock, image) VALUES
('Buku Tulis', 'Buku tulis 40 lembar untuk keperluan sekolah.', 5000, 100, 'buku.jpg'),
('Pulpen Biru', 'Pulpen tinta biru, nyaman digunakan.', 3000, 200, 'pulpen.jpg'),
('Penghapus', 'Penghapus karet untuk pensil.', 1500, 150, 'penghapus.jpg'),
('Roti Sobek', 'Roti sobek isi coklat.', 8000, 50, 'roti.jpg'),
('Air Mineral', 'Air mineral botol 600ml.', 4000, 120, 'air.jpg');
