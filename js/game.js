var all_players = [];
var total_pot = 0;

var player_colors = [];
var colors = ["blue", "red", "yellow", "green", "orange", "purple"];
var color_schemes = {
    "blue":{"primary":"#2196F3", "dark_primary":"#1976D2"},
    "red":{"primary":"#F44336", "dark_primary":"#D32F2F"},
    "yellow":{"primary":"#FFC107", "dark_primary":"#FFA000"},
    "green":{"primary":"#4CAF50", "dark_primary":"#388E3C"},
    "orange":{"primary":"#FF5722", "dark_primary":"#E64A19"},
    "purple":{"primary":"#9C27B0", "dark_primary":"#7B1FA2"}
};

// Execute when the page is loaded
$(document).ready(function () {

    // Load initial information
    update_stats();

    // Timer to update the player stats
    setInterval(function () {
        update_stats()
    }, 1500);

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

            total_pot = 0;

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
                total_pot += (parseInt(player_info['current_bid']));
            }

            $("#pot_total").html(total_pot);

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
    var color_scheme;

    var color_scheme_exists = false;
    for (var ii = 0; ii < player_colors.length;ii ++)
    {
        var obj = player_colors[ii];
        if (obj[id] != undefined)
        {
            color_scheme = obj[id];
            color_scheme_exists = true;
            break;
        }
    }

    if (!color_scheme_exists)
    {
        color_scheme = color_schemes[colors[parseInt(Math.random() * colors.length)]];
        var player_scheme = {};
        player_scheme[id] = color_scheme;
        player_colors.push(player_scheme);
    }

    // Create a new div element in the main container to hold the new player information
    $("#main_container").append(
        $("<div />").attr({class: "player_container", id: "player_" + id}).css("display", "none")
    );

    // Add all information to the player container
    var player_container = $("#player_" + id);

    player_container.load("player_layout.html", function () {

        // Get the newly created container
        $('> .player_name', player_container).html(player_info['username']);
        player_container.children(".player_name").css("background", color_scheme["primary"]);
        player_container.children(".player_name").text(player_info['username']);

        // Set the player chips fields
        var player_chips = player_container.children(".player_chips");
        player_chips.children(".current_bid").html(player_info['current_bid']);
        player_chips.children(".chips").html(parseInt(player_info['chips']) + parseInt(player_info['current_bid']));

        // Set the player stats fields
        var player_stats = player_container.find(".player_stats");
        player_stats.find(".wins").html(player_info['games_won']);
        player_stats.find(".losses").html(player_info['games_lost']);
        player_stats.find(".chips_won").html(player_info['chips_won']);
        player_stats.find(".chips_lost").html(player_info['chips_lost']);

        var win_button = player_container.find(".player_won");
        win_button.attr("onclick", "player_has_won(" + id + ")");
        win_button.css("background", color_scheme["primary"]);
        win_button.mouseover(function(){
            $(this).css("background", color_scheme["dark_primary"]);
        }).mouseout(function(){
            $(this).css("background", color_scheme["primary"]);
        });

        // Show the player stats
        player_container.fadeIn(1000);
    });

}