# CREATE DATABASE pinkcapy;
# USE pinkcapy;
# DROP DATABASE pinkcapy;

-- =========================
-- 1. USER & PROFILE
-- =========================

CREATE TABLE users
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    email    VARCHAR(100) NOT NULL UNIQUE,
    phone    VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    salt     VARCHAR(255),
    is_admin BOOLEAN DEFAULT FALSE,
    status   TINYINT UNSIGNED DEFAULT 1 CHECK (status BETWEEN 1 AND 4)
);

CREATE TABLE user_profiles
(
    user_id       INT PRIMARY KEY,
    first_name    VARCHAR(150),
    middle_name   VARCHAR(150),
    last_name     VARCHAR(150),
    avatar        VARCHAR(255),
    profile       TEXT,
    registered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login    DATETIME
#     FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE roles
(
    id     INT AUTO_INCREMENT PRIMARY KEY,
    name   VARCHAR(150) NOT NULL,
    `desc` TEXT
);

CREATE TABLE user_roles
(
    user_id INT,
    role_id INT,
    PRIMARY KEY (user_id, role_id)
#     FOREIGN KEY (user_id) REFERENCES users (id),
#     FOREIGN KEY (role_id) REFERENCES roles (id)
);

-- =========================
-- 2. BLOG & CATEGORY
-- =========================

CREATE TABLE blog_posts
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(150) NOT NULL,
    meta_title VARCHAR(150),
    slug       VARCHAR(150),
    thumb      VARCHAR(255),
    summary    TEXT,
    content    TEXT,
    conclusion VARCHAR(255),
    status     TINYINT UNSIGNED DEFAULT 1 CHECK (status BETWEEN 1 AND 3)
);

CREATE TABLE categories
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    parent_id  INT NULL,
    level      INT DEFAULT 1,
    title      VARCHAR(150),
    meta_title VARCHAR(150),
    slug       VARCHAR(150),
    `desc`     TEXT
#     FOREIGN KEY (parent_id) REFERENCES categories (id)
);

-- =========================
-- 3. PRODUCT & META & TAG
-- =========================

CREATE TABLE products
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(150),
    meta_title   VARCHAR(150),
    slug         VARCHAR(150),
    thumb        VARCHAR(255),
    `desc`       TEXT,
    summary      TEXT,
    type         VARCHAR(150),
    sku          VARCHAR(150),
    price        DECIMAL(12, 2),
    quantity     INT,
    published_at DATETIME,
    status       TINYINT UNSIGNED DEFAULT 1 CHECK (status BETWEEN 1 AND 5),
    discount     DECIMAL(12, 2),
    starts_at    DATETIME,
    ends_at      DATETIME
);

CREATE TABLE product_metas
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    `key`      VARCHAR(150),
    content    TEXT
#     FOREIGN KEY (product_id) REFERENCES products (id)
);

CREATE TABLE product_categories
(
    product_id  INT,
    category_id INT,
    PRIMARY KEY (product_id, category_id)
#     FOREIGN KEY (product_id) REFERENCES products (id),
#     FOREIGN KEY (category_id) REFERENCES categories (id)
);

CREATE TABLE tags
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(150),
    meta_title VARCHAR(150),
    slug       VARCHAR(150),
    `desc`     TEXT
);

CREATE TABLE product_tags
(
    product_id INT,
    tag_id     INT,
    PRIMARY KEY (product_id, tag_id)
#     FOREIGN KEY (product_id) REFERENCES products (id),
#     FOREIGN KEY (tag_id) REFERENCES tags (id)
);

-- =========================
-- 4. CART & ORDER
-- =========================

CREATE TABLE carts
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT,
    first_name  VARCHAR(150),
    middle_name VARCHAR(150),
    last_name   VARCHAR(150),
    phone       VARCHAR(20),
    email       VARCHAR(100),
    line1       VARCHAR(255),
    line2       VARCHAR(255),
    city        VARCHAR(255),
    province    VARCHAR(255),
    country     VARCHAR(255),
    status      TINYINT UNSIGNED DEFAULT 1 CHECK (status BETWEEN 1 AND 3),
    note        VARCHAR(255)
