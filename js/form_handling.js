$(document).ready(function () {
    detect_mobile();
});

$(window).bind('orientationchange resize', function (event) {
    if (event.orientation) {
        if (event.orientation == 'landscape') {
            if (window.rotation == 90) {
                $("#bid_style").attr("href", "style/bid_page_desktop.css");
                $("#form_style").attr("href", "style/form_desktop.css");
            } else {
                $("#bid_style").attr("href", "style/bid_page_mobile.css");
                $("#form_style").attr("href", "style/form_mobile.css");
            }
        }
    }
});

function detect_mobile() {
    if (navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)
    ) {
        $("#bid_style").attr("href", "style/bid_page_mobile.css");
        $("#form_style").attr("href", "style/form_mobile.css");
    }
    else {
        $("#bid_style").attr("href", "style/bid_page_desktop.css");
        $("#form_style").attr("href", "style/form_desktop.css");
    }
}
