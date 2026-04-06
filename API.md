# 班级匿名反馈系统 - API 接口文档 & 数据库设计

## 一、数据库设计

### 1.1 数据库创建

```sql
CREATE DATABASE IF NOT EXISTS `forum_anon`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `forum_anon`;
```

### 1.2 表结构

#### 用户表 `users`

| 字段 | 类型 | 约束 | 说明 |
|------|------|------|------|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | 用户ID |
| username | VARCHAR(50) | NOT NULL, UNIQUE | 用户名 |
| password | VARCHAR(255) | NOT NULL | 密码(BCrypt哈希) |
| email | VARCHAR(100) | NULL | 邮箱 |
| role | ENUM('admin','student') | DEFAULT 'student' | 角色 |
| status | ENUM('active','inactive','pending') | DEFAULT 'active' | 状态 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE | 更新时间 |

#### 反馈分类表 `feedback_categories`

| 字段 | 类型 | 约束 | 说明 |
|------|------|------|------|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | 分类ID |
| name | VARCHAR(50) | NOT NULL | 分类名称 |
| description | VARCHAR(200) | NULL | 分类描述 |
| icon | VARCHAR(50) | NULL | 图标名称 |
| color | VARCHAR(100) | NULL | 渐变颜色值 |
| sort_order | INT | DEFAULT 0 | 排序权重 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |

#### 反馈表 `feedbacks`

| 字段 | 类型 | 约束 | 说明 |
|------|------|------|------|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | 反馈ID |
| category | VARCHAR(50) | NOT NULL | 分类名称 |
| content | TEXT | NOT NULL | 反馈内容 |
| status | ENUM('pending','in-progress','resolved') | DEFAULT 'pending' | 处理状态 |
| author | VARCHAR(50) | NULL | 提交者(匿名可为空) |
| user_id | INT UNSIGNED | NULL, FK | 关联用户ID |
| notes | TEXT | NULL | 处理备注 |
| notes_time | DATETIME | NULL | 备注更新时间 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 创建时间 |
| updated_at | DATETIME | DEFAULT CURRENT_TIMESTAMP ON UPDATE | 更新时间 |

#### 反馈状态历史表 `feedback_status_history`

| 字段 | 类型 | 约束 | 说明 |
|------|------|------|------|
| id | INT UNSIGNED | PK, AUTO_INCREMENT | 记录ID |
| feedback_id | INT UNSIGNED | NOT NULL, FK | 反馈ID |
| status | ENUM('pending','in-progress','resolved') | NOT NULL | 变更后的状态 |
| description | VARCHAR(200) | NULL | 状态变更描述 |
| created_at | DATETIME | DEFAULT CURRENT_TIMESTAMP | 变更时间 |

### 1.3 完整建表 SQL

