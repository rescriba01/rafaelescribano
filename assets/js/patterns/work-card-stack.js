// Scope it to prevent collisions
(() => {
    /**
     * Initialize card stack functionality
     * 
     * @since 1.0.0
     */
    const initCardStacks = () => {
        const cardStacks = document.querySelectorAll('.cards-wrapper');
        
        cardStacks.forEach(stack => {
            const cards = stack.querySelectorAll('.card');
            const indexes = Array.from({ length: cards.length }, (_, i) => i);

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
            setIndexes(indexes);

            // Handle click events
            stack.addEventListener('click', () => {
                indexes.unshift(indexes.pop());
                setIndexes(indexes);
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