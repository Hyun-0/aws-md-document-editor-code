<?php
session_start();

$response = array('loggedIn' => false, 'username' => '');

if (isset($_SESSION['username'])) {
    $response['loggedIn'] = true;
    $response['username'] = $_SESSION['username'];  // Display the username
}

echo json_encode($response);
?>

