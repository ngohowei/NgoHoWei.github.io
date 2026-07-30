<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data (trimmed)
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        echo "Please fill in all fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please provide a valid email address.";
        exit;
    }

    // Escape values for HTML
    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

    // Email details
    $to = 'ngohowei@gmail.com';
    $subject = 'New Contact Form Submission';

    // Use site domain as From to improve deliverability
    $domain = isset($_SERVER['SERVER_NAME']) ? preg_replace('/[^a-z0-9.\-]/i','', $_SERVER['SERVER_NAME']) : 'localhost';
    $from = 'no-reply@' . $domain;
    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $safeEmail\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    // Email body
    $body = "<h2>Contact Form Submission</h2>";
    $body .= "<p><strong>Name:</strong> $safeName</p>";
    $body .= "<p><strong>Email:</strong> $safeEmail</p>";
    $body .= "<p><strong>Message:</strong><br>$safeMessage</p>";

    // Logging & diagnostics
    $logLines = [];
    $logLines[] = "Time: " . date("Y-m-d H:i:s");
    $logLines[] = "IP: " . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N/A');
    $logLines[] = "Name: $safeName";
    $logLines[] = "Email: $safeEmail";
    $logLines[] = "To: $to";
    $logLines[] = "Server name: " . (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'N/A');
    $logLines[] = "sendmail_path: " . ini_get('sendmail_path');
    $logLines[] = "mail_available: " . (function_exists('mail') ? 'yes' : 'no');

    // Attempt to send
    $additional_parameters = '-f' . $from;
    $sent = false;
    if (function_exists('mail')) {
        $sent = @mail($to, $subject, $body, $headers, $additional_parameters);
    }

    $logLines[] = 'mail_result: ' . ($sent ? 'success' : 'failure');
    $logLines[] = 'headers: ' . str_replace("\r\n", ' | ', $headers);

    // Write debug log (email_debug.log in same folder)
    $logFile = __DIR__ . DIRECTORY_SEPARATOR . 'email_debug.log';
    @file_put_contents($logFile, implode("\n", $logLines) . "\n---------------------\n", FILE_APPEND | LOCK_EX);

    if ($sent) {
        echo "Email sent successfully.";
    } else {
        echo "Failed to send email. Check email_debug.log for details.";
    }
} else {
    echo "Invalid request.";
}
?>
