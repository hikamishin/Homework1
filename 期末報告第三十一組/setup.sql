-- TCG Market 資料庫建立腳本
-- 執行方式：mysql -u root -p tcgmarket < setup.sql
-- 或在 phpMyAdmin 的 SQL 頁籤貼上執行

CREATE DATABASE IF NOT EXISTS tcgmarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tcgmarket;

-- -------------------------------------------------------
-- 使用者表
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    name        VARCHAR(100) NOT NULL,
    role        ENUM('member','admin') NOT NULL DEFAULT 'member',
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, password, name, role) VALUES
    ('member', '123456',   '一般會員',   'member'),
    ('admin',  'admin123', '平台管理員', 'admin');

-- -------------------------------------------------------
-- 卡牌覆寫表（管理員修改後的價格/庫存）
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS card_overrides (
    card_id  VARCHAR(20)  NOT NULL PRIMARY KEY,
    price    INT UNSIGNED NOT NULL,
    stock    INT UNSIGNED NOT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- 購物車表
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS cart_items (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    card_id     VARCHAR(20)  NOT NULL,
    quantity    INT UNSIGNED NOT NULL DEFAULT 1,
    added_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_card (user_id, card_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- 訂單表
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(30) NOT NULL UNIQUE,
    user_id     INT UNSIGNED NOT NULL,
    total       INT UNSIGNED NOT NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- 訂單明細表
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_items (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id    INT UNSIGNED NOT NULL,
    card_id     VARCHAR(20)  NOT NULL,
    quantity    INT UNSIGNED NOT NULL,
    price       INT UNSIGNED NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- 收藏表
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS collections (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    card_id     VARCHAR(20)  NOT NULL,
    added_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_card (user_id, card_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- 評價表
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS reviews (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    review_code VARCHAR(30)  NOT NULL UNIQUE,
    user_id     INT UNSIGNED NOT NULL,
    card_id     VARCHAR(20)  NOT NULL,
    card_name   VARCHAR(100) NOT NULL,
    rating      TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment     TEXT NOT NULL,
    status      ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    handled_at  DATETIME DEFAULT NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- 檢舉表
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS reports (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_code VARCHAR(30)  NOT NULL UNIQUE,
    user_id     INT UNSIGNED NOT NULL,
    card_id     VARCHAR(20)  NOT NULL,
    card_name   VARCHAR(100) NOT NULL,
    reason      VARCHAR(100) NOT NULL,
    message     TEXT NOT NULL,
    status      ENUM('pending','processing','resolved','rejected') NOT NULL DEFAULT 'pending',
    handled_at  DATETIME DEFAULT NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
