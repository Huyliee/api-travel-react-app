<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Xác nhận đơn đặt tour của bạn</h1>
    <h2>Chào: {{$order->name}}</h2>
    <p>Chúng tôi xin chân thành cảm ơn bạn đã lựa chọn dịch vụ của chúng tôi. Dưới đây là thông tin chi tiết về đơn đặt tour của bạn:</p>
    <h2>Thông tin đơn đặt tour</h2>
    <ul>
        <li><strong>Tên tour:</strong> {{ $tour->name_tour }}</li>
        <li><strong>Ngày đi:</strong> {{  $date->date }}</li>
        <li><strong>Số lượng khách:</strong>
    </li>
        <li><strong>Tổng cộng:</strong> {{$order->total_price}}đ</li>
    </ul>

    <h2>Thông tin liên hệ:</h2>
    <ul>
        <li><strong>Họ tên:</strong> {{$order->name}}</li>
        <li><strong>Địa chỉ email:</strong> {{$order->email}}</li>
        <li><strong>Điện thoại:</strong> {{$order->phone}}</li>
    </ul>

    <h2>Phương thức thanh toán:</h2>
    <p><strong>Phương thức thanh toán đã chọn:</strong> {{$paymentMethod}}</p>
    <p><strong>Hướng dẫn thanh toán:</strong></p>

    <p>Vui lòng kiểm tra kỹ thông tin đơn đặt tour và chắc chắn rằng tất cả các thông tin đều chính xác. Nếu có bất kỳ sai sót hoặc cần điều chỉnh, vui lòng liên hệ với chúng tôi ngay để chúng tôi có thể hỗ trợ bạn.</p>
    
    @if ($paymentMethod === 'Tiền mặt')
    <p>Xác nhận thanh toán: Chúng tôi sẽ tiếp nhận thanh toán bằng tiền mặt khi bạn đến trước ngày khởi hành của tour. Hãy mang đủ tiền mặt và thanh toán tại văn phòng chúng tôi hoặc địa điểm được thông báo cụ thể.</p>
    @elseif ($paymentMethod === 'MoMo')
    <p><strong>Xác nhận thanh toán:</strong> Nếu bạn đã thực hiện thanh toán, hãy bỏ qua email này. Nếu bạn chưa thanh toán, vui lòng sử dụng <a href="http://localhost:3000/booking/payment/{{ $order->id_order_tour }}/idTour/{{ $tour->id_tour }}/date/{{$idDate}}">link trang thanh toán</a> để tiến hành thanh toán theo hướng dẫn.</p>
    @else
    <p>Phương thức thanh toán không hợp lệ.</p>
    @endif
    
    <p>Nếu bạn cần hỗ trợ hoặc có bất kỳ câu hỏi nào, hãy liên hệ với chúng tôi qua email <a href="mailto:hieu745233@gmail.com">[địa chỉ email hỗ trợ]</a> hoặc số điện thoại <a href="tel:0931487873">[số điện thoại hỗ trợ]</a>.</p>
    
    <p>Chúng tôi rất mong đợi được đón tiếp bạn trong chuyến du lịch sắp tới. Xin hãy yên tâm rằng chúng tôi luôn sẵn sàng phục vụ bạn một cách tốt nhất.</p>
    
    <p>Trân trọng,</p>
    <p>Travel2H</p>

    <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn có số đơn: {{ $order->id_order_tour }}</p>

<!-- Hiển thị các thông tin khác về đơn hàng -->

</body>
</html>