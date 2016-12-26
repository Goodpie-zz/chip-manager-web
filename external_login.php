<?php
// Open connection
require_once('php/connect.php');
$connection = getConnection("config.ini");

$username = $_GET['username'];
$password = $_GET['password'];

$result = 0;

// Query Database for username
$user_query = "SELECT * FROM `player` WHERE `username`='$username'";
$user_result = $connection->query($user_query);
$user = $user_result->fetch_assoc();

// Check if the user exists
if ($user != NULL && $username != "") {
    // Check password
    if ($password == $user['password']) {
        $result = $user['ID'];
    }
}

// Update the player status to logged in
$connection->query("UPDATE `player` SET `connected`=1 WHERE `ID`=$result");

echo $result;

// Close connection
$connection->close();

?>