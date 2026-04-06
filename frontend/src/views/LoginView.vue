<!-- src/views/LoginView.vue -->
<template>
    <div class="login-page">
        <!-- 毛玻璃背景层 -->
        <div class="glass-background">
            <div class="bg-blur-1"></div>
            <div class="bg-blur-2"></div>
            <div class="bg-blur-3"></div>
        </div>

        <!-- 居中毛玻璃卡片 -->
        <div class="glass-card">
            <!-- 卡片头部 -->
            <div class="card-header">
                <div class="logo-wrapper">
                    <div class="logo">
                        <svg
                            width="40"
                            height="40"
                            viewBox="0 0 64 64"
                            fill="none"
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
                </div>
                <h1 class="app-title">班级匿名反馈</h1>
                <br></br>
                <p class="app-subtitle">欢迎回来，请登录您的账户</p>
            </div>

            <!-- 登录表单 -->
            <form @submit.prevent="handleLogin" class="login-form">
                <!-- 用户名输入 -->
                <div class="input-group">
                    <label for="username" class="input-label">
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 18 18"
                            fill="none"
                        >
                            <path
                                d="M9 9C10.6569 9 12 7.65685 12 6C12 4.34315 10.6569 3 9 3C7.34315 3 6 4.34315 6 6C6 7.65685 7.34315 9 9 9Z"
                                stroke="currentColor"
                                stroke-width="1.2"
                            />
                            <path
                                d="M15 16C15 12.6863 12.3137 10 9 10C5.68629 10 3 12.6863 3 16"
                                stroke="currentColor"
                                stroke-width="1.2"
                                stroke-linecap="round"
                            />
                        </svg>
                    </label>
                    <input
                        id="username"
                        v-model="form.username"
                        type="text"
                        placeholder="用户名或邮箱"
                        required
                        autocomplete="username"
                        class="glass-input"
                    />
                </div>

                <!-- 密码输入 -->
                <div class="input-group">
                    <label for="password" class="input-label">
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 18 18"
                            fill="none"
                        >
                            <rect
                                x="3"
                                y="8"
                                width="12"
                                height="7"
                                rx="1.5"
                                stroke="currentColor"
                                stroke-width="1.2"
                            />
                            <path
                                d="M5 8V5C5 3.34315 6.34315 2 8 2C9.65685 2 11 3.34315 11 5V8"
                                stroke="currentColor"
                                stroke-width="1.2"
                                stroke-linecap="round"
                            />
                            <circle
                                cx="9"
                                cy="12"
                                r="0.8"
                                fill="currentColor"
                            />
                        </svg>
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="密码"
                        required
                        autocomplete="current-password"
                        class="glass-input"
                    />
                    <button
                        type="button"
                        class="password-toggle"
                        @click="showPassword = !showPassword"
                        tabindex="-1"
                    >
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 18 18"
                            fill="none"
                        >
                            <path
                                v-if="showPassword"
                                d="M2 2L16 16M4 4C2.343 5.657 2 9 2 9C2 9 5 14 9 14C10.005 14 10.859 13.656 11.597 13.203M7.5 7.5C7.22386 7.5 7 7.72386 7 8C7 8.27614 7.22386 8.5 7.5 8.5C7.77614 8.5 8 8.27614 8 8C8 7.72386 7.77614 7.5 7.5 7.5ZM7.5 7.5L10.5 10.5M13 13L14.5 14.5M9 4C13 4 16 9 16 9C16 9 15.041 10.5 13.5 12"
                                stroke="currentColor"
                                stroke-width="1.2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                v-else
                                d="M2 9C2 9 5 4 9 4C13 4 16 9 16 9C16 9 13 14 9 14C5 14 2 9 2 9Z"
                                stroke="currentColor"
                                stroke-width="1.2"
                            />
                            <circle
                                v-if="!showPassword"
                                cx="9"
                                cy="9"
                                r="2.5"
                                stroke="currentColor"
                                stroke-width="1.2"
                            />
                        </svg>
                    </button>
                </div>

                <!-- 记住我和忘记密码 -->
                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input
                            type="checkbox"
                            v-model="form.remember"
                            class="checkbox-input"
                        />
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-label">记住我</span>
                    </label>

                    <router-link to="/forgot-password" class="forgot-link">
                        忘记密码？
                    </router-link>
                </div>

                <!-- 登录按钮 -->
                <button
                    type="submit"
                    class="login-btn"
                    :class="{ loading: loading }"
                    :disabled="loading"
                >
                    <span v-if="!loading" class="btn-text">
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 18 18"
                            fill="none"
                            style="margin-right: 8px"
                        >
                            <path
                                d="M6 9H12M9 6L12 9L9 12"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        登录
                    </span>
                    <span v-if="loading" class="btn-loading">
                        <span class="loading-spinner"></span>
                        登录中...
                    </span>
                </button>

                <!-- 错误消息 -->
                <transition name="fade">
                    <div v-if="error" class="error-message">
                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 16 16"
                            fill="none"
                        >
                            <circle
                                cx="8"
                                cy="8"
                                r="7"
                                stroke="currentColor"
                                stroke-width="1"
                            />
                            <path
                                d="M8 5V9M8 11V12"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                            />
                        </svg>
                        <span>{{ error }}</span>
                    </div>
                </transition>

                <!-- 分隔线 -->
                <!-- <div class="divider">
                    <span class="divider-line"></span>
                    <span class="divider-text">或</span>
                    <span class="divider-line"></span>
                </div> -->

                <!-- 社交登录 -->
                <!-- <div class="social-login">
          <button type="button" class="social-btn google" @click="_loginWithGoogle">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M15.64 8.20455C15.64 7.56636 15.5827 6.95273 15.4764 6.36364H8V9.845H12.8436C12.635 10.97 12.0009 11.9232 11.0477 12.5614V14.8195H13.9564C15.6582 13.2527 15.64 10.9455 15.64 8.20455Z" fill="#4285F4"/>
              <path d="M8 16C10.43 16 12.4673 15.1941 13.9564 13.8195L11.0477 11.5614C10.2418 12.1014 9.2109 12.4205 8 12.4205C5.65591 12.4205 3.67182 10.8373 2.96409 8.71H0.957275V11.0418C2.43818 13.9832 5.48182 16 8 16Z" fill="#34A853"/>
              <path d="M2.96409 8.71C2.78409 8.17 2.68182 7.59318 2.68182 7C2.68182 6.40682 2.78409 5.83 2.96409 5.29V2.95818H0.957273C0.347727 4.17318 0 5.54773 0 7C0 8.4523 0.347727 9.8268 0.957273 11.0418L2.96409 8.71Z" fill="#FBBC05"/>
              <path d="M8 2.57955C9.3214 2.57955 10.5077 3.03364 11.4405 3.92545L14.0218 1.34409C12.4632 -0.108182 10.4259 -1 8 -1C5.48182 -1 2.43818 1.01682 0.957275 3.95818L2.96409 6.29C3.67182 4.16273 5.65591 2.57955 8 2.57955Z" fill="#EA4335"/>
            </svg>
            <span>Google</span>
          </button>

          <button type="button" class="social-btn github" @click="_loginWithGithub">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6 15.42 6 15.21C6 15.02 6 14.46 6 13.75C3.8 14.26 3.3 13.06 3.3 13.06C2.95 12.25 2.4 12 2.4 12C1.7 11.6 2.4 11.6 2.4 11.6C3.1 11.65 3.45 12.3 3.45 12.3C4.13 13.45 5.2 13.13 6 12.9C6.05 12.43 6.23 12.08 6.45 11.91C4.7 11.73 2.9 11.03 2.9 7.87C2.9 6.95 3.2 6.2 3.65 5.65C3.55 5.45 3.2 4.6 3.65 3.55C3.65 3.55 4.35 3.35 6 4.5C6.65 4.33 7.35 4.25 8.05 4.25C8.75 4.25 9.45 4.33 10.1 4.5C11.75 3.35 12.45 3.55 12.45 3.55C12.9 4.6 12.55 5.45 12.45 5.65C12.9 6.2 13.2 6.95 13.2 7.87C13.2 11.04 11.4 11.73 9.65 11.91C9.9 12.13 10.1 12.55 10.1 13.3C10.1 14.5 10.1 15.45 10.1 15.7C10.1 15.91 10.22 16.15 10.63 16.08C13.8 15.03 16.1 12.04 16.1 8C16.1 3.58 12.52 0 8.1 0H8Z" fill="currentColor"/>
            </svg>
            <span>GitHub</span>
          </button>
        </div> -->

                <!-- 注册链接 -->
                <div class="register-section">
                    <span class="register-text">还没有账户？</span>
                    <router-link to="/register" class="register-link">
                        立即注册
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive } from "vue";
import { useRouter, useRoute } from "vue-router";
import { login } from "../api/auth";

