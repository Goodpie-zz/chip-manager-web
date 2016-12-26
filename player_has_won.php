<?php

require_once('php/connect.php');
$connection = getConnection("config.ini");

// Retrieve winning player ID
$winner_id = $_POST['id'];

// Get all currently playing players
$players_query = "SELECT * FROM `player` WHERE `connected`=1";
$player_result = $connection->query($players_query);

// Initiate winning player varaibles
$win_amount = 0;
$winner_chips = 0;
$winner_chips_won = 0;
$winner_wins = 0;
$winner_bet = 0;

if ($player_result->num_rows > 0) {

    // Loop through each player and get their current information
    while ($row = $player_result->fetch_assoc()) {
        $id = $row['ID'];
        $chips = $row['chips'];
        $bid = $row['chips_bid'];
        $wins = $row['games_won'];
        $losses = $row['games_lost'];
        $chips_won = $row['chips_won'];
        $chips_lost = $row['chips_lost'];

        // If the current player is NOT the winner, remove their bid and add a loss
        if ($id != $winner_id) {
            $win_amount += $bid;
            $chips_lost += $bid;
            $losses += 1;
            $bid = 0;
            $connection->query("UPDATE `player` SET `chips_lost`=$chips_lost, `games_lost`=$losses, `chips_bid`=0, `needs_update`=1 WHERE `ID`=$id");
            // Else, add their bid back to their chip pile and add a win AND get current information
        } else {
            $winner_chips = $chips + $bid;
            $winner_chips_won = $chips_won;
            $winner_wins = $wins;
        }
    }

    // Update player winnings
    $winner_chips += $win_amount;
    $winner_wins += 1;
    $winner_chips_won += $win_amount;
    $connection->query("UPDATE `player` SET `chips`=$winner_chips, `chips_won`=$winner_chips_won, `games_won`=$winner_wins, `chips_bid`=0, `needs_update`=1 WHERE `ID`=$winner_id");

}

$connection->close();


?>