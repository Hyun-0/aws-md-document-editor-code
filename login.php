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

    // Prepare SQL query to fetch user from the database
    $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    $result = $conn->query($sql);

    // If user exists
    if ($result->num_rows > 0) {
        // Redirect to index page with user ID session
        session_start();
        $_SESSION['username'] = $user;
        header("Location: index.html"); // Redirect to index.html after successful login
        exit();
    } else {
        echo "로그인 실패. 아이디 또는 비밀번호를 확인해주세요.";
    }
}

$conn->close();
?>

