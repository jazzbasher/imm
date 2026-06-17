document.addEventListener('DOMContentLoaded', function () {
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        setTimeout(() => {
            // Smooth fade effect
            flashMessage.style.transition = "opacity 0.5s ease";
            flashMessage.style.opacity = "0";
                
            // Remove from DOM completely after fading out
            setTimeout(() => flashMessage.remove(), 500); 
        }, 4000); // Displays for 4 seconds
    }
});