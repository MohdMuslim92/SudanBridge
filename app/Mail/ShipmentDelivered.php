<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Shipment;

class ShipmentDelivered extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment;
    public $recipientName;

    /**
     * Create a new message instance.
     *
     * @param  Shipment  $shipment
     * @param  string  $recipientName
     * @return void
     */
    public function __construct(Shipment $shipment, $recipientName)
    {
        $this->shipment = $shipment;
        $this->recipientName = $recipientName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.shipment-delivered')
            ->subject('Shipment Delivered');
    }
}
?>
