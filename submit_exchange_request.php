<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sender_email = $_SESSION["user_email"];
    $receiver_email = $_POST["receiver_email"];
    $request_type = $_POST["request_type"];
    $details = $_POST["details"];

    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "skill";

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO exchange_requests (sender_email, receiver_email, request_type, details) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $sender_email, $receiver_email, $request_type, $details);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    header("Location: view_profile.php");
    exit;
}
?>
