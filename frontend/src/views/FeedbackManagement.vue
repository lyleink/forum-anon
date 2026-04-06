<template>
    <div class="feedback-management-page">
        <!-- 毛玻璃背景层 -->
        <div class="glass-background">
            <div class="bg-blur-1"></div>
            <div class="bg-blur-2"></div>
            <div class="bg-blur-3"></div>
        </div>

        <!-- 主内容容器 -->
        <div class="feedback-management-container">
            <!-- 头部 -->
            <header class="feedback-management-header">
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
                    <h1 class="app-title">反馈管理</h1>
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

            <!-- 操作栏 -->
            <div class="action-bar">
                <div class="search-filter-section">
                    <div class="search-box">
                        <Icon icon-class="search" size="sm" fill="#718096" />
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="搜索反馈内容..."
                            @input="onSearch"
                        />
                    </div>
                    <div class="filter-group">
                        <select v-model="selectedCategory" @change="onFilter">
                            <option value="">所有分类</option>
                            <option
                                v-for="category in categories"
                                :key="category.value"
                                :value="category.value"
                            >
                                {{ category.label }}
                            </option>
                        </select>
                        <select v-model="selectedStatus" @change="onFilter">
                            <option value="">所有状态</option>
                            <option value="pending">待处理</option>
                            <option value="in-progress">处理中</option>
                            <option value="resolved">已解决</option>
                        </select>
                        <button class="reset-btn" @click="resetFilters">
                            <Icon icon-class="refresh-cw" size="sm" fill="currentColor" />
                            重置筛选
                        </button>
                    </div>
                </div>
                <div class="action-buttons">
                    <button class="export-btn" @click="exportData">
                        <Icon icon-class="download" size="sm" fill="currentColor" />
                        导出数据
                    </button>
                    <button class="refresh-btn" @click="refreshData">
                        <Icon icon-class="refresh-cw" size="sm" fill="currentColor" />
                        刷新
                    </button>
                </div>
            </div>

            <!-- 反馈列表 -->
            <div class="feedback-list-section">
                <div class="section-header">
                    <h3 class="section-title">
                        反馈列表 ({{ filteredFeedbacks.length }})
                        <span class="status-summary">
                            <span class="status-count pending">
                                {{ getStatusCount('pending') }} 待处理
                            </span>
                            <span class="status-count in-progress">
                                {{ getStatusCount('in-progress') }} 处理中
                            </span>
                            <span class="status-count resolved">
                                {{ getStatusCount('resolved') }} 已解决
                            </span>
                        </span>
                    </h3>
                    <div class="pagination-info" v-if="totalPages > 1">
                        第 {{ currentPage }} / {{ totalPages }} 页
                        (共 {{ filteredFeedbacks.length }} 条)
                    </div>
                </div>

                <!-- 反馈列表 -->
                <div class="feedback-list">
                    <div
                        v-for="feedback in paginatedFeedbacks"
                        :key="feedback.id"
                        class="feedback-item"
                        :class="feedback.status"
                    >
                        <div class="feedback-header">
                            <div class="feedback-category">
                                <div
                                    class="category-badge"
                                    :style="{
                                        background: getCategoryColor(feedback.category),
                                    }"
                                >
                                    {{ feedback.category }}
                                </div>
                                <span class="feedback-time">{{ feedback.time }}</span>
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
                            <p class="feedback-text">{{ feedback.content }}</p>
                            <div class="feedback-meta" v-if="feedback.author">
                                <Icon icon-class="user" size="xs" fill="#718096" />
                                <span class="meta-text">提交者: {{ feedback.author }}</span>
                            </div>
                        </div>
                        <div class="feedback-actions">
                            <button
                                class="action-btn view-btn"
                                @click="viewFeedbackDetail(feedback)"
                            >
                                <Icon
                                    icon-class="file-text"
                                    size="sm"
                                    fill="currentColor"
                                />
                                查看详情
                            </button>
                            <button
                                v-if="feedback.status === 'pending' || feedback.status === 'in-progress'"
                                class="action-btn resolve-btn"
                                @click="resolveFeedback(feedback.id)"
                            >
                                <Icon
                                    icon-class="check-circle"
                                    size="sm"
                                    fill="currentColor"
                                />
                                标记为已解决
                            </button>
                            <button
                                class="action-btn delete-btn"
                                @click="deleteFeedback(feedback.id)"
                                title="删除"
                            >
                                <Icon
                                    icon-class="trash-2"
                                    size="sm"
                                    fill="currentColor"
                                />
                                删除
                            </button>
                        </div>
                    </div>

                    <!-- 无数据提示 -->
                    <div v-if="filteredFeedbacks.length === 0" class="no-data">
                        <Icon icon-class="inbox" size="xl" fill="#9CA3AF" />
                        <p>暂无反馈数据</p>
                        <p class="no-data-hint">尝试调整筛选条件或刷新页面</p>
                    </div>
                </div>

                <!-- 分页 -->
                <div class="pagination" v-if="totalPages > 1">
                    <button
                        class="pagination-btn prev"
                        :disabled="currentPage === 1"
                        @click="goToPage(currentPage - 1)"
                    >
                        <Icon icon-class="chevron-left" size="sm" fill="currentColor" />
                        上一页
                    </button>
                    <div class="page-numbers">
                        <button
                            v-for="page in pageNumbers"
                            :key="page"
                            class="page-number"
                            :class="{ active: page === currentPage }"
                            @click="goToPage(page)"
                        >
                            {{ page }}
                        </button>
                        <span v-if="hasMorePages" class="page-ellipsis">...</span>
                    </div>
                    <button
                        class="pagination-btn next"
                        :disabled="currentPage === totalPages"
                        @click="goToPage(currentPage + 1)"
                    >
                        下一页
                        <Icon icon-class="chevron-right" size="sm" fill="currentColor" />
                    </button>
                </div>
            </div>

            <!-- 页脚 -->
            <footer class="feedback-management-footer">
                <p class="footer-text">
                    © {{ currentYear }} 班级匿名反馈系统 - 反馈管理. 版本 1.0.0
                </p>
                <div class="footer-links">
                    <router-link to="/admin" class="footer-link"
                        >返回管理面板</router-link
                    >
                    <span class="footer-separator">•</span>
                    <a href="#" class="footer-link" @click.prevent="exportData"
                        >导出数据</a
                    >
                    <span class="footer-separator">•</span>
                    <router-link to="/dashboard" class="footer-link"
                        >用户面板</router-link
                    >
                </div>
            </footer>

            <!-- 反馈详情模态框 -->
            <div v-if="showDetailModal" class="modal-overlay" @click.self="closeDetailModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>反馈详情</h3>
                        <button class="modal-close" @click="closeDetailModal">
                            <Icon icon-class="x" size="sm" fill="currentColor" />
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="selectedFeedback" class="feedback-detail">
                            <div class="detail-row">
                                <span class="detail-label">ID:</span>
                                <span class="detail-value">#{{ selectedFeedback.id }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">分类:</span>
                                <span class="detail-value category-badge" :style="{
                                    background: getCategoryColor(selectedFeedback.category),
                                }">
                                    {{ selectedFeedback.category }}
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">状态:</span>
                                <span class="detail-value status-badge" :class="selectedFeedback.status">
                                    {{ getStatusText(selectedFeedback.status) }}
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">提交时间:</span>
                                <span class="detail-value">{{ selectedFeedback.time }}</span>
                            </div>
                            <div class="detail-row" v-if="selectedFeedback.author">
                                <span class="detail-label">提交者:</span>
                                <span class="detail-value">{{ selectedFeedback.author }}</span>
                            </div>
                            <div class="detail-content">
                                <h4>反馈内容:</h4>
                                <div class="content-box">
                                    {{ selectedFeedback.content }}
                                </div>
                            </div>
                            <div class="detail-notes" v-if="selectedFeedback.notes">
                                <h4>处理备注:</h4>
                                <div class="notes-box">
                                    {{ selectedFeedback.notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="modal-btn secondary" @click="closeDetailModal">
                            关闭
                        </button>
                        <button
                            v-if="selectedFeedback && (selectedFeedback.status === 'pending' || selectedFeedback.status === 'in-progress')"
                            class="modal-btn primary"
                            @click="resolveFeedback(selectedFeedback.id)"
                        >
                            标记为已解决
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import Icon from "../components/Icon.vue";
import {
    getFeedbacks,
    getFeedback,
    updateFeedbackStatus,
    deleteFeedback as apiDeleteFeedback,
    exportFeedbacks,
} from "../api/feedbacks";
import { logout } from "../api/auth";

const router = useRouter();

const username = computed(() => {
    return localStorage.getItem("username") || "管理员";
});

const currentYear = computed(() => new Date().getFullYear());

const categories = ref<any[]>([]);

const searchQuery = ref("");
const selectedCategory = ref("");
const selectedStatus = ref("");

const currentPage = ref(1);
const itemsPerPage = 10;

const showDetailModal = ref(false);
const selectedFeedback = ref<any>(null);

const feedbacks = ref<any[]>([]);
const totalFeedbacks = ref(0);

const filteredFeedbacks = computed(() => {
    return feedbacks.value;
});

const totalPages = computed(() => {
    return Math.ceil(totalFeedbacks.value / itemsPerPage);
});

const paginatedFeedbacks = computed(() => {
    return feedbacks.value;
});

const pageNumbers = computed(() => {
    const pages = [];
    const maxPagesToShow = 5;

    let startPage = Math.max(1, currentPage.value - 2);
    let endPage = Math.min(totalPages.value, startPage + maxPagesToShow - 1);

    if (endPage - startPage + 1 < maxPagesToShow) {
        startPage = Math.max(1, endPage - maxPagesToShow + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }

    return pages;
});

const hasMorePages = computed(() => {
    return pageNumbers.value[pageNumbers.value.length - 1] < totalPages.value;
});

const getStatusCount = (status: string) => {
    return feedbacks.value.filter((f) => f.status === status).length;
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

const loadFeedbacks = async () => {
    try {
        const res: any = await getFeedbacks({
            page: currentPage.value,
            pageSize: itemsPerPage,
            keyword: searchQuery.value || undefined,
            category: selectedCategory.value || undefined,
            status: selectedStatus.value || undefined,
        });
        if (res.code === 200) {
            feedbacks.value = (res.data.list || []).map((fb: any) => ({
                ...fb,
                time: fb.created_at,
            }));
            totalFeedbacks.value = res.data.total || 0;
        }
    } catch (e) {
        console.error("加载反馈列表失败", e);
    }
};

const onSearch = () => {
    currentPage.value = 1;
    loadFeedbacks();
};

const onFilter = () => {
    currentPage.value = 1;
    loadFeedbacks();
};

const resetFilters = () => {
    searchQuery.value = "";
    selectedCategory.value = "";
    selectedStatus.value = "";
    currentPage.value = 1;
    loadFeedbacks();
};

const goToPage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        loadFeedbacks();
        window.scrollTo({ top: 0, behavior: "smooth" });
    }
};

const viewFeedbackDetail = async (feedback: any) => {
    try {
        const res: any = await getFeedback(feedback.id);
        if (res.code === 200) {
            selectedFeedback.value = {
                ...res.data,
                time: res.data.created_at,
            };
            showDetailModal.value = true;
        }
    } catch (e) {
        alert("加载反馈详情失败");
    }
};

const closeDetailModal = () => {
    showDetailModal.value = false;
    selectedFeedback.value = null;
};

const resolveFeedback = async (id: number) => {
    try {
        const res: any = await updateFeedbackStatus(id, "resolved");
        if (res.code === 200) {
            const feedback = feedbacks.value.find((f) => f.id === id);
            if (feedback) {
                feedback.status = "resolved";
            }
            if (selectedFeedback.value && selectedFeedback.value.id === id) {
                selectedFeedback.value.status = "resolved";
            }
            alert(`反馈 #${id} 已标记为已解决`);
        } else {
            alert(res.msg || "操作失败");
        }
    } catch (e: any) {
        alert(e?.msg || "操作失败");
    }
};

const deleteFeedback = async (id: number) => {
    if (confirm(`确定要删除反馈 #${id} 吗？此操作不可撤销。`)) {
        try {
            const res: any = await apiDeleteFeedback(id);
            if (res.code === 200) {
                alert(`反馈 #${id} 已删除`);
                if (selectedFeedback.value && selectedFeedback.value.id === id) {
                    closeDetailModal();
                }
                loadFeedbacks();
            } else {
                alert(res.msg || "删除失败");
            }
        } catch (e: any) {
            alert(e?.msg || "删除失败");
        }
    }
};

const refreshData = () => {
    currentPage.value = 1;
    loadFeedbacks();
};

const exportData = async () => {
    try {
        const blob: any = await exportFeedbacks();
        const url = window.URL.createObjectURL(new Blob([blob]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", `feedbacks_${Date.now()}.csv`);
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (e) {
        alert("导出失败");
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
    loadFeedbacks();
    try {
        const { getCategories } = await import("../api/categories");
        const res: any = await getCategories();
        if (res.code === 200) {
            categories.value = res.data;
        }
    } catch (e) {
        // ignore
    }
});
</script>

<style scoped>
/* 使用 Dashboard 的样式 */
.feedback-management-page {
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
.feedback-management-container {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
}

/* 头部样式 - 匹配 Dashboard */
.feedback-management-header {
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
    font-size: 14px;
    font-weight: 600;
    color: #2d3748;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.logout-btn:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
    color: #2d3748;
}

/* 操作栏样式 - 匹配 Dashboard */
.action-bar {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.05),
        0 1px 3px rgba(0, 0, 0, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
}

.search-filter-section {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    flex: 1;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 12px;
    min-width: 300px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.search-box:focus-within {
    border-color: #9ca8cc;
    box-shadow: 0 0 0 3px rgba(156, 168, 204, 0.15);
    background: rgba(255, 255, 255, 0.9);
}

.search-box input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 0.95rem;
    color: #4a5568;
    background: transparent;
}

.search-box input::placeholder {
    color: #a0aec0;
    opacity: 0.8;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.filter-group select {
    padding: 10px 16px;
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.8);
    color: #4a5568;
    font-size: 0.95rem;
    cursor: pointer;
    outline: none;
    min-width: 120px;
    transition: all 0.3s ease;
}

.reset-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 8px;
    color: #4a5568;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.reset-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-1px);
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 12px;
}

.export-btn,
.refresh-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 8px;
    color: #4a5568;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.export-btn:hover,
.refresh-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-1px);
}

.export-btn {
    background: linear-gradient(135deg, #667eea 0%, #5a67d8 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(124, 141, 181, 0.2);
}

.export-btn:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #4f5cbf 100%);
    border-color: #5a67d8;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(124, 141, 181, 0.3);
}

/* 反馈列表区域 - 匹配 Dashboard */
.feedback-list-section {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    padding: 40px;
    box-shadow:
        0 10px 30px rgba(0, 0, 0, 0.05),
        0 1px 3px rgba(0, 0, 0, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
    margin-bottom: 40px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 16px;
}

.status-summary {
    display: flex;
    gap: 12px;
    font-size: 14px;
}

.status-count {
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 500;
}

.status-count.pending {
    background: #fff7ed;
    color: #c2410c;
}

.status-count.in-progress {
    background: #eff6ff;
    color: #1d4ed8;
}

.status-count.resolved {
    background: #f0fdf4;
    color: #15803d;
}

.pagination-info {
    font-size: 14px;
    color: #718096;
}

/* 反馈列表 */
.feedback-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.feedback-item {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 16px;
    padding: 28px;
    transition: all 0.3s ease;
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.04),
        0 1px 3px rgba(0, 0, 0, 0.04),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
}

.feedback-item:hover {
    transform: translateY(-4px);
    box-shadow:
        0 12px 32px rgba(0, 0, 0, 0.08),
        0 2px 6px rgba(0, 0, 0, 0.06),
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
    color: white;
    font-size: 12px;
    font-weight: 500;
}

.feedback-time {
    font-size: 12px;
    color: #718096;
}

.feedback-status {
    display: flex;
    align-items: center;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.pending {
    background: #fff7ed;
    color: #c2410c;
}

.status-badge.in-progress {
    background: #eff6ff;
    color: #1d4ed8;
}

.status-badge.resolved {
    background: #f0fdf4;
    color: #15803d;
}

.feedback-content {
    margin-bottom: 16px;
}

.feedback-text {
    margin: 0 0 12px 0;
    color: #4a5568;
    line-height: 1.6;
}

.feedback-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #718096;
}

.feedback-actions {
    display: flex;
    gap: 12px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 12px 20px;
    border: none;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.8);
    color: #4a5568;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.action-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.view-btn:hover {
    background: linear-gradient(135deg, #667eea 0%, #5a67d8 100%);
    color: white;
}

.process-btn:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.resolve-btn:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.delete-btn:hover {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

/* 无数据提示 */
.no-data {
    text-align: center;
    padding: 60px 20px;
    color: #9CA3AF;
}

.no-data-hint {
    font-size: 14px;
    margin-top: 8px;
    color: #a0aec0;
}

/* 分页 */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.pagination-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    background: white;
    color: #4a5568;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pagination-btn:hover:not(:disabled) {
    background: #f7fafc;
    border-color: #cbd5e0;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-numbers {
    display: flex;
    align-items: center;
    gap: 4px;
}

.page-number {
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    background: white;
    color: #4a5568;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 40px;
}

.page-number:hover {
    background: #f7fafc;
}

.page-number.active {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

.page-ellipsis {
    padding: 8px;
    color: #a0aec0;
}

/* 模态框 */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
}

.modal-close {
    padding: 8px;
    border: none;
    background: none;
    color: #718096;
    cursor: pointer;
    border-radius: 6px;
}

.modal-close:hover {
    background: #f7fafc;
}

.modal-body {
    padding: 24px;
}

.feedback-detail {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.detail-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.detail-label {
    font-weight: 600;
    color: #4a5568;
    min-width: 80px;
}

.detail-value {
    color: #2d3748;
}

.detail-content,
.detail-notes {
    margin-top: 16px;
}

.detail-content h4,
.detail-notes h4 {
    margin: 0 0 12px 0;
    color: #4a5568;
    font-size: 16px;
}

.content-box,
.notes-box {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    line-height: 1.6;
}

.notes-box {
    background: #f0fdf4;
    border-color: #bbf7d0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid #e2e8f0;
}

.modal-btn {
    padding: 10px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.modal-btn.secondary {
    background: white;
    border: 1px solid #e2e8f0;
    color: #4a5568;
}

.modal-btn.secondary:hover {
    background: #f7fafc;
}

.modal-btn.primary {
    background: #667eea;
    border: 1px solid #667eea;
    color: white;
}

.modal-btn.primary:hover {
    background: #5a67d8;
}

/* 页脚 - 匹配 Dashboard */
.feedback-management-footer {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid rgba(203, 213, 224, 0.4);
    text-align: center;
}

.footer-text {
    font-size: 0.875rem;
    color: #718096;
    margin-bottom: 12px;
}

.footer-links {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.footer-link {
    font-size: 14px;
    color: #667eea;
    text-decoration: none;
    transition: color 0.2s ease;
}

.footer-link:hover {
    color: #5a67d8;
    text-decoration: underline;
}

.footer-separator {
    color: #cbd5e0;
}

/* 响应式设计 */
@media (max-width: 768px) {
    .admin-container {
        padding: 16px;
    }

    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .search-filter-section {
        flex-direction: column;
        align-items: stretch;
    }

    .search-box {
        min-width: auto;
    }

    .filter-group {
        flex-wrap: wrap;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .section-title {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .status-summary {
        flex-wrap: wrap;
    }

    .feedback-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .feedback-actions {
        flex-wrap: wrap;
    }

    .pagination {
        flex-wrap: wrap;
    }
}

@media (max-width: 480px) {
    .app-title {
        font-size: 18px;
    }

    .user-info {
        flex-direction: column;
        align-items: flex-end;
    }

    .feedback-actions .action-btn {
        flex: 1;
        justify-content: center;
    }
}

/* 暗色模式支持 */
@media (prefers-color-scheme: dark) {
    .admin-page {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    }

    .app-title,
    .username,
    .section-title,
    .modal-header h3,
    .detail-label {
        color: #e2e8f0;
    }

    .footer-text,
    .feedback-time,
    .pagination-info {
        color: #a0aec0;
    }

    .feedback-text,
    .detail-value {
        color: #cbd5e0;
    }

    .feedback-item,
    .action-bar,
    .feedback-list-section,
    .modal-content {
        background: rgba(26, 32, 44, 0.9);
        border-color: #4a5568;
    }

    .logout-btn,
    .reset-btn,
    .export-btn,
    .refresh-btn,
    .action-btn,
    .pagination-btn,
    .page-number {
        background: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }

    .logout-btn:hover,
    .reset-btn:hover,
    .export-btn:hover,
    .refresh-btn:hover,
    .action-btn:hover,
    .pagination-btn:hover:not(:disabled),
    .page-number:hover {
        background: #4a5568;
    }

    .export-btn {
        background: #667eea;
        border-color: #667eea;
    }

    .export-btn:hover {
        background: #5a67d8;
    }

    .page-number.active {
        background: #667eea;
        border-color: #667eea;
    }

    .modal-btn.secondary {
        background: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }

    .modal-btn.secondary:hover {
        background: #4a5568;
    }

    .content-box {
        background: #2d3748;
        border-color: #4a5568;
    }

    .notes-box {
        background: rgba(16, 185, 129, 0.1);
        border-color: #10b981;
    }

    .no-data {
        color: #a0aec0;
    }
}
</style>
