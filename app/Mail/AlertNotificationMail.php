<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Customer;
use App\Models\Order;

class AlertNotificationMail extends Mailable {
    use Queueable, SerializesModels;

    /**
     * Customer instance.
     *
     * @var Customer
     */
    public $customer;
    
    /**
     * Order instance.
     *
     * @var Order
     */
    public $order;
    
    /**
     * Medication name.
     *
     * @var string
     */
    public $medicationName;
    
    /**
     * Lot number of the medication.
     *
     * @var string
     */
    public $lotNumber;

    /**
     * Create a new message instance.
     *
     * @param Customer $customer
     * @param Order $order
     * @param string $medicationName
     * @param string $lotNumber
     * @return void
     */
    public function __construct(Customer $customer, Order $order, string $medicationName, string $lotNumber) {
        $this->customer = $customer;
        $this->order = $order;
        $this->medicationName = $medicationName;
        $this->lotNumber = $lotNumber;
    }

    /**
     * Build the message.
     *
     * @return AlertNotificationMail
     */
    public function build(): AlertNotificationMail {
        return $this->subject('Pharmacovigilance Alert - Medication Warning')
                    ->view('emails.alert-notification')
                    ->with([
                        'customerName' => $this->customer->name,
                        'orderId' => $this->order->id,
                        'purchaseDate' => $this->order->purchase_date,
                        'medicationName' => $this->medicationName,
                        'lotNumber' => $this->lotNumber,
                        'recommendedAction' => 'Please discontinue use of this medication and contact your healthcare provider immediately.'
                    ]);
    }
}