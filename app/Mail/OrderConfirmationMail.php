<?php

namespace App\Mail;

use App\Models\api\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('hieu745233@gmail.com', 'Travel2h')
            ->view('emails.order_confirmation')
            ->subject('Xác nhận đơn hàng #' . $this->order->id_order_tour)
            ->with([
                'order' => $this->order,
                'orderNumber' => $this->order->id_order_tour
            ]);
    }    
    
}
