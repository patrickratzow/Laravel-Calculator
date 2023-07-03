import { defineStore } from "pinia";
import {ref, reactive, readonly, computed} from "vue";
import axios from "axios";

export const useCalculator = defineStore("calculator", () => {
    const previousInput = reactive([]);
    const input = reactive([]);
    const error = ref(null);
    const history = reactive([]);

    async function executeExpression(expression) {
        try {
            const response = await axios.get('/api/calculator', {
                params: {
                    input: expression
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

    async function calculate() {
        const inputSnapshot = [...input];
        const string = input.join("");
        const calculationResult = await executeExpression(string);
        if (!calculationResult.success) {
            error.value = calculationResult.payload.error;

            await addToHistory(inputSnapshot, calculationResult.payload.error, true);

            return;
        }

        clear();
        previousInput.splice(0, previousInput.length);
        previousInput.push(...inputSnapshot);
        input.push(calculationResult.payload.result);

        await addToHistory(inputSnapshot, calculationResult.payload.result);
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
        previousInput.splice(0, previousInput.length);
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

                return number.toLocaleString({
                    maximumFractionDigits: 4,
                });
            })
            .join("")
            // Add spaces around operations
            .replace(/([+\-*/^])/g, " $1 ");
    };

    async function addToHistory(input, result, isError = false) {
        history.push({
            input,
            success: !isError,
            result
        });

        await saveHistory();
    }

    async function saveHistory() {
        await axios.post('/api/calculator/history', {
            history
        });
    }

    const formattedPreviousInput = computed(() => formatExpressions(previousInput));
    const formattedInput = computed(() => formatExpressions(input))
    const formattedHistory = computed(() => {
        return history.map((item) => {
            return {
                ...item,
                input: formatExpressions(item.input),
            }
        });
    });

    return {
        calculate,
        push: pushToInput,
        pop: popFromInput,
        clear,
        surround,
        previousInput: formattedPreviousInput,
        input: formattedInput,
        history: formattedHistory,
        error: readonly(error),
    }
});
