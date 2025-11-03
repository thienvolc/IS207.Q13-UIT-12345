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
    last_login    DATETIME,
    FOREIGN KEY (user_id) REFERENCES users (id)
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
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (role_id) REFERENCES roles (id)
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
    `desc`     TEXT,
    FOREIGN KEY (parent_id) REFERENCES categories (id)
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
    content    TEXT,
    FOREIGN KEY (product_id) REFERENCES products (id)
);

CREATE TABLE product_categories
(
    product_id  INT,
    category_id INT,
    PRIMARY KEY (product_id, category_id),
    FOREIGN KEY (product_id) REFERENCES products (id),
    FOREIGN KEY (category_id) REFERENCES categories (id)
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
    PRIMARY KEY (product_id, tag_id),
    FOREIGN KEY (product_id) REFERENCES products (id),
    FOREIGN KEY (tag_id) REFERENCES tags (id)
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
    note        VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users (id)
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
    note       VARCHAR(255),
    FOREIGN KEY (cart_id) REFERENCES carts (id),
    FOREIGN KEY (product_id) REFERENCES products (id)
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
    note           VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users (id)
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
    note       VARCHAR(255),
    FOREIGN KEY (order_id) REFERENCES orders (id),
    FOREIGN KEY (product_id) REFERENCES products (id)
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
    type     TINYINT UNSIGNED DEFAULT 1 CHECK (status BETWEEN 1 AND 2),
    mode     VARCHAR(150),
    status   TINYINT UNSIGNED DEFAULT 1 CHECK (status BETWEEN 1 AND 6),
    FOREIGN KEY (order_id) REFERENCES orders (id)
);

CREATE TABLE configs
(
    `key`   VARCHAR(100) PRIMARY KEY,
    `value` VARCHAR(255),
    type  tinyint,
    `desc`         VARCHAR(255),
    updated_at   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE api_tokens
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(100) NOT NULL UNIQUE,  -- Token (phải là duy nhất)
    expires_at DATETIME,                 -- Thời gian hết hạn
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);