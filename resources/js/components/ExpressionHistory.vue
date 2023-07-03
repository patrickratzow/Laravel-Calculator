<script setup>
import { computed } from 'vue';
import {useCalculator} from "../composables/useCalculator.js";

const calculator = useCalculator();
const entryCount = computed(() => {
    const count = calculator.history.length;
    if (count === 0) return '(empty)';
    if (count === 1) return '(1 calculation)';

    return `(${count} calculations)`;
});

</script>

<template>
    <div class="mt-4 md:mt-0 md:ml-4 bg-primary rounded-lg p-4 self-start text-gray-200 min-w-[16rem] min-h-[16rem] md:min-h-0 self-stretch">
        <h3 class="text-md text-left">
            History
            <span class="text-sm text-gray-400">{{ entryCount }}</span>
        </h3>
        <ul v-if="calculator.history.length" class="mt-1">
            <li
                v-for="entry in calculator.history"
                :key="entry.id"
                class="text-right mb-3"
            >
                <div class="text-sm text-gray-400">{{ entry.input }} =</div>
                <div :class="{
                    'text-lg': entry.success,
                    'text-white': entry.success,
                    'text-md': !entry.success,
                    'text-red-500': !entry.success
                }">{{ entry.result }}</div>
            </li>
        </ul>
    </div>
</template>
