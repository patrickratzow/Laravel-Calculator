<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $input = $request->query('input');

        if (empty($input)) {
            return Response()
                ->json([
                    'error' => 'No input provided',
                ])
                ->setStatusCode(400);
        }

        return Response()
            ->json([
                'result' => $this->calculate($input),
            ])
            ->setStatusCode(200);
    }

    private function calculate(string $input): float
    {
        return 0;
    }
}