#     FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE cart_items
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    cart_id    INT,
    product_id INT,
    sku        VARCHAR(150),
    is_active  BOOLEAN DEFAULT TRUE,
    price      DECIMAL(12, 2),
    quantity   INT,
    discount   DECIMAL(12, 2),
    note       VARCHAR(255)
#     FOREIGN KEY (cart_id) REFERENCES carts (id),
#     FOREIGN KEY (product_id) REFERENCES products (id)
);

CREATE TABLE orders
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT,
    subtotal       DECIMAL(12, 2),
    tax            DECIMAL(12, 2),
    shipping       DECIMAL(12, 2),
    total          DECIMAL(12, 2),
    discount_total DECIMAL(12, 2),
    promo          VARCHAR(255),
    discount       DECIMAL(12, 2),
    grand_total    DECIMAL(12, 2),
    first_name     VARCHAR(150),
    middle_name    VARCHAR(150),
    last_name      VARCHAR(150),
    phone          VARCHAR(20),
    email          VARCHAR(100),
    line1          VARCHAR(255),
    line2          VARCHAR(255),
    city           VARCHAR(255),
    province       VARCHAR(255),
    country        VARCHAR(255),
    orders_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    status         TINYINT UNSIGNED DEFAULT 1 CHECK (status BETWEEN 1 AND 8),
    note           VARCHAR(255)
#     FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE order_items
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT,
    product_id INT,
    sku        VARCHAR(150),
    is_active  BOOLEAN DEFAULT TRUE,
    price      DECIMAL(12, 2),
    quantity   INT,
    discount   DECIMAL(12, 2),
    note       VARCHAR(255)
#     FOREIGN KEY (order_id) REFERENCES orders (id),
#     FOREIGN KEY (product_id) REFERENCES products (id)
);

-- =========================
-- 5. TRANSACTION & PARAMETER
-- =========================

CREATE TABLE transactions
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    amount   DECIMAL(12, 2),
    content  TEXT,
    code     VARCHAR(255),
    type     TINYINT UNSIGNED DEFAULT 1 CHECK (type BETWEEN 1 AND 2),
    mode     VARCHAR(150),
    status   TINYINT UNSIGNED DEFAULT 1 CHECK (status BETWEEN 1 AND 6)
#     FOREIGN KEY (order_id) REFERENCES orders (id)
);

