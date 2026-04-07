<template>
    <div class="settings-page">
        <!-- 毛玻璃背景层 -->
        <div class="glass-background">
            <div class="bg-blur-1"></div>
            <div class="bg-blur-2"></div>
            <div class="bg-blur-3"></div>
        </div>

        <!-- 主内容容器 -->
        <div class="settings-container">
            <!-- 头部 -->
            <header class="settings-header">
                <div class="header-left">
                    <div class="logo-wrapper">
                        <svg
                            width="32"
                            height="32"
                            viewBox="0 0 64 64"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <circle cx="32" cy="32" r="30" fill="url(#gradient)" stroke="#7C8DB5" stroke-width="2" />
                            <path d="M32 18L22 24V30C22 40 27 47 32 50C37 47 42 40 42 30V24L32 18Z" fill="white" />
                            <path d="M26 26H38C39.1 26 40 26.9 40 28V36C40 37.1 39.1 38 38 38H30L26 42V38C24.9 38 24 37.1 24 36V28C24 26.9 24.9 26 26 26Z" fill="#7C8DB5" />
                            <path d="M30 32C30 33.1 30.9 34 32 34C33.1 34 34 33.1 34 32C34 30.9 33.1 30 32 30C30.9 30 30 30.9 30 32Z" fill="white" />
                            <defs>
                                <linearGradient id="gradient" x1="32" y1="2" x2="32" y2="62" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#9CA8CC" />
                                    <stop offset="1" stop-color="#7C8DB5" />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h1 class="app-title">系统设置</h1>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-details">
                            <span class="username">{{ username }}</span>
                        </div>
                        <button class="logout-btn" @click="handleLogout">
                            <Icon icon-class="log-out" size="md" fill="currentColor" />
                            <span>退出</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- 设置面板 -->
            <div class="settings-grid">
                <!-- 基本设置 -->
                <div class="settings-card">
                    <h3 class="card-title">
                        <Icon icon-class="settings" size="sm" fill="currentColor" />
                        基本设置
                    </h3>
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">系统名称</span>
                            <span class="setting-desc">设置系统显示名称</span>
                        </div>
                        <input
                            v-model="settings.systemName"
                            class="setting-input"
                            type="text"
                            placeholder="班级匿名反馈系统"
                        />
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">每页显示条数</span>
                            <span class="setting-desc">列表每页显示的数据条数</span>
                        </div>
                        <select v-model="settings.pageSize" class="setting-input">
                            <option value="10">10 条</option>
                            <option value="20">20 条</option>
                            <option value="50">50 条</option>
                        </select>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">匿名提交</span>
                            <span class="setting-desc">是否允许匿名提交反馈</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" v-model="settings.allowAnonymous" />
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- 通知设置 -->
                <div class="settings-card">
                    <h3 class="card-title">
                        <Icon icon-class="bell" size="sm" fill="currentColor" />
                        通知设置
                    </h3>
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">新反馈通知</span>
                            <span class="setting-desc">收到新反馈时发送通知</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" v-model="settings.newFeedbackNotify" />
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">状态变更通知</span>
                            <span class="setting-desc">反馈状态变更时发送通知</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" v-model="settings.statusChangeNotify" />
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">系统公告</span>
                            <span class="setting-desc">在首页显示系统公告</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" v-model="settings.showAnnouncement" />
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- 公告编辑 -->
                <div class="settings-card">
                    <h3 class="card-title">
                        <Icon icon-class="message-square" size="sm" fill="currentColor" />
                        系统公告
                    </h3>
                    <div class="setting-item">
                        <textarea
                            v-model="settings.announcement"
                            class="setting-textarea"
                            placeholder="输入系统公告内容..."
                            rows="4"
                        ></textarea>
                    </div>
                    <div class="setting-actions">
                        <button class="save-btn" @click="saveAnnouncement">保存公告</button>
                    </div>
                </div>

                <!-- 数据管理 -->
                <div class="settings-card">
                    <h3 class="card-title">
                        <Icon icon-class="database" size="sm" fill="currentColor" />
                        数据管理
                    </h3>
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">导出所有反馈</span>
                            <span class="setting-desc">将所有反馈数据导出为 Excel 文件</span>
                        </div>
                        <button class="action-btn-small" @click="exportAllFeedbacks">
                            <Icon icon-class="download" size="xs" fill="currentColor" />
                            导出
                        </button>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">导出所有用户</span>
                            <span class="setting-desc">将所有用户数据导出为 Excel 文件</span>
                        </div>
                        <button class="action-btn-small" @click="exportAllUsers">
                            <Icon icon-class="download" size="xs" fill="currentColor" />
                            导出
                        </button>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">清理已解决数据</span>
                            <span class="setting-desc">清理所有已解决状态的历史记录</span>
                        </div>
                        <button class="action-btn-small danger" @click="cleanHistory">
                            <Icon icon-class="trash-2" size="xs" fill="currentColor" />
                            清理
                        </button>
                    </div>
                </div>

                <!-- 关于系统 -->
                <div class="settings-card">
                    <h3 class="card-title">
                        <Icon icon-class="info" size="sm" fill="currentColor" />
                        关于系统
                    </h3>
                    <div class="about-item">
                        <span class="about-label">系统版本</span>
                        <span class="about-value">v1.0.0</span>
                    </div>
                    <div class="about-item">
                        <span class="about-label">前端技术</span>
                        <span class="about-value">Vue 3 + TypeScript + Vite</span>
                    </div>
                    <div class="about-item">
                        <span class="about-label">后端技术</span>
                        <span class="about-value">ThinkPHP 8 + PHP 8.0+</span>
                    </div>
                    <div class="about-item">
                        <span class="about-label">数据库</span>
                        <span class="about-value">MySQL 5.7+</span>
                    </div>
                </div>
            </div>

            <!-- 页脚 -->
            <footer class="settings-footer">
                <p class="footer-text">
                    © {{ currentYear }} 班级匿名反馈系统 - 系统设置. 版本 1.0.0
                </p>
                <div class="footer-links">
                    <router-link to="/admin" class="footer-link">返回管理面板</router-link>
                    <span class="footer-separator">•</span>
                    <router-link to="/dashboard" class="footer-link">用户面板</router-link>
                </div>
            </footer>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import Icon from "../components/Icon.vue";
