CREATE DATABASE IF NOT EXISTS `forum_anon`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `forum_anon`;

CREATE TABLE `users` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `username`    VARCHAR(50)     NOT NULL COMMENT '用户名',
  `password`    VARCHAR(255)    NOT NULL COMMENT '密码(BCrypt哈希)',
  `email`       VARCHAR(100)    DEFAULT NULL COMMENT '邮箱',
  `role`        ENUM('admin','student') NOT NULL DEFAULT 'student' COMMENT '角色',
  `status`      ENUM('active','inactive','pending') NOT NULL DEFAULT 'active' COMMENT '状态',
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

CREATE TABLE `feedback_categories` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(50)     NOT NULL COMMENT '分类名称',
  `description` VARCHAR(200)    DEFAULT NULL COMMENT '分类描述',
  `icon`        VARCHAR(50)     DEFAULT NULL COMMENT '图标名称',
  `color`       VARCHAR(100)    DEFAULT NULL COMMENT '渐变颜色',
  `sort_order`  INT             NOT NULL DEFAULT 0 COMMENT '排序权重',
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='反馈分类表';

CREATE TABLE `feedbacks` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `category`    VARCHAR(50)     NOT NULL COMMENT '反馈分类',
  `content`     TEXT            NOT NULL COMMENT '反馈内容',
  `status`      ENUM('pending','in-progress','resolved') NOT NULL DEFAULT 'pending' COMMENT '处理状态',
  `author`      VARCHAR(50)     DEFAULT NULL COMMENT '提交者(匿名可为空)',
  `user_id`     INT UNSIGNED    DEFAULT NULL COMMENT '关联用户ID',
  `notes`       TEXT            DEFAULT NULL COMMENT '处理备注',
  `notes_time`  DATETIME        DEFAULT NULL COMMENT '备注更新时间',
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_category` (`category`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='反馈表';

CREATE TABLE `feedback_status_history` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `feedback_id` INT UNSIGNED    NOT NULL COMMENT '反馈ID',
  `status`      ENUM('pending','in-progress','resolved') NOT NULL COMMENT '变更后的状态',
  `description` VARCHAR(200)    DEFAULT NULL COMMENT '状态变更描述',
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_feedback_id` (`feedback_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='反馈状态历史表';

INSERT INTO `users` (`username`, `password`, `email`, `role`) VALUES
  ('admin', '123', 'admin@example.com', 'admin');

INSERT INTO `feedback_categories` (`name`, `description`, `icon`, `color`, `sort_order`) VALUES
  ('班级纪律',   '反映班级纪律、考勤、迟到早退等问题', 'clipboard',   'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', 1),
  ('学习建议',   '对班级学习氛围、学习方法等的建议',   'book',        'linear-gradient(135deg, #10b981 0%, #059669 100%)', 2),
  ('班级活动',   '班级活动组织、参与情况等反馈',       'calendar',    'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)', 3),
  ('同学关系',   '同学间相处、合作、矛盾等问题',       'users',       'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)', 4),
  ('班委工作',   '对班委工作、班级管理的意见建议',     'shield',      'linear-gradient(135deg, #ec4899 0%, #db2777 100%)', 5),
  ('其他班级问题','其他与班级相关的未分类问题',         'help-circle', 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)', 6);

-- 创建专用用户并授权（允许任意主机连接）
CREATE USER IF NOT EXISTS 'forum'@'%' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON `forum_anon`.* TO 'forum'@'%';
FLUSH PRIVILEGES;
