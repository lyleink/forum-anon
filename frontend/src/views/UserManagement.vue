<template>
    <div class="user-management-page">
        <!-- 毛玻璃背景层 -->
        <div class="glass-background">
            <div class="bg-blur-1"></div>
            <div class="bg-blur-2"></div>
            <div class="bg-blur-3"></div>
        </div>

        <!-- 主内容容器 -->
        <div class="user-management-container">
            <!-- 头部 -->
            <header class="user-management-header">
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
                    <h1 class="app-title">用户管理</h1>
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
                            placeholder="搜索用户名..."
                            @input="onSearch"
                        />
                    </div>
                    <div class="filter-group">
                        <button class="reset-btn" @click="resetFilters">
                            <Icon
                                icon-class="refresh-cw"
                                size="sm"
                                fill="currentColor"
                            />
                            重置筛选
                        </button>
                    </div>
                </div>
                <div class="action-buttons">
                    <button class="add-user-btn" @click="showAddUserModal">
                        <Icon
                            icon-class="user-plus"
                            size="sm"
                            fill="currentColor"
                        />
                        添加用户
                    </button>
                    <button class="export-btn" @click="exportData">
                        <Icon
                            icon-class="download"
                            size="sm"
                            fill="currentColor"
                        />
                        导出数据
                    </button>
                    <button class="refresh-btn" @click="refreshData">
                        <Icon
                            icon-class="refresh-cw"
                            size="sm"
                            fill="currentColor"
                        />
                        刷新
                    </button>
                </div>
            </div>

            <!-- 用户列表 -->
            <div class="user-list-section">
                <div class="section-header">
                    <h3 class="section-title">
                        用户列表 ({{ filteredUsers.length }})
                    </h3>
                    <div class="pagination-info" v-if="totalPages > 1">
                        第 {{ currentPage }} / {{ totalPages }} 页 (共
                        {{ filteredUsers.length }} 条)
                    </div>
                </div>

                <!-- 用户表格 -->
                <div class="user-table-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th class="checkbox-col">
                                    <input
                                        type="checkbox"
                                        v-model="selectAll"
                                        @change="toggleSelectAll"
                                    />
                                </th>
                                <th>ID</th>
                                <th>用户名</th>
                                <th class="actions-col">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="user in paginatedUsers"
                                :key="user.id"
                                :class="{
                                    selected: selectedUsers.includes(user.id),
                                }"
                            >
                                <td class="checkbox-col">
                                    <input
                                        type="checkbox"
                                        :value="user.id"
                                        v-model="selectedUsers"
                                    />
                                </td>
                                <td>{{ user.id }}</td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            <span>{{
                                                getInitials(user.username)
                                            }}</span>
                                        </div>
                                        <div class="user-info-cell">
                                            <span class="user-name">{{
                                                user.username
                                            }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="actions-col">
                                    <div class="table-actions">
                                        <button
                                            class="action-btn edit-btn"
                                            @click="editUser(user)"
                                            title="编辑用户"
                                        >
                                            <Icon
                                                icon-class="edit"
                                                size="xs"
                                                fill="currentColor"
                                            />
                                        </button>
                                        <button
                                            class="action-btn reset-btn-table"
                                            @click="resetPassword(user)"
                                            title="重置密码"
                                        >
                                            <Icon
                                                icon-class="refresh-cw"
                                                size="xs"
                                                fill="currentColor"
                                            />
                                        </button>
                                        <button
                                            class="action-btn delete-btn"
                                            @click="deleteUserFn(user)"
                                            title="删除用户"
                                        >
                                            <Icon
                                                icon-class="trash-2"
                                                size="xs"
                                                fill="currentColor"
                                            />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- 无数据提示 -->
                    <div v-if="filteredUsers.length === 0" class="no-data">
                        <Icon icon-class="users" size="xl" fill="#9CA3AF" />
                        <p>暂无用户数据</p>
                        <p class="no-data-hint">尝试调整筛选条件或添加新用户</p>
                    </div>
                </div>

                <!-- 批量操作 -->
                <div v-if="selectedUsers.length > 0" class="batch-actions">
                    <span class="batch-count"
                        >已选择 {{ selectedUsers.length }} 个用户</span
                    >
                    <div class="batch-buttons">
                        <button
                            class="batch-btn delete-btn"
                            @click="batchDeleteUsers"
                        >
                            <Icon
                                icon-class="trash-2"
                                size="sm"
                                fill="currentColor"
                            />
                            批量删除
                        </button>
                    </div>
                </div>

                <!-- 分页 -->
                <div class="pagination" v-if="totalPages > 1">
                    <button
                        class="pagination-btn prev"
                        :disabled="currentPage === 1"
                        @click="goToPage(currentPage - 1)"
                    >
                        <Icon
                            icon-class="chevron-left"
                            size="sm"
                            fill="currentColor"
                        />
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
                        <span v-if="hasMorePages" class="page-ellipsis"
                            >...</span
                        >
                    </div>
                    <button
                        class="pagination-btn next"
                        :disabled="currentPage === totalPages"
                        @click="goToPage(currentPage + 1)"
                    >
                        下一页
                        <Icon
                            icon-class="chevron-right"
                            size="sm"
                            fill="currentColor"
                        />
                    </button>
                </div>
            </div>

            <!-- 页脚 -->
            <footer class="user-management-footer">
                <p class="footer-text">
                    © {{ currentYear }} 班级匿名反馈系统 - 用户管理. 版本 1.0.0
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

            <!-- 添加/编辑用户模态框 -->
            <div
                v-if="showUserModal"
                class="modal-overlay"
                @click.self="closeUserModal"
            >
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>{{ isEditing ? "编辑用户" : "添加用户" }}</h3>
                        <button class="modal-close" @click="closeUserModal">
                            <Icon
                                icon-class="x"
                                size="sm"
                                fill="currentColor"
                            />
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveUser">
                            <div class="form-group">
                                <label for="username">用户名 *</label>
                                <input
                                    id="username"
                                    type="text"
                                    v-model="userForm.username"
                                    placeholder="输入用户名"
                                    required
                                />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button
                            class="modal-btn secondary"
                            @click="closeUserModal"
                        >
                            取消
                        </button>
                        <button class="modal-btn primary" @click="saveUser">
                            {{ isEditing ? "保存更改" : "添加用户" }}
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
    getUsers,
    createUser,
    updateUser,
    deleteUser,
    batchDeleteUsers as apiBatchDelete,
    resetPassword as apiResetPassword,
    exportUsers,
} from "../api/users";
import { logout } from "../api/auth";

