<?php

/**
 * Makes a bid for the player based on the ID given
 * Safety checks are handled in @Player class
 */


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
if (isset($_POST[ID]) && isset($_POST[AMOUNT])) {
    // Using POST parameters
    $id = $_POST[ID];
    $amount = $_POST[AMOUNT];
} else if (isset($_GET[ID]) && isset($_GET[AMOUNT])) {
    // Using GET parameters
    $id = $_GET[ID];
    $amount = $_GET[AMOUNT];
}

// If ID was set, place bid using player object
// Error checking on amount done via player method
if ($id != null && $amount != null) {

    // Make bid and update player information
    $player = new Player($id);

    // Make sure the player information is loaded correctly
    if ($player->load_information()) {

        // Make sure the bid was placed correctly
        if ($player->place_bid($amount)) {
            $player->set_needs_update(1);
            $return_data[DATA] = $player->get_all_info();
            $return_data[SUCCESS] = 1;
            $return_data[ERROR] = 0;
        } else {
            $return_data[ERROR] = "Invalid player ID";
        }


    }
}

// Return data
echo json_encode($return_data);