<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\AlertService;
use App\Http\Requests\Alert\SendAlertRequest;

class AlertController extends Controller {
    public AlertService $alertService;

    public function __construct(AlertService $alertService) {
        $this->alertService = $alertService;
    }

    public function send(SendAlertRequest $request): JsonResponse {
        try {
            $data = $this->alertService->sendAlert($request->validated(), auth()->user());
            
            return response()->json([
                'data' => $data,
            ], JsonResponse::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Error al enviar alerta',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}