const router = useRouter();

const username = computed(() => {
    return localStorage.getItem("username") || "管理员";
});

const currentYear = computed(() => new Date().getFullYear());

const searchQuery = ref("");

const currentPage = ref(1);
const itemsPerPage = 10;

const selectedUsers = ref<number[]>([]);
const selectAll = ref(false);

const showUserModal = ref(false);
const isEditing = ref(false);
const editingUserId = ref<number | null>(null);

const userForm = ref({
    username: "",
});

const users = ref<any[]>([]);
const totalUsers = ref(0);

const filteredUsers = computed(() => {
    return users.value;
});

const totalPages = computed(() => {
    return Math.ceil(totalUsers.value / itemsPerPage);
});

const paginatedUsers = computed(() => {
    return users.value;
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

const getInitials = (name: string) => {
    return name.charAt(0).toUpperCase();
};

const loadUsers = async () => {
    try {
        const res: any = await getUsers({
            page: currentPage.value,
            pageSize: itemsPerPage,
            keyword: searchQuery.value || undefined,
        });
        if (res.code === 200) {
            users.value = res.data.list || [];
            totalUsers.value = res.data.total || 0;
        }
    } catch (e) {
        console.error("加载用户列表失败", e);
    }
};

const onSearch = () => {
    currentPage.value = 1;
    selectedUsers.value = [];
    selectAll.value = false;
    loadUsers();
};

const resetFilters = () => {
    searchQuery.value = "";
    currentPage.value = 1;
    selectedUsers.value = [];
    selectAll.value = false;
    loadUsers();
};

const goToPage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        loadUsers();
        window.scrollTo({ top: 0, behavior: "smooth" });
    }
};

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedUsers.value = paginatedUsers.value.map((user) => user.id);
    } else {
        selectedUsers.value = [];
    }
};

