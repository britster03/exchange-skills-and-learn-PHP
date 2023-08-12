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

$profileFound = false;
$profileInfo = [];
$selected_user_email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search_username"])) {
    $search_username = $_POST["search_username"];

    $query = "SELECT * FROM dashboard_info WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $search_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $profileFound = true;
        $profileInfo = $result->fetch_assoc();
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen bg-gray-100">
        <div class="bg-blue-500 text-white py-4 text-center">
            <h1 class="text-2xl font-semibold">View Profile</h1>
        </div>
        <div class="p-8 max-w-lg mx-auto">
            <form action="view_profile.php" method="post" class="mb-4">
                <div class="mb-4">
                    <label for="search_username" class="block text-gray-700 font-semibold mb-2">Search by Username</label>
                    <input type="text" id="search_username" name="search_username" class="w-full p-2 border rounded">
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Search</button>
            </form>
            <?php if ($profileFound) { ?>
                <div class="bg-white p-4 rounded shadow-md">
                    <h2>Profile</h2>
                    <p><strong>Username:</strong> <?php echo $profileInfo["username"]; ?></p>
                    <p><strong>Skills Acquired:</strong> <?php echo $profileInfo["skills"]; ?></p>
            <?php } elseif ($_SERVER["REQUEST_METHOD"] === "POST") { ?>
                <p class="text-red-500 mt-2">Profile not found.</p>
            <?php } ?>
        </div>
    </div>
</body>
</html>
