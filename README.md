# Chip Manager Web

Handles server / web client side of the Casino Chip Manager.
This is intended to run on a local server and serve as a  great way to manage chips
when playing with friends / family.

#### Note:

This app was intended for personal use. I worked on a quick version that I worked on for a whole 5 hours around a year 
ago and this is my attempt at tidying it up and applying some new knowledge to.
 
## Setting Up

Tested using:

* `PHP 7.0.8` with `PHPUnit 5.7.4` to run the test cases
* `Composer 1.00-beta2`
* `MySQL 5.7.4`
* `Apache 2.4.18`

This will assume you have all the programs downloaded / running and will not walk through using Composer and PHPUnit

#### Setting up MySQL Schema

All the schemas are located in the director `MySQL/`.

To add the schema (assuming you are in the correct directory):

`mysql < MySQL/schema.sql`

#### Setting up the Config File

Chip Manager depends a lot on your config file, named `config.ini` which you should create and store in the root directory

The basic structure of the config file looks something like

    [database]
    username = username
    password = password
    server = server_address
    database = database_name
    
    [information]
    address = local_address
    logfile = log_file

You should replace each of the variables to match your setup.

### View Game in Progress

The index page will list everyone who is playing at the moment (by getting their connection status) and list them along 
 with their current information (such as their current bid / total chips).
 
### Playing the Game

`play.php` handles the client side of the game and allows people to play via a mobile friendly web client. This is very
similar to the Android app so switching between both shouldn't be an issue


## API Calls

The API calls are intended for external applications (such as the Android app) as well as for the use of some basic game tasks.

Each php file returns 3 parameters in the form of JSON:


| Name          | Description   | Always Present  |
| :------------ |:------------- | :-------------- |
| error      | If there is an error message present, a string description will be returned. Else, 0 will be returned | TRUE           |
| success      | Whether the transaction was successful or not (0 or 1)     | TRUE            |
| data | The data that was returned. Will often have sub levels of JSON.      | FALSE            |


### Game

API calls that affect the game in progress or get information about the current game. These are stored in the `api/game` directory.


#### connected_players.php

Get's all the connected players ID's in an array under `data['players']`.

#### connected_players_info.php

Get's all the connected players information including:

* ID
* username
* chips
* current_bid
* games_won
* games_lost
* chips_won
* chips_lost

The player information is stored under `data['players']`.

#### login.php

Logs in the player based on username / password combination and sets the player as connected.

Requires two `POST` parameters:

* `username` - Username of player to login
* `password` - Password to verify the player

#### player_won.php

Let's the game know the player has won the current game. They will receive all the chips that all players have bid.

Requires one `GET` or `POST` parameter:

* `ID` - The winning players ID

For verification, it returns the winning players ID under `data['winner']` and the amount the player has won under `data['chips_won']`.

### Player

API calls that affect the player. Requires the `ID` as a `GET` or `POST` parameter.

#### make_bid.php

Places a new bid for the player.

Requires an additional `GET` or `POST` parameter:

* `amount` - The amount the player wants to bet

Returns all player info under `data`

#### player_info.php

Gets all the users current information.

Takes an additional optional parameter:
 
 * `needs_update` - If set to `1`, will check if the player needs to be updated before returning information
 
 Returns all player info under `data`
 
#### reset_bid.php

Resets the player current bid to `0`

#### update.php

Checks if the player needs to be updated. If so, return all player information.

Returns whether the player was updated under `data['needs_update']` and information about the player under `data['player']`

## TODO:

* Use hashed passwords
* Use media queries in CSS instead of user agent detector and multiple style sheets
* Write API reference
* Tidy up API files and Player functions
* Make sure everything is suited for use by external applications
* Change overall theme (maybe light and dark)