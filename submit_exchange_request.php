<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $request = $_POST['request'] ?? '';



    $uploadDirectory = "pdf/";
    $resumePath = $uploadDirectory . basename($_FILES["resume"]["name"]);
    move_uploaded_file($_FILES["resume"]["tmp_name"], $resumePath);


    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "skill";


    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

  
    $sql_insert = "INSERT INTO exchange_request_info (name, email, skills, request, resume_path) VALUES ('$name', '$email', '$skills','$request', '$resumePath')";
    if ($conn->query($sql_insert) === TRUE) {

        header("Location: dashboard.php"); 
    } else {

        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }

    

    $conn->close();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
