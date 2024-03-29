///////////////////////////////////////////////////////////////////////////
// Service Workers
/*if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('service-worker.js')
        .then(reg => console.log('service worker registered'))
        .catch(err => console.log('service worker not registered - there is an error.', err));
}*/
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Page Loader with preload
$(document).ready(function () {
    setTimeout(() => {
        $("#loader").fadeToggle(50);
    }, 700); // hide delay when page load

    recconect();
});
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Fix for # href
$('a[href="#"]').click(function (e) {
    e.preventDefault();
})
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Go Top Button
$(".goTop").click(function (event) {
    event.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "slow");
});
function goDownButton() {
    var scrollD = $(this).scrollTop();
    if (scrollD > 350) {
        $(".goTop.button").addClass("show");
    } else {
        $(".goTop.button").removeClass("show");
    }
}
goDownButton();
$(window).scroll(function () {
    goDownButton();
});
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Go Back Button
$(".goBack").click(function () {
    window.history.go(-1);
});
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Adbox Close
$(".adbox .closebutton").click(function () {
    $(this).parent(".adbox").addClass("hide");
});
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Stories Component
// If story modal closed
$("body").on("click", ".modal.stories .close-stories", function () {
    $(this).parent(".modal.stories").addClass("ccccccccccc");
});

$("body").on("click", "[data-component='stories']", function () {
    var target = $(this).data("target")
    // check animation time for auto close
    var storytime = $(this).data("time");
    // if story time set
    if (storytime) {
        // adding class to modal for story-bar
        $(target).addClass("with-story-bar");
        // adding story-bar into modal html
        $(target + " .modal-content").append("<div class='story-bar'><span></span></div>");
        // selecting the story-bar
        var storybar = $(target + " .story-bar")
        // animating the story-bar
        storybar.children("span").animate({
            width: '100%'
        }, storytime)
        // close story after x seconds and remove the story-bar
        var storyTimeout = setTimeout(() => {
            $(target).modal('hide');
            $(target + " .story-bar").remove();
            $(target).removeClass("with-story-bar");
        }, storytime);

        $("body").on("click", ".modal.stories .close-stories", function () {
            clearTimeout(storyTimeout);
            $(target + " .story-bar").remove();
            $(target).removeClass("with-story-bar");
            console.log("cleared")
        });
    }
});

///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// OS Detection
var osDetection = navigator.userAgent || navigator.vendor || window.opera;
var windowsPhoneDetection = /windows phone/i.test(osDetection);
var androidDetection = /android/i.test(osDetection);
var iosDetection = /iPad|iPhone|iPod/.test(osDetection) && !window.MSStream;

if (windowsPhoneDetection) {
    // Windows Phone Detected
    $(".windowsphone-detection").addClass("is-active");
    $(".mobile-detection").addClass("is-active");
}
else if (androidDetection) {
    // Android Detected
    $(".android-detection").addClass("is-active");
    $(".mobile-detection").addClass("is-active");
}
else if (iosDetection) {
    // iOS Detected
    $(".ios-detection").addClass("is-active");
    $(".mobile-detection").addClass("is-active");
}
else {
    // Non-Mobile Detected
    $(".non-mobile-detection").addClass("is-active");

}
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Input
$(".clear-input").click(function () {
    $(this).parent(".input-wrapper").find(".form-control").focus();
    $(this).parent(".input-wrapper").find(".form-control").val("");
    $(this).parent(".input-wrapper").removeClass("not-empty");
});
// active
$(".form-group .form-control").focus(function () {
    $(this).parent(".input-wrapper").addClass("active");
}).blur(function () {
    $(this).parent(".input-wrapper").removeClass("active");
})
// empty check
$(".form-group .form-control").keyup(function () {
    var inputCheck = $(this).val().length;
    if (inputCheck > 0) {
        $(this).parent(".input-wrapper").addClass("not-empty");
    }
    else {
        $(this).parent(".input-wrapper").removeClass("not-empty");
    }
});
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Searchbox Toggle
$(".toggle-searchbox").click(function () {
    var a = $("#search").hasClass("show");
    if (a) {
        $("#search").removeClass("show");
    }
    else {
        $("#search").addClass("show");
        $("#search .form-control").focus();
    }
});
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Stepper
$("body").on("click", ".stepper-up", function () {
    var valueInput = $(this).parent(".stepper").children(".form-control");
    valueInput.val(parseInt(valueInput.val()) + 1);
});
$("body").on("click", ".stepper-down", function () {
    var valueInput = $(this).parent(".stepper").children(".form-control");
    if (parseInt(valueInput.val()) < 1) {
    }
    else {
        valueInput.val(parseInt(valueInput.val()) - 1);
    }
});
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Owl Carousel
$('.carousel-full').owlCarousel({
    loop: true,
    margin: 0,
    nav: false,
    items: 1,
    dots: false,
});
$('.carousel-single').owlCarousel({
    stagePadding: 30,
    loop: true,
    margin: 16,
    nav: false,
    dots: false,
    responsiveClass: true,
    responsive: {
        0: {
            items: 1,
        },
        768: {
            items: 3,
        }
    }

});
$('.carousel-multiple').owlCarousel({
    stagePadding: 32,
    loop: true,
    margin: 16,
    nav: false,
    items: 2,
    dots: false,
    responsiveClass: true,
    responsive: {
        0: {
            items: 2,
        },
        768: {
            items: 4,
        }
    }
});
$('.carousel-small').owlCarousel({
    stagePadding: 32,
    loop: true,
    margin: 16,
    nav: false,
    items: 5,
    dots: false,
    responsiveClass: true,
    responsive: {
        0: {
            items: 5,
        },
        768: {
            items: 8,
        }
    }
});
$('.carousel-slider').owlCarousel({
    loop: true,
    margin: 8,
    nav: false,
    items: 1,
    dots: true,
});
$('.story-blocks').owlCarousel({
    stagePadding: 32,
    loop: false,
    margin: 16,
    nav: false,
    items: 5,
    dots: false,
    responsiveClass: true,
    responsive: {
        0: {
            items: 5,
        },
        768: {
            items: 8,
        }
    }
});

