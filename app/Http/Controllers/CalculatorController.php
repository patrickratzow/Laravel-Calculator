<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalculationResultResource;
use CalcTek\Calculator\Contracts\CalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CalculatorController extends Controller
{
    private CalculatorService $calculatorService;

    public function __construct(CalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    /**
     * Returns the result of the calculation
     *
     * @param Request $request
     * @return JsonResponse
     */
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
        if (!is_string($input)) {
            return response()
                ->json([
                    'error' => 'Input must be a string',
                ])
                ->setStatusCode(400);
        }

        try {
            $result = $this->calculatorService->calculate($input);
            $resource = CalculationResultResource::make(['result' => $result]);

            return response()
                ->json($resource)
                ->setStatusCode(200);
        } catch (Throwable $e) {
            return response()
                ->json([
                    'error' => $e->getMessage(),
                ])
                ->setStatusCode(400);
        }
    }
}
