<template>
    <div class="admin-page">
        <!-- 毛玻璃背景层 -->
        <div class="glass-background">
            <div class="bg-blur-1"></div>
            <div class="bg-blur-2"></div>
            <div class="bg-blur-3"></div>
        </div>

        <!-- 主内容容器 -->
        <div class="admin-container">
            <!-- 头部 -->
            <header class="admin-header">
                <div class="header-left">
                    <div class="logo-wrapper">
                        <svg
                            width="32"
                            height="32"
                            viewBox="0 0 64 64"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <!-- 渐变圆形背景 -->
                            <circle
                                cx="32"
                                cy="32"
                                r="30"
                                fill="url(#gradient)"
                                stroke="#7C8DB5"
                                stroke-width="2"
                            />

                            <!-- 盾牌轮廓（代表匿名保护） -->
                            <path
                                d="M32 18L22 24V30C22 40 27 47 32 50C37 47 42 40 42 30V24L32 18Z"
                                fill="white"
                            />

                            <!-- 对话气泡（代表反馈） -->
                            <path
                                d="M26 26H38C39.1 26 40 26.9 40 28V36C40 37.1 39.1 38 38 38H30L26 42V38C24.9 38 24 37.1 24 36V28C24 26.9 24.9 26 26 26Z"
                                fill="#7C8DB5"
                            />

                            <!-- 盾牌内的对话气泡细节 -->
                            <path
                                d="M30 32C30 33.1 30.9 34 32 34C33.1 34 34 33.1 34 32C34 30.9 33.1 30 32 30C30.9 30 30 30.9 30 32Z"
                                fill="white"
                            />

                            <defs>
                                <linearGradient
                                    id="gradient"
                                    x1="32"
                                    y1="2"
                                    x2="32"
                                    y2="62"
                                    gradientUnits="userSpaceOnUse"
                                >
                                    <stop stop-color="#9CA8CC" />
                                    <stop offset="1" stop-color="#7C8DB5" />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h1 class="app-title">班级匿名反馈系统 - 管理面板</h1>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-details">
                            <span class="username">{{ username }}</span>
                        </div>
                        <button class="logout-btn" @click="handleLogout">
                            <Icon
                                icon-class="log-out"
                                size="md"
                                fill="currentColor"
                            />
                            <span>退出</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- 欢迎横幅 -->
            <div class="welcome-banner">
                <div class="banner-content">
                    <h2>欢迎，管理员！</h2>
                    <p>您可以在这里管理班级反馈系统</p>
                    <p>{{ currentDate }}</p>
                </div>
                <div class="banner-icon">
                    <div class="icon-wrapper">
                        <Icon icon-class="shield" size="xl" fill="white" />
                    </div>
                </div>
            </div>

            <!-- 统计数据概览 -->
            <div class="stats-section">
                <h3 class="section-title">系统概览</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-content">
                            <div
                                class="stat-icon"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #667eea 0%,
                                        #764ba2 100%
                                    );
                                "
                            >
                                <Icon
                                    icon-class="message-square"
                                    size="lg"
                                    fill="white"
                                />
                            </div>
                            <div class="stat-info">
                                <h4>{{ stats.totalFeedbacks }}</h4>
                                <p>总反馈数</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <div
                                class="stat-icon"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #10b981 0%,
                                        #059669 100%
                                    );
                                "
                            >
                                <Icon
                                    icon-class="users"
                                    size="lg"
                                    fill="white"
                                />
                            </div>
                            <div class="stat-info">
                                <h4>{{ stats.activeUsers }}</h4>
                                <p>活跃用户</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <div
                                class="stat-icon"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #f59e0b 0%,
                                        #d97706 100%
                                    );
                                "
                            >
                                <Icon
                                    icon-class="bell"
                                    size="lg"
                                    fill="white"
                                />
                            </div>
                            <div class="stat-info">
                                <h4>{{ stats.pendingFeedbacks }}</h4>
                                <p>待处理反馈</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <div
                                class="stat-icon"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #8b5cf6 0%,
                                        #7c3aed 100%
                                    );
                                "
                            >
                                <Icon
                                    icon-class="check-circle"
                                    size="lg"
                                    fill="white"
                                />
                            </div>
                            <div class="stat-info">
                                <h4>{{ stats.resolvedFeedbacks }}</h4>
                                <p>已解决反馈</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 管理功能卡片 -->
            <div class="management-section">
                <h3 class="section-title">管理功能</h3>
                <div class="management-grid">
                    <div
                        class="management-card"
                        @click="navigateTo('feedback')"
                    >
                        <div class="management-card-content">
                            <div
                                class="management-icon"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #ec4899 0%,
                                        #db2777 100%
                                    );
                                "
                            >
                                <Icon
                                    icon-class="message-square"
                                    size="lg"
                                    fill="white"
                                />
                            </div>
                            <div class="management-info">
                                <h4>反馈管理</h4>
                                <p>查看、处理、删除用户反馈</p>
                            </div>
                        </div>
                        <div class="management-action">
                            <Icon
                                icon-class="arrow-right"
                                size="sm"
                                fill="#718096"
                            />
                        </div>
                    </div>
                    <div class="management-card" @click="navigateTo('users')">
                        <div class="management-card-content">
                            <div
                                class="management-icon"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #3b82f6 0%,
                                        #1d4ed8 100%
                                    );
                                "
                            >
                                <Icon
                                    icon-class="users"
                                    size="lg"
                                    fill="white"
                                />
                            </div>
                            <div class="management-info">
                                <h4>用户管理</h4>
                                <p>管理用户账户和权限</p>
                            </div>
                        </div>
                        <div class="management-action">
                            <Icon
                                icon-class="arrow-right"
                                size="sm"
                                fill="#718096"
                            />
                        </div>
                    </div>
                    <div
                        class="management-card"
                        @click="navigateTo('analytics')"
                    >
                        <div class="management-card-content">
                            <div
                                class="management-icon"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #10b981 0%,
                                        #059669 100%
                                    );
                                "
                            >
                                <Icon
                                    icon-class="pie-chart"
                                    size="lg"
                                    fill="white"
                                    stroke="white"
                                />
                            </div>
                            <div class="management-info">
                                <h4>数据统计</h4>
                                <p>查看系统使用情况和趋势</p>
                            </div>
                        </div>
                        <div class="management-action">
                            <Icon
                                icon-class="arrow-right"
                                size="sm"
                                fill="#718096"
                            />
                        </div>
                    </div>
                    <div
                        class="management-card"
                        @click="navigateTo('settings')"
                    >
                        <div class="management-card-content">
                            <div
                                class="management-icon"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #6b7280 0%,
                                        #4b5563 100%
                                    );
                                "
                            >
                                <Icon
                                    icon-class="settings"
                                    size="lg"
                                    fill="white"
                                />
                            </div>
                            <div class="management-info">
                                <h4>系统设置</h4>
                                <p>配置系统参数和通知</p>
                            </div>
                        </div>
                        <div class="management-action">
                            <Icon
                                icon-class="arrow-right"
                                size="sm"
                                fill="#718096"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- 最近反馈 -->
            <div class="recent-section">
                <div class="section-header">
                    <h3 class="section-title">最近反馈</h3>
                    <button
                        class="view-all-btn"
                        @click="navigateTo('feedback')"
                    >
                        查看全部
                        <Icon
                            icon-class="arrow-right"
                            size="sm"
                            fill="currentColor"
                        />
                    </button>
                </div>
                <div class="recent-feedbacks">
                    <div
                        v-for="feedback in recentFeedbacks"
                        :key="feedback.id"
                        class="feedback-item"
                    >
                        <div class="feedback-header">
                            <div class="feedback-category">
                                <div
                                    class="category-badge"
                                    :style="{
                                        background: getCategoryColor(
                                            feedback.category,
                                        ),
                                    }"
                                >
                                    {{ feedback.category }}
                                </div>
                                <span class="feedback-time">{{
                                    feedback.time
                                }}</span>
                            </div>
                            <div class="feedback-status">
                                <div
                                    class="status-badge"
                                    :class="feedback.status"
                                >
                                    {{ getStatusText(feedback.status) }}
                                </div>
                            </div>
                        </div>
                        <div class="feedback-content">
                            <p>{{ feedback.content }}</p>
                        </div>
                        <div class="feedback-actions">
                            <button
                                class="action-btn view-btn"
                                @click.stop="viewFeedback(feedback.id)"
                            >
                                <Icon
                                    icon-class="file-text"
                                    size="sm"
                                    fill="currentColor"
                                />
                                查看详情
                            </button>
                            <button
                                v-if="feedback.status === 'pending'"
                                class="action-btn resolve-btn"
                                @click.stop="resolveFeedback(feedback.id)"
                            >
                                <Icon
                                    icon-class="check-circle"
                                    size="sm"
                                    fill="currentColor"
                                />
                                标记为已解决
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 页脚 -->
            <footer class="admin-footer">
                <p class="footer-text">
                    © {{ currentYear }} 班级匿名反馈系统 - 管理后台. 版本 1.0.0
                </p>
                <!-- <div class="footer-links">
                    <router-link to="/dashboard" class="footer-link"
                        >返回用户面板</router-link
                    >
                    <span class="footer-separator">•</span>
                    <a href="#" class="footer-link" @click.prevent="exportData"
                        >导出数据</a
                    >
                    <span class="footer-separator">•</span>
                    <router-link to="/help" class="footer-link"
                        >管理帮助</router-link
                    >
                </div> -->
            </footer>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import Icon from "../components/Icon.vue";
