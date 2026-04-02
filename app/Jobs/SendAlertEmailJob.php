<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertNotificationMail;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class SendAlertEmailJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer;
    protected $order;
    protected $medicationName;
    protected $lotNumber;

    public function __construct(Customer $customer, Order $order, string $medicationName, string $lotNumber) {
        $this->customer = $customer;
        $this->order = $order;
        $this->medicationName = $medicationName;
        $this->lotNumber = $lotNumber;
    }

    public function handle(): void {
        try {
            Mail::to($this->customer->email)->send(new AlertNotificationMail(
                $this->customer,
                $this->order,
                $this->medicationName,
                $this->lotNumber
            ));
            
            Log::info('Alert email sent successfully', [
                'customer_id' => $this->customer->id,
                'order_id' => $this->order->id,
                'email' => $this->customer->email
            ]);
        } catch(\Exception $e) {
            Log::error('Failed to send alert email', [
                'customer_id' => $this->customer->id,
                'order_id' => $this->order->id,
                'error' => $e->getMessage()
            ]);
            
            if($this->attempts() < 3) {
                $this->release(300);
            }
        }
    }
}