const showAddUserModal = () => {
    isEditing.value = false;
    editingUserId.value = null;
    userForm.value = {
        username: "",
    };
    showUserModal.value = true;
};

const editUser = (user: any) => {
    isEditing.value = true;
    editingUserId.value = user.id;
    userForm.value = {
        username: user.username,
    };
    showUserModal.value = true;
};

const resetPassword = async (user: any) => {
    if (confirm(`确定要重置用户 "${user.username}" 的密码吗？`)) {
        try {
            const res: any = await apiResetPassword(user.id);
            if (res.code === 200) {
                alert(`用户 "${user.username}" 的密码已重置为默认密码`);
            } else {
                alert(res.msg || "重置密码失败");
            }
        } catch (e: any) {
            alert(e?.msg || "重置密码失败");
        }
    }
};

const deleteUserFn = async (user: any) => {
    if (confirm(`确定要删除用户 "${user.username}" 吗？此操作不可撤销。`)) {
        try {
            const res: any = await deleteUser(user.id);
            if (res.code === 200) {
                alert(`用户 "${user.username}" 已删除`);
                loadUsers();
                const selectedIndex = selectedUsers.value.indexOf(user.id);
                if (selectedIndex !== -1) {
                    selectedUsers.value.splice(selectedIndex, 1);
                }
            } else {
                alert(res.msg || "删除失败");
            }
        } catch (e: any) {
            alert(e?.msg || "删除失败");
        }
    }
};

const batchDeleteUsers = async () => {
    if (selectedUsers.value.length === 0) return;

    if (
        confirm(
            `确定要删除选中的 ${selectedUsers.value.length} 个用户吗？此操作不可撤销。`,
        )
    ) {
        try {
            const res: any = await apiBatchDelete(selectedUsers.value);
            if (res.code === 200) {
                alert(res.msg || "用户已批量删除");
                selectedUsers.value = [];
                selectAll.value = false;
                loadUsers();
            } else {
                alert(res.msg || "批量删除失败");
            }
        } catch (e: any) {
            alert(e?.msg || "批量删除失败");
        }
    }
};

const closeUserModal = () => {
    showUserModal.value = false;
    userForm.value = {
        username: "",
    };
};

const saveUser = async () => {
    if (!userForm.value.username.trim()) {
        alert("请填写用户名");
        return;
    }

    try {
        if (isEditing.value && editingUserId.value) {
            const res: any = await updateUser(editingUserId.value, {
                username: userForm.value.username,
            });
            if (res.code === 200) {
                alert("用户信息已更新");
            } else {
                alert(res.msg || "更新失败");
                return;
            }
        } else {
            const res: any = await createUser({
                username: userForm.value.username,
                password: userForm.value.username,
            });
            if (res.code === 200) {
                alert("用户已添加，密码默认为用户名");
            } else {
                alert(res.msg || "添加失败");
                return;
            }
        }

        closeUserModal();
        loadUsers();
    } catch (e: any) {
        alert(e?.msg || "操作失败");
    }
};

const refreshData = () => {
    currentPage.value = 1;
    selectedUsers.value = [];
    selectAll.value = false;
    loadUsers();
};

