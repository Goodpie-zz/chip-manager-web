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

## TODO:

* Use hashed passwords
* Use media queries in CSS instead of user agent detector and multiple style sheets
* Write API reference
* Tidy up API files and Player functions
* Make sure everything is suited for use by external applications
* Change overall theme (maybe light and dark)