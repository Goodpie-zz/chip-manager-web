<?php

// Open Connection
require_once('php/connect.php');
$connection = getConnection("config.ini");

// Query db
$player_query = "SELECT * FROM `player` WHERE `connected`=1";
$player_result = $connection->query($player_query);

$players = [];


// Loop through results
if ($player_result->num_rows > 0) { // IF players are connected
    while ($row = $player_result->fetch_assoc()) { // Fetch all rows
        $id = $row['ID'];
        $name = $row['name'];
        $chips = $row['chips'];
        $bid = $row['chips_bid'];
        $wins = $row['games_won'];
        $losses = $row['games_lost'];
        $chips_won = $row['chips_won'];
        $chips_lost = $row['chips_lost'];

        // Add to array for JSON conversion
        $player = array(
            "id" => $id,
            "name" => $name,
            "chips" => $chips,
            "bid" => $bid,
            "wins" => $wins,
            "losses" => $losses,
            "chips_won" => $chips_won,
            "chips_lost" => $chips_lost
        );

        array_push($players, $player);
    }

    // Echo converted JSON
    $json_ret = json_encode($players);
    echo $json_ret;
}

// Close Connection
$connection->close();
?>