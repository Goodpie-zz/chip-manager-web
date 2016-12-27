<?php

include('php/GamePlayers.php');
require('php/Helpers.php');

session_start();

$connection = Helpers::get_connection();

// Get player information from session
if (isset($_SESSION[Helpers::PLAYER_SESSION]) && isset($_SESSION[Helpers::LOGGED_IN])) {
    // Load player information
    $player = $_SESSION[Helpers::PLAYER_SESSION];
    $player->load_player_information();
    $player_information = $player->get_all_player_info();
}


// Get user information for update
$sql = "SELECT * FROM `player` WHERE ID=$id";
$result = mysqli_query($connection, $sql);
$row = $row = mysqli_fetch_assoc($result);

// Get all information required
$current_chips = $row['chips'];
$bid_chips = $row['chips_bid'];


// Update information
$new_chips = $current_chips + $bid_chips - $bid_amount;
$update_sql = "UPDATE `player` SET `chips_bid`=$bid_amount, `chips`=$new_chips WHERE `ID`=$id";
$connection->query($update_sql);

// Return information for update
$player = array(
    "id" => $id,
    "chips" => $new_chips,
    "bid" => $bid_amount
);

echo json_encode($player);

// Close connection
$connection->close();