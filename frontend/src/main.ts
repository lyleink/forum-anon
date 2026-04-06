// src/main.ts
import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import "./style.css";
import App from "./App.vue";
import LoginView from "./views/LoginView.vue";
import DashboardView from "./views/DashboardView.vue";
import AdminView from "./views/AdminView.vue";
import FeedbackManagement from "./views/FeedbackManagement.vue";
import FeedbackDetail from "./views/FeedbackDetail.vue";
import UserManagement from "./views/UserManagement.vue";

// 定义路由
const routes = [
    {
        path: "/",
        name: "Dashboard",
        component: DashboardView,
        meta: {
            requiresAuth: true, // 需要登录才能访问
            title: "仪表板",
        },
    },
    {
        path: "/admin",
        name: "AdminView",
        component: AdminView,
        meta: {
            requiresAuth: true,
            title: "管理面板",
        },
    },
    {
        path: "/admin/feedback",
        name: "FeedbackManagement",
        component: FeedbackManagement,
        meta: {
            requiresAuth: true,
            title: "反馈管理",
        },
    },
    {
        path: "/admin/feedback/:id",
        name: "FeedbackDetail",
        component: FeedbackDetail,
        meta: {
            requiresAuth: true,
            title: "反馈详情",
        },
    },
    {
        path: "/admin/users",
        name: "UserManagement",
        component: UserManagement,
        meta: {
            requiresAuth: true,
            title: "用户管理",
        },
    },
    {
        path: "/login",
        name: "Login",
        component: LoginView,
        meta: {
            requiresAuth: false,
            guestOnly: true, // 仅限未登录用户访问
            title: "登录",
        },
    },
    // 404 页面
    {
        path: "/:pathMatch(.*)*",
        name: "NotFound",
        component: () => import("./views/NotFoundView.vue"),
        meta: {
            requiresAuth: false,
            title: "页面未找到",
        },
    },
];

// 创建路由实例
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// 检查登录状态的函数
const checkAuth = (): boolean => {
    // 这里可以根据你的实际认证方式修改
    const token = localStorage.getItem("token");
    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
    return !!(token || isLoggedIn);
};

// 全局前置守卫
router.beforeEach((to, from, next) => {
    console.log(`从 ${from.path} 导航到 ${to.path}`);

    const isAuthenticated = checkAuth();

    // 如果需要认证但未登录
    if (to.meta.requiresAuth && !isAuthenticated) {
        console.log("需要认证，跳转到登录页");
        next({
            name: "Login",
            query: { redirect: to.fullPath }, // 保存目标路径，登录后跳转
        });
    }
    // 如果已登录但访问仅限未登录用户的页面（如登录页）
    else if (to.meta.guestOnly && isAuthenticated) {
        console.log("已登录，跳转到仪表板");
        next({ name: "Dashboard" });
    }
    // 其他情况正常导航
    else {
        next();
    }
});

// 全局后置钩子（可选）
router.afterEach((to, from) => {
    // 设置页面标题
    if (to.meta.title) {
        document.title = `${to.meta.title} - 班级匿名反馈`;
    } else {
        document.title = "班级匿名反馈";
    }

    // 可以在这里添加页面访问统计等
    console.log(`导航完成：${from.path} -> ${to.path}`);
});

// 创建 Vue 应用并注册路由
const app = createApp(App);
app.use(router);
app.mount("#app");
