<?php
session_start();
if (!isset($_SESSION["user_email"])) {
    header("Location: login.php");
    exit;
}

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "skill";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$request_id = $_GET["request_id"];
$sql = "SELECT * FROM exchange_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary CSS links here -->
</head>
<body>
    <!-- Display request status and details here -->
    <p>Request Status: <?php echo $row["status"]; ?></p>
    <?php if ($row["status"] === "accepted") { ?>
        <p>Your exchange request has been accepted by <?php echo $row["sender_email"]; ?>. To know more, start a chat with the user.</p>
    <?php } elseif ($row["status"] === "rejected") { ?>
        <p>Your exchange request has been rejected by <?php echo $row["sender_email"]; ?>.</p>
    <?php } ?>
</body>
</html>
