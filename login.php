<?php
// Start a new session
session_start();

// Check if the user is already logged in
if (isset($_SESSION["user_email"])) {
    // Redirect to the dashboard if already logged in
    header("Location: dashboard.php");
    exit;
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $email = $_POST["email"];
    $password = $_POST["password"];

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

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows === 1) {
        // Bind the result to variables
        $stmt->bind_result($fetchedEmail, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, store user's email in the session
            $_SESSION["user_email"] = $email;

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            // Password is incorrect
            $loginError = "Incorrect email or password.";
        }
    } else {
        // User not found
        $loginError = "Incorrect email or password.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-md max-w-sm w-full">
            <h1 class="text-2xl font-semibold mb-4">Login</h1>
            <?php if (isset($loginError)) { ?>
                <p class="text-red-600"><?php echo $loginError; ?></p>
            <?php } ?>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full p-2 border rounded">
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
