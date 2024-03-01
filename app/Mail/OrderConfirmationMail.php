<?php

namespace App\Mail;

use App\Models\api\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Api\DateGo;
use App\Models\Api\DetailOrder;
use App\Models\Api\Tour;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $paymentMethod;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order , Tour $tour , $paymentMethod)
    {
        $this->order = $order;
        $this->tour = $tour;
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->order;
        $tour = $this->tour; 
        $paymentMethod = $this->paymentMethod;

    
        $idDate = $order->id_date;
    
        $date = DateGo::find($idDate);

        
        // Kiểm tra nếu có DetailOrder tồn tại
            return $this->from('hieu745233@gmail.com', 'Travel2h')
                ->view('emails.order_confirmation')
                ->subject('Xác nhận đơn hàng #' . $order->id_order_tour)
                ->with([
                    'name' => $order->name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'order' => $order,
                    'orderNumber' => $order->id_order_tour, // Hoặc truyền giá trị mặc định khác bạn muốn nếu không tìm thấy tour
                    'date' => $date, // Truyền giá trị null cho biến $date nếu không có thông tin ngày
                    'totalPrice' => $order->total_price,
                    'tour'=>$tour,
                    'paymentMethod' => $paymentMethod,
                    'idDate' => $idDate
                ]);

    }
    
        
    
    
}
