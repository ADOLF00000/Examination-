<?php
include("../../../conn.php");

// Check if database connection exists
if (!isset($conn) || $conn === null) {
    die(json_encode(array("res" => "error", "msg" => "Database connection failed")));
}

function sendCredentials($email, $password, $fullname) {
    // Gmail SMTP settings
    $smtpServer = "smtp.gmail.com";
    $smtpPort = 587;
    $smtpUsername = "calumpanghs123@gmail.com"; // Replace with your Gmail address
    $smtpPassword = "gtfqqqxcsxgqswpd"; // Replace with your Gmail app password
    
    $to = $email;
    $subject = 'Your Exam System Login Credentials';
    
    $message = "
    <html>
    <head>
        <title>Exam System Login Credentials</title>
    </head>
    <body>
        <h2>Welcome to the Exam System!</h2>
        <p>Dear $fullname,</p>
        <p>Your account has been created. Here are your login credentials:</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Password:</strong> $password</p>
        <p>Please login using these credentials and change your password after your first login.</p>
        <p>Best regards,<br>Exam System Admin</p>
    </body>
    </html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Exam System <$smtpUsername>" . "\r\n";
    $headers .= "Reply-To: $smtpUsername" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Set SMTP settings
    ini_set("SMTP", $smtpServer);
    ini_set("smtp_port", $smtpPort);
    ini_set("sendmail_from", $smtpUsername);
    ini_set("sendmail_path", "C:\\xampp\\sendmail\\sendmail.exe -t");

    return mail($to, $subject, $message, $headers);
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if exmne_id is set
    if (!isset($_POST['exmne_id'])) {
        die(json_encode(array("res" => "error", "msg" => "No examinee ID provided")));
    }

    $exmne_id = $_POST['exmne_id'];
    
    try {
        // Get examinee details
        $stmt = $conn->prepare("SELECT * FROM examinee_tbl WHERE exmne_id = ?");
        $stmt->execute([$exmne_id]);
        $selExmne = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($selExmne) {
            $email = $selExmne['exmne_email'];
            $password = $selExmne['exmne_password'];
            $fullname = $selExmne['exmne_fullname'];
            
            if (sendCredentials($email, $password, $fullname)) {
                $res = array("res" => "success");
            } else {
                $res = array("res" => "failed", "msg" => "Failed to send email");
            }
        } else {
            $res = array("res" => "not_found", "msg" => "Examinee not found");
        }
    } catch (PDOException $e) {
        $res = array("res" => "error", "msg" => "Database error: " . $e->getMessage());
    }
    
    echo json_encode($res);
}
?> 