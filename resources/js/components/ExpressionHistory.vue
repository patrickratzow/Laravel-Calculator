<script setup>
import { computed } from 'vue';

const props = defineProps({
    history: {
        type: Array,
        default: () => [
            {
                expression: '1+1',
                result: 2,
            },
            {
                expression: '2+2',
                result: 4,
            }
        ],
    },
});

const entryCount = computed(() => {
    const count = props.history.length;
    if (count === 0) return '';
    if (count === 1) return '(1 entry)';

    return `(${count} entries)`;
});

</script>

<template>
    <div class="mt-4 md:mt-0 md:ml-4 bg-primary rounded-lg p-4 self-start text-gray-200 min-w-[16rem] min-h-[16rem] md:min-h-0 self-stretch">
        <h3 class="text-md text-left">
            History
            <span class="text-sm text-gray-400">{{ entryCount }}</span>
        </h3>
        <ul v-if="props.history.length" class="mt-1">
            <li
                v-for="entry in props.history"
                :key="entry.id"
                class="text-right mb-3"
            >
                <div class="text-sm text-gray-400">{{ entry.expression }}=</div>
                <div class="text-lg text-white">{{ entry.result }}</div>
            </li>
        </ul>
    </div>
</template>
