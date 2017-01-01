<?php

/**
 * Retrieves all information about the player
 */


include(__DIR__ . '/../../php/Player.php');
require_once(__DIR__ . '/../../php/Helpers.php');

const NEEDS_UPDATE = 'needs_update';
const ID = 'id';

// Player defaults to null
$player = null;
$needs_update = 0;

// Set default return message
$return_data = array(
    'error' => 'Invalid parameters',
    'success' => 0
);

// Get request params
if (isset($_POST[ID]) && isset($_POST[NEEDS_UPDATE])) {
    // ID was sent through a post request
    $id = $_POST[ID];
    $needs_update = $_POST[NEEDS_UPDATE];
    $player = new Player($id);
} else if (isset($_GET[ID]) && isset($_GET[NEEDS_UPDATE])) {
    // ID was sent through a get request
    $id = $_GET[ID];
    $needs_update = $_GET[NEEDS_UPDATE];
    $player = new Player($id);
}

// If player was found,
if ($player != null) {

    $requires_update = (($needs_update && $player->needs_update() == 1) || (!$needs_update));
    if ($requires_update) {
        if ($player->load_information()) {

            // Set success data
            $return_data['data'] = $player->get_all_info();
            $return_data['error'] = 0;
            $return_data['success'] = 1;

        } else {
            // Failed to fetch data so return error message
            $return_data['error'] = "Unable to load player $id information";
        }
    } else {
        $return_data['error'] = "No update required";
    }
}


// Return data
echo json_encode($return_data);