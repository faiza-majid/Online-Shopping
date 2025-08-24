-- Create database and tables for the Online Shopping mini project
CREATE DATABASE IF NOT EXISTS onlineshoppings;
USE onlineshoppings;

-- Customers
CREATE TABLE IF NOT EXISTS customers (
  customer_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE,
  phone VARCHAR(20),
  address VARCHAR(255)
);

-- Products
CREATE TABLE IF NOT EXISTS products (
  product_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock INT NOT NULL DEFAULT 0
);

-- Orders (header)
CREATE TABLE IF NOT EXISTS orders (
  order_id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

-- Order Details (line items)
CREATE TABLE IF NOT EXISTS order_details (
  order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE RESTRICT
);

-- Payments
CREATE TABLE IF NOT EXISTS payments (
  payment_id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(50) NOT NULL,
  payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);

-- Some starter data
INSERT INTO customers (name, email, phone, address) VALUES
('Ali Khan', 'ali@example.com', '0300-0000000', 'Lahore, PK'),
('Sara Ahmed', 'sara@example.com', '0311-1111111', 'Karachi, PK')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO products (name, description, price, stock) VALUES
('T-Shirt', 'Cotton T-Shirt', 1200.00, 50),
('Jeans', 'Blue Denim Jeans', 3500.00, 30),
('Shoes', 'Running Shoes', 5500.00, 20);
