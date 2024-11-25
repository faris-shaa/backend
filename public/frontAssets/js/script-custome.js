var swiper = new Swiper(".event_details_swiper", {
  slidesPerView: 2.3,
  spaceBetween: 10,
  mousewheel: {
    thresholdDelta: 70
  },
  loop: true,
  // autoplay: {
  //   delay: 2000,
  //   disableOnInteraction: false,
  // },
  scrollbar: {
    el: ".swiper-scrollbar",
    draggable: true,
    dragSize: 226 // Set the size of the scrollbar drag element

  },
  breakpoints: {
    320: {
      slidesPerView: 1,
      spaceBetween: 10
    },
    480: {
      slidesPerView: 1,
      spaceBetween: 10
    },
    640: {
      slidesPerView: 2,
      spaceBetween: 10
    },
    768: {
      slidesPerView: 2.3,
      spaceBetween: 10
    }
  }
});


const menuBtn = document.getElementById('menu-btn');
const sidebar = document.getElementById('sidebar');
const closeBtn = document.getElementById('close-btn');
var language = document.getElementById('lang').value;
menuBtn.addEventListener('click', () => {
  language != 'English' ? sidebar.classList.toggle('translate-x-full') : sidebar.classList.toggle('-translate-x-full')

});

closeBtn.addEventListener('click', (e) => {
  language != 'English' ? sidebar.classList.toggle('translate-x-full') : sidebar.classList.toggle('-translate-x-full')

});

document.addEventListener('click', (e) => {

  if (e.target.id === 'sidebar') {
    language != 'English' ? sidebar.classList.toggle('translate-x-full') : sidebar.classList.toggle('-translate-x-full')
  }
});


$(document).ready(function () {
  $('#menu-droplist').on('click', function (e) {
    e.stopPropagation();
    var $menu = $('#dropdownMenu');
    if ($menu.hasClass('opacity-0')) {
      $menu.removeClass('opacity-0 scale-95 hidden').addClass('opacity-100 scale-100');
    } else {
      $menu.removeClass('opacity-100 scale-100').addClass('opacity-0 scale-95 hidden');
    }
  });

  $(document).on('click', function () {
    var $menu = $('#dropdownMenu');
    if ($menu.hasClass('opacity-100')) {
      $menu.removeClass('opacity-100 scale-100').addClass('opacity-0 scale-95 hidden');
    }
  });
});
$(document).ready(function () {
  $('#menu-droplist-user').on('click', function (e) {
    e.stopPropagation();
    var $menu = $('#dropdownMenu-user');
    if ($menu.hasClass('opacity-0')) {
      $menu.removeClass('opacity-0 scale-95 hidden').addClass('opacity-100 scale-100');
    } else {
      $menu.removeClass('opacity-100 scale-100').addClass('opacity-0 scale-95 hidden');
    }
  });

  $(document).on('click', function () {
    var $menu = $('#dropdownMenu-user');
    if ($menu.hasClass('opacity-100')) {
      $menu.removeClass('opacity-100 scale-100').addClass('opacity-0 scale-95 hidden');
    }
  });
});


$(document).ready(function () {
  $(".toggleCategories").click(function (e) {
    e.preventDefault();
    $("#categoryList").toggleClass("hidden");
  });
});
$(document).ready(function () {
  $(".toggleuser-list").click(function (e) {
    e.preventDefault();
    $("#userList").toggleClass("hidden");
  });
});


$(document).ready(function () {
  $('.custom-radio').on('click', function () {
    // Remove 'checked' class from all custom radios
    $('.custom-radio').removeClass('checked');
    // Add 'checked' class to the clicked custom radio
    $(this).addClass('checked');
    // Uncheck all radio inputs
    $('input[name="payment_method"]').prop('checked', false);
    // Check the corresponding radio input
    $(this).next('input').prop('checked', true);
  });
});


