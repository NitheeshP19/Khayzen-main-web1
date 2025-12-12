(function(){
  // Logic for handling RD Navbar submenu toggles with event delegation
  // This ensures it works even if RD Navbar clones elements for mobile view
  
  function closeAllMenus(exceptMenuId) {
      var menus = ['products-menu', 'tech-menu'];
      menus.forEach(function(id) {
          if (id === exceptMenuId) return;
          var menu = document.getElementById(id);
          var btn = document.querySelector('.rd-nav-button[aria-controls="' + id + '"]');
          
          if (menu) {
              menu.classList.remove('open');
              menu.setAttribute('aria-hidden', 'true');
              menu.style.display = ''; // Revert to CSS
          }
          if (btn) {
              btn.setAttribute('aria-expanded', 'false');
          }
      });
  }

  function toggleMenu(btn) {
      var menuId = btn.getAttribute('aria-controls');
      var menu = document.getElementById(menuId);
      if(!menu) return;

      var isOpen = btn.getAttribute('aria-expanded') === 'true';

      if (isOpen) {
          // Close
          btn.setAttribute('aria-expanded', 'false');
          menu.setAttribute('aria-hidden', 'true');
          menu.classList.remove('open');
          menu.style.display = '';
      } else {
          // Open
          // Close other menus first (optional, but good for mobile)
          closeAllMenus(menuId);

          btn.setAttribute('aria-expanded', 'true');
          menu.setAttribute('aria-hidden', 'false');
          menu.classList.add('open');
          menu.style.display = 'block';
      }
  }

  // Event Delegation
  document.body.addEventListener('click', function(e) {
      // Find if clicked element is a nav button or inside one
      var btn = e.target.closest('.rd-nav-button');
      if (btn) {
          e.preventDefault();
          e.stopPropagation();
          toggleMenu(btn);
      } else {
          // If clicked outside, close menus
          var menu = e.target.closest('.rd-navbar-dropdown');
          if (!menu) {
               closeAllMenus();
          }
      }
  });
  
  // Handle touch events specifically if needed (though click often covers it)
  // Some mobile browsers need touchstart/pointerdown
  document.body.addEventListener('pointerdown', function(e){
      // Only interfere if it's a nav button
       var btn = e.target.closest('.rd-nav-button');
       if(btn && e.pointerType === 'touch'){
           // Let the click handler handle it to avoid double fire, 
           // OR handle it here and preventDefault.
           // Usually click is safer for hybrid.
       }
  });

  // Close on resize
  window.addEventListener('resize', function() {
      closeAllMenus();
  });
})();
