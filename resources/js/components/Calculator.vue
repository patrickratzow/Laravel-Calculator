<script setup>
import { ref, reactive, computed } from "vue";
import CalculatorButton from "./CalculatorButton.vue";
import axios from "axios";

const input = reactive([]);
const lastInput = reactive([]);
const result = ref(null);
const error = ref(null);
const history = reactive([]);

async function fetchCalculate(input) {
    try {
        const response = await axios.get('/api/calculator', {
            params: {
                input
            }
        });

        return {
            success: true,
            payload: {
                result: response.data.result,
            }
        }
    } catch (e) {
        return {
            success: false,
            payload: {
                error: e.response.data.error,
            }
        }
    }
}

function addToHistory(input, result) {
    history.push({
        input,
        result
    })
}

async function calculate() {
    const inputSnapshot = [...input];
    const string = input.join("");
    const calculationResult = await fetchCalculate(string);
    if (!calculationResult.success) {
        error.value = calculationResult.payload.error;

        return;
    }

    clear();
    lastInput.splice(0, lastInput.length);
    lastInput.push(...inputSnapshot);
    input.push(calculationResult.payload.result);

    addToHistory(string, calculationResult.payload.result);
}

function resetError() {
    error.value = null;
}

function pushToInput(value) {
    resetError();

    input.push(value);
}

function clear() {
    resetError();

    // Cannot just reassign due to reactivity
    lastInput.splice(0, lastInput.length);
    input.splice(0, input.length);
}

function popFromInput() {
    resetError();

    input.pop();
}

function surround(text) {
    resetError();

    input.unshift("(");
    input.unshift(text);
    if (input.length === 2) return;

    // A hack to not surround things like log(8+ completely
    const lastValue = input[input.length - 1];
    if (isNaN(lastValue)) return;

    // If it's not just 'text(' then we need to add a closing bracket
    input.push(")");
}

const formatExpressions = (expression) => {
    return expression
        // Reduce all sequential numbers into one number
        .reduce((acc, value) => {
            if (acc.length === 0) return [value];

            const lastValue = acc[acc.length - 1];
            if (!isNaN(lastValue) && !isNaN(value)) {
                acc[acc.length - 1] = lastValue + value;
                return acc;
            }

            acc.push(value);
            return acc;
        }, [])
        // Special characters
        .map((value) => {
            if (value === "pi") return "π";
            if (value === "sqrt") return "√";

            return value;
        })
        // Deal with number formatting
        .map((value) => {
            if (isNaN(value)) return value;

            const number = parseFloat(value);
            if (isNaN(number)) return value;

            return number.toLocaleString();
        })
        .join("")
        // Add spaces around operations
        .replace(/([+\-*/^])/g, " $1 ");
};

const formattedLastInput = computed(() => formatExpressions(lastInput));
const formattedInput = computed(() => formatExpressions(input));
</script>

<template>
    <div class="flex flex-col bg-primary rounded-lg p-4 max-w-[20rem] min-h-[33rem] justify-between">
        <div class="flex flex-col text-white text-right">
            <div v-if="error" class="text-red-500 text-md">
                {{ error }}
            </div>
            <template v-else>
                <div v-if="formattedLastInput.length" class="text-gray-300 text-sm mb-2">
                    {{ formattedLastInput }}
                </div>
                <div
                    class="text-4xl"
                    :class="{ 'mt-4': !formattedLastInput.length }">
                    <input
                        v-if="formattedInput.length"
                        class="w-full text-right bg-transparent active:outline-none"
                        :value="formattedInput"
                        readonly
                    />
                    <span class="text-gray-300" v-else>0</span>
                </div>
            </template>
        </div>
        <div class="grid grid-rows-6 grid-flow-col gap-x-3 gap-y-2">
            <!-- First column-->
            <CalculatorButton variant="small" @click="pushToInput('^')">^</CalculatorButton>
            <CalculatorButton variant="clear" @click="clear">C</CalculatorButton>
            <CalculatorButton @click="pushToInput('7')">7</CalculatorButton>
            <CalculatorButton @click="pushToInput('4')">4</CalculatorButton>
            <CalculatorButton @click="pushToInput('1')">1</CalculatorButton>
            <CalculatorButton @click="pushToInput('0')">0</CalculatorButton>

            <!-- Second column-->
            <CalculatorButton variant="small" @click="pushToInput('pi')">π</CalculatorButton>
            <CalculatorButton @click="pushToInput('(')">(</CalculatorButton>
            <CalculatorButton @click="pushToInput('8')">8</CalculatorButton>
            <CalculatorButton @click="pushToInput('5')">5</CalculatorButton>
            <CalculatorButton @click="pushToInput('2')">2</CalculatorButton>
            <CalculatorButton @click="pushToInput('.')">.</CalculatorButton>

            <!-- Third column-->
            <CalculatorButton variant="small" @click="surround('log')">log</CalculatorButton>
            <CalculatorButton @click="pushToInput(')')">)</CalculatorButton>
            <CalculatorButton @click="pushToInput('9')">9</CalculatorButton>
            <CalculatorButton @click="pushToInput('6')">6</CalculatorButton>
            <CalculatorButton @click="pushToInput('3')">3</CalculatorButton>
            <CalculatorButton @click="popFromInput()">-.</CalculatorButton>

            <!-- Fourth column-->
            <CalculatorButton variant="small" @click="surround('sqrt')">√</CalculatorButton>
            <CalculatorButton variant="highlight" @click="pushToInput('/')">/</CalculatorButton>
            <CalculatorButton variant="highlight" @click="pushToInput('*')">*</CalculatorButton>
            <CalculatorButton variant="highlight" @click="pushToInput('-')">-</CalculatorButton>
            <CalculatorButton variant="highlight" @click="pushToInput('+')">+</CalculatorButton>
            <CalculatorButton variant="success" @click="calculate">=</CalculatorButton>
        </div>
    </div>
</template>
