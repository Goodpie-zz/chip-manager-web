<?php

require('../php/Helpers.php');

// Player defaults to null
$player = null;

// Default return data is null
$return_data = array('error' => 'Invalid parameters');

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
        // Return player information
        $return_data = array('data' => $player->get_all_info());
    } else {
        // Failed to fetch data so return error message
        $return_data = array('error' => "Unable to load player $player->getId() information");
    }
}

// Return data
echo json_encode($return_data);