<template>
    <div class="analytics-page">
        <!-- 毛玻璃背景层 -->
        <div class="glass-background">
            <div class="bg-blur-1"></div>
            <div class="bg-blur-2"></div>
            <div class="bg-blur-3"></div>
        </div>

        <!-- 主内容容器 -->
        <div class="analytics-container">
            <!-- 头部 -->
            <header class="analytics-header">
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
                    <h1 class="app-title">数据统计</h1>
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

            <!-- 统计概览卡片 -->
            <div class="stats-section">
                <h3 class="section-title">数据概览</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-content">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                                <Icon icon-class="message-square" size="lg" fill="white" />
                            </div>
                            <div class="stat-info">
                                <h4>{{ dashboardStats.totalFeedbacks }}</h4>
                                <p>总反馈数</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%)">
                                <Icon icon-class="bell" size="lg" fill="white" />
                            </div>
                            <div class="stat-info">
                                <h4>{{ dashboardStats.pendingFeedbacks }}</h4>
                                <p>待处理反馈</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%)">
                                <Icon icon-class="play-circle" size="lg" fill="white" />
                            </div>
                            <div class="stat-info">
                                <h4>{{ dashboardStats.inProgressFeedbacks }}</h4>
                                <p>处理中反馈</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-content">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)">
                                <Icon icon-class="check-circle" size="lg" fill="white" />
                            </div>
                            <div class="stat-info">
                                <h4>{{ dashboardStats.resolvedFeedbacks }}</h4>
                                <p>已解决反馈</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 分类统计 -->
            <div class="chart-section">
                <div class="chart-card">
                    <h3 class="chart-title">
                        <Icon icon-class="pie-chart" size="sm" fill="currentColor" />
                        反馈分类统计
                    </h3>
                    <div class="category-bars">
                        <div
                            v-for="item in categoryBars"
                            :key="item.name"
                            class="category-bar-item"
                        >
                            <div class="bar-header">
                                <div class="bar-label">
                                    <div class="bar-dot" :style="{ background: item.color }"></div>
                                    <span>{{ item.name }}</span>
                                </div>
                                <span class="bar-count">{{ item.count }}</span>
                            </div>
                            <div class="bar-track">
                                <div
                                    class="bar-fill"
                                    :style="{ width: item.percent + '%', background: item.color }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 状态分布 -->
                <div class="chart-card">
                    <h3 class="chart-title">
                        <Icon icon-class="activity" size="sm" fill="currentColor" />
                        反馈状态分布
                    </h3>
                    <div class="status-rings">
                        <div
                            v-for="ring in statusRings"
                            :key="ring.label"
                            class="status-ring-item"
                        >
                            <div class="ring-visual">
                                <svg viewBox="0 0 100 100" class="ring-svg">
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="8" />
                                    <circle
                                        cx="50" cy="50" r="40" fill="none"
                                        :stroke="ring.color" stroke-width="8"
                                        :stroke-dasharray="circumference"
                                        :stroke-dashoffset="circumference - (circumference * ring.percent / 100)"
                                        transform="rotate(-90 50 50)"
                                        stroke-linecap="round"
                                    />
                                    <text x="50" y="50" text-anchor="middle" dominant-baseline="central" class="ring-text" :fill="ring.color">
                                        {{ ring.percent }}%
                                    </text>
                                </svg>
                            </div>
                            <div class="ring-info">
                                <span class="ring-label" :style="{ color: ring.color }">{{ ring.label }}</span>
                                <span class="ring-count">{{ ring.count }} 条</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 最近反馈 -->
            <div class="recent-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <Icon icon-class="clock" size="sm" fill="currentColor" />
                        最近反馈
                    </h3>
                    <router-link to="/admin/feedback" class="view-all-btn">
                        查看全部
                        <Icon icon-class="arrow-right" size="sm" fill="currentColor" />
                    </router-link>
                </div>
                <div class="recent-feedbacks">
                    <div
                        v-for="feedback in recentFeedbacks"
                        :key="feedback.id"
                        class="feedback-item"
                    >
                        <div class="feedback-header">
                            <div class="feedback-category">
                                <div class="category-badge" :style="{ background: getCategoryColor(feedback.category) }">
                                    {{ feedback.category }}
                                </div>
                                <span class="feedback-time">{{ feedback.created_at }}</span>
                            </div>
                            <div class="feedback-status">
                                <div class="status-badge" :class="feedback.status">
                                    {{ getStatusText(feedback.status) }}
                                </div>
                            </div>
                        </div>
                        <div class="feedback-content">
                            <p>{{ feedback.content?.substring(0, 60) }}{{ feedback.content?.length > 60 ? '...' : '' }}</p>
                        </div>
                        <div class="feedback-actions">
                            <button class="action-btn view-btn" @click.stop="viewFeedback(feedback.id)">
                                <Icon icon-class="file-text" size="sm" fill="currentColor" />
                                查看详情
                            </button>
                        </div>
                    </div>
                    <div v-if="recentFeedbacks.length === 0" class="no-data">
                        <Icon icon-class="inbox" size="xl" fill="#9CA3AF" />
                        <p>暂无反馈数据</p>
                    </div>
                </div>
            </div>

            <!-- 页脚 -->
            <footer class="analytics-footer">
                <p class="footer-text">
                    © {{ currentYear }} 班级匿名反馈系统 - 数据统计. 版本 1.0.0
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
import { getDashboardStats } from "../api/stats";
import { logout } from "../api/auth";

