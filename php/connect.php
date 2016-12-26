<?php

function getConnection($config_file)
{

    // Load the config file
    $config = parse_ini_file($config_file);

    // Setup the connection variables
    $username = $config['username'];
    $password = $config['password'];
    $server = $config['server'];
    $database = $config['database'];

    // Create connection using MySQLI object
    $connection = new mysqli(
        $server,
        $username,
        $password,
        $database
    );

    // PHP will die if there is no connection
    if ($connection->connect_error) {
        die ("Connection failed: " . $connection->connect_error);
    } else {
        return $connection;
    }
}
