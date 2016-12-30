<?php

/**
 * Logs in a player and sets their connection status to connected as well as
 * retrieve information about the player.
 * Should only be handled using POST request.
 * GET request is for debugging only.
 */


// Import the required classes
include(__DIR__ . '/../php/Player.php');
require_once(__DIR__ . '/../php/Helpers.php');

// Set constants for overly used strings
const USERNAME = "username";
const PASSWORD = "password";

// Default values
$username = null;
$password = null;

$found_user = false;

// Default return data if nothing works
$return_data = array(
    'success' => 0,
    'error' => 'Invalid parameters found'
);

// Get request parameters
if (isset($_POST[USERNAME]) && isset($_POST[PASSWORD])) {
    $username = $_POST[USERNAME];
    $password = $_POST[PASSWORD];
} else if (isset($_GET[USERNAME]) && isset($_GET[PASSWORD])) {
    // TODO: Remove GET method. Only for basic debugging
    $username = $_GET[USERNAME];
    $password = $_GET[PASSWORD];
}

// Check if we got any params
if ($username != null && $password != null) {
    // Open connection
    $connection = Helpers::get_connection();

    // Query and check that username and password are correct
    $query = "SELECT ID FROM player WHERE username='$username' LIMIT 1";
    $result = $connection->query($query);

    // Get results
    if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $id = $row['ID'];
        $player = new Player($id);

        // Check that the player can load information
        if ($player->load_information()) {
            $player->set_connection(1);

            // Set return parameters
            $return_data['success'] = 1;
            $return_data['data'] = $player->get_all_info();
            $return_data['error'] = 0;

            // Valid connection
            $found_user = true;
        } else {
            // Just a safety check. We know the player exists
            $return_data['error'] = 'Invalid player ID';
        }
    } else {
        $return_data['error'] = 'Invalid player ID';
    }

    // Close connection
    $connection->close();

}

// Return JSON encoded data
echo json_encode($return_data);

