<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Shipment;

class ShipmentCreatedForRecipient extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment;
    /**
     * Create a new message instance.
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
        return $this->markdown('emails.shipment-created-for-recipient')
            ->subject('Shipment Created');
    }
}
