<?php
// Starting the session
include('php/Player.php');

session_start();

require('php/Helpers.php');

// Make sure the user is already logged in
if (!isset($_SESSION[Helpers::LOGGED_IN]) && !isset($_SESSION[Helpers::PLAYER_SESSION])) {
    // Redirect user to the login page if invalid session variables
    $address = Helpers::get_address();
    header("Location: http://$address/login.php");
} else {
    $player = $_SESSION[Helpers::PLAYER_SESSION];
    $player->update_player();
}

?>

<!DOCTYPE HTML>

<html>
<head>
    <title>Make Bets</title>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,100,900' rel='stylesheet' type='text/css'>
    <!-- Lato Light, Normal, Bold -->
    <link rel="stylesheet" href="css/bid_page_desktop.css">
</head>

<body>
<div id="main_container">
    <div id="current_bid_container">
        <!-- Displays the players name -->
        <div id="name">
            Player
        </div>
        <!-- Displays player current chips and bid amounts -->
        <div id="bid_chips">
            0
        </div>
        <div id="current_chips">
            0
        </div>
        <div id="bid_amounts">
            <button id="bid_button" onclick="add_bid(1)">+1</button>
            <button id="bid_button" onclick="add_bid(5)">+5</button>
            <button id="bid_button" onclick="add_bid(10)">+10</button>
            <button id="bid_button" onclick="add_bid(50)">+50</button>
            <button id="clear_bid_button" onclick="clear_bid()">Clear</button>
        </div>
        <button id="make_bid" onclick="make_bid()">Submit Bid</button>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="js/bid_handling.js"></script>
</body>
</html>