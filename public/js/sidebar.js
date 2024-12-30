// public/js/sidebar.js
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const logo = document.getElementById("logo");
    const toggleIcon = document.getElementById("toggleIcon");
    
    // Save state to localStorage
    const isExpanded = sidebar.classList.contains("w-64");
    localStorage.setItem('sidebarExpanded', !isExpanded);
    
    if (isExpanded) {
        sidebar.classList.remove("w-64");
        sidebar.classList.add("w-16");
        logo.classList.add("hidden");
        toggleIcon.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>`;
    } else {
        sidebar.classList.remove("w-16");
        sidebar.classList.add("w-64");
        logo.classList.remove("hidden");
        toggleIcon.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>`;
    }
}

// On page load, restore sidebar state
document.addEventListener('DOMContentLoaded', function() {
    const sidebarExpanded = localStorage.getItem('sidebarExpanded');
    if (sidebarExpanded === 'false') {
        toggleSidebar();
    }
});