const header = document.querySelector('header');
function fixedNavBar(){
    header.classList.toggle('scrolled',window.pageYOffset > 0)    
}
fixedNavBar();
window.addEventListener('scroll', fixedNavBar);

let menu = document.querySelector('#menu-btn');
let userBtn = document.querySelector('#user-btn');

menu.addEventListener('click', function(){
    let nav = document.querySelector('.navbar');
    nav.classList.toggle('active');    
})

userBtn.addEventListener('click', function(){
    let userBox = document.querySelector('.user-box');
    userBox.classList.toggle('active');    
})

const closeBtn = document.querySelector('#close-form');

closeBtn.addEventListener('click',()=>{
    document.querySelector('.update-container').style.display='none'
})