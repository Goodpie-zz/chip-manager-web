<?php

class Player
{
    private $id;
    private $username;
    private $chips;
    private $current_bid;
    private $chips_won;
    private $chips_lost;
    private $games_won;
    private $games_lost;

    /**
     * Player constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->username = "username";
        $this->chips = 0;
        $this->chips_won = 0;
        $this->chips_lost = 0;
        $this->games_won = 0;
        $this->games_lost = 0;
        $this->current_bid = 0; // Default current bid to 0
    }

    /**
     * @param $amount
     */
    public function place_bid($amount)
    {
        $connection = Helpers::get_connection();

        // Fetch the current values from the db
        $select_success = false;
        $select_query = "SELECT `current_bid`, `chips` FROM `player` WHERE `ID`=?";
        $select_statement = $connection->prepare($select_query);
        $select_statement->bind_param('i', $this->id);
        $select_statement->execute();

        // Fetch results from database
        $result = $select_statement->get_result();
        if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $select_success = true;

            // Update the local parameters to match database
            $this->current_bid = $row['current_bid'] + $amount;
            $this->chips = $row['chips'] - $amount;
        }

        // Free memory
        $select_statement->free_result();
        $select_statement->close();

        if ($select_success) {
            // Update the database with the new values
            $update_query = "UPDATE `player` SET `current_bid` = ?, chips = ? WHERE `ID`=?";
            $update_statement = $connection->prepare($update_query);
            $update_statement->bind_param('iii', $this->current_bid, $this->chips, $this->id);
            $update_statement->execute();
            $update_statement->close();
        }

        // Close connection
        $connection->close();
    }

    public function set_connection_status($connected)
    {
        // Check that we have a valid parameter
        if ($connected == Helpers::CONNECTED || $connected == Helpers::DISCONNECTED) {
            // Establish connection
            $connection = Helpers::get_connection();

            // Prepare statement and execute
            $query = "UPDATE `player` SET `connected`=? WHERE ID=?";
            $statement = $connection->prepare($query);
            $statement->bind_param('i', $connected);
            $statement->execute();
            $statement->close();

            // Close connection
            $connection->close();
        }
    }

    /**
     * @return string
     */
    public function get_simple_player_info()
    {
        // Return all relevant information about the player
        $player = array(
            'username' => $this->username,
            'chips' => $this->chips,
            'current_bid' => $this->current_bid
        );

        // Encode to JSON and return
        return json_encode($player);
    }

    /**
     * @return string
     */
    public function get_all_player_info()
    {
        // Return all relevant information about the player
        $player = array(
            'username' => $this->username,
            'chips' => $this->chips,
            'current_bid' => $this->current_bid,
            'chips_won' => $this->chips_won,
            'chips_lost' => $this->chips_lost,
            'games_won' => $this->games_won,
            'games_lost' => $this->games_lost
        );

        // Encode to JSON and return
        return json_encode($player);
    }

    /**
     * Updates the player information
     */
    public function update_player()
    {
        if ($this->needs_updating()) {

            // Establish connection
            $connection = Helpers::get_connection();

            // Prepare the query and execute it
            $query = "SELECT * FROM `player` WHERE 'id'=?";
            $statement = $connection->prepare($query);
            $statement->bind_param('i', $id);
            $statement->execute();

            // Get the results from the query
            $result = $statement->get_result();

            // If succeeded, fetch and parse the results
            if ($player_row = $result->fetch_array(MYSQLI_ASSOC)) {

                // Parse the results
                $this->username = $player_row['username'];
                $this->chips = $player_row['chips'];
                $this->current_bid = $player_row['current_bid'];
                $this->chips_won = $player_row['chips_won'];
                $this->chips_lost = $player_row['chips_lost'];
                $this->games_won = $player_row['games_won'];
                $this->games_lost = $player_row['games_lost'];
            }

            // Free memory
            $statement->free_result();
            $statement->close();

            // Change status to does not require update
            $update_query = "UPDATE `player` SET `needs_update`=0 WHERE `ID`=?";
            $update_statement = $connection->prepare($update_query);
            $update_statement->bind_param('i', $this->id);
            $update_statement->execute();
            $update_statement->close();

            // Close connection
            $connection->close();
        }
    }

    /**
     * Checks if the player state need updating
     * @return bool
     */
    private function needs_updating()
    {
        // Establish connection
        $connection = Helpers::get_connection();

        $needs_updating = false;

        // Prepare and execute query
        $query = "SELECT `needs_update` FROM `player` WHERE `ID`=?";
        $statement = $connection->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        // Parse the results
        $result = $statement->get_result();
        if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $needs_updating = $row['needs_updating'];
        }

        // Free memory and close connection
        $statement->free_result();
        $statement->close();
        $connection->close();

        return $needs_updating;
    }

    /**
     * Check if the user is connected
     * @return bool
     */
    public function is_connected()
    {
        // Establish connection
        $connection = Helpers::get_connection();

        $connected = false;

        // Prepare and execute query
        $query = "SELECT `connected` FROM `player` WHERE `ID`=?";
        $statement = $connection->prepare($query);
        $statement->bind_param("i", $this->id);
        $statement->execute();

        // Parse the results
        $result = $statement->get_result();
        if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $connected = $row['connected'];
        }

        $statement->free_result();
        $statement->close();

        // Close connection
        $connection->close();

        return $connected;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return int
     */
    public function getChips(): int
    {
        return $this->chips;
    }

    /**
     * @return int
     */
    public function getCurrentBid(): int
    {
        return $this->current_bid;
    }

    /**
     * @return int
     */
    public function getChipsWon(): int
    {
        return $this->chips_won;
    }

    /**
     * @return int
     */
    public function getChipsLost(): int
    {
        return $this->chips_lost;
    }

    /**
     * @return int
     */
    public function getGamesWon(): int
    {
        return $this->games_won;
    }

    /**
     * @return int
     */
    public function getGamesLost(): int
    {
        return $this->games_lost;
    }



}