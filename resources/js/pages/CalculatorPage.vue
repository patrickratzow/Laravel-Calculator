<script setup>
import Calculator from "../components/Calculator.vue";
import CalculatorHistory from "../components/CalculatorHistory.vue";
</script>

<template>
    <div class="flex flex-col items-center text-center">
        <div class="mb-12 md:mb-16">
            <h1 class="text-white md:text-black text-4xl font-bold mb-1">CalcTek Calculator</h1>
            <p class="text-md text-gray-200 md:text-gray-700">A calculator for the modern age</p>
        </div>
        <div class="relative flex flex-col md:flex-row items-center justify-center">
            <Calculator />
            <CalculatorHistory />
        </div>
    </div>
</template>
