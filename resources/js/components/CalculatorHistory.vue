<script setup>
import {computed, onBeforeMount} from 'vue';
import {useCalculator} from "../composables/useCalculator.js";

const calculator = useCalculator();
const entryCount = computed(() => {
    const count = calculator.history.length;
    if (count === 0) return '(empty)';
    if (count === 1) return '(1 calculation)';

    return `(${count} calculations)`;
});

onBeforeMount(() => calculator.load());
</script>

<template>
    <div class="flex h-full md:h-auto mt-4 md:mt-0 md:ml-4 bg-primary rounded-lg p-4 text-gray-200 min-w-[16rem] min-h-[16rem] md:min-h-0 self-stretch">
        <div class="flex flex-col h-full justify-between w-full">
            <div>
                <h3 class="text-md text-left">
                    History
                    <span class="text-sm text-gray-400">{{ entryCount }}</span>
                </h3>
                <ul v-if="calculator.history.length" class="mt-1">
                    <li
                        v-for="(entry, index) in calculator.history"
                        :key="index"
                        class="text-right mb-3 flex flex-row justify-between items-center"
                    >
                        <button class="p-2 text-gray-500 hover:text-red-400" @click="calculator.removeHistoryItem(index)" >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div>
                            <div class="text-sm text-gray-400">{{ entry.input }} =</div>
                            <div :class="{
                                'text-lg': entry.success,
                                'text-white': entry.success,
                                'text-md': !entry.success,
                                'text-red-500': !entry.success
                                }"
                            >
                                {{ entry.result }}
                            </div>
                        </div>

                    </li>
                </ul>
            </div>
                <button
                    v-if="calculator.history.length"
                    @click="calculator.clearHistory"
                    class="text-sm text-gray-400 hover:text-red-500 bg-transparent active:outline-none"
                >
                    Clear History
                </button>
            </div>
    </div>
</template>