const router = useRouter();

const username = computed(() => localStorage.getItem("username") || "管理员");
const currentYear = computed(() => new Date().getFullYear());

const dashboardStats = ref({
    totalFeedbacks: 0,
    activeUsers: 0,
    pendingFeedbacks: 0,
    inProgressFeedbacks: 0,
    resolvedFeedbacks: 0,
});

const categoryStats = ref<any[]>([]);
const recentFeedbacks = ref<any[]>([]);

const categoryColors: Record<string, string> = {
    "班级纪律": "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
    "学习建议": "linear-gradient(135deg, #10b981 0%, #059669 100%)",
    "班级活动": "linear-gradient(135deg, #f59e0b 0%, #d97706 100%)",
    "同学关系": "linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)",
    "班委工作": "linear-gradient(135deg, #ec4899 0%, #db2777 100%)",
    "其他班级问题": "linear-gradient(135deg, #6b7280 0%, #4b5563 100%)",
};

const categoryBars = computed(() => {
    const maxCount = Math.max(...categoryStats.value.map((c) => c.count || 0), 1);
    return categoryStats.value.map((item) => ({
        name: item.category,
        count: item.count || 0,
        percent: Math.round(((item.count || 0) / maxCount) * 100),
        color: categoryColors[item.category] || "linear-gradient(135deg, #6b7280 0%, #4b5563 100%)",
    }));
});

const totalForPercent = computed(() => {
    return (
        (dashboardStats.value.pendingFeedbacks || 0) +
        (dashboardStats.value.inProgressFeedbacks || 0) +
        (dashboardStats.value.resolvedFeedbacks || 0)
    );
});

const circumference = 2 * Math.PI * 40;

const statusRings = computed(() => {
    const total = totalForPercent.value || 1;
    const statuses = [
        {
            label: "待处理",
            count: dashboardStats.value.pendingFeedbacks,
            color: "#f59e0b",
        },
        {
            label: "处理中",
            count: dashboardStats.value.inProgressFeedbacks,
            color: "#10b981",
        },
        {
            label: "已解决",
            count: dashboardStats.value.resolvedFeedbacks,
            color: "#8b5cf6",
        },
    ];
    return statuses.map((s) => ({
        ...s,
        percent: Math.round((s.count / total) * 100),
    }));
});

const getStatusText = (status: string) => {
    const statusMap: Record<string, string> = {
        pending: "待处理",
        "in-progress": "处理中",
        resolved: "已解决",
    };
    return statusMap[status] || status;
};

const getCategoryColor = (category: string) => {
    return (
        categoryColors[category] || "linear-gradient(135deg, #6b7280 0%, #4b5563 100%)"
    );
};

const viewFeedback = (id: number) => {
    router.push(`/admin/feedback/${id}`);
};

const loadStats = async () => {
    try {
        const res: any = await getDashboardStats();
        if (res.code === 200) {
            const d = res.data;
            dashboardStats.value = {
                totalFeedbacks: d.totalFeedbacks || 0,
                activeUsers: d.activeUsers || 0,
                pendingFeedbacks: d.pendingFeedbacks || 0,
                inProgressFeedbacks: d.statusStats?.["in-progress"] || 0,
                resolvedFeedbacks: d.resolvedFeedbacks || 0,
            };
            categoryStats.value = d.categoryStats || [];
            recentFeedbacks.value = d.recentFeedbacks || [];
        }
    } catch (e) {
        console.error("加载统计数据失败", e);
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
    loadStats();
});
</script>

