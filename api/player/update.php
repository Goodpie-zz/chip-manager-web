<?php

/**
 * Retrieves all information about the player
 */


include(__DIR__ . '/../../php/Player.php');
require_once(__DIR__ . '/../../php/Helpers.php');

const ERROR = 'error';
const SUCCESS = 'success';
const DATA = 'data';
const NEEDS_UPDATE = 'needs_update';
const PLAYER = 'player';

// Player defaults to null
$player = null;
$needs_update = 0;

// Set default return message
$return_data = array(
    ERROR => 'Invalid parameters',
    SUCCESS => 0
);

// Get request params
if (isset($_POST['id'])) {
    // ID was sent through a post request
    $id = $_POST['id'];
    $player = new Player($id);
} else if (isset($_GET['id'])) {
    // ID was sent through a get request
    $id = $_GET['id'];
    $player = new Player($id);
}

// If player was found,
if ($player != null) {

    if ($player->needs_update()) {
        $return_data[SUCCESS] = 1;
        $return_data[ERROR] = 0;
        $return_data[DATA][NEEDS_UPDATE] = 1;

        $player->update();

        $return_data[DATA][PLAYER] = $player->get_all_info();
    } else {
        $return_data[SUCCESS] = 1;
        $return_data[ERROR] = 0;
        $return_data[DATA][NEEDS_UPDATE] = 0;
    }
}


// Return data
echo json_encode($return_data);