CREATE TABLE configs
(
    `key`   VARCHAR(100) PRIMARY KEY,
    `value` VARCHAR(255),
    type  tinyint,
    `desc`         VARCHAR(255),
    updated_at   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



# Seed data

INSERT INTO products (title, meta_title, slug, thumb, `desc`, summary, type, sku, price, quantity, published_at, status, discount, starts_at, ends_at) VALUES
       ('Wireless In-Ear Headphones', 'Tai nghe in-ear không dây – Âm thanh rõ nét', 'wireless-in-ear-headphones', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Tai nghe in-ear Bluetooth 5.2, chống ồn ANC, pin lên đến 8 giờ.', 'Trải nghiệm âm thanh thoải mái mọi lúc.', 'Audio', 'HEA-001', 79.99, 250, '2025-10-01 09:00:00', 1, 10.00, '2025-10-05 00:00:00', '2025-11-05 23:59:59'),
       ('Gaming Wireless Mouse', 'Chuột không dây gaming – Hỗ trợ DPI cao', 'gaming-wireless-mouse', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Chuột không dây dành cho game, DPI lên tới 16000, RGB, thiết kế công thái học.', 'Giữ chắc tay, phản hồi nhanh.', 'Computers', 'MOU-002', 49.50, 180, '2025-09-20 14:30:00', 1, 5.50, '2025-09-25 00:00:00', '2025-10-25 23:59:59'),
       ('Mechanical Keyboard RGB', 'Bàn phím cơ RGB – Switch đỏ', 'mechanical-keyboard-rgb', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Bàn phím cơ switch đỏ, layout full-size, LED RGB, keycap PBT.', 'Sáng đẹp, phản hồi nhanh, cho cả văn phòng & gaming.', 'Computers', 'KEY-003', 99.99, 120, '2025-09-10 10:15:00', 1, 0.00, NULL, NULL),
       ('Smart Fitness Watch 42mm', 'Đồng hồ thông minh 42mm – Theo dõi sức khỏe', 'smart-fitness-watch-42mm', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Đồng hồ thông minh màn hình 1.4″, đo nhịp tim, SPO2, GPS tích hợp.', 'Một thiết bị đeo đa năng cho lối sống năng động.', 'Wearables', 'WAT-004', 159.00, 90, '2025-08-15 08:00:00', 1, 15.00, '2025-08-20 00:00:00', '2025-09-20 23:59:59'),
       ('Bluetooth Speaker Portable', 'Loa bluetooth di động – Âm thanh mạnh mẽ', 'bluetooth-speaker-portable', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Loa bluetooth chống nước IP67, pin 24 giờ, kết nối đa thiết bị.', 'Âm nhạc mọi nơi, mọi lúc.', 'Audio', 'SPK-005', 69.95, 200, '2025-07-25 13:45:00', 1, 7.95, '2025-07-30 00:00:00', '2025-08-30 23:59:59'),
       ('USB-C Hub 7 in 1', 'Hub USB-C 7-trong-1 – Mở rộng kết nối', 'usb-c-hub-7-in-1', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Hub USB-C đa cổng: HDMI 4K, USB-A, SD/TF, Ethernet, PD 100W.', 'Giải pháp mở rộng cho laptop và MacBook.', 'Computers', 'HUB-006', 39.99, 300, '2025-06-10 11:20:00', 1, 3.99, '2025-06-15 00:00:00', '2025-07-15 23:59:59'),
       ('Wireless Ergonomic Mouse', 'Chuột không dây công thái học – Giảm mỏi tay', 'wireless-ergonomic-mouse', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Chuột không dây thiết kế công thái học, hỗ trợ đa thiết bị, pin tới 12 tháng.', 'Giải pháp tiện dụng cho văn phòng.', 'Computers', 'MOU-007', 34.95, 260, '2025-05-30 09:00:00', 1, 0.00, NULL, NULL),
       ('Noise-Cancelling Over-Ear Headphones', 'Tai nghe over-ear chống ồn – Âm thanh cao cấp', 'noise-cancelling-over-ear-headphones', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Tai nghe over-ear với ANC, bass mạnh, dây tháo rời, pin tới 30 giờ.', 'Chìm đắm trong âm nhạc.', 'Audio', 'HEA-008', 199.00, 50, '2025-05-10 15:30:00', 1, 20.00, '2025-05-12 00:00:00', '2025-06-12 23:59:59'),
       ('Mechanical Gaming Keyboard Tenkeyless', 'Bàn phím cơ gaming tenkeyless – RGB', 'mechanical-gaming-keyboard-tenkeyless', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Bàn phím cơ layout TKL (80 %), switch xanh, LED RGB, dây USB-C tháo rời.', 'Cho game thủ và coder chuyên nghiệp.', 'Computers', 'KEY-009', 129.00, 75, '2025-04-20 10:00:00', 1, 12.00, '2025-04-22 00:00:00', '2025-05-22 23:59:59'),
       ('Smartwatch Premium Edition', 'Đồng hồ thông minh Premium – Dây thép', 'smartwatch-premium-edition', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Smartwatch premium phiên bản dây thép, màn hình AMOLED, GPS, LTE riêng.', 'Đẳng cấp & tiện ích.', 'Wearables', 'WAT-010', 349.00, 40, '2025-03-01 08:00:00', 1, 30.00, '2025-03-05 00:00:00', '2025-04-05 23:59:59'),
       ('USB Flash Drive 128GB', 'USB Flash Drive 128GB – Tốc độ cao', 'usb-flash-drive-128gb', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'USB-A 3.2, read up to 300 MB/s, bảo hành 5 năm.', 'Lưu trữ dữ liệu nhanh chóng.', 'Computers', 'USB-011', 24.99, 500, '2025-02-20 12:00:00', 1, 2.49, '2025-02-25 00:00:00', '2025-03-25 23:59:59'),
       ('Wireless Charging Pad', 'Đế sạc không dây – Qi Fast-Charge', 'wireless-charging-pad', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Đế sạc không dây chuẩn Qi, max 15W, thiết kế mỏng, đèn LED báo.', 'Tiện lợi cho smartphone & earbuds.', 'Mobile Accessories', 'CHA-012', 29.95, 320, '2025-01-15 18:30:00', 1, 0.00, NULL, NULL),
       ('Bluetooth Earbuds Sport', 'Tai nghe true-wireless sport – Chống mồ hôi', 'bluetooth-earbuds-sport', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Earbuds TWS thiết kế sports, chống mồ hôi IP55, bass mạnh, case sạc nhanh.', 'Âm nhạc & tập luyện.', 'Audio', 'EAR-013', 59.99, 210, '2024-12-10 07:45:00', 1, 5.00, '2024-12-15 00:00:00', '2025-01-15 23:59:59'),
       ('Portable SSD 1TB', 'Ổ cứng di động SSD 1TB – USB-C', 'portable-ssd-1tb', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'SSD di động dung lượng 1TB, giao tiếp USB-C 10Gbps, chống sốc.', 'Lưu trữ tốc độ cao cho laptop & travel.', 'Computers', 'SSD-014', 119.00, 140, '2024-11-05 11:11:00', 1, 10.00, '2024-11-10 00:00:00', '2024-12-10 23:59:59'),
       ('Webcam Full HD 1080p', 'Webcam Full HD 1080p – Micro tích hợp', 'webcam-full-hd-1080p', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Webcam 1080p, micro dual-mike, ánh sáng tự động điều chỉnh, kèm tripod mini.', 'Cho hội họp trực tuyến & streaming.', 'Computers', 'CAM-015', 39.99, 310, '2024-10-01 09:00:00', 1, 4.00, '2024-10-05 00:00:00', '2024-11-05 23:59:59'),
       ('Noise-Isolating In-Ear Earphones', 'Tai nghe in-ear cách ly tiếng ồn – dây', 'noise-isolating-in-ear-earphones', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Tai nghe có dây in-ear, nút cao su khử tiếng ồn, jack 3.5mm, hộp đựng kèm.', 'Giải pháp đơn giản nhưng hiệu quả.', 'Audio', 'HEA-016', 19.95, 600, '2024-09-15 16:00:00', 1, 0.00, NULL, NULL),
       ('Smart Band Fitness Tracker', 'Vòng tay thông minh – Theo dõi bước & giấc ngủ', 'smart-band-fitness-tracker', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Vòng tay thông minh đo bước, giấc ngủ, nhịp tim, thông báo smartphone.', 'Giúp bạn khỏe hơn mỗi ngày.', 'Wearables', 'BAN-017', 39.50, 350, '2024-08-20 07:00:00', 1, 3.50, '2024-08-25 00:00:00', '2024-09-25 23:59:59'),
       ('Gaming Headset Over-Ear', 'Headset gaming over-ear – Mic boom', 'gaming-headset-over-ear', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Headset gaming over-ear, mic boom, surround 7.1, đèn RGB, dây tháo rời.', 'Cho game thủ kết nối vào chiến trường.', 'Audio', 'HEA-018', 89.99, 90, '2024-07-10 13:35:00', 1, 8.99, '2024-07-15 00:00:00', '2024-08-15 23-59-59'),
       ('LED Monitor 24-inch FHD', 'Màn hình LED 24″ FHD – Tần số 75Hz', 'led-monitor-24-inch-fhd', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Màn hình LED 24 inch Full HD, tần số quét 75Hz, viền mỏng, chân đế xoay.', 'Hiển thị rõ nét cho văn phòng & học tập.', 'Computers', 'MON-019', 149.00, 65, '2024-06-01 10:00:00', 1, 12.00, '2024-06-05 00:00:00', '2024-07-05 23-59-59'),
       ('Smartphone Gimbal Stabilizer', 'Gimbal ổn định smartphone – 3 trục', 'smartphone-gimbal-stabilizer', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/c/h/chuot-choi-game-co-day-logitech-g502-hero.png', 'Gimbal 3 trục cho smartphone, chống rung, theo dõi chủ thể, điều khiển app.', 'Cho vlogger & creator nội dung.', 'GAD-020', 129.99, 55, '2024-05-15 18:00:00', 1, 12.99, '2024-05-20 00:00:00', '2024-06-20 23-59-59');



INSERT INTO users (email, phone, password, salt, is_admin, status) VALUES
       ('user@example.com',       '0901111222', '5e884898da28047151d0e56f8dc62927', 'r4nD0mS4lT12345', FALSE, 1),
       ('admin@example.com',      '0909999888', '8c6976e5b5410415bde908bd4dee15df', 'Adm1nS4lT67890', TRUE, 1);


