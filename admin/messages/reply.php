<?php
require_once '../../config.php';
checkAdminLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $messageId = (int)$_POST['messageId'];
    $recipientEmail = trim($_POST['email']);
    $recipientName = trim($_POST['name']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Validation
    if (empty($recipientEmail) || empty($subject) || empty($message)) {
        echo json_encode([
            'success' => false,
            'message' => 'Бүх талбарыг бөглөнө үү!'
        ]);
        exit;
    }
    
    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'И-мэйл хаяг буруу байна!'
        ]);
        exit;
    }
    
    // Get site settings
    $siteName = getSetting('site_name');
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $siteName . " <noreply@" . $_SERVER['HTTP_HOST'] . ">" . "\r\n";
    $headers .= "Reply-To: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
    
    // Email body
    $emailBody = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
            .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
            .message { background: white; padding: 15px; border-left: 4px solid #667eea; margin: 20px 0; white-space: pre-wrap; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 5px 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>" . htmlspecialchars($siteName) . "</h2>
            </div>
            <div class='content'>
                <p>Сайн байна уу " . htmlspecialchars($recipientName) . ",</p>
                <div class='message'>
                    " . nl2br(htmlspecialchars($message)) . "
                </div>
                <p>Хүндэтгэсэн,<br><strong>" . htmlspecialchars($siteName) . "</strong></p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " " . htmlspecialchars($siteName) . ". Бүх эрх хуулиар хамгаалагдсан.</p>
                <p>Энэ и-мэйл автоматаар илгээгдсэн. Хариу бичих шаардлагагүй.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send email
    $mailSent = @mail($recipientEmail, $subject, $emailBody, $headers);
    
    if ($mailSent) {
        // Log the reply (optional - create a replies table if needed)
        // For now, we'll just return success
        
        echo json_encode([
            'success' => true,
            'message' => 'Хариу амжилттай илгээгдлээ!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'И-мэйл илгээхэд алдаа гарлаа. Серверийн mail() функц тохируулагдаагүй байж магадгүй.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Буруу хүсэлт!'
    ]);
}
?>