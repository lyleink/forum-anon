<template>
    <div class="feedback-page">
        <!-- 毛玻璃背景层 -->
        <div class="glass-background">
            <div class="bg-blur-1"></div>
            <div class="bg-blur-2"></div>
            <div class="bg-blur-3"></div>
        </div>

        <!-- 主内容容器 -->
        <div class="feedback-container">
            <!-- 头部 -->
            <header class="feedback-header">
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
                    <h1 class="app-title">班级匿名反馈系统</h1>
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
                    <h2>欢迎回来，{{ username }}！</h2>
                    <p>请选择您要反馈的班级问题类型</p>
                    <p>{{ currentDate }}</p>
                </div>
                <div class="banner-icon">
                    <div class="icon-wrapper">
                        <Icon icon-class="user" size="xl" fill="white" />
                    </div>
                </div>
            </div>

            <!-- 主内容区域 -->
            <main class="feedback-main">
                <!-- 反馈类别选择（未选择类别时显示） -->
                <div v-if="!selectedCategory" class="categories-section">
                    <h3 class="section-title">班级反馈类别</h3>
                    <div class="categories-grid">
                        <div
                            v-for="category in feedbackCategories"
                            :key="category.id"
                            class="category-card"
                            @click="selectCategory(category)"
                        >
                            <div class="category-card-content">
                                <div
                                    class="category-icon"
                                    :style="{ background: category.color }"
                                >
                                    <Icon
                                        :icon-class="category.icon"
                                        size="lg"
                                        fill="white"
                                    />
                                </div>
                            <div class="category-info">
                                <h4>{{ category.name }}</h4>
                                <p>{{ category.description }}</p>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 反馈表单（选择类别后显示） -->
                <div v-else class="feedback-form-section">
                    <div class="form-header">
                        <button class="back-btn" @click="goBack">
                            <Icon
                                icon-class="arrow-left"
                                size="sm"
                                fill="currentColor"
                            />
                            <span>返回</span>
                        </button>
                            <h3 class="form-title">
                                {{ selectedCategory.name }} - 反馈
                            </h3>
                    </div>

                    <div class="glass-card form-card">
                        <div class="form-group">
                            <label class="input-label">
                                <Icon
                                    icon-class="mail"
                                    size="sm"
                                    fill="currentColor"
                                />
                            </label>
                            <div class="input-group">
                                <textarea
                                    v-model="feedbackContent"
                                    class="glass-textarea"
                                    :placeholder="`请详细描述您遇到的${selectedCategory.name}问题...`"
                                    rows="8"
                                ></textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button
                                class="submit-btn"
                                @click="submitFeedback"
                                :disabled="!feedbackContent.trim()"
                            >
                                <span v-if="!isSubmitting">提交反馈</span>
                                <div v-else class="btn-loading">
                                    <div class="loading-spinner"></div>
                                    <span>提交中...</span>
                                </div>
                            </button>
                            <button class="cancel-btn" @click="goBack">
                                取消
                            </button>
                        </div>

                        <div
                            v-if="submitMessage"
                            class="submit-message"
                            :class="submitMessage.type"
                        >
                            <Icon
                                v-if="submitMessage.type === 'success'"
                                icon-class="check-circle"
                                size="sm"
                                fill="currentColor"
                            />
                            <Icon
                                v-if="submitMessage.type === 'error'"
                                icon-class="x-circle"
                                size="sm"
                                fill="currentColor"
                            />
                            <span>{{ submitMessage.text }}</span>
                        </div>
                    </div>

                    <div class="form-tips">
                        <h4>填写提示：</h4>
                        <ul>
                            <li>请详细描述问题发生的时间、地点和具体情况</li>
                            <li>如需匿名反馈，请勿包含个人身份信息</li>
                            <li>反馈内容将严格保密，仅用于问题改进</li>
                            <li>提交后无法修改，请仔细检查后再提交</li>
                        </ul>
                    </div>
                </div>
            </main>

            <!-- 页脚 -->
            <footer class="dashboard-footer">
                <p class="footer-text">
                    © {{ currentYear }} 班级匿名反馈系统.
                    保护您的隐私是我们的责任.
                </p>
                <div class="footer-links">
                    <router-link to="/privacy" class="footer-link"
                        >隐私政策</router-link
                    >
                    <span class="footer-separator">•</span>
                    <router-link to="/terms" class="footer-link"
                        >使用条款</router-link
                    >
                    <span class="footer-separator">•</span>
                    <router-link to="/help" class="footer-link"
                        >帮助中心</router-link
                    >
                </div>
            </footer>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import Icon from "../components/Icon.vue";
