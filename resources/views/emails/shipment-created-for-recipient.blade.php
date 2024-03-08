<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Created</title>
</head>
<body>
<!-- Header -->
<div style="background-color: #f8f9fa; padding: 20px; text-align: center;">
    <img src="http://127.0.0.1:8000/img/sudanbridge.png" alt="{{ config('app.name') }} Logo"  style="max-width: 200px;">
</div>

<!-- Body -->
<div style="padding: 20px;">
    <h1 style="text-align: center;">Shipment Created</h1>
    <p>Dear <strong>{{ $shipment->recipient->name }}</strong>,</p>
    <p>Your package <strong>{{ $shipment->item->name }}</strong> will be shipped soon. Here are the details:</p>
    <ul>
        <li><strong>Sender Name:</strong> {{ $shipment->sender->name }}</li>
        <li><strong>Tracking Token:</strong> {{ $shipment->tracking_token }}</li>
    </ul>
    <p style="text-align: center;">
        <a href="http://127.0.0.1:8000/tracking" style="background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; display: inline-block;">Track Shipment</a>
    </p>
</div>

<!-- Footer -->
<div style="background-color: #f8f9fa; padding: 20px; text-align: center;">
    <div style="margin: 0 auto; max-width: 600px; align-items: center; text-align: center; align-content: center;">
        <p>{{ config('app.name') }}</p>
        <p>123 St, Khartoum, Sudan</p>
        <p>Phone: +123456789</p>
        <p>Email: info@sudanbridge.com</p>
    </div>

    <!-- Social media icons with links -->
    <a href="#"><img src="http://127.0.0.1:8000/svg/facebook.svg" alt="Facebook" style="margin-right: 10px;"></a>
    <a href="#"><img src="http://127.0.0.1:8000/svg/X.svg" alt="Twitter" style="margin-right: 10px;"></a>
    <a href="#"><img src="http://127.0.0.1:8000/svg/instagram.svg" alt="Instagram" style="margin-right: 10px;"></a>
</div>
</body>
</html>
