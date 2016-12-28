<?php

require_once(__DIR__ . '/../php/Helpers.php');

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
     * @return bool
     */
    public function place_bid($amount)
    {
        $connection = Helpers::get_connection();
        $bid_placed = false;

        if ($amount <= $this->chips && $amount > 0) {
            // Fetch the current values from the db
            $select_success = false;
            $select_query = "SELECT current_bid, chips FROM player WHERE ID=$this->id LIMIT 1";

            // Fetch results from database
            $result = $connection->query($select_query);
            if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $select_success = true;

                // Update the local parameters to match database
                $this->current_bid = $row['current_bid'] + $amount;
                $this->chips = $row['chips'] - $amount;
            }

            if ($select_success) {
                // Update the database with the new values
                $update_query = "UPDATE player SET current_bid = ?, chips = ? WHERE ID=?";
                $update_statement = $connection->prepare($update_query);
                $update_statement->bind_param('iii', $this->current_bid, $this->chips, $this->id);
                $update_statement->execute();
                $update_statement->close();
                $bid_placed = true;
            }
        }

        // Close connection
        $connection->close();

        return $bid_placed;
    }

    public function set_connection($connected)
    {
        // Check that we have a valid parameter
        if ($connected == Helpers::CONNECTED || $connected == Helpers::DISCONNECTED) {
            // Establish connection
            $connection = Helpers::get_connection();

            // Prepare statement and execute
            $query = "UPDATE `player` SET `connected`=? WHERE ID=?";
            $statement = $connection->prepare($query);
            $statement->bind_param('ii', $connected, $this->id);
            $statement->execute();
            $statement->close();

            // Close connection
            $connection->close();
        }
    }

    /**
     * @return string
     */
    public function get_simple_info()
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
    public function get_all_info()
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
    public function update()
    {
        $updated = false;
        if ($this->needs_update()) {

            $connection = Helpers::get_connection();

            // Fetch all player information
            $this->load_information();

            // Change status to does not require update
            $update_query = "UPDATE `player` SET `needs_update`=0 WHERE `ID`=?";
            $update_statement = $connection->prepare($update_query);
            $update_statement->bind_param('i', $this->id);
            $update_statement->execute();
            $update_statement->close();

            // Close connection
            $connection->close();

            $updated = true ;
            $this->set_needs_update(0);
        }

        return $updated;
    }

    /**
     * Checks if the player state need updating
     * @return bool
     */
    private function needs_update()
    {
        // Establish connection
        $connection = Helpers::get_connection();

        $needs_updating = false;

        // Prepare and execute query
        $query = "SELECT needs_update FROM player WHERE ID=$this->id";

        // Parse the results
        $result = $connection->query($query);
        if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $needs_updating = $row['needs_update'];
        }

        // c3lose connection
        $connection->close();

        return $needs_updating;
    }

    /**
     * Loads all player information
     */
    public function load_information()
    {
        // Establish connection
        $connection = Helpers::get_connection();

        // Return boolean if successful
        $success = true;

        // Prepare the query and execute it
        $query = "SELECT * FROM player WHERE ID=$this->id LIMIT 1";

        // Get the results from the query
        $result = $connection->query($query);

        if (!$result)
        {
            echo $connection->error;
        }

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
        } else {
            $success = false;
        }


        $connection->close();

        return $success;
    }

    public function set_needs_update($needs_update)
    {
        $connection = Helpers::get_connection();

        // Update the status of needs update on player
        $query = "UPDATE `player` SET `needs_update`=? WHERE ID=?";
        $statement = $connection->prepare($query);
        $statement->bind_param('ii', $needs_update, $this->id);
        $statement->execute();
        $statement->close();

        $connection->close();
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
        $query = "SELECT connected FROM player WHERE ID=$this->id LIMIT 1";

        // Parse the results
        $result = $connection->query($query);
        if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $connected = $row['connected'];
        }

        // Close connection
        $connection->close();

        return $connected;
    }

    /**
     * @return mixed
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function get_username(): string
    {
        return $this->username;
    }

    /**
     * @return int
     */
    public function get_chips(): int
    {
        return $this->chips;
    }

    /**
     * @return int
     */
    public function get_current_bid(): int
    {
        return $this->current_bid;
    }

    /**
     * @return int
     */
    public function get_chips_won(): int
    {
        return $this->chips_won;
    }

    /**
     * @return int
     */
    public function get_chips_lost(): int
    {
        return $this->chips_lost;
    }

    /**
     * @return int
     */
    public function get_games_won(): int
    {
        return $this->games_won;
    }

    /**
     * @return int
     */
    public function get_games_lost(): int
    {
        return $this->games_lost;
    }


}