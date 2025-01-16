window.onload = function() {
    slider();
}

function slider() {
    let slideIndex = 0;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");

    // Initially display the first slide
    showSlides();

    // Function to show slides
    function showSlides() {
        // Hide all slides initially
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";  
            slides[i].classList.remove("fade");
        }

        // Increment slide index and loop back to the first slide if necessary
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }    

        // Remove "slideractive" class from all dots
        for (let i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" slideractive", "");
        }

        // Show the current slide and highlight the active dot
        slides[slideIndex-1].style.display = "block";  
        slides[slideIndex-1].classList.add("fade"); // Add fade effect

        dots[slideIndex-1].className += " slideractive"; // Add the active class to the dot

        // Change slide every 6 seconds (adjust this number to your preference)
        setTimeout(showSlides, 6000);  // Change image every 6 seconds
    }

    // Function to allow manual navigation when a dot is clicked
    function currentSlide(n) {
        slideIndex = n - 1;
        showSlides();
    }

    // Add event listeners to the dots
    for (let i = 0; i < dots.length; i++) {
        dots[i].addEventListener("click", function() {
            currentSlide(i + 1); // i + 1 because slide index starts from 1, not 0
        });
    }
}