const exportData = async () => {
    try {
        const blob: any = await exportUsers();
        const url = window.URL.createObjectURL(new Blob([blob]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", `users_${Date.now()}.csv`);
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

onMounted(() => {
    loadUsers();
});
</script>

<style scoped>
/* 使用 Dashboard 的样式 */
.user-management-page {
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

.user-management-container {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
}

/* 头部样式 - 匹配 Dashboard */
.user-management-header {
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

.add-user-btn,
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

.add-user-btn:hover,
.export-btn:hover,
.refresh-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-1px);
}

.add-user-btn {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-color: #10b981;
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.add-user-btn:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-color: #059669;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
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

/* 用户列表区域 - 匹配 Dashboard */
.user-list-section {
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

.status-count.active {
    background: #f0fdf4;
    color: #15803d;
}

.status-count.inactive {
    background: #fef2f2;
    color: #dc2626;
}

.status-count.pending {
    background: #fffbeb;
    color: #d97706;
}

.pagination-info {
    font-size: 14px;
    color: #718096;
}

/* 用户表格 */
.user-table-container {
    overflow-x: auto;
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.04),
        0 1px 3px rgba(0, 0, 0, 0.04),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
}

.user-table {
    width: 100%;
    border-collapse: collapse;
    background: transparent;
}

.user-table thead {
    background: rgba(255, 255, 255, 0.8);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}

.user-table th {
    padding: 20px;
    text-align: left;
    font-size: 0.95rem;
    font-weight: 600;
    color: #4a5568;
    white-space: nowrap;
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}

.user-table td {
    padding: 20px;
    text-align: left;
    font-size: 0.95rem;
    color: #4a5568;
    white-space: nowrap;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.user-table td.checkbox-col {
    width: 50px;
    min-width: 50px;
    text-align: center;
}

.user-table td:nth-child(2) {
    /* ID列 */
    width: 80px;
    min-width: 80px;
}

.user-table td:nth-child(3) {
    /* 用户名列 */
    width: 250px;
    min-width: 250px;
}

.user-table td.actions-col {
    width: 280px;
    min-width: 280px;
}

.user-table th.checkbox-col {
    width: 50px;
    min-width: 50px;
    text-align: center;
}

.user-table th:nth-child(2) {
    /* ID列 */
    width: 80px;
    min-width: 80px;
}

.user-table th:nth-child(3) {
    /* 用户名列 */
    width: 250px;
    min-width: 250px;
}
.user-table th.actions-col {
    width: 280px;
    min-width: 280px;
}

.user-table tbody tr {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.user-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.5);
    transform: translateY(-1px);
}

.user-table tbody tr.selected {
    background: rgba(59, 130, 246, 0.1);
}

.user-table td {
    padding: 20px;
    font-size: 0.95rem;
    color: #4a5568;
    white-space: nowrap;
}

.checkbox-col {
    width: 40px;
    text-align: center;
}

.actions-col {
    width: 180px;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.user-info-cell {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: #2d3748;
}

.role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.role-badge.student {
    background: #e0f2fe;
    color: #0369a1;
}

.role-badge.teacher {
    background: #fef3c7;
    color: #92400e;
}

.role-badge.admin {
    background: #f3e8ff;
    color: #7c3aed;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: #f0fdf4;
    color: #15803d;
}

.status-badge.inactive {
    background: #fef2f2;
    color: #dc2626;
}

.status-badge.pending {
    background: #fffbeb;
    color: #d97706;
}

.table-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.8);
    color: #4a5568;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.action-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.view-btn:hover {
    background: linear-gradient(135deg, #667eea 0%, #5a67d8 100%);
    border-color: #667eea;
    color: white;
}

.edit-btn:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-color: #f59e0b;
    color: white;
}

.disable-btn:hover,
.enable-btn:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-color: #10b981;
    color: white;
}

.reset-btn-table:hover {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    border-color: #8b5cf6;
    color: white;
}

.delete-btn:hover {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-color: #ef4444;
    color: white;
}

/* 无数据提示 */
.no-data {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
}

.no-data-hint {
    font-size: 14px;
    margin-top: 8px;
    color: #a0aec0;
}

/* 批量操作 */
.batch-actions {
    margin-top: 20px;
    padding: 16px;
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.batch-count {
    font-size: 14px;
    font-weight: 600;
    color: #4a5568;
}

.batch-buttons {
    display: flex;
    align-items: center;
    gap: 12px;
}

.batch-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.8);
    color: #4a5568;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.batch-btn:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.batch-btn.disable-btn:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-color: #10b981;
    color: white;
}

.batch-btn.enable-btn:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-color: #10b981;
    color: white;
}

.batch-btn.delete-btn:hover {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-color: #ef4444;
    color: white;
}

/* 分页 */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.3);
}