<style scoped>
.analytics-page {
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

.analytics-container {
    position: relative; z-index: 1;
    max-width: 1200px; margin: 0 auto;
}

/* Header */
.analytics-header {
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

/* Stats Section */
.stats-section { margin-bottom: 30px; }
.section-title {
    font-size: 1.3rem; font-weight: 700; color: #4a5568;
    margin: 0 0 20px 0; display: flex; align-items: center; gap: 10px;
}
.stats-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}
.stat-card {
    background: rgba(255,255,255,0.7); border-radius: 16px;
    border: 1px solid rgba(203,213,224,0.5); padding: 24px;
    backdrop-filter: blur(10px); transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.stat-content { display: flex; align-items: center; gap: 16px; }
.stat-icon {
    width: 56px; height: 56px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.stat-info h4 {
    font-size: 1.8rem; font-weight: 700; color: #2d3748; margin: 0; line-height: 1;
}
.stat-info p { font-size: 0.85rem; color: #718096; margin: 4px 0 0; }

/* Chart Section */
.chart-section {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px; margin-bottom: 30px;
}
.chart-card {
    background: rgba(255,255,255,0.7); border-radius: 16px;
    border: 1px solid rgba(203,213,224,0.5); padding: 24px;
    backdrop-filter: blur(10px);
}
.chart-title {
    font-size: 1.1rem; font-weight: 700; color: #4a5568;
    margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;
}

/* Category Bars */
.category-bar-item { margin-bottom: 16px; }
.category-bar-item:last-child { margin-bottom: 0; }
.bar-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 6px;
}
.bar-label { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: #4a5568; }
.bar-dot { width: 10px; height: 10px; border-radius: 50%; }
.bar-count { font-weight: 600; color: #4a5568; font-size: 0.9rem; }
.bar-track {
    width: 100%; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;
}
.bar-fill { height: 100%; border-radius: 4px; transition: width 0.6s ease; }

/* Status Rings */
.status-rings { display: flex; gap: 24px; flex-wrap: wrap; justify-content: center; }
.status-ring-item {
    display: flex; flex-direction: column; align-items: center; gap: 10px;
}
.ring-svg { width: 100px; height: 100px; }
.ring-text { font-size: 16px; font-weight: 700; }
.ring-info { display: flex; flex-direction: column; align-items: center; }
.ring-label { font-size: 0.9rem; font-weight: 600; }
.ring-count { font-size: 0.8rem; color: #718096; }

/* Recent Feedback */
.recent-section { margin-bottom: 30px; }
.section-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 16px;
}
.section-header .section-title { margin-bottom: 0; }
.view-all-btn {
    display: flex; align-items: center; gap: 4px;
    background: none; border: none; color: #667eea;
    font-size: 0.9rem; cursor: pointer; font-weight: 500;
    text-decoration: none;
}
.view-all-btn:hover { color: #764ba2; }

.recent-feedbacks {
    display: flex; flex-direction: column; gap: 12px;
}
.feedback-item {
    background: rgba(255,255,255,0.7); border-radius: 12px;
    border: 1px solid rgba(203,213,224,0.5); padding: 16px;
    backdrop-filter: blur(10px); transition: transform 0.2s;
}
.feedback-item:hover { transform: translateY(-2px); }
.feedback-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 10px;
}
.feedback-category { display: flex; align-items: center; gap: 10px; }
.category-badge {
    padding: 3px 10px; border-radius: 12px;
    color: white; font-size: 0.75rem; font-weight: 600;
}
.feedback-time { font-size: 0.8rem; color: #a0aec0; }
.feedback-status .status-badge {
    padding: 3px 10px; border-radius: 12px;
    font-size: 0.75rem; font-weight: 600; color: white;
}
.status-badge.pending { background: #f59e0b; }
.status-badge.in-progress { background: #10b981; }
.status-badge.resolved { background: #8b5cf6; }
.feedback-content p {
    font-size: 0.9rem; color: #4a5568; margin: 0;
    line-height: 1.5;
}
.feedback-actions { display: flex; gap: 8px; margin-top: 10px; }
.action-btn {
    display: flex; align-items: center; gap: 4px;
    padding: 6px 12px; border-radius: 8px;
    font-size: 0.8rem; font-weight: 500; cursor: pointer;
    border: 1px solid rgba(203,213,224,0.5); background: rgba(255,255,255,0.5);
    color: #4a5568; transition: all 0.2s;
}
.view-btn:hover { border-color: #667eea; color: #667eea; }

.no-data {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 40px; color: #9CA3AF; gap: 10px;
}
.no-data p { margin: 0; }

/* Footer */
.analytics-footer {
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
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .chart-section { grid-template-columns: 1fr; }
    .status-rings { gap: 16px; }
}
@media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr; }
    .analytics-header { flex-direction: column; gap: 16px; }
}
</style>
