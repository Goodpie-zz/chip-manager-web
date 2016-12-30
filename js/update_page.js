var all_players = [];

$(document).ready(function () {

    init_stats();
    /**var update_tick = setInterval(function () {
        update_stats()
    }, 2000);**/
    /**var refresh_timer = setTimeout(function () {
        location.reload()
    }, 600000);**/

});

function init_stats() {

    $.ajax({
        url: "api/get_connected_players.php",
        dataType: 'json'
    }).done(function (data) {
        // Successfully fetched information, now update players
        // First check there are no issues
        var error = data['error'];
        if (error == 0) {
            var connected_players = data['data']['players'];
            all_players = [];
            // Loop through player ID's and refresh players
            for (var i = 0; i <= all_players.length; i++) {
                var id = connected_players[i];
                load_player(id);
                all_players[i] = id;


            }
        }
    });

}

function load_player(id) {
    var player_info = false;
    // Make a request to the server to fetch the information
    $.ajax({
        url: "api/get_player_info.php",
        dataType: 'json',
        method: 'POST',
        data: {ID: id}
    }).done(function (data) {
        // Check no errors
        var error = data['error'];
        if (error == 0) {
            player_info = data['data'];
        }
    });

    if (player_info) {
        add_player_to_page(id, player_info);
    }
}

function add_player_to_page(id, player_info) {

    var id = player_info['id'];
    $("#main_container").append(
        $("<div />").attr({class: "player_container", id: "player_" + id}).css("display", "none")
    );

    // Add all information to the player container
    var player_container = $("#player_" + id);

    player_container.load("player_layout.html");
    player_container.find(".player_name").html(player_info['username']);

    var player_chips = player_container.find("player_chips");
    player_chips.find(".bidding").html(player_info['current_bid']);
    player_chips.find(".chips").html(player_info['chips'] + player_info['current_bid']);

    var player_stats = player_container.find("player_stats");
    player_stats.find(".wins").html(player_info['games_won']);
    player_stats.find(".losses").html(player_info['games_lost']);
    player_stats.find(".chips_won").html(player_info['chips_won']);
    player_stats.find(".chips_lost").html(player_info['chips_lost']);

    // Unhide the player stats
    player_container.fadeIn(1000);

}
