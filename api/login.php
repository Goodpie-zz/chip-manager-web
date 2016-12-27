<?php

include('../php/Player.php');
require('../php/Helpers.php');

const USERNAME = "username";
const PASSWORD = "password";

// Default values
$username = null;
$password = null;

$found_user = false;

$return_data = array('error' => 'Invalid parameters found');

// Get request parameters
if (isset($_POST[USERNAME]) && isset($_POST[PASSWORD])) {
    $username = $_POST[USERNAME];
    $password = $_POST[PASSWORD];
} else if (isset($_GET[USERNAME]) && isset($_GET[PASSWORD])) {
    $username = $_GET[USERNAME];
    $password = $_GET[PASSWORD];
}

// Check if we got any params
if ($username != null && $password != null) {
    // Open connection
    $connection = Helpers::get_connection();

    // Query and check that username and password are correct
    $query = "SELECT `ID` FROM `player` WHERE `username` = ? AND `password` = ? LIMIT 1";
    $statement = $connection->prepare($query);
    $statement->bind_param('ii', $username, $password);
    $statement->execute();
    $result = $statement->get_result();

    // Get results
    if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $id = $row['ID'];
        $player = new Player($id);
        if ($player->load_information()) {
            $player->set_connection(1);
            $return_data = array('data' => $player->get_all_info());
            $found_user = true;
        } else {
            $return_data = array('error' => 'Unable to find player');
        }
    } else {
        $return_data = array('error' => 'Unable to find player');
    }

    // Free memory
    $statement->free_result();
    $statement->close();

    // Close connection
    $connection->close();

    echo $return_data;

}

