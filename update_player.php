<?php
session_start();
require_once('php/connect.php');
$connection = getConnection("config.ini");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = $_SESSION['id'];
}

$players_query = "SELECT * FROM `player` WHERE `ID`=$id";
$player_result = $connection->query($players_query);
$player_row = mysqli_fetch_assoc($player_result);

// Gets the players chips and bid to update UI
if ($player_row['needs_update'] == 1) {
    $chips = $player_row['chips'];
    $bid = $player_row['chips_bid'];
    $name = $player_row['name'];
    $p = array(
        'name' => $name,
        'chips' => $chips,
        'bid' => $bid
    );

    $connection->query("UPDATE `player` SET `needs_update`=0 WHERE `ID`=$id");

    // Returns JSON
    echo json_encode($p);
} else {
    echo 0;
}

// Close connection
$connection->close();
?>
    