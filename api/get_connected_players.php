<?php

include(__DIR__ . '/../php/Player.php');
require_once(__DIR__ . '/../php/Helpers.php');

// Set default return data
$return_data = array(
    'error' => 'query failed',
    'success' => false,
    'data' => null
);

// Establish connection
$connection = Helpers::get_connection();

// Select all players who are connected
$query = "SELECT ID FROM player WHERE connected=1";
$result = $connection->query($query);

if ($result->num_rows > 0) {
    $return_data['data'] = array('rows' => $result->num_rows);
    $return_data['data']['players'] = array();
    $return_data['success'] = 1;
    $return_data['error'] = 0;
}

// Get all IDs who are connected
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    array_push($return_data['data']['players'], (int)$row['ID']);

}

// Return JSON encoded data
echo json_encode($return_data);