import { createFeedback } from "../api/feedbacks";
import { getCategories } from "../api/categories";
import { logout } from "../api/auth";

const router = useRouter();

const username = computed(() => {
    return localStorage.getItem("username") || "匿名用户";
});

const currentDate = computed(() => {
    return new Date().toLocaleDateString("zh-CN", {
        year: "numeric",
        month: "long",
        day: "numeric",
        weekday: "long",
    });
});

const currentYear = computed(() => {
    return new Date().getFullYear();
});

const feedbackCategories = ref<any[]>([]);

const selectedCategory = ref<any>(null);
const feedbackContent = ref("");
const isSubmitting = ref(false);
const submitMessage = ref<{ type: string; text: string } | null>(null);

const selectCategory = (category: any) => {
    selectedCategory.value = category;
    feedbackContent.value = "";
    submitMessage.value = null;
};

const goBack = () => {
    selectedCategory.value = null;
    feedbackContent.value = "";
    submitMessage.value = null;
};

const submitFeedback = async () => {
    if (!feedbackContent.value.trim() || !selectedCategory.value) return;

    isSubmitting.value = true;
    submitMessage.value = null;

    try {
        const res: any = await createFeedback({
            category: selectedCategory.value.name || selectedCategory.value.title,
            content: feedbackContent.value,
        });

        if (res.code === 200) {
            submitMessage.value = {
                type: "success",
                text: "反馈提交成功！我们会尽快处理您的反馈。",
            };

            setTimeout(() => {
                feedbackContent.value = "";
                submitMessage.value = null;
            }, 3000);
        } else {
            submitMessage.value = {
                type: "error",
                text: res.msg || "提交失败，请稍后重试。",
            };
        }
    } catch (error: any) {
        submitMessage.value = {
            type: "error",
            text: error?.msg || "提交失败，请稍后重试。",
        };
    } finally {
        isSubmitting.value = false;
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
    localStorage.removeItem("rememberMe");
    localStorage.removeItem("isAdmin");

    router.push("/login");
};

onMounted(async () => {
    try {
        const res: any = await getCategories();
        if (res.code === 200) {
            feedbackCategories.value = res.data;
        }
    } catch (e) {
        console.error("加载分类失败", e);
    }
});
</script>

<style scoped>
.feedback-page {
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
.feedback-container {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
}

/* 头部样式 */
.feedback-header {
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
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* 主内容区域 */
.feedback-main {
    margin-bottom: 40px;
}

/* 类别选择 */
.categories-section {
    margin-bottom: 40px;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #4a5568;
    margin: 0 0 24px 0;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(420px, 1fr));
    gap: 20px;
}

.category-card {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 16px;
    padding: 28px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.04),
        0 1px 3px rgba(0, 0, 0, 0.04),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
}

.category-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 12px 32px rgba(0, 0, 0, 0.08),
        0 2px 6px rgba(0, 0, 0, 0.06),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    border-color: rgba(156, 168, 204, 0.4);
}

.category-card-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.category-info h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #4a5568;
    margin: 0 0 8px 0;
}

.category-info p {
    font-size: 0.95rem;
    color: #718096;
    margin: 0;
    line-height: 1.5;
}

/* 反馈表单 */
.feedback-form-section {
    max-width: 800px;
    margin: 0 auto;
}

.form-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 8px;
    color: #4a5568;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-1px);
}

.form-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #4a5568;
    margin: 0;
}

.form-card {
    padding: 40px;
    margin-bottom: 30px;
}

.form-group {
    position: relative;
}

.input-label {
    position: absolute;
    left: 16px;
    top: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a0aec0;
    z-index: 1;
}

