<?php 
session_start();
include("../../../conn.php");

// Check if database connection exists
if (!isset($conn) || $conn === null) {
    die(json_encode(array("res" => "error", "msg" => "Database connection failed")));
}

extract($_POST);

// Validate input
if (empty($username) || empty($pass)) {
    die(json_encode(array("res" => "error", "msg" => "Username and password are required")));
}

try {
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin_acc WHERE admin_user = :username AND admin_pass = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $pass);
    $stmt->execute();
    
    $selAccRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0) {
        $_SESSION['admin'] = array(
            'admin_id' => $selAccRow['admin_id'],
            'adminnakalogin' => true
        );
        $res = array("res" => "success");
    } else {
        $res = array("res" => "invalid");
    }
} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    $res = array("res" => "error", "msg" => "Database error occurred");
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    $res = array("res" => "error", "msg" => "An error occurred");
}

echo json_encode($res);
?>