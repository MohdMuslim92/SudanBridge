<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Shipment;

class RecipientShipmentOutForDelivery extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment;

    /**
     * Create a new message instance.
     *
     * @param  Shipment  $shipment
     * @return void
     */
    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.recipient-shipment-out-for-delivery')
            ->subject('Shipment Out for Delivery');
    }
}