.pagination-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 12px 20px;
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.8);
    color: #4a5568;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.pagination-btn:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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
    padding: 12px 16px;
    border: 1px solid rgba(203, 213, 224, 0.6);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.8);
    color: #4a5568;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 44px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.page-number:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.page-number.active {
    background: linear-gradient(135deg, #667eea 0%, #5a67d8 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(124, 141, 181, 0.2);
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
    max-width: 500px;
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

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 8px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    color: #4a5568;
    outline: none;
    transition: border-color 0.2s ease;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #667eea;
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
.user-management-footer {
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

    .batch-actions {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }

    .batch-buttons {
        width: 100%;
        flex-wrap: wrap;
    }

    .batch-btn {
        flex: 1;
        justify-content: center;
    }

    .pagination {
        flex-wrap: wrap;
    }

    .user-table {
        font-size: 12px;
    }

    .user-table th,
    .user-table td {
        padding: 12px 8px;
    }

    .table-actions {
        flex-wrap: wrap;
        gap: 4px;
    }

    .action-btn {
        width: 28px;
        height: 28px;
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

    .action-buttons {
        width: 100%;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .add-user-btn,
    .export-btn,
    .refresh-btn {
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
    .user-name,
    .batch-count {
        color: #e2e8f0;
    }

    .footer-text,
    .pagination-info {
        color: #a0aec0;
    }

    .user-table th,
    .user-table td {
        color: #cbd5e0;
    }

    .user-list-section,
    .action-bar,
    .modal-content,
    .batch-actions,
    .user-table {
        background: rgba(26, 32, 44, 0.9);
        border-color: #4a5568;
    }

    .user-table thead {
        background: #2d3748;
    }

    .user-table tbody tr {
        border-color: #4a5568;
    }

    .user-table tbody tr:hover {
        background: #2d3748;
    }

    .user-table tbody tr.selected {
        background: rgba(59, 130, 246, 0.1);
    }

    .logout-btn,
    .reset-btn,
    .add-user-btn,
    .export-btn,
    .refresh-btn,
    .action-btn,
    .batch-btn,
    .pagination-btn,
    .page-number,
    .modal-btn.secondary {
        background: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }

    .logout-btn:hover,
    .reset-btn:hover,
    .export-btn:hover,
    .refresh-btn:hover,
    .action-btn:hover,
    .batch-btn:hover,
    .pagination-btn:hover:not(:disabled),
    .page-number:hover,
    .modal-btn.secondary:hover {
        background: #4a5568;
    }

    .add-user-btn {
        background: #10b981;
        border-color: #10b981;
    }

    .add-user-btn:hover {
        background: #059669;
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

    .modal-btn.primary {
        background: #667eea;
        border-color: #667eea;
    }

    .modal-btn.primary:hover {
        background: #5a67d8;
    }

    .form-group input,
    .form-group select {
        background: #2d3748;
        border-color: #4a5568;
        color: #e2e8f0;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #667eea;
    }

    .user-avatar {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .role-badge.student {
        background: rgba(3, 105, 161, 0.1);
        color: #7dd3fc;
    }

    .role-badge.teacher {
        background: rgba(146, 64, 14, 0.1);
        color: #fbbf24;
    }

    .role-badge.admin {
        background: rgba(124, 58, 237, 0.1);
        color: #c4b5fd;
    }

    .status-badge.active {
        background: rgba(21, 128, 61, 0.1);
        color: #86efac;
    }

    .status-badge.inactive {
        background: rgba(220, 38, 38, 0.1);
        color: #fca5a5;
    }

    .status-badge.pending {
        background: rgba(217, 119, 6, 0.1);
        color: #fcd34d;
    }

    .status-count.active {
        background: rgba(21, 128, 61, 0.1);
        color: #86efac;
    }

    .status-count.inactive {
        background: rgba(220, 38, 38, 0.1);
        color: #fca5a5;
    }

    .status-count.pending {
        background: rgba(217, 119, 6, 0.1);
        color: #fcd34d;
    }

    .no-data {
        color: #a0aec0;
    }
}
</style>