import { getDashboardStats } from "../api/stats";
import { updateFeedbackStatus } from "../api/feedbacks";
import { logout } from "../api/auth";

const router = useRouter();

const username = computed(() => {
    return localStorage.getItem("username") || "管理员";
});

const currentDate = computed(() => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const day = String(now.getDate()).padStart(2, "0");
    const weekdays = ["日", "一", "二", "三", "四", "五", "六"];
    const weekday = weekdays[now.getDay()];
    return `${year}年${month}月${day}日 星期${weekday}`;
});

const currentYear = computed(() => new Date().getFullYear());

const stats = ref({
    totalFeedbacks: 0,
    activeUsers: 0,
    pendingFeedbacks: 0,
    resolvedFeedbacks: 0,
});

const recentFeedbacks = ref<any[]>([]);

const navigateTo = (page: string) => {
    const routeMap: Record<string, string> = {
        feedback: "/admin/feedback",
        users: "/admin/users",
        analytics: "/admin/analytics",
        settings: "/admin/settings",
    };

    const routePath = routeMap[page];

    if (routePath) {
        router.push(routePath);
    } else {
        alert(`页面未找到: ${page}`);
    }
};

const getCategoryColor = (category: string) => {
    const colors: Record<string, string> = {
        班级纪律: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
        学习建议: "linear-gradient(135deg, #10b981 0%, #059669 100%)",
        班级活动: "linear-gradient(135deg, #f59e0b 0%, #d97706 100%)",
        同学关系: "linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)",
        班委工作: "linear-gradient(135deg, #ec4899 0%, #db2777 100%)",
    };
    return (
        colors[category] || "linear-gradient(135deg, #6b7280 0%, #4b5563 100%)"
    );
};

