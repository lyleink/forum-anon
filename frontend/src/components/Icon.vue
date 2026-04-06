<template>
    <svg
        :class="svgClass"
        aria-hidden="true"
        :width="computedSize"
        :height="computedSize"
        :fill="computedFill"
        viewBox="0 0 24 24"
        v-bind="$attrs"
    >
        <use :xlink:href="iconName" />
    </svg>
</template>

<script setup lang="ts">
import { computed } from "vue";

interface IconProps {
    // 图标名称，对应 symbols 中的 id
    iconClass: string;
    // 自定义类名
    className?: string;
    // 图标大小：预设值或数字
    size?: "xs" | "sm" | "md" | "lg" | "xl" | number;
    // 填充颜色（纯色面性图标）
    fill?: string;
}

const props = withDefaults(defineProps<IconProps>(), {
    iconClass: "",
    className: "",
    size: "md",
    fill: "currentColor",
});

const iconName = computed(() => `#${props.iconClass}`);

const computedSize = computed(() => {
    if (typeof props.size === "number") {
        return props.size;
    }

    const sizeMap = {
        xs: 16,
        sm: 20,
        md: 24,
        lg: 32,
        xl: 40,
    };

    return sizeMap[props.size];
});

const computedFill = computed(() => props.fill);

const svgClass = computed(() => {
    const classes = ["svg-icon"];

    if (props.className) {
        classes.push(props.className);
    }

    return classes.join(" ");
});
</script>

<style scoped>
.svg-icon {
    display: inline-block;
    vertical-align: middle;
}

svg {
    display: inline-block;
    vertical-align: middle;
}
</style>
