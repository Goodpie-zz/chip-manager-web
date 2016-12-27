<?php

include('../php/GamePlayers.php');
require('../php/Helpers.php');

// Constants for overused strings
const ID = "id";
const AMOUNT = "amount";

// Set default values
$id = null;
$amount = null;

// Get player information from session
if (isset($_POST[ID]) && isset($_POST[AMOUNT])) {
    // Using POST parameters
    $id = $_POST[ID];
    $amount = $_POST[AMOUNT];
} else if (isset($_GET[ID]) && isset($_POST[AMOUNT])) {
    // Using GET parameters
    $id = $_GET[ID];
    $amount = $_GET[AMOUNT];
}

if ($id != null && $amount != null) {
    // Make bid and update player information
    $player = new Player($id);
    $player->load_information();
    $player->place_bid($amount);
    $player->set_needs_update(1);
}