import { logout } from "../api/auth";
import { exportFeedbacks } from "../api/feedbacks";
import { exportUsers } from "../api/users";

const router = useRouter();

const username = computed(() => localStorage.getItem("username") || "管理员");
const currentYear = computed(() => new Date().getFullYear());

const settings = ref({
    systemName: "班级匿名反馈系统",
    pageSize: "10",
    allowAnonymous: true,
    newFeedbackNotify: true,
    statusChangeNotify: true,
    showAnnouncement: false,
    announcement: "",
});

const saveAnnouncement = () => {
    localStorage.setItem("system_announcement", settings.value.announcement);
    alert("公告已保存");
};

const exportAllFeedbacks = async () => {
    try {
        const blob: any = await exportFeedbacks();
        const url = window.URL.createObjectURL(new Blob([blob]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", `feedbacks_${Date.now()}.xlsx`);
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (e) {
        alert("导出失败");
    }
};

const exportAllUsers = async () => {
    try {
        const blob: any = await exportUsers();
        const url = window.URL.createObjectURL(new Blob([blob]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", `users_${Date.now()}.xlsx`);
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (e) {
        alert("导出失败");
    }
};

const cleanHistory = () => {
    if (confirm("确定要清理所有已解决状态的历史记录吗？此操作不可撤销。")) {
        alert("清理功能需要后端支持，请联系管理员");
    }
};

const handleLogout = async () => {
    try {
        await logout();
    } catch (e) {}
    localStorage.removeItem("token");
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("username");
    localStorage.removeItem("userEmail");
    localStorage.removeItem("loginTime");
    localStorage.removeItem("isAdmin");
    router.push("/login");
};

onMounted(() => {
    const savedAnnouncement = localStorage.getItem("system_announcement");
    if (savedAnnouncement !== null) {
        settings.value.announcement = savedAnnouncement;
    }
});
</script>

<style scoped>
.settings-page {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    width: 100vw; height: 100vh;
    background: linear-gradient(135deg, #f0f4f8 0%, #e6edf5 100%);
    overflow-y: auto;
    padding: 20px;
}

.glass-background {
    position: fixed; width: 100%; height: 100%;
    pointer-events: none; top: 0; left: 0; z-index: 0;
}
.bg-blur-1, .bg-blur-2, .bg-blur-3 {
    position: absolute; border-radius: 50%;
    background: rgba(200, 210, 230, 0.15);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
.bg-blur-1 { width: 300px; height: 300px; top: -100px; right: -100px; }
.bg-blur-2 { width: 200px; height: 200px; bottom: -50px; left: -50px; }
.bg-blur-3 { width: 150px; height: 150px; top: 50%; left: 10%; }

.settings-container {
    position: relative; z-index: 1;
    max-width: 1200px; margin: 0 auto;
}

/* Header */
.settings-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px 0; margin-bottom: 30px;
}
.header-left { display: flex; align-items: center; gap: 16px; }
.logo-wrapper {
    display: flex; align-items: center; justify-content: center;
    width: 48px; height: 48px;
    background: rgba(124, 141, 181, 0.1); border-radius: 12px;
}
.app-title {
    font-size: 1.8rem; font-weight: 700; color: #4a5568;
    margin: 0; letter-spacing: -0.5px;
}
.header-right { display: flex; align-items: center; }
.user-info { display: flex; align-items: center; gap: 16px; }
.user-details { display: flex; flex-direction: column; align-items: flex-end; }
.username { font-size: 1rem; font-weight: 600; color: #4a5568; }
.logout-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 16px; background: rgba(255,255,255,0.8);
    border: 1px solid rgba(203,213,224,0.6); border-radius: 8px;
    color: #4a5568; font-size: 0.9rem; font-weight: 500; cursor: pointer;
    transition: all 0.2s;
}
.logout-btn:hover { background: rgba(255,255,255,0.95); color: #e53e3e; }

/* Settings Grid */
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
    margin-bottom: 30px;
}

.settings-card {
    background: rgba(255,255,255,0.7); border-radius: 16px;
    border: 1px solid rgba(203,213,224,0.5); padding: 24px;
    backdrop-filter: blur(10px);
}

.card-title {
    font-size: 1.1rem; font-weight: 700; color: #4a5568;
    margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;
}

/* Setting Items */
.setting-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid rgba(203,213,224,0.3);
}
.setting-item:last-child { border-bottom: none; }

.setting-info { display: flex; flex-direction: column; gap: 4px; }
.setting-label { font-size: 0.95rem; font-weight: 600; color: #4a5568; }
.setting-desc { font-size: 0.8rem; color: #718096; }

.setting-input {
    padding: 8px 14px; border-radius: 8px;
    border: 1px solid rgba(203,213,224,0.5);
    background: rgba(255,255,255,0.6);
    color: #4a5568; font-size: 0.9rem;
    outline: none; transition: border-color 0.2s;
    min-width: 140px;
}
.setting-input:focus { border-color: #667eea; }

.setting-textarea {
    width: 100%; padding: 10px 14px; border-radius: 8px;
    border: 1px solid rgba(203,213,224,0.5);
    background: rgba(255,255,255,0.6);
    color: #4a5568; font-size: 0.9rem;
    outline: none; resize: vertical;
    transition: border-color 0.2s;
    font-family: inherit;
}
.setting-textarea:focus { border-color: #667eea; }

.setting-actions {
    display: flex; justify-content: flex-end; padding-top: 12px;
}

.save-btn {
    padding: 8px 20px; border-radius: 8px; border: none;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white; font-size: 0.85rem; font-weight: 600;
    cursor: pointer; transition: opacity 0.2s;
}
.save-btn:hover { opacity: 0.9; }

/* Toggle Switch */
.toggle-switch {
    position: relative; display: inline-block;
    width: 44px; height: 24px; flex-shrink: 0;
}
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background: #cbd5e0; border-radius: 24px;
    transition: background 0.3s;
}
.toggle-slider:before {
    content: ""; position: absolute;
    height: 18px; width: 18px; left: 3px; bottom: 3px;
    background: white; border-radius: 50%;
    transition: transform 0.3s;
}
.toggle-switch input:checked + .toggle-slider {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(20px);
}

/* Small Action Buttons */
.action-btn-small {
    display: flex; align-items: center; gap: 4px;
    padding: 6px 14px; border-radius: 8px;
    border: 1px solid rgba(203,213,224,0.5);
    background: rgba(255,255,255,0.6);
    color: #4a5568; font-size: 0.8rem; font-weight: 500;
    cursor: pointer; transition: all 0.2s;
}
.action-btn-small:hover {
    background: rgba(102,126,234,0.1); border-color: #667eea; color: #667eea;
}
.action-btn-small.danger:hover {
    background: rgba(229,62,62,0.1); border-color: #e53e3e; color: #e53e3e;
}

/* About */
.about-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(203,213,224,0.3);
}
.about-item:last-child { border-bottom: none; }
.about-label { font-size: 0.9rem; color: #718096; }
.about-value { font-size: 0.9rem; font-weight: 600; color: #4a5568; }

/* Footer */
.settings-footer {
    padding: 20px 0; border-top: 1px solid rgba(203,213,224,0.4);
    text-align: center;
}
.footer-text { font-size: 0.85rem; color: #718096; margin: 0 0 10px; }
.footer-links { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
.footer-link {
    color: #667eea; text-decoration: none; font-size: 0.85rem; font-weight: 500;
}
.footer-link:hover { color: #764ba2; }
.footer-separator { color: #a0aec0; }

@media (max-width: 768px) {
    .settings-grid { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .settings-header { flex-direction: column; gap: 16px; }
    .setting-item { flex-direction: column; align-items: flex-start; gap: 10px; }
    .setting-input { width: 100%; }
}
</style>
