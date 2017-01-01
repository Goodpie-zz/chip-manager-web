<?php


// Include files
include(__DIR__ . '/php/Player.php');
require_once(__DIR__ . '/php/Helpers.php');


// Start the session
session_start();

// Make sure the user is already logged in
if (!isset($_SESSION[Helpers::LOGGED_IN]) && !isset($_SESSION[Helpers::PLAYER_SESSION])) {
    // Redirect user to the login page if invalid session variables
    $address = Helpers::get_address();
    header("Location: http://$address/login.php");
} else {
    $player = $_SESSION[Helpers::PLAYER_SESSION];
    $player->load_information();
}

?>

<!DOCTYPE HTML>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">

    <title>Make Bets</title>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,100,900' rel='stylesheet' type='text/css'>

    <!-- Lato Light, Normal, Bold -->
    <link id="bid_style" rel="stylesheet" href="style/bid_page_desktop.css">
</head>

<body>
<input type="hidden" value="<?php echo $player->get_id() ?>" id="player-id">

<div id="main_container">
    <div id="current_bid_container">
        <!-- Displays the players name -->
        <div id="name">
            Player
        </div>
        <!-- Displays player current chips and bid amounts -->
        <div id="current_bid">
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
            <button id="clear_bid_button" onclick="reset_bid()">Clear</button>
        </div>
        <button id="make_bid" onclick="make_bid()">Submit Bid</button>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="js/bid_client.js"></script>
<script src="js/mobile_dection.js"></script>
</body>
</html>