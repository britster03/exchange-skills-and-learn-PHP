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

$receiver_email = $_SESSION["user_email"];
$sql = "SELECT * FROM exchange_requests WHERE receiver_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $receiver_email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary CSS links here -->
</head>
<body>
    <!-- Display exchange requests here -->
    <ul>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <li>
                Sender: <?php echo $row["sender_email"]; ?>,
                Request Type: <?php echo $row["request_type"]; ?>,
                Status: <?php echo $row["status"]; ?>,
                <a href="request_status.php?request_id=<?php echo $row["id"]; ?>">View Details</a>
            </li>
        <?php } ?>
    </ul>
</body>
</html>
