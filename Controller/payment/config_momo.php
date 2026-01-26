<?php
// Thông tin cấu hình MoMo
$momo_partnerCode = 'MOMO'; // Partner Code
$momo_accessKey = 'F8BBA842ECF85'; // Access Key
$momo_secretKey = 'K951B6PE1waDMi640xX08PD3vg6EkVlz'; // Secret Key
$momo_endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
$momo_returnUrl = "http://localhost/doanphp/Controller/payment/momo_return.php"; // URL MoMo trả về sau khi thanh toán
$momo_notifyUrl = "http://localhost/doanphp/Controller/payment/momo_return.php"; // URL MoMo gửi thông báo (IPN)
