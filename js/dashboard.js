var currentSlide = 0;

function closeNotification() {
    document.getElementById("notification").style.display = "none";
}

function openModal() {
    document.getElementById("infoModal").style.display = "block";
}

function closeModal() {
    document.getElementById("infoModal").style.display = "none";
}

function showSlide(index) {
    var slides = document.getElementsByClassName("slide");
    if (slides.length === 0) return;
    if (index >= slides.length) currentSlide = 0;
    if (index < 0) currentSlide = slides.length - 1;
    for (var i = 0; i < slides.length; i++) {
        slides[i].classList.remove("active");
    }
    slides[currentSlide].classList.add("active");
}

function nextSlide() { currentSlide++; showSlide(currentSlide); }
function previousSlide() { currentSlide--; showSlide(currentSlide); }
