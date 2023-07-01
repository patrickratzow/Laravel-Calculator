<?php

namespace App\Http\Controllers;

use CalcTek\Calculator\Contracts\CalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    private CalculatorService $calculatorService;

    public function __construct(CalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    public function index(Request $request): JsonResponse
    {
        $input = $request->query('input');

        if (empty($input)) {
            return response()
                ->json([
                    'error' => 'No input provided',
                ])
                ->setStatusCode(400);
        }

        $result = $this->calculatorService->calculate($input);

        return response()
            ->json([
                'result' => $result
            ])
            ->setStatusCode(200);
    }
}