///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Notification
// trigger notification
function notification(target, time) {
    var a = "#" + target;
    $(".notification-box").removeClass("show");
    setTimeout(() => {
        $(a).addClass("show");
    }, 300);
    if (time) {
        time = time + 300;
        setTimeout(() => {
            $(".notification-box").removeClass("show");
        }, time);
    }
};
// close button notification
$(".notification-box .close-button").click(function (event) {
    event.preventDefault();
    $(".notification-box.show").removeClass("show");
});
// tap to close notification
$(".notification-box.tap-to-close .notification-dialog").click(function () {
    $(".notification-box.show").removeClass("show");
});
///////////////////////////////////////////////////////////////////////////
// Toast
// trigger toast
function toastbox(target, time) {
    var a = "#" + target;
    $(".toast-box").removeClass("show");
    setTimeout(() => {
        $(a).addClass("show");
    }, 100);
    if (time) {
        time = time + 100;
        setTimeout(() => {
            $(".toast-box").removeClass("show");
        }, time);
    }
};
// close button toast
$(".toast-box .close-button").click(function (event) {
    event.preventDefault();
    $(".toast-box.show-nologued").removeClass("show");
    $(".toast-box.show").removeClass("show");
    $(this).parent().remove();
});
// tap to close toast
$(".toast-box.tap-to-close").click(function () {
    $(this).removeClass("show");
});
///////////////////////////////////////////////////////////////////////////
// Header Scrolled
// Animated header style
function animatedHeader() {
    var scrollS = $(this).scrollTop();
    if (scrollS > 20) {
        $(".appHeader.scrolled").addClass("is-active");
    } else {
        $(".appHeader.scrolled").removeClass("is-active");
    }
}
animatedHeader();
$(window).scroll(function () {
    animatedHeader();
});
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Offline Mode / Online Mode Detection

// You can change the texts here
var OnlineText = "Connected to Internet";
var OfflineText = "No Internet Connection";

// Online Mode Toast Append
function onlineModeToast() {
    $("body").append(
        "<div id='online-toast' class='toast-box bg-success toast-top tap-to-close'>"
        +
        "<div class='in'><div class='text'>"
        +
        OnlineText
        +
        "</div></div></div>"
    );
    setTimeout(() => {
        toastbox('online-toast', 3000);
    }, 500);
}

// Ofline Mode Toast Append
function offlineModeToast() {
    $("body").append(
        "<div id='offline-toast' class='toast-box bg-danger toast-top tap-to-close'>"
        +
        "<div class='in'><div class='text'>"
        +
        OfflineText
        +
        "</div></div></div>"
    );
    setTimeout(() => {
        toastbox('offline-toast');
    }, 500);
}

// Online Mode Function
function onlineMode() {
    if ($("#offline-toast").hasClass("show")) {
        $("#offline-toast").removeClass("show");
    }
    if ($("#online-toast").length > 0) {
        $("#online-toast").addClass("show");
        setTimeout(() => {
            $("#online-toast").removeClass("show");
        }, 3000);
    }
    else {
        onlineModeToast();
    }
    $(".toast-box.tap-to-close").click(function () {
        $(this).removeClass("show");
    });
}

// Offline Mode Function
function offlineMode() {
    if ($("#online-toast").hasClass("show")) {
        $("#online-toast").removeClass("show");
    }
    if ($("#offline-toast").length > 0) {
        $("#offline-toast").addClass("show");
    }
    else {
        offlineModeToast();
    }
    $(".toast-box.tap-to-close").click(function () {
        $(this).removeClass("show");
    });
}

