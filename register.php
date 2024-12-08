<?php
// Connect to the database
$servername = "final-database.c3ymyq00sgfm.ap-northeast-2.rds.amazonaws.com";
$username = "admin";
$password = "admin1234";
$dbname = "project_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepare SQL query to insert the new user into the database
    $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";

    if ($conn->query($sql) === TRUE) {
        echo "회원가입이 완료되었습니다.";
        header("Location: login.html"); // Redirect to login page after successful registration
        exit();
    } else {
        echo "에러: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

