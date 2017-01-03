<?php

/**
 * Class Helpers
 *
 * Contains static functions and constants that are there to help!
 */
class Helpers
{
    // Connection constants
    const CONNECTED = 1;
    const DISCONNECTED = 0;

    // Session variables
    const PLAYER_SESSION = "current_player";
    const LOGGED_IN = "logged_in";

    /**
     * Gets server address
     *
     * Gets the server address from the config file in the root directory
     *
     * @return mixed
     */
    public static final function get_address()
    {
        $config = parse_ini_file(__DIR__ . '/../config.ini');
        return $config['address'];
    }

    /**
     * Connect to database
     *
     * Establishes connection to the database by loading parameters from a config file in the root directory and
     * creating a MySQLi object
     *
     * @return mysqli
     */
    public static final function get_connection()
    {
        // Load the config file
        $config = parse_ini_file(__DIR__ . '/../config.ini');


        // Setup the connection variables
        $username = $config['username'];
        $password = $config['password'];
        $server = $config['server'];
        $database = $config['database'];

        // Create connection using MySQLi object
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

    /**
     * Logs error message
     *
     * Used to log error messages to the defined error file in the config
     *
     * @param string $message Message to log
     */
    public static final function log_error_message($message)
    {
        $config = $config = parse_ini_file(__DIR__ . '/../config.ini');
        $log_file = $config['logfile'];

        // Log the error
        error_log($message, 3, $log_file);
    }
}