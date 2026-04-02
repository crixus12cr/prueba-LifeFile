<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\AlertService;
use App\Http\Requests\Alert\SendAlertRequest;

class AlertController extends Controller {
    /**
     * AlertService instance.
     *
     * @var AlertService
     */
    public AlertService $alertService;

    /**
     * Create a new AlertController instance.
     *
     * @param AlertService $alertService
     */
    public function __construct(AlertService $alertService) {
        $this->alertService = $alertService;
    }

    /**
     * Send an alert to a customer.
     *
     * @param SendAlertRequest $request
     * @return JsonResponse
     */
    public function send(SendAlertRequest $request): JsonResponse {
        try {
            $user = $request->user('sanctum');
            
            return $this->alertService->sendAlert($request->validated(), $user);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to send alert',
                'errors' => [$e->getMessage()],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}