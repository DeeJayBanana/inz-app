import 'bootstrap/dist/js/bootstrap.bundle.min.js';

document.addEventListener('DOMContentLoaded',() => {
   const menuContent = document.querySelector('.menu-content');
   const menuOffCanvas = document.querySelector('#offcanvasWithBothOptions .offcanvas-body');

   if(menuContent && menuOffCanvas) {
       menuOffCanvas.innerHTML = menuContent.innerHTML;
   }
});
