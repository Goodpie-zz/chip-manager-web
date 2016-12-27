<?php
// Start session
session_start();

// Connect to database
require_once('php/Helpers.php');

// Get the current user
if (isset($_SESSION[Helpers::PLAYER_SESSION])) {
    $player = $_SESSION[Helpers::PLAYER_SESSION];
    $player->set_connection_status(1);
}

