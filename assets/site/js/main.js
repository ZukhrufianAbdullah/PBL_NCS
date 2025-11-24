/**
 * Minimal enhancements to mirror mockup behaviour:
 *  - Navbar shadow after scroll
 *  - Close dropdown when clicking outside on mobile
 */

document.addEventListener("DOMContentLoaded", () => {
    const navbar = document.querySelector(".lab-navbar");

    const toggleNavShadow = () => {
        if (!navbar) return;
        if (window.scrollY > 16) {
            navbar.classList.add("nav-scrolled");
        } else {
            navbar.classList.remove("nav-scrolled");
        }
    };

    window.addEventListener("scroll", toggleNavShadow, { passive: true });
    toggleNavShadow();

    // Prevent dropdown menu from closing when inside is clicked (touch devices)
    document.querySelectorAll(".dropdown-menu").forEach((menu) => {
        menu.addEventListener("click", (event) => {
            event.stopPropagation();
        });
    });
});

