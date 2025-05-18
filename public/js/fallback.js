document.addEventListener('DOMContentLoaded', function() {
    // Handle image errors
    document.querySelectorAll('img').forEach(function(img) {
        img.addEventListener('error', function() {
            // Only replace if fallback isn't already in use (prevent infinite loop)
            if (!this.src.includes('source.unsplash.com') && !this.src.includes('placeholder.com')) {
                if (this.alt.toLowerCase().includes('kite') || this.alt.toLowerCase().includes('surf')) {
                    this.src = 'https://source.unsplash.com/random/800x600/?kitesurfing';
                } else {
                    this.src = 'https://via.placeholder.com/800x600/4B5563/FFFFFF?text=Windkracht+12';
                }
            }
        });
    });

    // Mobile menu toggle
    const menuButton = document.querySelector('nav button');
    const menuItems = document.querySelector('nav .hidden.md\\:flex');
    
    if (menuButton && menuItems) {
        menuButton.addEventListener('click', function() {
            if (menuItems.classList.contains('hidden')) {
                menuItems.classList.remove('hidden');
                menuItems.classList.add('flex', 'flex-col', 'absolute', 'top-16', 'left-0', 'right-0', 'bg-blue-900', 'p-4', 'shadow-lg', 'z-50');
            } else {
                menuItems.classList.add('hidden');
                menuItems.classList.remove('flex', 'flex-col', 'absolute', 'top-16', 'left-0', 'right-0', 'bg-blue-900', 'p-4', 'shadow-lg', 'z-50');
            }
        });
    }
});
