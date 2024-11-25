const arrow_right_second = document.querySelector("#arrow-right-second");
const arrow_left_second = document.querySelector("#arrow-left-second");
const slider_second = document.querySelector(".slider-second");

const interval_second = 200;

function slide(event) {
    const current_property_left_slider = parseInt(getComputedStyle(slider_second).left);

    let new_pos_left_slider;

    if (event.currentTarget === arrow_right_second) {

        if (Math.abs(current_property_left_slider) + interval_second + slider_second.clientWidth >= slider.scrollWidth) {
            slider_second.style.left = "0px";
        } else {

            new_pos_left_slider = `${current_property_left_slider + interval_second}px`;
        }

        slider_second.style.left = new_pos_left_slider;
    }

    if (event.currentTarget === arrow_left_second) {
        new_pos_left_slider = current_property_left_slider - interval_second;

        if (new_pos_left_slider < 0) {
            slider_second.style.left = "0px";
        } else {
            slider_second.style.left = `${new_pos_left_slider}px`;
        }
    }
}

arrow_left_second.addEventListener("click", slide);
arrow_right_second.addEventListener("click", slide);

// // Auto-slide functionality
// let autoSlide = setInterval(() => {
//     arrow_right_second.click();
// }, 3000);

// // Pause auto-slide on mouse hover
// slider_second.addEventListener("mouseover", () => {
//     clearInterval(autoSlide);
// });

// // Resume auto-slide when mouse leaves
// slider_second.addEventListener("mouseout", () => {
//     autoSlide = setInterval(() => {
//         arrow_right_second.click();
//     }, 3000);
// });