const getStatusText = (status: string) => {
    const statusMap: Record<string, string> = {
        pending: "待处理",
        "in-progress": "处理中",
        resolved: "已解决",
    };
    return statusMap[status] || status;
};

const viewFeedback = (id: number) => {
    router.push(`/admin/feedback/${id}`);
};

const resolveFeedback = async (id: number) => {
    try {
        const res: any = await updateFeedbackStatus(id, "resolved");
        if (res.code === 200) {
            const feedback = recentFeedbacks.value.find((f) => f.id === id);
            if (feedback) {
                feedback.status = "resolved";
            }
            stats.value.pendingFeedbacks = Math.max(0, stats.value.pendingFeedbacks - 1);
            stats.value.resolvedFeedbacks += 1;
            alert(`反馈 #${id} 已标记为已解决`);
        }
    } catch (e) {
        alert("操作失败");
    }
};

const handleLogout = async () => {
    try {
        await logout();
    } catch (e) {
        // ignore
    }
    localStorage.removeItem("token");
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("username");
    localStorage.removeItem("userEmail");
    localStorage.removeItem("loginTime");
    localStorage.removeItem("isAdmin");
    router.push("/login");
};

onMounted(async () => {
    try {
        const res: any = await getDashboardStats();
        if (res.code === 200) {
            stats.value = {
                totalFeedbacks: res.data.totalFeedbacks,
                activeUsers: res.data.activeUsers,
                pendingFeedbacks: res.data.pendingFeedbacks,
                resolvedFeedbacks: res.data.resolvedFeedbacks,
            };
            recentFeedbacks.value = (res.data.recentFeedbacks || []).map((fb: any) => ({
                id: fb.id,
                category: fb.category,
                content: fb.content?.substring(0, 40) + "...",
                time: fb.created_at,
                status: fb.status,
            }));
        }
    } catch (e) {
        console.error("加载统计数据失败", e);
    }
});
</script>

