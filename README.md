# 班级匿名反馈系统

一个基于 Vue 3 + ThinkPHP 8 的班级匿名反馈管理系统，支持学生匿名提交反馈、管理员处理反馈的全流程管理。

## 技术栈

### 前端
- Vue 3 + TypeScript
- Vite
- Vue Router
- Axios

### 后端
- ThinkPHP 8
- PHP 8.0+
- MySQL
- JWT 认证

## 项目结构

```
forum-anon/
├── frontend/              # 前端项目
│   ├── src/
│   │   ├── api/          # API 请求模块
│   │   ├── assets/       # 静态资源
│   │   ├── components/   # 公共组件
│   │   ├── views/        # 页面视图
│   │   ├── App.vue       # 根组件
│   │   └── main.ts       # 入口文件
│   ├── package.json
│   └── vite.config.ts    # Vite 配置
├── backend/               # 后端项目
│   ├── app/
│   │   ├── controller/   # 控制器
│   │   ├── model/        # 模型
│   │   ├── middleware/   # 中间件
│   │   └── common.php    # 公共函数
│   ├── config/           # 配置文件
│   ├── route/            # 路由配置
│   ├── public/           # 入口文件
│   └── composer.json
├── database.sql           # 数据库脚本
└── API.md                 # API 接口文档
```

## 功能特性

### 学生端
- ✅ 用户登录认证
- ✅ 匿名反馈提交（6 种类别）
- ✅ 毛玻璃 UI 设计
- ✅ 响应式布局

### 管理端
- ✅ 反馈列表管理（搜索、筛选、分页）
- ✅ 反馈详情查看与状态更新
- ✅ 处理备注功能
- ✅ 状态变更历史记录
- ✅ 用户管理（CRUD、批量删除、密码重置）
- ✅ 数据统计仪表盘
- ✅ 数据导出（CSV）

## 快速开始

### 环境要求

- Node.js 18+
- PHP 8.0+
- MySQL 5.7+
- Composer

### 1. 克隆项目

```bash
git clone <repository-url>
cd forum-anon
```

### 2. 数据库配置

```bash
# 登录 MySQL
mysql -u root -p

# 执行数据库脚本
source database.sql
```

> 脚本会自动创建数据库、表结构和初始数据

### 3. 后端启动

```bash
cd backend

# 复制环境变量
cp .example.env .env

# 编辑 .env 文件，配置数据库连接信息
# DB_HOST=127.0.0.1
# DB_NAME=forum_anon
# DB_USER=forum
# DB_PASS=123456

# 安装依赖
composer install

# 启动开发服务器
php think run
# 或
php -S localhost:8000 -t public/
```

后端服务将运行在 `http://localhost:8000`

### 4. 前端启动

```bash
cd frontend

# 安装依赖
npm install

# 启动开发服务器
npm run dev
```

前端服务将运行在 `http://localhost:5173`

## 默认账号

### 管理员账号

| 用户名 | 密码 | 角色 |
|--------|------|------|
| admin | admin123 | admin |

### 学生账号

| 用户名 | 密码 | 角色 |
|--------|------|------|
| 123456 | 123456 | student |

> 可通过管理端创建更多学生账号

## 配置说明

### 后端环境变量 (.env)

```env
APP_DEBUG = true
APP_HOST = 0.0.0.0
APP_PORT = 8000

DB_TYPE = mysql
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_NAME = forum_anon
DB_USER = forum
DB_PASS = 123456
DB_CHARSET = utf8mb4

JWT_SECRET = your_secret_key_here
JWT_EXPIRE = 86400
```

### 前端代理配置 (vite.config.ts)

```typescript
server: {
  proxy: {
    '/api': {
      target: 'http://localhost:8000',
      changeOrigin: true,
    },
  },
}
```

## 反馈类别

系统预置 6 种反馈类别：

| 类别 | 说明 |
|------|------|
| 班级纪律 | 反映班级纪律、考勤、迟到早退等问题 |
| 学习建议 | 对班级学习氛围、学习方法等的建议 |
| 班级活动 | 班级活动组织、参与情况等反馈 |
| 同学关系 | 同学间相处、合作、矛盾等问题 |
| 班委工作 | 对班委工作、班级管理的意见建议 |
| 其他班级问题 | 其他与班级相关的未分类问题 |

## API 文档

详细 API 接口文档请查看 [API.md](./API.md)

### 主要接口

| 接口 | 方法 | 说明 |
|------|------|------|
| /api/auth/login | POST | 用户登录 |
| /api/auth/logout | POST | 退出登录 |
| /api/feedbacks | GET | 获取反馈列表 |
| /api/feedbacks | POST | 提交反馈 |
| /api/feedbacks/:id | GET | 获取反馈详情 |
| /api/feedbacks/:id/status | PUT | 更新反馈状态 |
| /api/categories | GET | 获取分类列表 |
| /api/users | GET | 获取用户列表（管理员） |
| /api/stats/dashboard | GET | 统计仪表盘（管理员） |

## 部署

### 生产环境部署

#### 前端构建

```bash
cd frontend
npm run build
# 构建产物在 dist/ 目录
```

将 `dist/` 目录部署到 Nginx/Apache 等 Web 服务器。

#### 后端部署

推荐使用 Nginx + PHP-FPM 部署：

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/backend/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

> 确保关闭 `APP_DEBUG` 并设置正确的 `JWT_SECRET`

## 常见问题

### 1. 中文乱码问题

确保数据库、数据表、连接均使用 `utf8mb4` 字符集：

```sql
ALTER TABLE table_name CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. JWT Token 过期

默认 Token 有效期为 24 小时（86400 秒），可在 `.env` 中调整：

```env
JWT_EXPIRE = 604800  # 7 天
```

### 3. 跨域问题

开发环境通过 Vite 代理解决。生产环境需配置 CORS 或使用 Nginx 反向代理。

## 许可证

本项目仅供学习使用。

## 更新日志

### v1.0.0 (2024-04-06)

- 初始版本发布
- 支持学生匿名提交反馈
- 支持管理员反馈管理（CRUD、状态更新、备注）
- 支持用户管理
- 支持数据统计仪表盘
- 支持数据导出（CSV）
