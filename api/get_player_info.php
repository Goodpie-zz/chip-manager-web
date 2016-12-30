<?php

/**
 * Retrieves all information about the player
 */

include(__DIR__ . '/../php/Player.php');
require_once(__DIR__ . '/../php/Helpers.php');

// Player defaults to null
$player = null;

// Set default return message
$return_data = array(
    'error' => 'Invalid parameters',
    'success' => 0
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
    if ($player->load_information()) {

        // Set success data
        $return_data['data'] = $player->get_all_info();
        $return_data['error'] = 0;
        $return_data['success'] = 1;

    } else {
        // Failed to fetch data so return error message
        $return_data['error'] = "Unable to load player $id information";
    }
}

// Return data
echo json_encode($return_data);