var all_players = [];

// Execute when the page is loaded
$(document).ready(function () {

    // Load initial information
    update_stats();

    // Timer to update the player stats
    setInterval(function () {
        update_stats()
    }, 2000);

    // Timer to reload the page after extended duration to prevent crashes that may occur
    setTimeout(function () {
        location.reload()
    }, 600000);

});

/**
 * Gets all the connected players from an AJAX call and then updates/adds
 * their information accordingly
 */
function update_stats() {

    $.ajax({
        url: "api/game/connected_players_info.php",
        dataType: 'json'
    }).done(function (data) {
        // Successfully fetched information, now update players
        // First check there are no issues
        if (data['error'] == 0) {

            // Get all the connected players from the return data
            var connected_players = data['data']['players'];

            // Old players as a reference to the last list of connected players
            var old_players = all_players;
            all_players = [];

            // Loop through player ID's and refresh players
            for (var i = 0; i < connected_players.length; i++) {
                var player_info = connected_players[i];
                all_players.push(player_info['ID']);

                // Add the new player to the page
                add_player_to_page(player_info);
            }

            // Loop through the old players and new players and check for disconnected players
            for (var j = 0; j < old_players.length; j++) {

                var player_found = false;
                for (var k = 0; k < all_players.length; k++) {
                    if (old_players[j] === all_players[k]) {
                        player_found = true;
                        break;
                    }
                }

                // Player not found, remove their information from page
                if (!player_found) {
                    $("#player_" + old_players[j]).remove();
                }
            }
        }
    });
}

/**
 * Function call when the player has won
 * @param id The id of the player who has won
 */
function player_has_won(id) {
    $.ajax({
        url: "api/game/player_won.php?id=" + id,
        dataType: 'json'
    }).done(function (data) {
        if (data['error'] != 0) {
            // If not error, update the player information
            update_stats();
        }
    });
}

/**
 * Adds a new layout for a connected player
 * @param player_info JSON information of the newly connected player
 */
function add_player_to_page(player_info) {

    // Get the new players ID
    var id = player_info["ID"];

    // Create a new div element in the main container to hold the new player information
    $("#main_container").append(
        $("<div />").attr({class: "player_container", id: "player_" + id}).css("display", "none")
    );

    // Add all information to the player container
    var player_container = $("#player_" + id);

    player_container.load("player_layout.html", function () {

        // Get the newly created container
        $('> .player_name', player_container).html(player_info['username']);
        player_container.children(".player_name").text(player_info['username']);

        // Set the player chips fields
        var player_chips = player_container.children(".player_chips");
        player_chips.children(".bidding").html(player_info['current_bid']);
        player_chips.children(".chips").html(parseInt(player_info['chips']) + parseInt(player_info['current_bid']));

        // Set the player stats fields
        var player_stats = player_container.find(".player_stats");
        player_stats.find(".wins").html(player_info['games_won']);
        player_stats.find(".losses").html(player_info['games_lost']);
        player_stats.find(".chips_won").html(player_info['chips_won']);
        player_stats.find(".chips_lost").html(player_info['chips_lost']);

        var win_button = player_container.find(".player_won");
        win_button.attr("onclick", "player_has_won(" + id + ")");

        // Show the player stats
        player_container.fadeIn(1000);
    });

}