document.addEventListener("DOMContentLoaded", function() {
    const footer = document.querySelector(".footer-fixed");

    window.addEventListener("scroll", function() {
        const scrollPosition = window.scrollY + window.innerHeight;
        const pageHeight = document.documentElement.scrollHeight;

        if (scrollPosition >= pageHeight) {
            footer.classList.add("footer-bottom"); // Footer devient normal en bas de page
        } else {
            footer.classList.remove("footer-bottom"); // Footer reste fixe en navigation
        }
    });
});
