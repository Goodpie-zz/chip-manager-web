<?php

// Import classes
require_once(__DIR__ . '/php/Helpers.php');
include(__DIR__ . '/php/Player.php');

// Start session
session_start();

// Get the current user
if (isset($_SESSION[Helpers::PLAYER_SESSION])) {
    $player = $_SESSION[Helpers::PLAYER_SESSION];
    $player->set_connection_status(1);
    session_regenerate_id();
}