```sql
CREATE DATABASE IF NOT EXISTS `forum_anon`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `forum_anon`;

-- 用户表
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

-- 反馈分类表
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

-- 反馈表
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

-- 反馈状态历史表
CREATE TABLE `feedback_status_history` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `feedback_id` INT UNSIGNED    NOT NULL COMMENT '反馈ID',
  `status`      ENUM('pending','in-progress','resolved') NOT NULL COMMENT '变更后的状态',
  `description` VARCHAR(200)    DEFAULT NULL COMMENT '状态变更描述',
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_feedback_id` (`feedback_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='反馈状态历史表';

-- 插入默认管理员账号 (密码: admin123)
INSERT INTO `users` (`username`, `password`, `email`, `role`) VALUES
  ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'admin');

-- 插入默认反馈分类
INSERT INTO `feedback_categories` (`name`, `description`, `icon`, `color`, `sort_order`) VALUES
  ('班级纪律',   '反映班级纪律、考勤、迟到早退等问题', 'clipboard',   'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', 1),
  ('学习建议',   '对班级学习氛围、学习方法等的建议',   'book',        'linear-gradient(135deg, #10b981 0%, #059669 100%)', 2),
  ('班级活动',   '班级活动组织、参与情况等反馈',       'calendar',    'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)', 3),
  ('同学关系',   '同学间相处、合作、矛盾等问题',       'users',       'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)', 4),
  ('班委工作',   '对班委工作、班级管理的意见建议',     'shield',      'linear-gradient(135deg, #ec4899 0%, #db2777 100%)', 5),
  ('其他班级问题','其他与班级相关的未分类问题',         'help-circle', 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)', 6);
```

---

## 二、通用规范

### 2.1 基础信息

- **Base URL**: `http://<host>:<port>/api`
- **数据格式**: JSON
- **字符编码**: UTF-8
- **认证方式**: JWT Bearer Token (通过 `Authorization: Bearer <token>` 传递)

### 2.2 统一响应格式

**成功响应:**
```json
{
  "code": 200,
  "msg": "操作成功",
  "data": { ... }
}
```

**分页响应:**
```json
{
  "code": 200,
  "msg": "操作成功",
  "data": {
    "list": [ ... ],
    "total": 100,
    "page": 1,
    "pageSize": 10
  }
}
```

**错误响应:**
```json
{
  "code": 401,
  "msg": "未登录或token已过期",
  "data": null
}
```

### 2.3 状态码

| code | 说明 |
|------|------|
| 200 | 成功 |
| 400 | 请求参数错误 |
| 401 | 未认证/Token无效 |
| 403 | 权限不足 |
| 404 | 资源不存在 |
| 500 | 服务器内部错误 |

---

## 三、API 接口列表

### 3.1 认证模块 `/api/auth`

#### POST `/api/auth/login` 登录

**请求体:**
```json
{
  "username": "admin",
  "password": "admin123",
  "remember": false
}
```

**响应:**
```json
{
  "code": 200,
  "msg": "登录成功",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIs...",
    "user": {
      "id": 1,
      "username": "admin",
      "email": "admin@example.com",
      "role": "admin"
    }
  }
}
```

#### POST `/api/auth/logout` 退出登录

**请求头:** `Authorization: Bearer <token>`

**响应:**
```json
{
  "code": 200,
  "msg": "退出成功",
  "data": null
}
```

---

### 3.2 用户管理模块 `/api/users`

> 仅管理员可访问

#### GET `/api/users` 获取用户列表

**Query 参数:**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| page | int | 否 | 页码，默认1 |
| pageSize | int | 否 | 每页条数，默认10 |
| keyword | string | 否 | 搜索关键词(用户名) |

**响应:**
```json
{
  "code": 200,
  "msg": "操作成功",
  "data": {
    "list": [
      {
        "id": 1,
        "username": "zhangsan",
        "email": "zhangsan@example.com",
        "role": "student",
        "status": "active",
        "created_at": "2024-03-01 10:00:00"
      }
    ],
    "total": 50,
    "page": 1,
    "pageSize": 10
  }
}
```

#### POST `/api/users` 创建用户

**请求体:**
```json
{
  "username": "newuser",
  "password": "password123",
  "email": "newuser@example.com",
  "role": "student"
}
```

**响应:**
```json
{
  "code": 200,
  "msg": "用户创建成功",
  "data": {
    "id": 13,
    "username": "newuser",
    "email": "newuser@example.com",
    "role": "student",
    "status": "active"
  }
}
```

#### PUT `/api/users/:id` 更新用户

**请求体:**
```json
{
  "username": "updatedname",
  "email": "updated@example.com",
  "role": "student",
  "status": "active"
}
```

**响应:**
```json
{
  "code": 200,
  "msg": "用户更新成功",
  "data": { ... }
}
```

#### DELETE `/api/users/:id` 删除单个用户

**响应:**
```json
{
  "code": 200,
  "msg": "用户已删除",
  "data": null
}
```

#### DELETE `/api/users/batch` 批量删除用户

**请求体:**
```json
{
  "ids": [1, 2, 3]
}
```

**响应:**
```json
{
  "code": 200,
  "msg": "已删除 3 个用户",
  "data": null
}
```

#### POST `/api/users/:id/reset-password` 重置密码

**响应:**
```json
{
  "code": 200,
  "msg": "密码已重置为默认密码",
  "data": {
    "id": 1,
    "username": "zhangsan"
  }
}
```

#### GET `/api/users/export` 导出用户数据

**响应:** 返回 CSV/Excel 文件流

---

### 3.3 反馈管理模块 `/api/feedbacks`

#### GET `/api/feedbacks` 获取反馈列表

**Query 参数:**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| page | int | 否 | 页码，默认1 |
| pageSize | int | 否 | 每页条数，默认10 |
| keyword | string | 否 | 搜索关键词(内容/分类) |
| category | string | 否 | 筛选分类 |
| status | string | 否 | 筛选状态: pending/in-progress/resolved |

**响应:**
```json
{
  "code": 200,
  "msg": "操作成功",
  "data": {
    "list": [
      {
        "id": 1,
        "category": "班级纪律",
        "content": "最近有同学频繁迟到，影响课堂秩序...",
        "status": "pending",
        "author": "匿名用户A",
        "notes": "",
        "notes_time": null,
        "created_at": "2024-03-15 10:30:00",
        "updated_at": "2024-03-15 10:30:00"
      }
    ],
    "total": 156,
    "page": 1,
    "pageSize": 10
  }
}
```

#### GET `/api/feedbacks/:id` 获取反馈详情

**响应:**
```json
{
  "code": 200,
  "msg": "操作成功",
  "data": {
    "id": 1,
    "category": "班级纪律",
    "content": "最近有同学频繁迟到，影响课堂秩序，希望老师能加强管理。建议实行迟到登记制度，并与平时成绩挂钩。",
    "status": "pending",
    "author": "匿名用户A",
    "user_id": null,
    "notes": "",
    "notes_time": null,
    "created_at": "2024-03-15 10:30:00",
    "updated_at": "2024-03-15 10:30:00",
    "status_history": [
      {
        "id": 1,
        "status": "pending",
        "description": "反馈已提交",
        "created_at": "2024-03-15 10:30:00"
      }
    ]
  }
}
```

#### POST `/api/feedbacks` 提交反馈

> 学生端使用，匿名提交

**请求体:**
```json
{
  "category": "班级纪律",
  "content": "详细的反馈内容..."
}
```

**响应:**
```json
{
  "code": 200,
  "msg": "反馈提交成功",
  "data": {
    "id": 157,
    "category": "班级纪律",
    "content": "详细的反馈内容...",
    "status": "pending",
    "created_at": "2024-03-16 09:00:00"
  }
}
```

#### PUT `/api/feedbacks/:id/status` 更新反馈状态

**请求体:**
```json
{
  "status": "in-progress"
}
```

**响应:**
```json
{
  "code": 200,
  "msg": "状态已更新",
  "data": {
    "id": 1,
    "status": "in-progress",
    "updated_at": "2024-03-16 10:00:00"
  }
}
```

#### PUT `/api/feedbacks/:id/notes` 添加处理备注

**请求体:**
```json
{
  "notes": "已安排每周三、周五下午4-5点进行课后辅导"
}
```

**响应:**
```json
{
  "code": 200,
  "msg": "备注已保存",
  "data": {
    "id": 1,
    "notes": "已安排每周三、周五下午4-5点进行课后辅导",
    "notes_time": "2024-03-16 10:00:00"
  }
}
```

#### DELETE `/api/feedbacks/:id` 删除反馈

**响应:**
```json
{
  "code": 200,
  "msg": "反馈已删除",
  "data": null
}
```

#### GET `/api/feedbacks/export` 导出反馈数据

**响应:** 返回 CSV/Excel 文件流

---

### 3.4 反馈分类模块 `/api/categories`

#### GET `/api/categories` 获取分类列表

**响应:**
```json
{
  "code": 200,
  "msg": "操作成功",
  "data": [
    {
      "id": 1,
      "name": "班级纪律",
      "description": "反映班级纪律、考勤、迟到早退等问题",
      "icon": "clipboard",
      "color": "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
      "sort_order": 1
    },
    {
      "id": 2,
      "name": "学习建议",
      "description": "对班级学习氛围、学习方法等的建议",
      "icon": "book",
      "color": "linear-gradient(135deg, #10b981 0%, #059669 100%)",
      "sort_order": 2
    }
  ]
}
```

---

### 3.5 统计模块 `/api/stats`

> 仅管理员可访问

#### GET `/api/stats/dashboard` 获取仪表盘统计

**响应:**
```json
{
  "code": 200,
  "msg": "操作成功",
  "data": {
    "totalFeedbacks": 156,
    "activeUsers": 42,
    "pendingFeedbacks": 12,
    "resolvedFeedbacks": 89,
    "categoryStats": [
      { "category": "班级纪律", "count": 35 },
      { "category": "学习建议", "count": 42 },
      { "category": "班级活动", "count": 28 },
      { "category": "同学关系", "count": 25 },
      { "category": "班委工作", "count": 26 }
    ],
    "statusStats": {
      "pending": 12,
      "in-progress": 15,
      "resolved": 89
    },
    "recentFeedbacks": [
      {
        "id": 1,
        "category": "班级纪律",
        "content": "最近有同学频繁迟到...",
        "status": "pending",
        "created_at": "2024-03-15 10:30:00"
      }
    ]
  }
}
```

---

## 四、前端对接说明

### 4.1 需要修改的前端文件

前端当前使用 localStorage 模拟数据，需替换为真实 API 调用。建议在 `src/` 下创建以下文件：

```
src/
├── api/
│   ├── request.ts        # axios 实例 + 拦截器
│   ├── auth.ts           # 认证接口
│   ├── users.ts          # 用户管理接口
│   ├── feedbacks.ts      # 反馈管理接口
│   ├── categories.ts     # 分类接口
│   └── stats.ts          # 统计接口
```

### 4.2 请求拦截器示例

```typescript
// src/api/request.ts
import axios from 'axios'

const request = axios.create({
  baseURL: '/api',
  timeout: 10000,
})

request.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

request.interceptors.response.use(
  response => response.data,
  error => {
    if (error.response?.status === 401) {
      localStorage.clear()
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default request
```

### 4.3 路由对应关系

| 前端路由 | 对应页面 | 主要 API |
|----------|----------|----------|
| `/login` | LoginView | POST /api/auth/login |
| `/` | DashboardView | POST /api/feedbacks, GET /api/categories |
| `/admin` | AdminView | GET /api/stats/dashboard |
| `/admin/feedback` | FeedbackManagement | GET /api/feedbacks, DELETE /api/feedbacks/:id |
| `/admin/feedback/:id` | FeedbackDetail | GET /api/feedbacks/:id, PUT /api/feedbacks/:id/status |
| `/admin/users` | UserManagement | GET /api/users, POST/PUT/DELETE /api/users |

---

## 五、后端数据库配置说明

后端使用远程 MySQL 连接，需要在 `.env` 文件中配置：

```env
DB_HOST=<远程数据库地址>
DB_PORT=3306
DB_NAME=forum_anon
DB_USERNAME=<用户名>
DB_PASSWORD=<密码>
```

ThinkPHP 数据库配置位于 `backend/config/database.php`，使用 MySQL 驱动。
