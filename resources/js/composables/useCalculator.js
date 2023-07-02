import axios from 'axios';

export function useCalculator()
{
    async function calculate(input)
    {
        const response = await axios.get('/api/calculator', {
            params: {
                input
            }
        });

        return response.data;
    }

    return {
        calculate
    }
}
