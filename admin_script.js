document.addEventListener("DOMContentLoaded", function () {

    const userBtn = document.querySelector("#user-btn");
    const userBox = document.querySelector(".profile-detail");
    const toggle = document.querySelector(".toggle-btn");
    const sidebar = document.querySelector(".sidebar");

    if (userBtn && userBox) {
        userBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            userBox.classList.toggle("active");
        });
    }

    if (toggle && sidebar) {
        toggle.addEventListener("click", function (e) {
            e.stopPropagation();
            sidebar.classList.toggle("active");
        });
    }

    window.addEventListener("click", function (e) {
        if (userBox && !e.target.closest("#user-btn") && !e.target.closest(".profile-detail")) {
            userBox.classList.remove("active");
        }
    });

});
//for thumbnail slider
document.querySelectorAll('.thumb .small-images img').forEach(images => {
    images.onclick = () =>{
        src =images.getAttribute('src');
        document.querySelector('.thumb .big-image img').src = src;
    }
})