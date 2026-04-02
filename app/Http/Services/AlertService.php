<?php

namespace App\Http\Services;

use App\Http\Repositories\AlertRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class AlertService {
    public AlertRepository $alertRepository;

    public function __construct(AlertRepository $alertRepository) {
        $this->alertRepository = $alertRepository;
    }
    
    public function sendAlert(array $data, $user): array {
        // Aquí iría la lógica de envío de email
        // Por ahora simulamos el envío
        
        $alert = $this->alertRepository->create([
            'customer_id' => $data['customer_id'],
            'order_id' => $data['order_id'],
            'user_id' => $user->id,
            'sent_at' => Carbon::now(),
        ]);
        
        return [
            'alert_id' => $alert->id,
            'sent_at' => $alert->sent_at,
            'message' => 'Alert sent successfully',
        ];
    }
}