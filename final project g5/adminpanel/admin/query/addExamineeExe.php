<?php 
include("../../../conn.php");

// Check if database connection exists
if (!isset($conn) || $conn === null) {
    die(json_encode(array("res" => "error", "msg" => "Database connection failed")));
}

// Check if PHPMailer exists
if (!file_exists("../../../vendor/autoload.php")) {
    die(json_encode(array("res" => "error", "msg" => "PHPMailer not installed. Please run 'composer install' first")));
}

require_once("../../../vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

extract($_POST);

try {
    // Validate required fields
    if (empty($fullname) || empty($email) || empty($bdate)) {
        throw new \Exception("Required fields are missing");
    }

    $selExamineeFullname = $conn->query("SELECT * FROM examinee_tbl WHERE exmne_fullname='$fullname' ");
    $selExamineeEmail = $conn->query("SELECT * FROM examinee_tbl WHERE exmne_email='$email' ");

    if($gender == "0")
    {
        $res = array("res" => "noGender");
    }
    else if($course == "0")
    {
        $res = array("res" => "noCourse");
    }
    else if($year_level == "0")
    {
        $res = array("res" => "noLevel");
    }
    else if($selExamineeFullname->rowCount() > 0)
    {
        $res = array("res" => "fullnameExist", "msg" => $fullname);
    }
    else if($selExamineeEmail->rowCount() > 0)
    {
        $res = array("res" => "emailExist", "msg" => $email);
    }
    else
    {
        // Use birthdate as password
        $password = $bdate;
        
        $insData = $conn->query("INSERT INTO examinee_tbl(exmne_fullname,exmne_course,exmne_gender,exmne_birthdate,exmne_year_level,exmne_email,exmne_password) VALUES('$fullname','$course','$gender','$bdate','$year_level','$email','$password')  ");
        
        if($insData)
        {
            try {
                // Create a new PHPMailer instance
                $mail = new PHPMailer(true);

                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'calumpanghs123@gmail.com';
                $mail->Password = 'deyq aywq spnq rqgi';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Enable verbose debug output
                $mail->SMTPDebug = 3;
                $mail->Debugoutput = function($str, $level) {
                    error_log("PHPMailer Debug: $str");
                };

                // Set timeout
                $mail->Timeout = 60;

                // Recipients
                $mail->setFrom('calumpanghs123@gmail.com', 'Exam System Admin');
                $mail->addAddress($email, $fullname);

                // Content
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Your Exam System Login Credentials';
                $mail->Body = "
                    <h2>Welcome to the Exam System!</h2>
                    <p>Dear $fullname,</p>
                    <p>Your account has been created. Here are your login credentials:</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Password:</strong> $password</p>
                    <p>Please login using these credentials and change your password after your first login.</p>
                    <p>Best regards,<br>Exam System Admin</p>
                ";

                try {
                    if($mail->send()) {
                        error_log("Email sent successfully to: " . $email);
                        $res = array("res" => "success", "msg" => $email);
                    } else {
                        throw new Exception("Email could not be sent. Mailer Error: " . $mail->ErrorInfo);
                    }
                } catch (Exception $e) {
                    error_log("Email Error: " . $e->getMessage());
                    $res = array("res" => "success_no_mail", "msg" => $email . " - Error: " . $e->getMessage());
                }
            } catch (Exception $e) {
                error_log("Email Error: " . $e->getMessage());
                $res = array("res" => "success_no_mail", "msg" => $email . " - Error: " . $e->getMessage());
            }
        }
        else
        {
            $res = array("res" => "failed", "msg" => "Failed to insert examinee data");
        }
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $res = array("res" => "error", "msg" => "Database error: " . $e->getMessage());
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    $res = array("res" => "error", "msg" => "An error occurred: " . $e->getMessage());
}

echo json_encode($res);
?>