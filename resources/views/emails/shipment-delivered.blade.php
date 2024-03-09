<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Delivered</title>
</head>
<body>
<!-- Header -->
<div style="background-color: #f8f9fa; padding: 20px; text-align: center;">
    <img src="http://127.0.0.1:8000/img/sudanbridge.png" alt="{{ config('app.name') }} Logo"  style="max-width: 200px;">
</div>

<!--body-->
<div style="padding: 20px;">
    <h1 style="text-align: center;">Your Shipment Has Been Delivered!</h1>
    <p>Dear <strong>{{ $recipientName }}</strong>,</p>
    <p>You got your shipment delivered successfully! 🎉</p>
    <p>Thank you for choosing {{ config('app.name') }} for your shipping needs. We appreciate your trust in us.</p>
    <p>If you have a moment, we'd love to hear about your experience with our service. Your feedback helps us improve and serve you better. Please take a moment to rate your experience by clicking the link below:</p>
    <p style="text-align: center;">
        <a href="#" style="background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; display: inline-block;">Rate Our Service</a>
    </p>
    <p>Once again, thank you for choosing {{ config('app.name') }}. We look forward to serving you again in the future!</p>
    <p>Best regards,<br>{{ config('app.name') }} Team</p>
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
