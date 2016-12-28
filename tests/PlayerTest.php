<?php


class PlayerTest extends PHPUnit_Framework_TestCase
{

    protected $player;

    public function setUp()
    {
        include_once (__DIR__ . '/../php/Player.php');
        $this->player = new Player(1);
        $this->player->load_information();
    }

    public function test_update()
    {
        // Test update on valid player (assuming player exists with id of 1)
        $this->player = new Player(1);
        $this->assertTrue($this->player->load_information(), "Failed to update valid player");

        // Test update on invalid player (assuming no player exists with id of -1)
        $this->player = new Player(-1);
        $this->assertTrue(!$this->player->load_information(), "Updated player with invalid ID");

    }

    public function test_connection()
    {
        // First check that the player is connected
        $this->player->set_connection(1);
        $this->assertTrue($this->player->is_connected() == 1);

        // Check the player is disconnected
        $this->player->set_connection(0);
        $this->assertTrue($this->player->is_connected() == 0);
    }

    public function test_chips()
    {
        // Check that the chips are greater than 0
        $this->assertGreaterThanOrEqual(0, $this->player->get_chips(), "Invalid chip amount (< 0)");
    }

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

        // Test negative bid
        $this->assertTrue(!$this->player->place_bid(-10));

        // Test bid of 0
        $this->assertTrue(!$this->player->place_bid(0));
    }
}
