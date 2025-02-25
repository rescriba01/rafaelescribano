/**
 * PhotoSwipe Helper
 * 
 * Provides a helper function to ensure PhotoSwipe is loaded before initializing.
 * 
 * @package re
 * @since 1.0.0
 */

/**
 * Check if PhotoSwipe is loaded and initialize the lightbox
 * 
 * @param {Function} callback - Function to call when PhotoSwipe is loaded
 */
function ensurePhotoSwipeLoaded(callback) {
    // Check if PhotoSwipe is already loaded
    if (typeof PhotoSwipe !== 'undefined' && typeof PhotoSwipeLightbox !== 'undefined') {
        callback();
        return;
    }

    // If not loaded, wait for it
    let attempts = 0;
    const maxAttempts = 10;
    
    const checkPhotoSwipe = () => {
        attempts++;
        
        if (typeof PhotoSwipe !== 'undefined' && typeof PhotoSwipeLightbox !== 'undefined') {
            callback();
            return;
        }
        
        if (attempts >= maxAttempts) {
            console.error('PhotoSwipe failed to load after multiple attempts');
            return;
        }
        
        // Try again after a short delay
        setTimeout(checkPhotoSwipe, 200);
    };
    
    // Start checking
    checkPhotoSwipe();
}

// Export the helper function
window.ensurePhotoSwipeLoaded = ensurePhotoSwipeLoaded; 