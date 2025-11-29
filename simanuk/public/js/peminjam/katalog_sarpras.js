document.addEventListener("DOMContentLoaded", function () {
   // Sidebar Logic (Mobile)
   const sidebar = document.getElementById('sidebar');
   const toggleBtn = document.getElementById('toggleSidebar');
   const closeBtn = document.getElementById('closeSidebar');

   if (sidebar && toggleBtn) {
      toggleBtn.addEventListener('click', () => sidebar.classList.remove('-translate-x-full'));
   }
   if (sidebar && closeBtn) {
      closeBtn.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));
   }

   // Auto-switch tab based on URL
   const urlParams = new URLSearchParams(window.location.search);
   if (urlParams.has('page_prasarana')) {
      switchTab('prasarana');
   } else {
      switchTab('sarana');
   }
});

function switchTab(tabName) {
   const btnSarana = document.getElementById('tab-sarana-btn');
   const btnPrasarana = document.getElementById('tab-prasarana-btn');
   const contentSarana = document.getElementById('tab-sarana-content');
   const contentPrasarana = document.getElementById('tab-prasarana-content');

   // Class Styles
   const activeClass = "border-blue-600 text-blue-600 active";
   const inactiveClass = "border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300";

   if (tabName === 'sarana') {
      contentSarana.classList.remove('hidden');
      contentPrasarana.classList.add('hidden');

      btnSarana.className = "inline-block p-4 border-b-2 rounded-t-lg group transition-all duration-300 " + activeClass;
      btnPrasarana.className = "inline-block p-4 border-b-2 rounded-t-lg group transition-all duration-300 " + inactiveClass;
   } else {
      contentSarana.classList.add('hidden');
      contentPrasarana.classList.remove('hidden');

      // Custom color for prasarana tab if desired, otherwise stick to activeClass
      btnPrasarana.className = "inline-block p-4 border-b-2 rounded-t-lg group transition-all duration-300 " + activeClass;
      btnSarana.className = "inline-block p-4 border-b-2 rounded-t-lg group transition-all duration-300 " + inactiveClass;
   }
}