<script setup>
import { computed, defineProps } from "vue";

const props = defineProps({
    variant: {
        type: String,
        default: "default",
    },
    click: {
        type: Function,
        required: true,
    },
});

const classes = computed(() => {
    const arr = [
        props.variant == "small" ? "p-0" : "p-2",
        props.variant == "highlight"
            ? "text-white"
            : props.variant == "success"
            ? "text-white"
            : "text-gray-100",
        props.variant == "highlight"
            ? "bg-orange-400"
            : props.variant == "success"
            ? "bg-green-400"
            : props.variant == "clear" 
            ? "bg-red-400"
            : "bg-gray-700",
        props.variant === "small" ? "text-xs" : "text-xl",
        props.variant === "small" ? null : "w-16",
    ];
    if (props.variant === "small") {
        arr.push("mt-5");
        arr.push("h-6");
    }

    return arr;
});
</script>

<template>
    <button class="rounded-full font-semibold" :class="[classes]" @click="props.click">
        <slot></slot>
    </button>
</template>
