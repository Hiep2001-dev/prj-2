<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông tin đơn hàng</title>
</head>

<body>
    <h1>Cảm ơn bạn đã mua hàng!</h1>
    <p>Mã đơn hàng: {{ $orderDetails['order']->id }}</p>
    <p>Người nhận: {{ $orderDetails['order']->name }}</p>
    <p>Số điện thoại: {{ $orderDetails['order']->phone }}</p>
    <p>Địa chỉ: {{ $orderDetails['order']->address }}</p>
    <p>Phí ship: {{ number_format($orderDetails['order']->transport_fee, 0, ',', '.') }} VNĐ</p>

    <p>Hình thức thanh toán: {{ $orderDetails['order']->payment->name }}</p>


    <h3>Chi tiết sản phẩm:</h3>
    <ul>
        @foreach ($orderDetails['details'] as $detail)
            <li>
                {{ $detail['name'] }}<br>
                Số lượng: {{ $detail['quantity'] }} x {{ number_format($detail['price'], 0, ',', '.') }} VNĐ <br>
                Size: {{ $detail['size'] }} <br>
                Màu: {{ $detail['color'] }}
            </li>
        @endforeach
    </ul>
    <p>Tổng tiền: {{ number_format($orderDetails['order']->total_money, 0, ',', '.') }} VNĐ</p>

    <p>Chúc bạn một ngày vui vẻ!</p>
</body>

</html>
