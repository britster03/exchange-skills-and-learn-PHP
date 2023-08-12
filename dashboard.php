<?php
// Start a new session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user_email"])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

// Create a connection to the database (replace with your database credentials)
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "skill";
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user's email from the session
$user_email = $_SESSION["user_email"];

// Check if the user has already submitted the form
$showForm = true;
$query = "SELECT * FROM dashboard_info WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User's details found, hide the form
    $showForm = false;
    $dashboardInfo = $result->fetch_assoc();
}

// Handle form submission
if ($showForm && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"]) && isset($_POST["skills"])) {
    $username = $_POST["username"];
    $skills = $_POST["skills"];

    // Insert data into the dashboard_info table
    $insertQuery = "INSERT INTO dashboard_info (email, username, skills) VALUES (?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("sss", $user_email, $username, $skills);
    $insertStmt->execute();
    $insertStmt->close();

    // Hide the form after submission
    $showForm = false;

    // Refresh the page to display the updated data
    header("Location: dashboard.php");
    exit;
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen bg-gray-100">
        <div class="bg-blue-500 text-white py-4 text-center">
            <h1 class="text-2xl font-semibold">Dashboard</h1>         
        </div>
        <div class="p-8 max-w-lg mx-auto">
            <?php if (!$showForm) { ?>
                <h2 class="text-xl font-semibold mb-2">Your Details</h2>
                <table class="w-full border">
                    <tr class="bg-blue-500 text-white">
                        <th class="py-2 px-4">Username</th>
                        <th class="py-2 px-4">Skills Acquired</th>
                    </tr>
                    <tr>
                        <td class="py-2 px-4"><?php echo $dashboardInfo["username"]; ?></td>
                        <td class="py-2 px-4"><?php echo $dashboardInfo["skills"]; ?></td>
                    </tr>
                </table>
            <?php } ?>
            <?php if ($showForm) { ?>
                <form action="dashboard.php" method="POST">
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
                        <input type="text" id="username" name="username" class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label for="skills" class="block text-gray-700 font-semibold mb-2">Skills Acquired</label>
                        <textarea id="skills" name="skills" class="w-full p-2 border rounded"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Submit</button>
                </form>
            <?php } ?>
            <!-- Add this link where you want to allow users to logout -->
            <form action="logout.php" method="post" class="mt-4">
    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
        Logout
    </button>
</form>
            <form action="view_profile.php" method="post" class="mt-4">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                        View Profile
                </button>
            </form>
            <form action="exchange_requests.php" method="post" class="mt-4">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                    View Exchange Requests
                </button>
            </form>
            
            <button type="submit" onclick="toggleJobRequestForm()" class="mt-4 bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                Make Skill Exchange Request.
            </button>
            <div id="jobRequestForm" class="mt-4" style="display: none;">
        <div class="container">
            <h3>Job Exchange Form</h3>
            <form action="submit_exchange_request.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="skills">Skills</label>
                <textarea name="skills" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="request">Requesting to</label>
                <textarea name="request" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="resume">Upload Resume</label>
                <input type="file" name="resume" class="form-control-file" required accept=".pdf">
            </div>
            <button type="submit" class="btn btn-success">Submit Exchange Request</button>
            </form>
        </div>
    </div>

        </div>
    </div>
    

<script>
    function toggleJobRequestForm() {
        const formDiv = document.getElementById("jobRequestForm");
        formDiv.style.display = formDiv.style.display === "none" ? "block" : "none";
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
