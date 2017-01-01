<?php

/**
 * Handles the player winning a game
 */

include(__DIR__ . '/../../php/Player.php');
require_once(__DIR__ . '/../../php/Helpers.php');

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

// If player is not null, we have a valid player
if ($player != null) {

    $player->load_information();
    $total_earnings = 0;

    // Open connection
    $connection = Helpers::get_connection();

    // First, get all the earnings the player has won
    $query = "SELECT ID, current_bid FROM player WHERE connected=1";
    $result = $connection->query($query);

    if ($result) {
        // Add all the rewards
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $id = $row['ID'];
            if ($id != $player->get_id()) {
                // Reset the bid and add it to the total earnings
                $losing_player = new Player((int)$row['ID']);
                $losing_player->load_information();
                $chips_lost = $losing_player->remove_bid_chips();
                $total_earnings += $chips_lost;

                // Update losing player
                $query = "UPDATE player SET chips_lost = chips_lost + ?, games_lost = games_lost + 1, needs_update=1 WHERE ID=?";
                $statement = $connection->prepare($query);
                $statement->bind_param("ii", $chips_lost, $id);
                $statement->execute();
                $statement->close();
            }
        }

        // Now update the current player
        $total_earnings += $player->remove_bid_chips();
        $query = "UPDATE player SET chips_won = chips_won + ?, games_won = games_won + 1, chips = chips + ?, needs_update=1 WHERE ID=?";
        $statement = $connection->prepare($query);
        $id = $player->get_id();
        $statement->bind_param('iii', $total_earnings, $total_earnings, $id);
        $statement->execute();
        $statement->close();

        // Set the success return data
        $return_data['success'] = 1;
        $return_data['error'] = 0;
        $return_data['data']['winner'] = $player->get_id();
        $return_data['data']['chips_won'] = $total_earnings;
    } else {
        $return_data['error'] = "Failed to connect to database";
    }

    // Close connection
    $connection->close();
}

echo json_encode($return_data);