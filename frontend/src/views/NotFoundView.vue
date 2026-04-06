<template>
  <div class="not-found-page">
    <!-- 毛玻璃背景层 -->
    <div class="glass-background">
      <div class="bg-blur-1"></div>
      <div class="bg-blur-2"></div>
      <div class="bg-blur-3"></div>
    </div>

    <!-- 主内容 -->
    <div class="not-found-container">
      <div class="error-content">
        <!-- 错误代码 -->
        <div class="error-code">
          <span class="error-digit">4</span>
          <span class="error-digit">0</span>
          <span class="error-digit">4</span>
        </div>

        <!-- 错误信息 -->
        <div class="error-message">
          <h1>页面未找到</h1>
          <p>
            抱歉，您访问的页面不存在或已被移除。<br />
            请检查URL是否正确，或使用以下链接返回。
          </p>
        </div>

        <!-- 主要操作按钮 -->
        <div class="action-buttons">
          <router-link to="/" class="action-button primary">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
              <path d="M3 10L10 3L17 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M6 10V16H14V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>返回首页</span>
          </router-link>

          <button @click="goBack" class="action-button secondary">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
              <path d="M15 5L10 10L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M10 5L5 10L10 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>返回上一页</span>
          </button>
        </div>

        <!-- 快速导航 -->
        <!-- <div class="quick-links">
          <p class="quick-links-title">快速导航</p>
          <div class="links-grid">
            <router-link to="/login" class="link-item">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M10 10C12.2091 10 14 8.20914 14 6C14 3.79086 12.2091 2 10 2C7.79086 2 6 3.79086 6 6C6 8.20914 7.79086 10 10 10Z"
                      stroke="currentColor" stroke-width="1.5" />
                <path d="M17 18C17 14.134 13.866 11 10 11C6.13401 11 3 14.134 3 18"
                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
              </svg>
              <span>登录页面</span>
            </router-link>

            <router-link to="/help" class="link-item">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>帮助中心</span>
            </router-link>
          </div>
        </div> -->

        <!-- 调试信息（开发环境显示） -->
        <!-- <div v-if="isDevelopment" class="debug-info">
          <p class="debug-title">调试信息</p>
          <div class="debug-content">
            <p>当前路径：<code>{{ currentPath }}</code></p>
            <p>路由名称：<code>{{ routeName }}</code></p>
            <p>来自路径：<code>{{ fromPath }}</code></p>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

// 当前路径信息
const currentPath = computed(() => route.path)
const routeName = computed(() => route.name || '未命名路由')

// 记录来自的路径
const fromPath = ref('')

// 页面访问信息
const pageAccessTime = ref<number>(0)
const userAgent = ref<string>('')
const pageLeaveTime = ref<number>(0)
const pageStayDuration = ref<number>(0)

// 错误报告配置
const enableErrorReporting = ref(true)

// 检查是否为开发环境
const isDevelopment = computed(() => {
  return import.meta.env.MODE === 'development' ||
         window.location.hostname === 'localhost' ||
         window.location.hostname === '127.0.0.1'
})

// 获取用户代理信息
const getUserAgentInfo = () => {
  const ua = navigator.userAgent
  userAgent.value = ua

  // 解析浏览器信息
  const browserInfo = {
    isMobile: /Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua),
    isChrome: /Chrome/i.test(ua),
    isFirefox: /Firefox/i.test(ua),
    isSafari: /Safari/i.test(ua) && !/Chrome/i.test(ua),
    isEdge: /Edg/i.test(ua),
    language: navigator.language,
    platform: navigator.platform
  }

  return browserInfo
}

// 发送错误报告到服务器（模拟）
const sendErrorReport = async (errorData: any) => {
  if (!enableErrorReporting.value) return

  try {
    // 在实际项目中，这里会发送到错误监控服务
    const reportData = {
      timestamp: new Date().toISOString(),
      page: 'NotFoundView',
      path: currentPath.value,
      error: errorData,
      userAgent: userAgent.value,
      referrer: document.referrer,
      environment: isDevelopment.value ? 'development' : 'production'
    }

    console.log('错误报告数据：', reportData)

    // 模拟API调用
    // await fetch('/api/error-report', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify(reportData)
    // })

  } catch (err) {
    console.error('发送错误报告失败：', err)
  }
}

// 记录页面访问
const logPageAccess = () => {
  pageAccessTime.value = Date.now()

  const browserInfo = getUserAgentInfo()

  const accessLog = {
    timestamp: new Date().toISOString(),
    page: 'NotFoundView',
    path: currentPath.value,
    routeName: routeName.value,
    referrer: document.referrer,
    userAgent: userAgent.value,
    browserInfo,
    screenResolution: `${window.screen.width}x${window.screen.height}`,
    viewportSize: `${window.innerWidth}x${window.innerHeight}`
  }

  console.log('页面访问日志：', accessLog)

  // 发送访问日志
  if (enableErrorReporting.value) {
    sendErrorReport({
      type: 'page_access',
      data: accessLog
    })
  }
}

