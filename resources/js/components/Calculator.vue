<script setup>
import CalculatorButton from "./CalculatorButton.vue";
import { useCalculator } from "../composables/useCalculator.js";
import { onMounted } from "vue";

const calculator = useCalculator();

onMounted(() => {
    document.addEventListener('keydown', (e) => {
        if (e.Handled) return;
        e.Handled = true;

        // Check if key is 0-9
        if (e.key.match(/^[0-9]+$/)) calculator.push(e.key);
        if (e.key === "(") calculator.push("(");
        if (e.key === ")")  calculator.push(")");
        if (e.key === "Backspace") calculator.pop();
        if (e.key === ".") calculator.push(".");
        if (e.key === "+") calculator.push("+");
        if (e.key === "-") calculator.push("-");
        if (e.key === "*") calculator.push("*");
        if (e.key === "/") calculator.push("/");
        if (e.key === "Enter") calculator.calculate();
    });
});

</script>

<template>
    <div class="flex flex-col bg-primary rounded-lg p-4 max-w-[20rem] min-h-[33rem] justify-between">
        <div class="flex flex-col text-white text-right">
            <div v-if="calculator.error" class="text-red-500 text-md">
                {{ calculator.error }}
            </div>
            <template v-else>
                <div v-if="calculator.previousInput.length" class="text-gray-300 text-sm mb-2">
                    {{ calculator.previousInput }}
                </div>
                <div
                    class="text-4xl"
                    :class="{ 'mt-4': !calculator.previousInput.length }">
                    <input
                        v-if="calculator.input.length"
                        class="w-full text-right bg-transparent active:outline-none"
                        :value="calculator.input"
                        readonly
                    />
                    <span class="text-gray-300" v-else>0</span>
                </div>
            </template>
        </div>
        <div class="grid grid-rows-6 grid-flow-col gap-x-3 gap-y-2">
            <!-- First column-->
            <CalculatorButton variant="small" @click="calculator.push('^')">^</CalculatorButton>
            <CalculatorButton variant="clear" @click="calculator.clear">C</CalculatorButton>
            <CalculatorButton @click="calculator.push('7')">7</CalculatorButton>
            <CalculatorButton @click="calculator.push('4')">4</CalculatorButton>
            <CalculatorButton @click="calculator.push('1')">1</CalculatorButton>
            <CalculatorButton @click="calculator.push('0')">0</CalculatorButton>

            <!-- Second column-->
            <CalculatorButton variant="small" @click="calculator.push('pi')">π</CalculatorButton>
            <CalculatorButton @click="calculator.push('(')">(</CalculatorButton>
            <CalculatorButton @click="calculator.push('8')">8</CalculatorButton>
            <CalculatorButton @click="calculator.push('5')">5</CalculatorButton>
            <CalculatorButton @click="calculator.push('2')">2</CalculatorButton>
            <CalculatorButton @click="calculator.push('.')">.</CalculatorButton>

            <!-- Third column-->
            <CalculatorButton variant="small" @click="calculator.surround('log')">log</CalculatorButton>
            <CalculatorButton @click="calculator.push(')')">)</CalculatorButton>
            <CalculatorButton @click="calculator.push('9')">9</CalculatorButton>
            <CalculatorButton @click="calculator.push('6')">6</CalculatorButton>
            <CalculatorButton @click="calculator.push('3')">3</CalculatorButton>
            <CalculatorButton @click="calculator.pop()"><i class="fa-solid fa-delete-left"></i></CalculatorButton>

            <!-- Fourth column-->
            <CalculatorButton variant="small" @click="calculator.surround('sqrt')">√</CalculatorButton>
            <CalculatorButton variant="highlight" @click="calculator.push('/')">/</CalculatorButton>
            <CalculatorButton variant="highlight" @click="calculator.push('*')">*</CalculatorButton>
            <CalculatorButton variant="highlight" @click="calculator.push('-')">-</CalculatorButton>
            <CalculatorButton variant="highlight" @click="calculator.push('+')">+</CalculatorButton>
            <CalculatorButton variant="success" @click="calculator.calculate">=</CalculatorButton>
        </div>
    </div>
</template>
