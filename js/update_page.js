var all_players = [];

$(document).ready(function () {

    update_stats();
    var update_tick = setInterval(function () {
        update_stats()
    }, 2000);
    var refresh_timer = setTimeout(function () {
        location.reload()
    }, 600000);

});

function update_stats() {

    $.ajax({
        url: "api/game/connected_players_info.php",
        dataType: 'json'
    }).done(function (data) {
        // Successfully fetched information, now update players
        // First check there are no issues

        var error = data['error'];
        if (error == 0) {
            var connected_players = data['data']['players'];
            var old_players = all_players;
            all_players = [];
            // Loop through player ID's and refresh players
            for (var i = 0; i < connected_players.length; i++) {
                var player_info = connected_players[i];
                all_players.push(player_info['ID']);
                add_player_to_page(player_info);
            }

            for (var j = 0; j < old_players.length; j++) {
                var player_found = false;
                for (var k = 0; k < all_players.length; k++) {
                    if (old_players[j] === all_players[k]) {
                        player_found = true;
                        break;
                    }
                }

                if (!player_found) {
                    $("#player_" + old_players[j]).remove();
                }
            }
        }
    });
}

function player_has_won(id) {
    $.ajax({
        url: "api/game/player_won.php?id=" + id,
        dataType: 'json'
    }).done(function (data) {
        if (data['error'] != 0) {
            update_stats();
        }
    });
}

function add_player_to_page(player_info) {

    var id = player_info["ID"];
    $("#main_container").append(
        $("<div />").attr({class: "player_container", id: "player_" + id}).css("display", "none")
    );

    // Add all information to the player container
    var player_container = $("#player_" + id);

    player_container.load("player_layout.html", function () {
        console.log(player_info['username']);
        $('> .player_name', player_container).html(player_info['username']);
        player_container.children(".player_name").text(player_info['username']);

        var player_chips = player_container.children(".player_chips");
        player_chips.children(".bidding").html(player_info['current_bid']);
        player_chips.children(".chips").html(parseInt(player_info['chips']) + parseInt(player_info['current_bid']));

        var player_stats = player_container.find(".player_stats");
        player_stats.find(".wins").html(player_info['games_won']);
        player_stats.find(".losses").html(player_info['games_lost']);
        player_stats.find(".chips_won").html(player_info['chips_won']);
        player_stats.find(".chips_lost").html(player_info['chips_lost']);

        var win_button = player_container.find(".player_won");
        win_button.attr("onclick", "player_has_won(" + id + ")");

        // Unhide the player stats
        player_container.fadeIn(1000);
    });

}

function check_json(data) {
    var jsonData = JSON.stringify(data, null, 4);
    var newWindow = window.open();
    newWindow.document.open();
    newWindow.document.write("" +
        "<html>" +
        "   <head>" +
        "       <title>JSON test</title> " +
        "   </head>" +
        "   <body>" +
        "       <pre>" + jsonData + "</pre>" +
        "   </body>" +
        "</html>");
}