// 记录页面离开
const logPageLeave = () => {
  pageLeaveTime.value = Date.now()
  pageStayDuration.value = pageLeaveTime.value - pageAccessTime.value

  const leaveLog = {
    timestamp: new Date().toISOString(),
    page: 'NotFoundView',
    path: currentPath.value,
    stayDuration: pageStayDuration.value,
    stayDurationFormatted: formatDuration(pageStayDuration.value)
  }

  console.log('页面离开日志：', leaveLog)
}

// 格式化持续时间
const formatDuration = (ms: number): string => {
  const seconds = Math.floor(ms / 1000)
  if (seconds < 60) {
    return `${seconds}秒`
  }

  const minutes = Math.floor(seconds / 60)
  const remainingSeconds = seconds % 60

  if (minutes < 60) {
    return `${minutes}分${remainingSeconds}秒`
  }

  const hours = Math.floor(minutes / 60)
  const remainingMinutes = minutes % 60

  return `${hours}小时${remainingMinutes}分${remainingSeconds}秒`
}

// 返回上一页
const goBack = () => {
  if (window.history.length > 1) {
    router.go(-1)
  } else {
    // 如果没有历史记录，跳转到首页
    router.push('/')
  }
}

// 组件挂载时记录来源
onMounted(() => {
  // 尝试从路由状态或会话存储获取来源
  const navigationEntry = performance.getEntriesByType('navigation')[0] as PerformanceNavigationTiming
  if (navigationEntry) {
    fromPath.value = document.referrer || '直接访问'
  }

  // 记录详细的访问信息
  logPageAccess()

  console.log('404页面：访问了不存在的路径', route.path)
  console.log('路由参数：', route.params)
  console.log('查询参数：', route.query)

  // 添加性能监控
  if ('performance' in window) {
    const perfEntries = performance.getEntriesByType('navigation')
    if (perfEntries.length > 0) {
      const navEntry = perfEntries[0] as PerformanceNavigationTiming
      console.log('页面性能数据：', {
        dnsLookup: navEntry.domainLookupEnd - navEntry.domainLookupStart,
        tcpConnect: navEntry.connectEnd - navEntry.connectStart,
        requestTime: navEntry.responseEnd - navEntry.requestStart,
        domInteractive: navEntry.domInteractive,
        domComplete: navEntry.domComplete,
        loadEvent: navEntry.loadEventEnd - navEntry.loadEventStart
      })
    }
  }
})

// 组件卸载前记录离开
onBeforeUnmount(() => {
  logPageLeave()

  // 清理资源
  console.log('404页面组件卸载，清理资源')
})
</script>

