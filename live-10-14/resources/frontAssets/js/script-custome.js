var swiper = new Swiper(".event_details_swiper", {
    slidesPerView: 2.3,
    spaceBetween: 10,
    loop: true, // Enable infinite looping
    autoplay: {
      delay: 2000, // Delay between slides in milliseconds
      disableOnInteraction: false, // Continue autoplay after user interactions
    },
    // pagination: {
    //   el: ".swiper-pagination",
    //   clickable: true,
    // },
  });
  