.input-group {
    position: relative;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 12px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.input-group:focus-within {
    border-color: #9ca8cc;
    box-shadow: 0 0 0 3px rgba(156, 168, 204, 0.15);
    background: rgba(255, 255, 255, 0.9);
}

.glass-textarea {
    width: 100%;
    padding: 16px 16px 16px 48px;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #4a5568;
    outline: none;
    resize: vertical;
    font-family: inherit;
    line-height: 1.6;
}

.glass-textarea::placeholder {
    color: #a0aec0;
    opacity: 0.8;
}

.form-actions {
    display: flex;
    gap: 16px;
    margin-top: 30px;
}

.submit-btn,
.cancel-btn {
    flex: 1;
    padding: 16px;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.submit-btn {
    background: linear-gradient(135deg, #9ca8cc 0%, #7c8db5 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(124, 141, 181, 0.2);
}

.submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(124, 141, 181, 0.3);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.cancel-btn {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    color: #4a5568;
}

.cancel-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.btn-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.loading-spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.submit-message {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    margin-top: 20px;
    font-size: 0.95rem;
    backdrop-filter: blur(8px);
}

.submit-message.success {
    background: rgba(198, 246, 213, 0.8);
    border: 1px solid rgba(72, 187, 120, 0.4);
    color: #065f46;
}

.submit-message.error {
    background: rgba(254, 226, 226, 0.8);
    border: 1px solid rgba(252, 165, 165, 0.4);
    color: #c53030;
}

.form-tips {
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(203, 213, 224, 0.4);
    border-radius: 12px;
    padding: 24px;
}

.form-tips h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #4a5568;
    margin: 0 0 16px 0;
}

.form-tips ul {
    margin: 0;
    padding-left: 20px;
}

.form-tips li {
    color: #718096;
    margin-bottom: 8px;
    line-height: 1.5;
}

.form-tips li:last-child {
    margin-bottom: 0;
}

/* 页脚 */
.dashboard-footer {
    text-align: center;
    padding: 30px 0;
    border-top: 1px solid rgba(203, 213, 224, 0.4);
    margin-top: 40px;
}

.footer-text {
    color: #718096;
    font-size: 0.9rem;
    margin: 0 0 12px 0;
}

.footer-links {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
}

.footer-link {
    color: #7c8db5;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.footer-link:hover {
    color: #6a7aad;
    text-decoration: underline;
}

.footer-separator {
    color: #cbd5e0;
    font-size: 0.8rem;
}

/* 响应式设计 */
@media (max-width: 1024px) {
    .categories-section {
        max-width: 800px;
    }

    .categories-grid {
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    }
}

@media (max-width: 768px) {
    .feedback-page {
        padding: 16px;
    }

    .feedback-header {
        flex-direction: row;
        gap: 16px;
        align-items: center;
        padding: 16px 0;
        margin-bottom: 24px;
    }

    .header-right {
        width: auto;
        justify-content: flex-end;
    }

    .welcome-banner {
        flex-direction: row;
        gap: 20px;
        align-items: center;
        padding: 24px;
    }

    .banner-content {
        flex: 1;
    }

    .banner-icon {
        align-self: center;
        margin-left: 12px;
        flex-shrink: 0;
    }

    .categories-section {
        max-width: 100%;
        padding: 0 16px;
    }

    .categories-grid {
        grid-template-columns: 1fr;
    }

    .category-card-content {
        gap: 16px;
    }

    .form-actions {
        flex-direction: column;
    }

    .form-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
}

@media (max-width: 480px) {
    .glass-card {
        padding: 24px 20px;
    }

    .welcome-banner {
        padding: 20px;
        gap: 16px;
        flex-direction: row;
        align-items: center;
    }

    .banner-content {
        flex: 1;
    }

    .banner-content h2 {
        font-size: 1.5rem;
        margin-bottom: 6px;
    }

    .banner-content p {
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    .banner-icon {
        margin-left: 12px;
        align-self: center;
        flex-shrink: 0;
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
    }

    .feedback-header {
        flex-direction: column;
        gap: 12px;
        padding: 12px 0;
        margin-bottom: 20px;
    }

    .header-left {
        width: 100%;
        justify-content: flex-start;
        gap: 10px;
        min-width: 0;
    }

    .logo-wrapper {
        width: 40px;
        height: 40px;
        min-width: 40px;
    }

    .app-title {
        font-size: 1.2rem;
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.2;
    }

    .header-right {
        width: 100%;
        justify-content: space-between;
    }

    .user-info {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
    }

    .user-details {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 6px;
        width: auto;
        justify-content: flex-start;
    }

    .username {
        font-size: 0.95rem;
    }

    .logout-btn {
        width: auto;
        justify-content: center;
        padding: 6px 10px;
        font-size: 0.8rem;
        min-width: 60px;
    }

    .category-card {
        padding: 20px;
    }

    .category-icon {
        width: 52px;
        height: 52px;
    }

    .category-info h4 {
        font-size: 1.1rem;
    }

    .category-info p {
        font-size: 0.9rem;
    }

    .form-card {
        padding: 24px;
    }
}

@media (max-width: 360px) {
    .feedback-header {
        flex-direction: column;
        gap: 8px;
        padding: 10px 0;
        margin-bottom: 16px;
    }

    .header-left {
        width: 100%;
        justify-content: flex-start;
        gap: 10px;
    }

    .logo-wrapper {
        width: 32px;
        height: 32px;
        min-width: 32px;
    }

    .app-title {
        font-size: 1.1rem;
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
        line-height: 1.2;
        flex: 1;
    }

    .user-info {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
    }

    .user-details {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 8px;
        width: auto;
        justify-content: flex-start;
        flex-wrap: nowrap;
        flex: 1;
    }

    .username {
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .logout-btn {
        width: auto;
        padding: 6px 10px;
        font-size: 0.8rem;
        min-width: 60px;
        align-self: center;
        margin-top: 0;
        flex-shrink: 0;
    }

    .welcome-banner {
        padding: 14px;
        gap: 10px;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
    }

    .banner-content {
        flex: 1;
        min-width: 0; /* 防止文本溢出 */
    }

    .banner-content h2 {
        font-size: 1.1rem;
        line-height: 1.3;
        margin-bottom: 4px;
    }

    .banner-content p {
        font-size: 0.8rem;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .banner-content p:last-child {
        font-size: 0.75rem;
        color: #a0aec0;
    }

    .banner-icon {
        align-self: center;
        margin: 0 0 0 12px;
        flex-shrink: 0;
    }

    .icon-wrapper {
        width: 40px;
        height: 40px;
    }

    .category-card {
        padding: 14px;
    }

    .category-icon {
        width: 44px;
        height: 44px;
    }

    .category-info h4 {
        font-size: 0.95rem;
    }

    .category-info p {
        font-size: 0.8rem;
    }

    .glass-card {
        padding: 18px 14px;
    }
}

/* 暗色模式适配 */
@media (prefers-color-scheme: dark) {
    .feedback-page {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    }

    .app-title,
    .username,
    .section-title,
    .form-title,
    .category-info h4,
    .form-tips h4,
    .banner-content h2 {
        color: #ffffff;
    }

    .category-info p,
    .footer-text,
    .form-tips li {
        color: #cbd5e0;
    }

    .banner-content p {
        color: #f1f5f9;
    }

    .category-card,
    .form-card,
    .form-tips {
        background: rgba(45, 55, 72, 0.7);
        border: 1px solid rgba(74, 85, 104, 0.4);
    }

    .welcome-banner {
        background: rgba(71, 85, 105, 0.9);
        border: 1px solid rgba(148, 163, 184, 0.5);
    }

    .input-group {
        background: rgba(26, 32, 44, 0.6);
        border-color: rgba(74, 85, 104, 0.5);
    }

    .glass-textarea {
        color: #e2e8f0;
    }

    .glass-textarea::placeholder {
        color: #a0aec0;
    }

    .logout-btn,
    .back-btn,
    .cancel-btn {
        background: rgba(26, 32, 44, 0.6);
        border-color: rgba(74, 85, 104, 0.5);
        color: #e2e8f0;
    }

    .submit-message.success {
        background: rgba(6, 95, 70, 0.3);
        border-color: rgba(16, 185, 129, 0.4);
        color: #10b981;
    }

    .submit-message.error {
        background: rgba(197, 48, 48, 0.2);
        border-color: rgba(252, 165, 165, 0.3);
        color: #fc8181;
    }
}
</style>