<style scoped>
.admin-page {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
    background: linear-gradient(135deg, #f0f4f8 0%, #e6edf5 100%);
    overflow-y: auto;
    padding: 20px;
}

/* 毛玻璃背景层 */
.glass-background {
    position: fixed;
    width: 100%;
    height: 100%;
    pointer-events: none;
    top: 0;
    left: 0;
    z-index: 0;
}

.bg-blur-1,
.bg-blur-2,
.bg-blur-3 {
    position: absolute;
    border-radius: 50%;
    background: rgba(200, 210, 230, 0.15);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.bg-blur-1 {
    width: 300px;
    height: 300px;
    top: -100px;
    right: -100px;
}

.bg-blur-2 {
    width: 200px;
    height: 200px;
    bottom: -50px;
    left: -50px;
}

.bg-blur-3 {
    width: 150px;
    height: 150px;
    top: 50%;
    left: 10%;
}

/* 主容器 */
.admin-container {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
}

/* 头部样式 */
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
    margin-bottom: 30px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.logo-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    background: rgba(124, 141, 181, 0.1);
    border-radius: 12px;
}

.app-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #4a5568;
    margin: 0;
    letter-spacing: -0.5px;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 16px;
}

.user-details {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.username {
    font-size: 1rem;
    font-weight: 600;
    color: #4a5568;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 8px;
    color: #4a5568;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* 欢迎横幅 */
.welcome-banner {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 40px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.05),
        0 1px 3px rgba(0, 0, 0, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
}

.banner-content {
    flex: 1;
}

.banner-content h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #4a5568;
    margin: 0 0 8px 0;
    text-align: left;
}

.banner-content p {
    font-size: 1rem;
    color: #718096;
    margin: 0 0 16px 0;
    text-align: left;
}

.banner-content p:last-child {
    margin: 0;
}

.banner-icon {
    opacity: 0.8;
    margin-left: 20px;
}

.icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #7c8db5 0%, #5a6b95 100%);
}

/* 统计卡片 */
.stats-section {
    margin-bottom: 40px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #4a5568;
    margin: 0 0 24px 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s ease;
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.04),
        0 1px 3px rgba(0, 0, 0, 0.04),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 12px 32px rgba(0, 0, 0, 0.08),
        0 2px 6px rgba(0, 0, 0, 0.06),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    border-color: rgba(156, 168, 204, 0.4);
}

.stat-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon svg,
.management-icon svg {
    display: block;
    margin: 0 auto;
}

.stat-info h4 {
    font-size: 2rem;
    font-weight: 700;
    color: #4a5568;
    margin: 0 0 4px 0;
    line-height: 1;
    text-align: center;
}

.stat-info p {
    font-size: 0.9rem;
    color: #718096;
    margin: 0;
    text-align: center;
}

.stat-trend {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 0.85rem;
    color: #718096;
    padding-top: 12px;
    border-top: 1px solid rgba(203, 213, 224, 0.3);
}

/* 管理功能卡片 */
.management-section {
    margin-bottom: 40px;
}

.management-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.management-card {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 16px;
    padding: 24px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.04),
        0 1px 3px rgba(0, 0, 0, 0.04),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
}

.management-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 12px 32px rgba(0, 0, 0, 0.08),
        0 2px 6px rgba(0, 0, 0, 0.06),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    border-color: rgba(156, 168, 204, 0.4);
}

.management-card-content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.management-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.management-info h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #4a5568;
    margin: 0 0 4px 0;
}

.management-info p {
    font-size: 0.9rem;
    color: #718096;
    margin: 0;
}

