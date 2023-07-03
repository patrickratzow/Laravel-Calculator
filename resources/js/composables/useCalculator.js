import { defineStore } from "pinia";
import {ref, reactive, readonly, computed} from "vue";
import axios from "axios";

export const useCalculator = defineStore("calculator", () => {
    const previousInput = reactive([]);
    const input = reactive([]);
    const error = ref(null);
    const historyInput = reactive([]);

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

            addToHistory(inputSnapshot, calculationResult.payload.error, true);

            return;
        }

        clear();
        previousInput.splice(0, previousInput.length);
        previousInput.push(...inputSnapshot);
        input.push(calculationResult.payload.result);

        addToHistory(inputSnapshot, calculationResult.payload.result);
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

    const formatNumber = (input) => {
        if (isNaN(input)) return input;

        const number = parseFloat(input);
        if (isNaN(number)) return input;

        return number.toLocaleString({
            maximumFractionDigits: 4,
        });
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
            .map(formatNumber)
            .join("")
            // Add spaces around operations
            .replace(/([+\-*/^])/g, " $1 ");
    };

    function addToHistory(input, result, isError = false) {
        historyInput.push({
            input,
            success: !isError,
            result
        });

        saveHistory();
    }

    function removeHistoryItem(index) {
        historyInput.splice(index, 1);

        saveHistory();
    }

    function saveHistory() {
        const json = JSON.stringify(historyInput);

        localStorage.setItem("history", json);
    }

    function loadHistory() {
        if (historyInput.length) return;
        const json = localStorage.getItem("history");
        if (!json) return;

        try {
            const history = JSON.parse(json);
            historyInput.push(...history)
        } catch (e) {
            console.error(e);
            // If there's an error, just clear the history
            localStorage.removeItem("history");
        }
    }

    function clearHistory() {
        historyInput.splice(0, Math.max(history.length - 1, 0));

        localStorage.removeItem("history");
    }

    const formattedPreviousInput = computed(() => formatExpressions(previousInput));
    const formattedInput = computed(() => formatExpressions(input))
    const formattedHistory = computed(() => {
        return historyInput.map((item) => {
            return {
                ...item,
                result: formatNumber(item.result),
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
        clearHistory,
        load: loadHistory,
        removeHistoryItem,
        previousInput: formattedPreviousInput,
        input: formattedInput,
        history: formattedHistory,
        error: readonly(error),
    }
});
