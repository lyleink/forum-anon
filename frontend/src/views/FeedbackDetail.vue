<template>
    <div class="feedback-detail-page">
        <!-- 毛玻璃背景层 -->
        <div class="glass-background">
            <div class="bg-blur-1"></div>
            <div class="bg-blur-2"></div>
            <div class="bg-blur-3"></div>
        </div>

        <!-- 主内容容器 -->
        <div class="feedback-detail-container">
            <!-- 头部 -->
            <header class="feedback-detail-header">
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
                    <h1 class="app-title">反馈详情</h1>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-details">
                            <span class="username">{{ username }}</span>
                            <span class="user-email">{{ email }}</span>
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

            <!-- 导航和操作栏 -->
            <div class="action-bar">
                <div class="nav-section">
                    <button class="back-btn" @click="goBack">
                        <Icon
                            icon-class="arrow-left"
                            size="sm"
                            fill="currentColor"
                        />
                        返回反馈列表
                    </button>
                    <div class="feedback-id">
                        <span class="id-label">反馈ID:</span>
                        <span class="id-value">#{{ feedbackId }}</span>
                    </div>
                </div>
                <div class="action-buttons">
                    <button
                        v-if="feedback.status === 'pending'"
                        class="action-btn process-btn"
                        @click="processFeedback"
                    >
                        <Icon
                            icon-class="play-circle"
                            size="sm"
                            fill="currentColor"
                        />
                        开始处理
                    </button>
                    <button
                        v-if="feedback.status === 'in-progress'"
                        class="action-btn resolve-btn"
                        @click="resolveFeedback"
                    >
                        <Icon
                            icon-class="check-circle"
                            size="sm"
                            fill="currentColor"
                        />
                        标记为已解决
                    </button>
                    <button class="action-btn delete-btn" @click="deleteFeedback">
                        <Icon
                            icon-class="trash-2"
                            size="sm"
                            fill="currentColor"
                        />
                        删除反馈
                    </button>
                </div>
            </div>

            <!-- 反馈详情主内容 -->
            <div class="feedback-detail-section">
                <div class="detail-card">
                    <!-- 基本信息 -->
                    <div class="detail-section">
                        <h3 class="section-title">基本信息</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">分类:</span>
                                <div
                                    class="category-badge detail-value"
                                    :style="{
                                        background: getCategoryColor(
                                            feedback.category,
                                        ),
                                    }"
                                >
                                    {{ feedback.category }}
                                </div>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">状态:</span>
                                <span
                                    class="status-badge detail-value"
                                    :class="feedback.status"
                                >
                                    {{ getStatusText(feedback.status) }}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">提交时间:</span>
                                <span class="detail-value">{{
                                    feedback.time
                                }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">提交者:</span>
                                <span class="detail-value">{{
                                    feedback.author || "匿名用户"
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 反馈内容 -->
                    <div class="detail-section">
                        <h3 class="section-title">反馈内容</h3>
                        <div class="content-box">
                            <p class="content-text">{{ feedback.content }}</p>
                        </div>
                    </div>

                    <!-- 处理备注 -->
                    <div class="detail-section">
                        <div class="section-header">
                            <h3 class="section-title">处理备注</h3>
                            <button
                                class="add-note-btn"
                                @click="showNoteForm = !showNoteForm"
                            >
                                <Icon
                                    icon-class="edit"
                                    size="sm"
                                    fill="currentColor"
                                />
                                {{ showNoteForm ? "取消" : "添加备注" }}
                            </button>
                        </div>
                        <div v-if="showNoteForm" class="note-form">
                            <textarea
                                v-model="newNote"
                                placeholder="输入处理备注..."
                                rows="4"
                            ></textarea>
                            <div class="note-actions">
                                <button
                                    class="cancel-btn"
                                    @click="cancelNote"
                                >
                                    取消
                                </button>
                                <button
                                    class="save-btn"
                                    @click="saveNote"
                                    :disabled="!newNote.trim()"
                                >
                                    保存备注
                                </button>
                            </div>
                        </div>
                        <div v-if="feedback.notes" class="notes-box">
                            <p class="notes-text">{{ feedback.notes }}</p>
                            <span class="notes-time">更新于: {{ feedback.notesTime }}</span>
                        </div>
                        <div v-else class="no-notes">
                            <Icon
                                icon-class="file-text"
                                size="lg"
                                fill="#9CA3AF"
                            />
                            <p>暂无处理备注</p>
                        </div>
                    </div>

                    <!-- 状态历史 -->
                    <div class="detail-section" v-if="statusHistory.length > 0">
                        <h3 class="section-title">状态历史</h3>
                        <div class="timeline">
                            <div
                                v-for="(event, index) in statusHistory"
                                :key="index"
                                class="timeline-item"
                            >
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <span class="event-status">{{
                                            getStatusText(event.status)
                                        }}</span>
                                        <span class="event-time">{{
                                            event.time
                                        }}</span>
                                    </div>
                                    <p class="event-desc">{{ event.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 侧边操作面板 -->
                <div class="sidebar-panel">
                    <div class="panel-section">
                        <h4 class="panel-title">快速操作</h4>
                        <div class="action-buttons-vertical">
                            <button
                                class="action-btn secondary"
                                @click="updateStatus('pending')"
                                :disabled="feedback.status === 'pending'"
                            >
                                <Icon
                                    icon-class="clock"
                                    size="sm"
                                    fill="currentColor"
                                />
                                设为待处理
                            </button>
                            <button
                                class="action-btn secondary"
                                @click="updateStatus('in-progress')"
                                :disabled="feedback.status === 'in-progress'"
                            >
                                <Icon
                                    icon-class="play-circle"
                                    size="sm"
                                    fill="currentColor"
                                />
                                设为处理中
                            </button>
                            <button
                                class="action-btn secondary"
                                @click="updateStatus('resolved')"
                                :disabled="feedback.status === 'resolved'"
                            >
                                <Icon
                                    icon-class="check-circle"
                                    size="sm"
                                    fill="currentColor"
                                />
                                设为已解决
                            </button>
                        </div>
                    </div>
                    <div class="panel-section">
                        <h4 class="panel-title">相关信息</h4>
                        <div class="info-list">
                            <div class="info-item">
                                <Icon
                                    icon-class="calendar"
                                    size="xs"
                                    fill="#718096"
                                />
                                <span>创建时间: {{ feedback.createTime || feedback.time }}</span>
                            </div>
                            <div class="info-item">
                                <Icon
                                    icon-class="refresh-cw"
                                    size="xs"
                                    fill="#718096"
                                />
                                <span>最后更新: {{ feedback.updateTime || "刚刚" }}</span>
                            </div>
                            <div class="info-item">
                                <Icon
                                    icon-class="hash"
                                    size="xs"
                                    fill="#718096"
                                />
                                <span>ID: #{{ feedback.id }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-section">
                        <h4 class="panel-title">导出与分享</h4>
                        <div class="action-buttons-vertical">
                            <button class="action-btn secondary" @click="exportFeedback">
                                <Icon
                                    icon-class="download"
                                    size="sm"
                                    fill="currentColor"
                                />
                                导出详情
                            </button>
                            <button class="action-btn secondary" @click="printFeedback">
                                <Icon
                                    icon-class="printer"
                                    size="sm"
                                    fill="currentColor"
                                />
                                打印
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 页脚 -->
            <footer class="feedback-detail-footer">
                <p class="footer-text">
                    © {{ currentYear }} 班级匿名反馈系统 - 反馈详情. 版本 1.0.0
                </p>
                <div class="footer-links">
                    <router-link to="/admin/feedback" class="footer-link"
                        >返回反馈管理</router-link
                    >
                    <span class="footer-separator">•</span>
                    <router-link to="/admin" class="footer-link"
                        >管理面板</router-link
                    >
                    <span class="footer-separator">•</span>
                    <router-link to="/dashboard" class="footer-link"
                        >用户面板</router-link
                    >
                </div>
            </footer>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import Icon from "../components/Icon.vue";
import {
    getFeedback,
    updateFeedbackStatus,
    updateFeedbackNotes,
    deleteFeedback as apiDeleteFeedback,
} from "../api/feedbacks";
import { logout } from "../api/auth";

const router = useRouter();
const route = useRoute();

const feedbackId = computed(() => {
    return parseInt(route.params.id as string) || 1;
});

const username = computed(() => {
    return localStorage.getItem("username") || "管理员";
});

const email = computed(() => {
    return localStorage.getItem("userEmail") || "admin@example.com";
});

const currentYear = computed(() => new Date().getFullYear());

const feedback = ref<any>({
    id: 0,
    category: "",
    content: "",
    time: "",
    status: "pending",
    author: "",
    notes: "",
    notesTime: "",
    createTime: "",
    updateTime: "",
});

const statusHistory = ref<any[]>([]);

const showNoteForm = ref(false);
const newNote = ref("");

const getCategoryColor = (category: string) => {
    const colors: Record<string, string> = {
        班级纪律: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
        学习建议: "linear-gradient(135deg, #10b981 0%, #059669 100%)",
        班级活动: "linear-gradient(135deg, #f59e0b 0%, #d97706 100%)",
        同学关系: "linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)",
        班委工作: "linear-gradient(135deg, #ec4899 0%, #db2777 100%)",
    };
    return (
        colors[category] ||
        "linear-gradient(135deg, #6b7280 0%, #4b5563 100%)"
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

const goBack = () => {
    router.push("/admin/feedback");
};

const loadFeedback = async () => {
    try {
        const res: any = await getFeedback(feedbackId.value);
        if (res.code === 200) {
            const data = res.data;
            feedback.value = {
                id: data.id,
                category: data.category,
                content: data.content,
                time: data.created_at,
                status: data.status,
                author: data.author || "匿名用户",
                notes: data.notes || "",
                notesTime: data.notes_time || "",
                createTime: data.created_at,
                updateTime: data.updated_at,
            };
            statusHistory.value = (data.status_history || []).map((h: any) => ({
                status: h.status,
                time: h.created_at,
                description: h.description,
            }));
        }
    } catch (e) {
        alert("加载反馈详情失败");
    }
};

const processFeedback = async () => {
    if (feedback.value.status === "pending") {
        try {
            const res: any = await updateFeedbackStatus(feedback.value.id, "in-progress");
            if (res.code === 200) {
                feedback.value.status = "in-progress";
                feedback.value.notesTime = new Date().toLocaleString();
                statusHistory.value.unshift({
                    status: "in-progress",
                    time: "刚刚",
                    description: "开始处理反馈",
                });
                alert("反馈已开始处理");
            }
        } catch (e) {
            alert("操作失败");
        }
    }
};

const resolveFeedback = async () => {
    if (feedback.value.status === "in-progress") {
        try {
            const res: any = await updateFeedbackStatus(feedback.value.id, "resolved");
            if (res.code === 200) {
                feedback.value.status = "resolved";
                feedback.value.updateTime = new Date().toLocaleString();
                statusHistory.value.unshift({
                    status: "resolved",
                    time: "刚刚",
                    description: "反馈已解决",
                });
                alert("反馈已标记为已解决");
            }
        } catch (e) {
            alert("操作失败");
        }
    }
};

const deleteFeedback = async () => {
    if (confirm(`确定要删除反馈 #${feedback.value.id} 吗？此操作不可撤销。`)) {
        try {
            const res: any = await apiDeleteFeedback(feedback.value.id);
            if (res.code === 200) {
                alert(`反馈 #${feedback.value.id} 已删除`);
                goBack();
            } else {
                alert(res.msg || "删除失败");
            }
        } catch (e) {
            alert("删除失败");
        }
    }
};

const updateStatus = async (status: string) => {
    const oldStatus = feedback.value.status;
    if (oldStatus !== status) {
        try {
            const res: any = await updateFeedbackStatus(feedback.value.id, status);
            if (res.code === 200) {
                feedback.value.status = status;
                feedback.value.updateTime = new Date().toLocaleString();

                const statusDescriptions: Record<string, string> = {
                    pending: "重新设为待处理状态",
                    "in-progress": "开始处理反馈",
                    resolved: "标记反馈为已解决",
                };

                statusHistory.value.unshift({
                    status: status,
                    time: "刚刚",
                    description: statusDescriptions[status] || `状态变更为${getStatusText(status)}`,
                });

                alert(`反馈状态已更新为: ${getStatusText(status)}`);
            }
        } catch (e) {
            alert("操作失败");
        }
    }
};

const saveNote = async () => {
    if (newNote.value.trim()) {
        try {
            const res: any = await updateFeedbackNotes(feedback.value.id, newNote.value);
            if (res.code === 200) {
                feedback.value.notes = newNote.value;
                feedback.value.notesTime = new Date().toLocaleString();
                newNote.value = "";
                showNoteForm.value = false;

                statusHistory.value.unshift({
                    status: feedback.value.status,
                    time: "刚刚",
                    description: "添加了处理备注",
                });

                alert("备注已保存");
            }
        } catch (e) {
            alert("保存备注失败");
        }
    }
};

const cancelNote = () => {
    newNote.value = "";
    showNoteForm.value = false;
};

const exportFeedback = () => {
    alert("导出功能开发中");
};

const printFeedback = () => {
    window.print();
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
    loadFeedback();
});
</script>

<style scoped>
/* 复用 AdminView 的样式 */
.feedback-detail-page {
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

.feedback-detail-container {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
}

/* 头部样式 - 复用 AdminView */
.feedback-detail-header {
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
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05),
        0 10px 15px rgba(0, 0, 0, 0.05);
}

.app-title {
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
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

.user-email {
    font-size: 12px;
    color: #718096;
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

/* 操作栏样式 */
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

.nav-section {
    display: flex;
    align-items: center;
    gap: 20px;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.back-btn:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
}

.feedback-id {
    display: flex;
    align-items: center;
    gap: 8px;
}

.id-label {
    font-size: 14px;
    color: #718096;
}

.id-value {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
    background: #f7fafc;
    padding: 4px 12px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

.action-buttons {
    display: flex;
    align-items: center;
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

/* 反馈详情区域 */
.feedback-detail-section {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 24px;
}

.detail-card {
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
}

.detail-section {
    margin-bottom: 30px;
}

.detail-section:last-child {
    margin-bottom: 0;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 20px 0;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.add-note-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #4a5568;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.add-note-btn:hover {
    background: #edf2f7;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.detail-item {
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

.category-badge {
    padding: 6px 16px;
    border-radius: 20px;
    color: white;
    font-size: 14px;
    font-weight: 500;
}

.status-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
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

.content-box {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
}

.content-text {
    margin: 0;
    color: #4a5568;
    line-height: 1.6;
    font-size: 15px;
}

.note-form {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
}

.note-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    color: #4a5568;
    resize: vertical;
    outline: none;
    transition: border-color 0.2s ease;
}

.note-form textarea:focus {
    border-color: #667eea;
}

.note-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 12px;
}

.cancel-btn,
.save-btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.cancel-btn {
    background: white;
    border: 1px solid #e2e8f0;
    color: #4a5568;
}

.cancel-btn:hover {
    background: #f7fafc;
}

.save-btn {
    background: #667eea;
    border: 1px solid #667eea;
    color: white;
}

.save-btn:hover:not(:disabled) {
    background: #5a67d8;
}

.save-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.notes-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 20px;
}

.notes-text {
    margin: 0 0 12px 0;
    color: #15803d;
    line-height: 1.6;
}

.notes-time {
    font-size: 12px;
    color: #6b7280;
}

.no-notes {
    text-align: center;
    padding: 40px 20px;
    color: #9CA3AF;
}

.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 6px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 4px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #667eea;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #667eea;
}

.timeline-content {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.event-status {
    font-weight: 600;
    color: #2d3748;
}

.event-time {
    font-size: 12px;
    color: #718096;
}

.event-desc {
    margin: 0;
    color: #4a5568;
    font-size: 14px;
}

/* 侧边面板 */
.sidebar-panel {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.panel-section {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    padding: 28px;
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.04),
        0 1px 3px rgba(0, 0, 0, 0.04),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
}

.panel-title {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 16px 0;
}

.action-buttons-vertical {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.action-btn.secondary {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 12px;
    color: #4a5568;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.action-btn.secondary:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.action-btn.secondary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #4a5568;
}

/* 页脚 - 复用 AdminView */
.feedback-detail-footer {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid rgba(203, 213, 224, 0.4);
    text-align: center;
}

.footer-text {
    font-size: 14px;
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
@media (max-width: 1024px) {
    .feedback-detail-section {
        grid-template-columns: 1fr;
    }

    .sidebar-panel {
        order: -1;
    }
}

@media (max-width: 768px) {
    .admin-container {
        padding: 16px;
    }

    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .nav-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .action-buttons {
        width: 100%;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .timeline {
        padding-left: 0;
    }

    .timeline::before {
        display: none;
    }

    .timeline-marker {
        position: static;
        display: inline-block;
        margin-right: 8px;
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

    .action-btn {
        flex: 1;
        justify-content: center;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .timeline-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
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
    .panel-title,
    .detail-label,
    .id-value,
    .event-status {
        color: #e2e8f0;
    }

    .user-email,
    .footer-text,
    .id-label,
    .event-time,
    .notes-time {
        color: #a0aec0;
    }

    .detail-value,
    .content-text,
    .event-desc,
    .info-item {
        color: #cbd5e0;
    }

    .detail-card,
    .action-bar,
    .panel-section,
    .content-box,
    .notes-box,
    .timeline-content {
        background: rgba(26, 32, 44, 0.9);
        border-color: #4a5568;
    }

    .logout-btn,
    .back-btn,
    .action-btn,
    .add-note-btn,
    .cancel-btn,
    .action-btn.secondary {
        background: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }

    .logout-btn:hover,
    .back-btn:hover,
    .action-btn:hover,
    .add-note-btn:hover,
    .cancel-btn:hover,
    .action-btn.secondary:hover:not(:disabled) {
        background: #4a5568;
    }

    .save-btn {
        background: #667eea;
        border-color: #667eea;
    }

    .save-btn:hover:not(:disabled) {
        background: #5a67d8;
    }

    .notes-box {
        background: rgba(16, 185, 129, 0.1);
        border-color: #10b981;
    }

    .notes-text {
        color: #10b981;
    }

    .no-notes {
        color: #a0aec0;
    }

    .timeline::before {
        background: #4a5568;
    }

    .timeline-marker {
        background: #667eea;
        border-color: #1a202c;
        box-shadow: 0 0 0 2px #667eea;
    }

    .note-form textarea {
        background: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }

    .note-form textarea:focus {
        border-color: #667eea;
    }

    .content-box {
        background: #2d3748;
    }

    .status-badge.pending {
        background: rgba(251, 146, 60, 0.1);
        color: #fb923c;
    }

    .status-badge.in-progress {
        background: rgba(59, 130, 246, 0.1);
        color: #60a5fa;
    }

    .status-badge.resolved {
        background: rgba(34, 197, 94, 0.1);
        color: #4ade80;
    }
}
</style>
