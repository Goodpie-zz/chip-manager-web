<?php


class PlayerTest extends PHPUnit_Framework_TestCase
{

    protected $player;

    /**
     * Setup the test cases
     */
    public function setUp()
    {
        include_once (__DIR__ . '/../php/Player.php');

        // Create a player object and load the information
        $this->player = new Player(1);
        $this->player->load_information();
    }

    /**
     * Tests updating the player information
     */
    public function test_update()
    {
        // Test update on valid player (assuming player exists with id of 1)
        $this->player = new Player(1);
        $this->assertTrue($this->player->load_information(), "Failed to update valid player");

        // Test update on invalid player (assuming no player exists with id of -1)
        $this->player = new Player(-1);
        $this->assertTrue(!$this->player->load_information(), "Updated player with invalid ID");
    }

    /**
     * Test connection status of player
     */
    public function test_connection()
    {
        // First check that the player is connected
        $this->player->set_connection(1);
        $this->assertTrue($this->player->is_connected() == 1);

        // Check the player is disconnected
        $this->player->set_connection(0);
        $this->assertTrue($this->player->is_connected() == 0);
    }

    /**
     * Test for valid chips
     */
    public function test_chips()
    {
        // Check that the chips are greater than 0
        $this->assertGreaterThanOrEqual(0, $this->player->get_chips(), "Invalid chip amount (< 0)");
    }

    /**
     * Test placing a bid
     */
    public function test_place_bid()
    {
        $current_chips = $this->player->get_chips();
        $current_bid = $this->player->get_current_bid();
        $bid_amount = 1;

        // First test local settings with valid bid
        $this->assertTrue($this->player->place_bid($bid_amount));
        $this->assertTrue($this->player->get_current_bid() == $current_bid + $bid_amount);
        $this->assertTrue($this->player->get_chips() == $current_chips - 1);

        // Test server settings
        $this->player->load_information();
        $this->assertTrue($this->player->get_current_bid() == $current_bid + $bid_amount);
        $this->assertTrue($this->player->get_chips() == $current_chips - 1);

        // Test negative bid and invalid amounts
        $this->assertTrue(!$this->player->place_bid(-10));
        $this->assertTrue(!$this->player->place_bid(0));

        $this->player->reset_bid();
    }

    public function test_reset_bid()
    {
        $bid_amount = 100;

        if ($this->player->place_bid($bid_amount)) {
            $result = $this->player->reset_bid();
            $this->assertTrue($result == $bid_amount);
            $this->assertTrue($this->player->get_current_bid() == 0);
        }

    }
}
