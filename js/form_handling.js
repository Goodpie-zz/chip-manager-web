$(document).ready(function () {
    detectmob();
});

$(window).bind('orientationchange resize', function (event) {
    if (event.orientation) {
        if (event.orientation == 'landscape') {
            if (window.rotation == 90) {
                $("#form_style").attr("href", "css/form_desktop.css");
            } else {
                $("#form_style").attr("href", "css/form_mobile.css");
            }
        }
    }
});

function detectmob() {
    if (navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)
    ) {
        $("#form_style").attr("href", "css/form_mobile.css");
    }
    else {
        $("#form_style").attr("href", "css/form_desktop.css");
    }
}
