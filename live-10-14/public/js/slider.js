 let slideIndex = 0;
  const slides = document.querySelectorAll('.slide');
  
  function showSlide(index) {
    slides.forEach((slide, i) => {
      if (i === index) {
        slide.style.display = 'block';
      } else {
        slide.style.display = 'none';
      }
    });
  }
  
  function prevSlide() {
    slideIndex = (slideIndex - 1 + slides.length) % slides.length;
    showSlide(slideIndex);
  }
  
  function nextSlide() {
    slideIndex = (slideIndex + 1) % slides.length;
    showSlide(slideIndex);
  }
  
  function autoSlide() {
    nextSlide();
    setTimeout(autoSlide, 3000); // Change slide every 3 seconds
  }
  
  autoSlide(); // Start auto sliding