/* =======================
   DOM READY
======================= */
$(document).ready(function () {

    // Check if jQuery and Slick are loaded
    if (typeof $.fn.slick === "undefined") {
        console.error("❌ jQuery or Slick not loaded");
        return;
    }

    /* =======================
       SLICK SLIDERS
    ======================= */

    // Hero slider
    $('.hero-slider').slick({
        autoplay: true,
        infinite: true,
        speed: 600,
        prevArrow: $('.prev'),
        nextArrow: $('.next'),
    });

    // Category slider
    $('.category-item').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        prevArrow: $('.left_1'),
        nextArrow: $('.right_1'),
        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 2, slidesToScroll:1, infinite:true } },
            { breakpoint: 768,  settings: { slidesToShow: 2, slidesToScroll:2 } },
            { breakpoint: 480,  settings: { slidesToShow: 1, slidesToScroll:1 } }
        ]
    });

    // Brand slider
    $('.brand-item').slick({
        lazyLoad: 'ondemand',
        slidesToShow: 4,
        slidesToScroll: 1,
        prevArrow: $('.left_2'),
        nextArrow: $('.right_2'),
        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 2, slidesToScroll:1, infinite:true } },
            { breakpoint: 600,  settings: { slidesToShow: 2, slidesToScroll:2 } },
            { breakpoint: 400,  settings: { slidesToShow: 1, slidesToScroll:1 } }
        ]
    });

    // Con slider
    $('.con').slick({
        autoplay:true,
        lazyLoad: 'ondemand',
        slidesToShow: 2,
        slidesToScroll: 1,
        prevArrow: $('.left'),
        nextArrow: $('.right'),
        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 2, slidesToScroll:1, infinite:true } },
            { breakpoint: 600,  settings: { slidesToShow: 2, slidesToScroll:2 } },
            { breakpoint: 400,  settings: { slidesToShow: 1, slidesToScroll:1 } }
        ]
    });

    // Popular Products slider
    $('#slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        arrows: false,   // hide default arrows
        dots: false,
        speed: 500,
        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 3 } },
            { breakpoint: 768,  settings: { slidesToShow: 2 } },
            { breakpoint: 480,  settings: { slidesToShow: 1 } }
        ]
    });

    // Custom arrows for popular products
    $('.left').click(function () {
        $('#slider').slick('slickPrev');
    });

    $('.right').click(function () {
        $('#slider').slick('slickNext');
    });
    


    /* =======================
       HEADER / UI TOGGLES
    ======================= */
    $('#user-btn').click(function () {
        $('.profile').toggleClass('active');
    });

    $('#menu-btn').click(function () {
        $('.navbar').toggleClass('active');
    });

    $('#search-btn').click(function () {
        $('.search-form').toggleClass('active');
        $('.profile').removeClass('active');
    });

    /* =======================
       COUNTDOWN TIMER
    ======================= */
    const second = 1000,
          minute = second * 60,
          hour = minute * 60,
          day = hour * 24;

    let today = new Date(),
        dd = String(today.getDate()).padStart(2, "0"),
        mm = String(today.getMonth() + 1).padStart(2, "0"),
        yyyy = today.getFullYear(),
        nextYear = yyyy + 1,
        dayMonth = "09/30/",
        eventDate = dayMonth + yyyy;

    today = mm + "/" + dd + "/" + yyyy;

    if (today > eventDate) {
        eventDate = dayMonth + nextYear;
    }

    const countdown = new Date(eventDate).getTime();

    setInterval(function () {
        const now = new Date().getTime();
        const distance = countdown - now;

        if (distance < 0) return;

        $('#days').text(Math.floor(distance / day));
        $('#hours').text(Math.floor((distance % day) / hour));
        $('#minutes').text(Math.floor((distance % hour) / minute));
        $('#seconds').text(Math.floor((distance % minute) / second));

    }, 1000);

});
