<?php
// Start session
session_start();

// Connect to database
require_once('php/connect.php');
$connection = getConnection("config.ini");

if (isset($_SESSION['id'])) {
    // Set the player as connected
    $id = $_SESSION['id'];
    $connection->query("UPDATE `player` SET `connected`=1 WHERE `ID`=$id");
}

// Close connection
$connection->close();
?>