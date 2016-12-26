<?php
session_start();
// Open Connection
require_once('php/connect.php');
$connection = getConnection("config.ini");

// Retrieve post variables
if (isset($_GET['id']) && isset($_GET['amount'])) {
    $bid_amount = $_GET['amount'];
    $id = $_GET['id'];
} else {
    $bid_amount = $_POST['amount'];
    $id = $_SESSION['id'];
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
?>