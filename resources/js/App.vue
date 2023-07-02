<script setup>
import Calculator from './components/Calculator.vue'
</script>

<template>
    <main class="bg-black md:bg-gray-200 h-full flex justify-center pt-4 md:pt-0 md:items-center">
        <div class="flex flex-col items-center text-center">
            <div class="mb-12 md:mb-16">
                <h1 class="text-white md:text-black text-4xl font-bold mb-1">CalcTek Calculator</h1>
                <p class="text-md text-gray-200 md:text-gray-700">A calculator for the modern age</p>
            </div>
            <div class="relative flex flex-col md:flex-row items-center justify-center">
                <Calculator />
                <div class="mt-8 md:mt-0 md:ml-4 bg-primary rounded-lg p-4 self-start text-gray-200 min-w-[16rem] self-stretch">
                    <h1 class="text-md text-left">History</h1>

                    <ul>

                    </ul>
                </div>
            </div>
        </div>
    </main>
</template>
