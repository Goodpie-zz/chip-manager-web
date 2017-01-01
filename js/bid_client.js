var current_bid;
var current_chips;
var id;


$(document).ready(function () {

    // Get the user ID from the hidden form element
    id = $("#player-id").val();

    // Initialize the player
    init_player(0);

    // Set interval to update the player information
    setInterval(function () {
        update_player();
    }, 1500);

    // Set interval to refresh the user session
    setInterval(function () {
        log_in_user();
    }, 60000)
});

/**
 * Logs in and refreshes the user session
 */
function log_in_user() {
    $.ajax({
        url: "connect.php"
    });
}

/**
 * Adds an amount to the current bid
 * @param amt Amount to add
 */
function add_bid(amt) {

    // Small check to ensure that we aren't betting more than we have
    // There is a server side check as well but this is just another check
    if (current_chips - amt >= 0) {

        // Update the global vars
        current_bid = current_bid + amt;
        current_chips = current_chips - amt;

        // Update the HTML
        $("#current_chips").html(current_chips);
        $("#current_bid").html(current_bid);

    }
}

/**
 * Resets the users current bid using an AJAX request
 */
function reset_bid() {
    $.ajax({
        url: "api/player/reset_bid.php?id=" + id,
        dataType: 'json'
    }).done(function (data) {
        // Ensure the ajax request was successful
        if (data['success'] === 1) {

            // Set global var values
            current_chips = parseInt(data['data']['chips']);
            current_bid = parseInt(data['data']['current_bid']);

            // Update the HTML
            $("#name").html(data['data']['username']);
            $("#current_chips").html(current_chips);
            $("#current_bid").html(current_bid);
        }
    });
}

/**
 * Makes a bid based on the users current bid
 */
function make_bid() {
    $.ajax({
        url: "api/player/make_bid.php?id=" + id + "&amount=" + current_bid,
        dataType: 'json'
    }).done(function (data) {
        if (data['success'] != 0) {

            // Set global var values
            current_chips = parseInt(data['data']['chips']);
            current_bid = parseInt(data['data']['current_bid']);

            // Update the HTML
            $("#name").html(data['data']['username']);
            $("#current_chips").html(current_chips);
            $("#current_bid").html(current_bid);
        }
    });
}

/**
 * Updates all information about the current user using an AJAX request
 */
function update_player() {
    $.ajax({
        url: "api/player/update.php?id=" + id,
        dataType: 'json'
    }).done(function (data) {

        // Check that request was successful
        if (data['success'] === 1) {

            var needs_update = data['data']['needs_update'];
            if (needs_update == 1) {
                var player = data['data']['player'];

                // Update global vars
                current_bid = parseInt(player['current_bid']);
                current_chips = parseInt(player['chips']);

                // Update HTML
                $("#name").html(player['username']);
                $("#current_chips").html(current_chips);
                $("#current_bid").html(current_bid);
            }


        }
    });
}

/**
 * Grabs all the player information
 * @param update_required
 */
function init_player(update_required) {
    var address = "api/player/player_info.php?id=" + id + "&needs_update=" + update_required;
    console.log(address);
    $.ajax({
        url: address,
        dataType: 'json',
    }).done(function (data) {
        console.log(data);
        if (data['success'] != 0) {

            // Update the global variables
            current_bid = parseInt(data['data']['current_bid']);
            current_chips = parseInt(data['data']['chips']);

            // Update the HTML
            $("#name").html(data['data']['username']);
            $("#current_chips").html(current_chips);
            $("#current_bid").html(current_bid);
        }
    });
}