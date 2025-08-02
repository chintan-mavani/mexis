<?php
header('Content-Type: application/json');

// Your email where inquiries will be sent
$to = 'ayanenterprise0821@gmail.com';

// Get form data
$name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$phone = isset($_POST['phone']) ? filter_var($_POST['phone'], FILTER_SANITIZE_STRING) : '';
$company = isset($_POST['company']) ? filter_var($_POST['company'], FILTER_SANITIZE_STRING) : '';
$product = isset($_POST['product']) ? filter_var($_POST['product'], FILTER_SANITIZE_STRING) : '';
$other_product = isset($_POST['other_product']) ? filter_var($_POST['other_product'], FILTER_SANITIZE_STRING) : '';
$quantity = isset($_POST['quantity']) ? filter_var($_POST['quantity'], FILTER_SANITIZE_STRING) : '';
$message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_STRING) : '';

// If "Other" product was selected, use the specified product
if ($product === 'Other' && !empty($other_product)) {
    $product = $other_product;
}

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($product) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
    exit;
}

// Email headers
$headers = "From: Mexis Website <noreply@mexis.in>\r\n";
$headers .= "Reply-To: $name <$email>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// Email subject
$subject = "New Product Inquiry: $product";

// Email body (HTML formatted)
$email_body = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        h2 { color: #2563eb; }
        .detail { margin-bottom: 10px; }
        .label { font-weight: bold; color: #1e293b; }
    </style>
</head>
<body>
    <h2>New Product Inquiry Received</h2>
    
    <div class='detail'><span class='label'>Name:</span> $name</div>
    <div class='detail'><span class='label'>Email:</span> $email</div>
    <div class='detail'><span class='label'>Phone:</span> $phone</div>
    <div class='detail'><span class='label'>Company:</span> " . ($company ? $company : 'Not provided') . "</div>
    <div class='detail'><span class='label'>Product:</span> $product</div>
    <div class='detail'><span class='label'>Estimated Quantity:</span> " . ($quantity ? $quantity : 'Not specified') . "</div>
    <div class='detail'><span class='label'>Message:</span></div>
    <div style='margin-top: 10px; padding: 10px; background: #f1f5f9; border-radius: 5px;'>$message</div>
    
    <p style='margin-top: 20px;'>This inquiry was submitted through the Mexis website contact form.</p>
</body>
</html>
";

// Send email to you
$mail_sent = mail($to, $subject, $email_body, $headers);

if ($mail_sent) {
    // Send confirmation to customer
    $customer_subject = "Thank you for your inquiry - Mexis";
    $customer_headers = "From: Mexis <noreply@mexis.in>\r\n";
    $customer_headers .= "MIME-Version: 1.0\r\n";
    $customer_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $customer_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            h2 { color: #2563eb; }
        </style>
    </head>
    <body>
        <h2>Thank you for contacting Mexis!</h2>
        <p>We have received your inquiry regarding <strong>$product</strong> and our team will get back to you within 24 hours.</p>
        <p>For urgent inquiries, please call us at +91 8460888016.</p>
        <p>Best regards,<br>The Mexis Team</p>
    </body>
    </html>
    ";
    
    mail($email, $customer_subject, $customer_body, $customer_headers);
    
    echo json_encode(['success' => true, 'message' => 'Thank you! Your inquiry has been submitted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'There was an error submitting your inquiry. Please try again later.']);
}
?>