$(document).ready(function () {
  $('.custom-radio').on('click', function () {
    $('.custom-radio').removeClass('checked');
    $(this).addClass('checked');
    $('input[name="payment_method"]').prop('checked', false);
    $(this).next('input').prop('checked', true);
    var paymentMethod = $(this).next('input').attr('id');
    $('[data-payment-holder]').addClass('hidden');
    $('[data-payment-holder="' + paymentMethod + '"]').removeClass('hidden');
  });
});



$('.close-modal').click(function () {
  $('.pop-modal').addClass('hidden');
});




var swiper_hero = new Swiper(".swiper-hero", {
  effect: "coverflow",
  mousewheel: {
    thresholdDelta: 70
  },
  grabCursor: true,
  centeredSlides: true,
  slidesPerView: "auto",
  loop: true,
  initialSlide: 1,
  lazy: {
    loadPrevNext: true,
    loadPrevNextAmount: 1,
  },
  coverflowEffect: {
    rotate: 0,
    stretch: 10,
    depth: 80,
    modifier: 10,
    slideShadows: true
  },
  breakpoints: {
    1024: {
      centeredSlides: true,
      coverflowEffect: {
        rotate: 0,
        stretch: 3,
        depth: 3,
        modifier: 80,
        slideShadows: true
      },
    },
    1440: {
      centeredSlides: true,
      coverflowEffect: {
        rotate: 0,
        stretch: 20,
        depth: 15,
        modifier: 15,
        slideShadows: true
      },
    }
  },
  on: {
    init: function () {
      updateActiveSlideInfo(this);
    },
    slideChangeTransitionEnd: function () {
      updateActiveSlideInfo(this);
    }
  }
});

function updateActiveSlideInfo(swiper) {
  var activeSlide = swiper.slides[swiper.activeIndex];
  if (activeSlide) {
    var activeSlideId = activeSlide.getAttribute('id');
    var element = document.querySelector(`[data-tiket-id="${activeSlideId}"]`);
    if (element) {
      document.querySelectorAll('.tiket-info > div').forEach(function (div) {
        div.classList.add('hidden');
      });
      element.classList.remove('hidden');
    }
  }
}



var swiper_Cat = new Swiper(".swiper-cat", {
  slidesPerView: 3.5,
  loop: true,
  mousewheel: {
    thresholdDelta: 70
  },
  breakpoints: {
    640: {
      slidesPerView: 2,
      spaceBetween: 16,
    },
    768: {
      slidesPerView: 4,
      spaceBetween: 16,
    },
    1024: {
      slidesPerView: 4,
      spaceBetween: 40,
    },
  },
});

function initializeSwiper() {

  let swiperEvent = new Swiper(".swiper-event", {
    slidesPerView: 1,
    loop: true,
    autoplay: {
      delay: 1200,
      disableOnInteraction: false,
    },
  });
  if (swiperEvent) {
    swiperEvent.destroy(true, true);
  }

}// Initialize Swiper on DOM content load


document.addEventListener('DOMContentLoaded', function () {
  initializeSwiper2();
  initializeSwiper();
  initializeSwiperSlot();
});

function initializeSwiper2() {
  let swiperContainer = document.querySelector(".swiper-devent2");
  if (!swiperContainer) {
    console.log("Swiper container not found");
    return;
  }

  let swiperEvent2 = new Swiper($('.swiper-devent2'), {
    slidesPerView: 1,
    loop: true,
    autoplay: {
      delay: 1200,
      disableOnInteraction: false,
    },
  });



  // Commenting out the destroy call for testing purposes
  // if (swiperEvent2) {
  //   swiperEvent2.destroy(true, true);
  // }
}


function initializeSwiperSlot() {
  var swiperslot = new Swiper(".ticket_avail", {
    slidesPerView: 2,
    spaceBetween: 10,
    loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
      640: {
        slidesPerView: 4,
        spaceBetween: 10
      },
      768: {
        slidesPerView: 4,
        spaceBetween: 10
      },
      991: {
        slidesPerView: 5,
        spaceBetween: 10
      },
      1200: {
        slidesPerView: 6,
        spaceBetween: 10
      }
    }
  });
}


