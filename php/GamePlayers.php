<?php

class GamePlayers
{
    private $players;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $players = array();
    }

    /**
     * Checks for uniquely connected players
     */
    public function check_connected_players()
    {
        require('php/Helpers.php');

        // Establish connection
        $connection = Helpers::get_connection();

        // Prepare query
        $query = "SELECT `ID` FROM `player` WHERE connected=1";
        $statement = $connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();

        // Parse results
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            // Fetch all the player information
            $new_player = $row['ID'];

            // Just refresh all the current players
            $this->players = array();
            array_push($this->players, $new_player);
        }

        // Free memory
        $statement->free_result();
        $statement->close();

        // Close connection
        $connection->close();
    }

    /**
     * Returns all the currently connected players
     * @return string
     */
    public function get_connected_players()
    {
        return json_encode($this->players);
    }

}