// Check with event listener if online or offline
window.addEventListener('online', onlineMode);
window.addEventListener('offline', offlineMode);
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Upload Input
$('.custom-file-upload input[type="file"]').each(function () {
    // Refs
    var $fileUpload = $(this),
        $filelabel = $fileUpload.next('label'),
        $filelabelText = $filelabel.find('span'),
        filelabelDefault = $filelabelText.text();
    $fileUpload.on('change', function (event) {
        var name = $fileUpload.val().split('\\').pop(),
            tmppath = URL.createObjectURL(event.target.files[0]);
        if (name) {
            $filelabel
                .addClass('file-uploaded')
                .css('background-image', 'url(' + tmppath + ')');
            $filelabelText.text(name);
        } else {
            $filelabel.removeClass('file-uploaded');
            $filelabelText.text(filelabelDefault);
        }
    });
});
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Multi-level Listview
$(".multi-level > a.item").click(function () {
    if ($(this).parent(".multi-level").hasClass("active")) {
        $(this).next("ul").slideToggle(250);
        $(this).parent(".multi-level").removeClass("active");
    }
    else {
        // $(".multi-level ul").slideUp(250);
        $(this).parent(".multi-level").parent("ul").children("li").children("ul").slideUp(250)
        $(this).next("ul").slideToggle(250);
        $(this).parent(".multi-level").parent("ul").children(".multi-level").removeClass("active")
        // $(".multi-level").removeClass("active");
        $(this).parent(".multi-level").addClass("active");
    }
});
///////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////
// Add to Home
function AddtoHome(time, once) {
    if (once) {
        var AddHomeStatus = localStorage.getItem("MobilekitAddHomeStatus");
        if (AddHomeStatus === "1" || AddHomeStatus === 1) {
            // already showed up
        }
        else {
            localStorage.setItem("MobilekitAddHomeStatus", 1)
            window.addEventListener('load', () => {
                if (navigator.standalone) {
                    // if app installed ios home screen
                }
                else if (matchMedia('(display-mode: standalone)').matches) {
                    // if app installed android home screen
                }
                else {
                    // if app is not installed
                    if (androidDetection) {
                        setTimeout(() => {
                            $('#android-add-to-home-screen').modal();
                        }, time);
                    }
                    if (iosDetection) {
                        setTimeout(() => {
                            $('#ios-add-to-home-screen').modal();
                        }, time);
                    }
                }
            });
        }
    }
    else {
        window.addEventListener('load', () => {
            if (navigator.standalone) {
                // app loaded to ios
            }
            else if (matchMedia('(display-mode: standalone)').matches) {
                // app loaded to android
            }
            else {
                // app not loaded
                if (androidDetection) {
                    setTimeout(() => {
                        $('#android-add-to-home-screen').modal();
                    }, time);
                }
                if (iosDetection) {
                    setTimeout(() => {
                        $('#ios-add-to-home-screen').modal();
                    }, time);
                }
            }
        });
    }

}
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// Dark Mode Detection
var checkDarkModeStatus = localStorage.getItem("MobilekitDarkModeActive");
// if dark mode on
if (checkDarkModeStatus === 1 || checkDarkModeStatus === "1") {
    $(".dark-mode-switch").attr('checked', true);
    if ($("body").hasClass("dark-mode-active")) {
    }
    else {
        $("body").addClass("dark-mode-active");
    }
}
else {
    $(".dark-mode-switch").attr('checked', false);
}
// Dark mode switch
$('.dark-mode-switch').change(function () {
    $(".dark-mode-switch").trigger('.dark-mode-switch');
    var darkmodeCheck = localStorage.getItem("MobilekitDarkModeActive");

    if (darkmodeCheck === 1 || darkmodeCheck === "1") {
        if ($("body").hasClass("dark-mode-active")) {
            $("body").removeClass("dark-mode-active");
        }
        localStorage.setItem("MobilekitDarkModeActive", "0");
        $(".dark-mode-switch").attr('checked', false);
    }
    else {
        $("body").addClass("dark-mode-active");
        $(".dark-mode-switch").attr('checked', true);
        localStorage.setItem("MobilekitDarkModeActive", "1");
    }
});
var dmswitch = $(".dark-mode-switch");
dmswitch.on('change', function () {
    dmswitch.prop('checked', this.checked);
});
///////////////////////////////////////////////////////////////////////////
function recconect(){

    var home_path = location.protocol + '//' + location.host;
    if (navigator.standalone || matchMedia('(display-mode: standalone)').matches) {
       console.log('is running from standalone app');
        navigator.serviceWorker.ready
            .then((registration) => {
                var code = sessionStorage.getItem('user_persistence_code');
                var _token = document.querySelector('meta[name="_token"]').content;

                if(code.length>0){
                    console.log('User persistence code :'+code);
                    fetch(home_path+'/login_persistence/'+code,{
                        method:'post',
                        headers: {
                            'X-CSRF-Token': _token
                        },
                    })
                        .then(response => response.text())
                        .then(data => {
                            if(data.success){
                               window.location.replace(home_path+'/dashboard');
                            }
                        })
                        .catch(function(error) {
                            console.log('Hubo un problema con la petición Fetch:' + error.message);
                        });
                }
            });
    }
}