<style scoped>
.not-found-page {
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

/* 毛玻璃背景层 - 与Login页面一致 */
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

/* 居中毛玻璃卡片 - 与Login页面一致 */
.not-found-container {
  width: 100%;
  max-width: 500px;
  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(16px) saturate(180%);
  -webkit-backdrop-filter: blur(16px) saturate(180%);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 24px;
  box-shadow:
    0 20px 40px rgba(0, 0, 0, 0.05),
    0 1px 3px rgba(0, 0, 0, 0.05),
    inset 0 1px 0 rgba(255, 255, 255, 0.6);
  padding: 50px 40px;
  z-index: 1;
  position: relative;
}

.error-content {
  text-align: center;
}

/* 错误代码样式 - 使用Login页面配色 */
.error-code {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin-bottom: 30px;
}

.error-digit {
  font-size: 6rem;
  font-weight: 800;
  color: #7c8db5;
  text-shadow: 0 4px 20px rgba(124, 141, 181, 0.2);
  line-height: 1;
}

.error-digit:nth-child(1) {
  color: #9ca8cc;
}

.error-digit:nth-child(2) {
  color: #7c8db5;
}

.error-digit:nth-child(3) {
  color: #6a7aad;
}

/* 错误信息 - 使用Login页面字体颜色 */
.error-message {
  margin-bottom: 40px;
}

.error-message h1 {
  font-size: 2.2rem;
  font-weight: 700;
  color: #4a5568;
  margin-bottom: 16px;
  letter-spacing: -0.5px;
}

.error-message p {
  font-size: 1rem;
  color: #718096;
  line-height: 1.6;
  margin: 0;
}

/* 操作按钮 - 使用Login页面按钮样式 */
.action-buttons {
  display: flex;
  gap: 16px;
  justify-content: center;
  margin-bottom: 40px;
}

.action-button {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 16px 24px;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  outline: none;
}

.action-button.primary {
  background: linear-gradient(135deg, #9ca8cc 0%, #7c8db5 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(124, 141, 181, 0.2);
}

.action-button.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(124, 141, 181, 0.3);
}

.action-button.secondary {
  background: rgba(255, 255, 255, 0.8);
  color: #4a5568;
  border: 1px solid rgba(203, 213, 224, 0.6);
  backdrop-filter: blur(8px);
}

.action-button.secondary:hover {
  background: rgba(255, 255, 255, 0.95);
  border-color: #cbd5e0;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* 快速链接 - 使用Login页面卡片样式 */
.quick-links {
  margin-top: 40px;
  padding-top: 40px;
  border-top: 1px solid rgba(203, 213, 224, 0.4);
}

.quick-links-title {
  color: #718096;
  font-size: 0.95rem;
  margin-bottom: 20px;
  font-weight: 500;
  text-align: center;
}

.links-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 12px;
  max-width: 500px;
  margin: 0 auto;
}

.link-item {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 16px;
  background: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(203, 213, 224, 0.6);
  border-radius: 12px;
  color: #7c8db5;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
  backdrop-filter: blur(8px);
}

.link-item:hover {
  background: rgba(255, 255, 255, 0.95);
  border-color: #9ca8cc;
  color: #6a7aad;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.link-item.disabled {
  color: #a0aec0;
  cursor: not-allowed;
  opacity: 0.6;
}

.link-item.disabled:hover {
  background: rgba(255, 255, 255, 0.8);
  border-color: rgba(203, 213, 224, 0.6);
  transform: none;
  box-shadow: none;
}

/* 调试信息 - 使用Login页面错误消息样式 */
.debug-info {
  margin-top: 40px;
  padding: 20px;
  background: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(203, 213, 224, 0.6);
  border-radius: 12px;
  text-align: left;
  backdrop-filter: blur(8px);
}

.debug-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: #718096;
  margin: 0 0 12px 0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.debug-content {
  font-size: 0.85rem;
  color: #4a5568;
}

.debug-content p {
  margin: 8px 0;
}

.debug-content code {
  background: rgba(255, 255, 255, 0.9);
  padding: 2px 6px;
  border-radius: 4px;
  border: 1px solid rgba(203, 213, 224, 0.6);
  font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
  color: #c53030;
}

/* 响应式设计 - 与Login页面保持一致 */
@media (max-width: 768px) {
  .not-found-container {
    padding: 40px 24px;
    border-radius: 20px;
    max-width: 400px;
  }

  .error-digit {
    font-size: 5rem;
  }

  .error-message h1 {
    font-size: 2rem;
  }

  .action-buttons {
    flex-direction: column;
  }

  .links-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .not-found-page {
    padding: 16px;
  }

  .not-found-container {
    padding: 32px 20px;
    border-radius: 18px;
    max-width: 100%;
  }

  .error-digit {
    font-size: 4rem;
  }

  .error-message h1 {
    font-size: 1.75rem;
  }

  .action-button {
    padding: 14px 20px;
  }

  .bg-blur-1,
  .bg-blur-2,
  .bg-blur-3 {
    opacity: 0.5;
  }
}

/* 动画效果 - 保持原有脉冲效果 */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.error-digit {
  animation: pulse 2s ease-in-out infinite;
}

.error-digit:nth-child(1) {
  animation-delay: 0s;
}

.error-digit:nth-child(2) {
  animation-delay: 0.2s;
}

.error-digit:nth-child(3) {
  animation-delay: 0.4s;
}

/* 暗色模式适配 - 与Login页面一致 */
@media (prefers-color-scheme: dark) {
  .not-found-page {
    background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
  }

  .not-found-container {
    background: rgba(45, 55, 72, 0.7);
    border: 1px solid rgba(74, 85, 104, 0.4);
  }

  .error-digit {
    color: #9ca8cc;
    text-shadow: 0 4px 20px rgba(156, 168, 204, 0.2);
  }

  .error-digit:nth-child(1) {
    color: #a0aec0;
  }

  .error-digit:nth-child(2) {
    color: #9ca8cc;
  }

  .error-digit:nth-child(3) {
    color: #7c8db5;
  }

  .error-message h1 {
    color: #e2e8f0;
  }

  .error-message p {
    color: #a0aec0;
  }

  .action-button.primary {
    background: linear-gradient(135deg, #8b99b8 0%, #6e7fa3 100%);
  }

  .action-button.secondary {
    background: rgba(26, 32, 44, 0.6);
    border-color: rgba(74, 85, 104, 0.5);
    color: #e2e8f0;
  }

  .action-button.secondary:hover {
    background: rgba(26, 32, 44, 0.8);
    border-color: rgba(74, 85, 104, 0.7);
  }

  .link-item {
    background: rgba(26, 32, 44, 0.6);
    border-color: rgba(74, 85, 104, 0.5);
    color: #9ca8cc;
  }

  .link-item:hover {
    background: rgba(26, 32, 44, 0.8);
    border-color: #9ca8cc;
    color: #a0aec0;
  }

  .link-item.disabled {
    color: #718096;
  }

  .debug-info {
    background: rgba(26, 32, 44, 0.6);
    border-color: rgba(74, 85, 104, 0.5);
  }

  .debug-title {
    color: #a0aec0;
  }

  .debug-content {
    color: #cbd5e0;
  }

  .debug-content code {
    background: rgba(26, 32, 44, 0.8);
    border-color: rgba(74, 85, 104, 0.6);
    color: #fc8181;
  }
}
</style>
