function toggleFAQ(id) {
    var answer = document.getElementById(id);
    if (!answer) return;
    answer.style.display = answer.style.display === "block" ? "none" : "block";
}
