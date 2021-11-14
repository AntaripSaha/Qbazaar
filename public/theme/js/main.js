(function ($) {
  "use strict";
  $(document).on("click", "#sidebarToggle", function () {
    if (window.innerWidth > 768) {
      $("#content-area").toggleClass("desktop-nav");
    } else {
      $("#content-area").toggleClass("mobile-nav");
    }
  });

  $(document).on("click", "#filter-accordion", function () {
    $("#filter-accordion-content").slideToggle();
  });

  /* Shopping Cart Area */
  $(document).on("click", ".btn-busket", function (event) {
    event.preventDefault();
    $(".shopping_cart").toggleClass("shopping_cart_on");
  });

  $(document).on("click", ".offsetmenu_close_btn", function (event) {
    event.preventDefault();
    $(".shopping_cart").removeClass("shopping_cart_on");
  });

  $(document).on(
    "delegate",
    "#productDetailsModal .product-qnt input, .shp_pro_details .product-qnt input",
    "keydown",
    function () {
      return false;
    }
  );

  // filter items on button click
  $(".portfolio-menu").on("click", "button", function () {
    var filterValue = $(this).attr("data-filter");
    $grid.isotope({ filter: filterValue });
  });

  //for menu active class
  $(".portfolio-menu button").on("click", function (event) {
    $(this)
      .siblings(".active")
      .removeClass("active");
    $(this).addClass("active");
    event.preventDefault();
  });

  $(document)
    // tile mouse actions
    .on("mouseover", ".img_producto_container", function () {
      $(this)
        .children(".product-details-img")
        .css({ transform: "scale(" + $(this).attr("data-scale") + ")" });
    })
    .on("mouseout", ".img_producto_container", function () {
      $(this)
        .children(".product-details-img")
        .css({ transform: "scale(1)" });
    })
    .on("mousemove", ".img_producto_container", function (e) {
      $(this)
        .children(".product-details-img")
        .css({
          "transform-origin":
            ((e.pageX - $(this).offset().left) / $(this).width()) * 100 +
            "% " +
            ((e.pageY - $(this).offset().top) / $(this).height()) * 100 +
            "%",
        });
    });
})(jQuery);
