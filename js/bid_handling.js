var current_bid;
var current_chips;
var id;


$(document).ready(function () {
    id = $("#player-id").val();
    init_player(0);


    var updateInterval = setInterval(function () {
        update_player();
    }, 1500);

    var userSession = setInterval(function () {
        log_in_user();
    }, 60000)
});

function log_in_user() {
    $.ajax({
        url: "connect_player.php"
    });
}

function add_bid(amt) {
    console.log(amt + current_bid);
    if (current_chips - amt >= 0) {
        current_bid = current_bid + amt;
        current_chips = current_chips - amt;
        $("#current_chips").html(current_chips);
        $("#bid_chips").html(current_bid);

    }
}

function reset_bid() {
    $.ajax({
        url: "api/player/reset_bid.php?id=" + id,
        dataType: 'json'
    }).done(function (data) {
        console.log(data);
        if (data['success'] != 0) {

            // Set global var values
            current_chips = parseInt(data['data']['chips']);
            current_bid = parseInt(data['data']['current_bid']);

            $("#name").html(data['data']['username']);
            $("#current_chips").html(current_chips);
            $("#bid_chips").html(current_bid);
        }
        else {
            console.log(data['error']);
        }
    });
}

function make_bid() {
    $.ajax({
        url: "api/player/make_bid.php?id=" + id + "&amount=" + current_bid,
        dataType: 'json'
    }).done(function (data) {
        if (data['success'] != 0) {
            $("#name").html(data['data']['username']);
            $("#current_chips").html(data['data']['chips']);
            $("#bid_chips").html(data['data']['current_bid']);
        }
        else {
            console.log(data['error']);
        }
    });
}

function update_player() {
    $.ajax({
        url: "api/player/update.php?id=" + id,
        dataType: 'json'
    }).done(function (data) {

        if (data['success'] != 0) {

            if (data['data']['needs_update'] == 1) {
                var player = data['data']['player'];
                current_bid = parseInt(player['current_bid']);
                current_chips = parseInt(player['chips']);
                $("#name").html(player['username']);
                $("#current_chips").html(current_chips);
                $("#bid_chips").html(current_bid);
            }


        }
    });
}

function init_player(update_required) {
    $.ajax({
        url: "api/player/player_info.php",
        dataType: 'json',
        method: 'POST',
        data: {id: id, needs_update: update_required}
    }).done(function (data) {
        if (data['success'] != 0) {
            current_bid = parseInt(data['data']['current_bid']);
            current_chips = parseInt(data['data']['chips']);
            $("#name").html(data['data']['username']);
            $("#current_chips").html(current_chips);
            $("#bid_chips").html(current_bid);

        }
    });
}