// Scope it to prevent collisions
(() => {
    /**
     * Initialize card stack functionality
     * 
     * @since 1.0.0
     */
    const initCardStacks = () => {
        const projectStacks = document.querySelectorAll('.re-project-stack');
        
        projectStacks.forEach(stack => {
            const cards = stack.querySelectorAll('.re-project-card:not(.re-project-card--hidden)');
            const arrIndexes = Array.from({ length: cards.length }, (_, i) => i);

            /**
             * Set data-slide attributes for cards
             * 
             * @param {Array} arr Array of indexes
             */
            const setIndexes = (arr) => {
                cards.forEach((card, i) => {
                    card.dataset.slide = arr[i];
                });
            };

            // Initialize card indexes
            setIndexes(arrIndexes);

            // Handle click events
            stack.addEventListener('click', () => {
                arrIndexes.unshift(arrIndexes.pop());
                setIndexes(arrIndexes);
            });
        });
    };

    // Initialize when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCardStacks);
    } else {
        initCardStacks();
    }
})(); 