var swiper_city = new Swiper(".swiper-city", {
  slidesPerView: 2.5,
  spaceBetween: 8,

  loop: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  mousewheel: {
    thresholdDelta: 70
  },
  breakpoints: {
    640: {
      slidesPerView: 2,
      spaceBetween: 14,
    },
    768: {
      slidesPerView: 3,
      spaceBetween: 14,
    },
    1024: {
      slidesPerView: 4,
      spaceBetween: 22,
    },
  },
});


var swiper_upcoming = new Swiper(".upcomingEventsConswiper", {
  slidesPerView: 1.2,
  spaceBetween: 24,

  loop: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  mousewheel: {
    thresholdDelta: 70
  },
  breakpoints: {
    425: {
      slidesPerView: 2.5,
      spaceBetween: 14,
    }
  },

});
var swiper_upcoming = new Swiper(".upcomingPreviousEvents", {
  slidesPerView: 1.2,
  spaceBetween: 24,

  loop: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  mousewheel: {
    thresholdDelta: 70
  },
  breakpoints: {
    425: {
      slidesPerView: 2.5,
      spaceBetween: 14,
    }
  },
});





$(document).ready(function () {

  $('#price-range-submit').hide();

  $("#min_price,#max_price").on('change', function () {

    $('#price-range-submit').show();

    var min_price_range = parseInt($(".min_price").val());

    var max_price_range = parseInt($(".max_price").val());

    if (min_price_range > max_price_range) {
      $('#max_price').val(min_price_range);
    }

    $(".slider-range").slider({
      values: [min_price_range, max_price_range]
    });

  });


  $("#min_price,#max_price").on("paste keyup", function () {

    $('#price-range-submit').show();

    var min_price_range = parseInt($(".min_price").val());

    var max_price_range = parseInt($(".max_price").val());

    if (min_price_range == max_price_range) {

      max_price_range = min_price_range + 100;

      $(".min_price").val(min_price_range);
      $(".max_price").val(max_price_range);
    }

    $(".slider-range").slider({
      values: [min_price_range, max_price_range]
    });

  });


  $(function () {
    $(".slider-range").slider({
      range: true,
      orientation: "horizontal",
      min: 0,
      max: 10000,
      values: [0, 10000],
      step: 100,

      slide: function (event, ui) {
        if (ui.values[0] == ui.values[1]) {
          return false;
        }

        $(".min_price").val(ui.values[0]);
        $(".max_price").val(ui.values[1]);
      }
    });

    $(".min_price").val($(".slider-range").slider("values", 0));
    $(".max_price").val($(".slider-range").slider("values", 1));

  });

  // $(".slider-range,#price-range-submit").click(function () {

  //   var min_price = $('#min_price').val();
  //   var max_price = $('#max_price').val();

  //   $("#searchResults").text("Here List of products will be shown which are cost between " + min_price + " " + "and" + " " + max_price + ".");
  // });

});





$(document).ready(function () {
  $('#filter_sldie').on('click', function (e) {
    var $id = $(this).data('id');
    var $element = $(`#${$id}`);
    $element.addClass("slide").removeClass("hidden slide-down");
    $('#overlay').addClass("slide").removeClass('hidden slide-down')
  });

  $('.close-slide').on('click', function (e) {
    var $id = $(this).data('id');
    var $element = $(`#${$id}`);
    $element.addClass("slide-down");

    $('#overlay').addClass("slide-down")

    $element.one('animationend', function () {
      $element.addClass('hidden').removeClass("slide");
      $('#overlay').addClass("hidden").removeClass('slide')

    });
  });
});


$(document).ready(function () {
  $(window).scroll(function () {
    var footerOffset = $('footer').offset().top;
    var windowHeight = $(window).height();
    var scrollPosition = $(window).scrollTop() + windowHeight;
    if ($(window).width() > 576) {
      return false;
    }
    if (scrollPosition >= footerOffset) {
      $('.filter-container').hide();
    } else {
      $('.filter-container').show();
    }
  });
});


