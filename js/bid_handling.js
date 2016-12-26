var currently_bid;

$(document).ready(function () {
    currently_bid = 0;
    make_bid();
    set_connected();

    var updateInterval = setInterval(function () {
        refresh();
    }, 1500)

    var userSession = setInterval(function () {
        log_in_user();
    }, 60000)
});

function log_in_user() {
    $.ajax({
        url: "keep_user_logged_in.php"
    });
}

function set_connected() {
    $.ajax({
        url: "connect_player.php"
    });
}

function add_bid(amt) {
    var current_chips = parseInt($("#current_chips").html());
    if (amt <= current_chips) {
        currently_bid += amt;
        var chips = parseInt($("#current_chips").html());
        $("#current_chips").html(parseInt(chips - amt));
        $("#bid_chips").html(currently_bid);
    }
}

function clear_bid() {
    currently_bid = 0;
    make_bid();
}

function make_bid() {
    $.ajax({
        url: "make_bid.php",
        method: "POST",
        data: {amount: currently_bid},
        dataType: 'json'
    }).done(function (p) {
        $("#name").html(p.name);
        $("#bid_chips").html(p.bid);
        $("#current_chips").html(p.chips);
        currently_bid = 0;
    });
}


function refresh() {
    $.ajax({
        url: "update_player.php",
        dataType: 'json'
    }).done(function (p) {
        if (p != null || p != "0") {
            $("#name").html(p.name);
            $("#current_chips").html(p.chips);
            $("#bid_chips").html(p.bid);
        }
    });
}