const router = useRouter();
const route = useRoute();

const form = reactive({
    username: "",
    password: "",
    remember: false,
});

const loading = ref(false);
const error = ref("");
const showPassword = ref(false);

const handleLogin = async () => {
    if (!form.username.trim() || !form.password.trim()) {
        error.value = "请输入用户名和密码";
        return;
    }

    loading.value = true;
    error.value = "";

    try {
        const res: any = await login(form.username, form.password, form.remember);

        console.log("登录响应:", res);

        if (res.code === 200) {
            console.log("准备保存 token:", res.data.token);
            localStorage.setItem("token", res.data.token);
            localStorage.setItem("isLoggedIn", "true");
            localStorage.setItem("username", res.data.user.username);
            localStorage.setItem("userEmail", res.data.user.email || "");
            localStorage.setItem("loginTime", new Date().toISOString());
            localStorage.setItem("isAdmin", res.data.user.role === "admin" ? "true" : "false");

            if (form.remember) {
                localStorage.setItem("rememberMe", "true");
            }

            if (res.data.user.role === "admin") {
                await router.push("/admin");
            } else {
                const redirect = route.query.redirect as string;
                await router.push(redirect || "/");
            }
        } else {
            error.value = res.msg || "登录失败";
        }
    } catch (err: any) {
        error.value = err?.msg || "登录失败，请检查用户名和密码";
        console.error("登录错误:", err);
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
.login-page {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f0f4f8 0%, #e6edf5 100%);
    overflow: hidden;
    padding: 20px;
}

/* 毛玻璃背景层 */
.glass-background {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
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

/* 居中毛玻璃卡片 */
.glass-card {
    width: 100%;
    max-width: 420px;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 24px;
    box-shadow:
        0 20px 40px rgba(0, 0, 0, 0.05),
        0 1px 3px rgba(0, 0, 0, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
    padding: 40px;
    z-index: 1;
    position: relative;
}

/* 卡片头部 */
.card-header {
    text-align: center;
    margin-bottom: 36px;
}

.logo-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: rgba(124, 141, 181, 0.1);
    border-radius: 16px;
    margin-bottom: 20px;
}

.app-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #4a5568;
    margin: 0 0 8px 0;
    letter-spacing: -0.5px;
}

.title-dot {
    color: #7c8db5;
}

.app-subtitle {
    font-size: 0.95rem;
    color: #718096;
    margin: 0;
    font-weight: 400;
}

/* 登录表单 */
.login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* 输入组 */
.input-group {
    position: relative;
    display: flex;
    align-items: center;
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

.input-label {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 16px;
    color: #a0aec0;
    transition: color 0.3s ease;
}

.input-group:focus-within .input-label {
    color: #7c8db5;
}

.glass-input {
    flex: 1;
    padding: 16px 16px 16px 0;
    border: none;
    background: transparent;
    font-size: 0.95rem;
    color: #4a5568;
    outline: none;
    min-height: 48px;
}

.glass-input::placeholder {
    color: #a0aec0;
    opacity: 0.8;
}

.password-toggle {
    position: absolute;
    right: 16px;
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #7c8db5;
}

/* 表单选项 */
.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.checkbox-wrapper {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.checkbox-input {
    display: none;
}

.checkbox-custom {
    width: 18px;
    height: 18px;
    border: 1.5px solid #cbd5e0;
    border-radius: 5px;
    margin-right: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.8);
}

.checkbox-input:checked + .checkbox-custom {
    background: #9ca8cc;
    border-color: #9ca8cc;
}

.checkbox-input:checked + .checkbox-custom::after {
    content: "✓";
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.checkbox-label {
    font-size: 0.9rem;
    color: #718096;
    font-weight: 500;
}

.forgot-link {
    font-size: 0.9rem;
    color: #7c8db5;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.forgot-link:hover {
    color: #6a7aad;
    text-decoration: underline;
}

/* 登录按钮 */
.login-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #9ca8cc 0%, #7c8db5 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(124, 141, 181, 0.2);
}

.login-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(124, 141, 181, 0.3);
}

.login-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.login-btn.loading {
    background: linear-gradient(135deg, #8b99b8 0%, #6e7fa3 100%);
}

.btn-text {
    display: flex;
    align-items: center;
    justify-content: center;
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

/* 错误消息 */
.error-message {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: rgba(254, 226, 226, 0.8);
    border: 1px solid rgba(252, 165, 165, 0.4);
    border-radius: 8px;
    color: #c53030;
    font-size: 0.9rem;
    backdrop-filter: blur(8px);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* 分隔线 */
.divider {
    display: flex;
    align-items: center;
    margin: 8px 0;
    color: #cbd5e0;
}

.divider-line {
    flex: 1;
    height: 1px;
    background: rgba(203, 213, 224, 0.6);
}

.divider-text {
    padding: 0 16px;
    font-size: 0.9rem;
    color: #a0aec0;
    font-weight: 500;
}

/* 社交登录 */
.social-login {
    display: flex;
    gap: 12px;
    margin-bottom: 8px;
}

.social-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 12px;
    color: #4a5568;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(8px);
}

.social-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.social-btn.google:hover {
    border-color: #4285f4;
    color: #4285f4;
}

.social-btn.github {
    color: #4a5568;
}

.social-btn.github:hover {
    border-color: #4a5568;
    color: #4a5568;
}

/* 注册链接 */
.register-section {
    text-align: center;
    padding-top: 16px;
    border-top: 1px solid rgba(203, 213, 224, 0.4);
}

.register-text {
    color: #718096;
    font-size: 0.95rem;
}

.register-link {
    color: #7c8db5;
    text-decoration: none;
    font-weight: 600;
    margin-left: 6px;
    transition: color 0.3s ease;
}

.register-link:hover {
    color: #6a7aad;
    text-decoration: underline;
}

/* 响应式设计 */
@media (max-width: 768px) {
    .glass-card {
        padding: 32px 24px;
        border-radius: 20px;
        max-width: 400px;
    }

    .app-title {
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .login-page {
        padding: 16px;
    }

    .glass-card {
        padding: 28px 20px;
        border-radius: 18px;
        max-width: 100%;
    }

    .social-login {
        flex-direction: column;
    }

    .bg-blur-1,
    .bg-blur-2,
    .bg-blur-3 {
        opacity: 0.5;
    }
}

/* 暗色模式适配 */
@media (prefers-color-scheme: dark) {
    .login-page {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    }

    .glass-card {
        background: rgba(45, 55, 72, 0.7);
        border: 1px solid rgba(74, 85, 104, 0.4);
    }

    .app-title {
        color: #e2e8f0;
    }

    .app-subtitle {
        color: #a0aec0;
    }

    .input-group {
        background: rgba(71, 85, 105, 0.95);
        border-color: rgba(203, 213, 224, 0.8);
    }

    .input-group:focus-within {
        background: rgba(71, 85, 105, 0.98);
        border-color: #9ca8cc;
        box-shadow: 0 0 0 3px rgba(156, 168, 204, 0.35);
    }

    .input-group:focus-within .input-label {
        color: #a5b4fc;
    }

    .input-label {
        color: #f1f5f9;
    }

    .glass-input {
        color: #ffffff;
    }

    .glass-input::placeholder {
        color: #cbd5e0;
    }

    .checkbox-custom {
        border-color: #64748b;
        background: rgba(71, 85, 105, 0.8);
    }

    .checkbox-label {
        color: #a0aec0;
    }

    .social-btn {
        background: rgba(26, 32, 44, 0.6);
        border-color: rgba(74, 85, 104, 0.5);
        color: #e2e8f0;
    }

    .error-message {
        background: rgba(127, 29, 29, 0.8);
        border-color: rgba(197, 48, 48, 0.4);
        color: #fed7d7;
    }

    .forgot-link {
        color: #9ca8cc;
    }

    .forgot-link:hover {
        color: #b0b9d9;
    }

    .register-text {
        color: #a0aec0;
    }

    .register-link {
        color: #9ca8cc;
    }

    .register-link:hover {
        color: #b0b9d9;
    }

    .password-toggle {
        color: #cbd5e0;
    }

    .password-toggle:hover {
        color: #ffffff;
    }
}
</style>