.management-action {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(203, 213, 224, 0.2);
    transition: all 0.3s ease;
    margin-left: 20px;
}

.management-card:hover .management-action {
    background: rgba(203, 213, 224, 0.4);
    transform: translateX(4px);
}

/* 最近反馈 */
.recent-section {
    margin-bottom: 40px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.view-all-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 8px;
    color: #4a5568;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-all-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.recent-feedbacks {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.feedback-item {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    box-shadow:
        0 4px 12px rgba(0, 0, 0, 0.04),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
}

.feedback-item:hover {
    transform: translateY(-2px);
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    border-color: rgba(156, 168, 204, 0.4);
}

.feedback-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.feedback-category {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: white;
    white-space: nowrap;
}

.feedback-time {
    font-size: 0.85rem;
    color: #718096;
}

.feedback-status {
    display: flex;
    align-items: center;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: #4a5568;
    background: rgba(203, 213, 224, 0.3);
}

.status-badge.pending {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.status-badge.in-progress {
    background: rgba(59, 130, 246, 0.1);
    color: #1d4ed8;
}

.status-badge.resolved {
    background: rgba(16, 185, 129, 0.1);
    color: #065f46;
}

.feedback-content p {
    font-size: 0.95rem;
    color: #4a5568;
    line-height: 1.5;
    margin: 0 0 16px 0;
}

.feedback-actions {
    display: flex;
    gap: 12px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-btn {
    background: rgba(156, 168, 204, 0.1);
    color: #4a5568;
}

.view-btn:hover {
    background: rgba(156, 168, 204, 0.2);
}

.resolve-btn {
    background: rgba(16, 185, 129, 0.1);
    color: #065f46;
}

.resolve-btn:hover {
    background: rgba(16, 185, 129, 0.2);
}

/* 页脚 */
.admin-footer {
    text-align: center;
    padding: 30px 0;
    border-top: 1px solid rgba(203, 213, 224, 0.4);
}

.footer-text {
    font-size: 0.9rem;
    color: #718096;
    margin: 0 0 16px 0;
}

.footer-links {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.footer-link {
    font-size: 0.9rem;
    color: #4a5568;
    text-decoration: none;
    transition: all 0.3s ease;
}

.footer-link:hover {
    color: #7c8db5;
    text-decoration: underline;
}

.footer-separator {
    font-size: 0.9rem;
    color: #cbd5e0;
    user-select: none;
}

/* 响应式设计 */
@media (max-width: 768px) {
    .admin-page {
        padding: 16px;
    }

    .admin-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }

    .header-right {
        width: 100%;
        justify-content: space-between;
    }

    .welcome-banner {
        flex-direction: column;
        gap: 20px;
        padding: 24px;
    }

    .banner-icon {
        margin-left: 0;
        margin-top: 10px;
    }

    .stats-grid,
    .management-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .feedback-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .feedback-actions {
        flex-direction: column;
    }

    .action-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .app-title {
        font-size: 1.4rem;
    }

    .stats-grid,
    .management-grid {
        grid-template-columns: 1fr;
    }

    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .user-details {
        align-items: flex-start;
    }

    .logout-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (prefers-color-scheme: dark) {
    .admin-page {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    }

    .app-title,
    .username,
    .section-title,
    .banner-content h2,
    .stat-info h4,
    .management-info h4,
    .feedback-content p {
        color: #f7fafc;
    }

    .banner-content p,
    .stat-info p,
    .management-info p,
    .footer-text,
    .feedback-time {
        color: #a0aec0;
    }

    .welcome-banner,
    .stat-card,
    .management-card,
    .feedback-item {
        background: rgba(45, 55, 72, 0.75);
        border-color: rgba(74, 85, 104, 0.3);
    }

    .logout-btn,
    .view-all-btn {
        background: rgba(74, 85, 104, 0.6);
        border-color: rgba(113, 128, 150, 0.4);
        color: #e2e8f0;
    }

    .logout-btn:hover,
    .view-all-btn:hover {
        background: rgba(90, 103, 125, 0.8);
        border-color: #718096;
    }

    .action-btn {
        background: rgba(74, 85, 104, 0.4);
        color: #e2e8f0;
    }

    .action-btn:hover {
        background: rgba(90, 103, 125, 0.6);
    }
}
</style>
