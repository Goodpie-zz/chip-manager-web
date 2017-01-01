<?php


include(__DIR__ . '/../../php/Player.php');
require_once(__DIR__ . '/../../php/Helpers.php');

// Constants for overused strings
const ID = "id";
const AMOUNT = "amount";
const ERROR = 'error';
const SUCCESS = 'success';
const DATA = 'data';

// Set default values
$id = null;
$amount = null;

// Set default return message
$return_data = array(
    ERROR => 'Invalid parameters',
    SUCCESS => 0
);

// Get player information from session
if (isset($_POST[ID])) {
    // Using POST parameters
    $id = $_POST[ID];
} else if (isset($_GET[ID])) {
    // Using GET parameters
    $id = $_GET[ID];
}

// If ID was set, place bid using player object
// Error checking on amount done via player method
if ($id != null) {

    // Make bid and update player information
    $player = new Player($id);
    $player->load_information();
    $player->reset_bid();
    $player->set_needs_update(1);

    $return_data[DATA] = $player->get_all_info();
    $return_data[SUCCESS] = 1;
    $return_data[ERROR] = 0;
}

// Return data
echo json_encode($return_data);