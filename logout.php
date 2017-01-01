<?php
include(__DIR__ . '/php/Player.php');
require_once(__DIR__ . '/php/Helpers.php');

session_start();

if (isset($_SESSION[Helpers::LOGGED_IN]) && isset($_SESSION[Helpers::PLAYER_SESSION])) {
    // Disconnect player
    $player = $_SESSION[Helpers::PLAYER_SESSION];
    $player->set_connection(0);

    // Unset the session variables
    unset($_SESSION[Helpers::LOGGED_IN]);
    unset($_SESSION[Helpers::PLAYER_SESSION]);
}

// Redirect the user to the login page
$address = Helpers::get_address();
header("Location: http://$address/login.php");