var first_run = true;
var all_players = [];

$(document).ready(function () {

    init_stats();
    var update_tick = setInterval(function () {
        update_stats()
    }, 2000);
    var refresh_timer = setTimeout(function () {
        location.reload()
    }, 600000);

});

function init_stats() {

    $.ajax({
        url: "get_player_information.php",
        dataType: 'json'
    }).done(function (playerArray) { // When AJAX request is complete, update page
        if (playerArray.length > 0) {
            for (var x = 0; x < playerArray.length; x++) {
                // Extract current player from array
                var player = playerArray[x];
                add_player(player);
            }
        }
    });

}

function add_player(player) {
    // Extract JSON elements
    var id = player.id;
    var name = player.name;
    var chips = parseInt(player.chips);
    var bid = parseInt(player.bid);
    var wins = parseInt(player.wins);
    var losses = parseInt(player.losses);
    var chips_won = parseInt(player.chips_won);
    var chips_lost = parseInt(player.chips_lost);

    var total_games = wins + losses;
    all_players.push(id);

    $("#main_container").append(
        $("<div />").attr({class: "player_container", id: "player_" + id}).css("display", "none")
    );

    $("#player_" + id).append(
        $("<div />").attr({class: "player_name"}).html(name)
    );

    $("#player_" + id).append(
        $("<div />").attr({class: "player_chips"}).html("Chips:<br/><span class='bidding'>" + bid + "</span>/<span class='chips'>" + chips + "</span>")
    );

    $("#player_" + id).append(
        $("<div />").attr({class: "player_stats"}).html(' Win / Loss (Games)<br/><span class="wins">' + wins + '</span>/<span class="losses">' + losses + '</span> (<span class="total_games">' + total_games + '</span>)<br/><br/>Chips Won / Chips Lost<br/><span class="chips_won">' + chips_won + '</span>/<span class="chips_lost">' + chips_lost + '</span>')
    );

    $("#player_" + id).append('<button class="player_won" onclick="player_has_won(' + id + ')">Player Has Won</button>');

    $("#player_" + id).fadeIn(1000);

}

function player_has_won(id) {
    console.log("ayy");
    $.ajax({
        url: "player_has_won.php",
        method: "POST",
        data: {id: id}
    }).done(function () {
        update_stats();
    });

}

function update_stats() {
    var current_players = [];
    $.ajax({
        url: "get_player_information.php",
        dataType: 'json'
    }).done(function (playerArray) {
        if (playerArray.length > 0) {
            for (var x = 0; x < playerArray.length; x++) {

                // Extract current player from array
                var player = playerArray[x];

                // Extract JSON vars
                var id = player.id;
                current_players.push(id);

                if (all_players.indexOf(id) > -1) {
                    var name = player.name;
                    var chips = parseInt(player.chips);
                    var bid = parseInt(player.bid);
                    var wins = parseInt(player.wins);
                    var losses = parseInt(player.losses);
                    var chips_won = parseInt(player.chips_won);
                    var chips_lost = parseInt(player.chips_lost);

                    var total_games = wins + losses;

                    // Update HTML
                    $("#player_" + id + " .chips").html(chips);
                    $("#player_" + id + " .bidding").html(bid);
                    $("#player_" + id + " .wins").html(wins);
                    $("#player_" + id + " .losses").html(losses);
                    $("#player_" + id + " .total_games").html(total_games);
                    $("#player_" + id + " .chips_won").html(chips_won);
                    $("#player_" + id + " .chips_lost").html(chips_lost);
                } else {
                    add_player(player);
                }
            }

        }
    });
}