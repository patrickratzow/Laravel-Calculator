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
    // TODO: This is a mess.. but it's not terrible really
    const arr = [
        props.variant === "highlight"
            ? "text-white"
            : props.variant === "success"
            ? "text-white"
            : props.variant === "clear"
            ? "text-dark-red"
            : "text-gray-100",
        props.variant === "highlight"
            ? "bg-orange"
            : props.variant === "success"
            ? "bg-green"
            : props.variant === "clear"
            ? "bg-dark-red-translucent"
            : "bg-secondary",
        props.variant === "small" ? "text-md" : "text-xl",
        props.variant === "small" ? null : "w-16",
    ];
    if (props.variant === "small") {
        arr.push("mt-5");
        arr.push("h-11");
    }

    return arr;
});
</script>

<template>
    <button class="rounded-full font-semibold" :class="[classes]" @click="props.click">
        <span class="p-0">
            <slot></slot>
        </span>
    </button>
</template>
