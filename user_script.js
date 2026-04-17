document.querySelector('#user-btn')?.addEventListener('click', () => {
    document.querySelector('.profile')?.classList.toggle('active');
});

document.querySelector('#menu-btn')?.addEventListener('click', () => {
    document.querySelector('.navbar')?.classList.toggle('active');
});

document.querySelector('#search-btn')?.addEventListener('click', () => {
    document.querySelector('.header .flex .search-form')?.classList.toggle('active');
});

//for thumbnail slider
document.querySelectorAll('.thumb .small-image img').forEach(images => {
    images.onclick = () =>{
        src =images.getAttribute('src');
        document.querySelector('.thumb .big-image img').src = src;
    }
})
