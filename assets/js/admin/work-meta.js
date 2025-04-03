/**
 * Work Meta Box Functionality
 * 
 * @package re
 * @since 1.0.0
 */

( () => {
    'use strict';

    /**
     * Utility functions for notifications and DOM manipulation
     */
    const utils = {
        /**
         * Create a WordPress admin notice
         * 
         * @param {string} message Notice message
         * @param {string} type   Notice type (error, success, warning, info)
         */
        createNotice: ( message, type = 'info' ) => {
            wp.data.dispatch( 'core/notices' ).createNotice(
                type,
                message,
                {
                    isDismissible: true,
                    type: 'snackbar'
                }
            );
        },

        /**
         * Create DOM element with attributes
         * 
         * @param {string}      tag     Element tag name
         * @param {Object}      attrs   Element attributes
         * @param {string|Node} content Element content
         * @return {HTMLElement}        Created element
         */
        createElement: ( tag, attrs = {}, content = '' ) => {
            const element = document.createElement( tag );
            Object.entries( attrs ).forEach( ( [ key, value ] ) => {
                if ( key === 'className' ) {
                    element.className = value;
                } else if ( key === 'dataset' ) {
                    Object.entries( value ).forEach( ( [ dataKey, dataValue ] ) => {
                        element.dataset[dataKey] = dataValue;
                    } );
                } else {
                    element.setAttribute( key, value );
                }
            } );
            if ( content ) {
                element.innerHTML = content;
            }
            return element;
        }
    };

    /**
     * Gallery state management
     */
    class GalleryState {
        constructor( container, input ) {
            this.container = container;
            this.input = input;
            console.log('Initializing gallery state...');
            console.log('Initial gallery data:', this.input.value ? JSON.parse(this.input.value) : []);
        }

        /**
         * Initialize state from existing data
         */
        async initialize() {
            if (!this.input.value) {
                return;
            }

            try {
                // Clear existing images first to prevent duplicates
                this.clearGalleryContainer();

                const data = JSON.parse(this.input.value);
                console.log('Parsed gallery data:', data);
                
                if (Array.isArray(data) && data.length > 0) {
                    // Create gallery container if it doesn't exist
                    let galleryContainer = this.container.querySelector('.gallery-images-container');
                    if (!galleryContainer) {
                        galleryContainer = this.createGalleryContainer();
                    }
                    
                    // Load and display all images
                    const imageIds = data.flatMap(item => item.images || []);
                    
                    if (imageIds.length > 0) {
                        try {
                            const attachments = await Promise.all(
                                imageIds.map(async (id) => {
                                    const attachment = wp.media.attachment(id);
                                    await attachment.fetch();
                                    return attachment;
                                })
                            );
                            
                            // Filter out any failed attachments
                            const validAttachments = attachments.filter(att => att && att.get('id'));
                            
                            // Add each attachment to the gallery
                            for (const attachment of validAttachments) {
                                this.addImageToGallery(attachment, galleryContainer);
                            }
                        } catch (err) {
                            console.error('Error loading attachments:', err);
                            utils.createNotice(
                                wp.i18n.__('Error loading some images. They may have been deleted.', 're'),
                                'warning'
                            );
                        }
                    }
                }
            } catch (e) {
                console.error('Error parsing gallery data:', e);
                utils.createNotice(wp.i18n.__('Error loading gallery data', 're'), 'error');
            }
        }

        /**
         * Clear the gallery container
         */
        clearGalleryContainer() {
            console.log('Clearing gallery container');
            // Remove only the gallery content, not section headers
            const galleryContainer = this.container.querySelector('.gallery-images-container');
            if (galleryContainer) {
                while (galleryContainer.firstChild) {
                    galleryContainer.removeChild(galleryContainer.firstChild);
                }
            } else {
                // If there's no container yet, clear everything
                while (this.container.firstChild) {
                    this.container.removeChild(this.container.firstChild);
                }
            }
        }

        /**
         * Create the gallery container
         * 
         * @return {HTMLElement} The gallery container element
         */
        createGalleryContainer() {
            const section = utils.createElement('div', {
                className: 'gallery-section',
            });

            const header = utils.createElement('div', 
                { className: 'gallery-section-header' },
                `<h4>${wp.i18n.__('Project Images Gallery', 're')}</h4>`
            );

            const imagesContainer = utils.createElement('div', {
                className: 'gallery-images-container'
            });

            section.appendChild(header);
            section.appendChild(imagesContainer);
            this.container.appendChild(section);
            
            return imagesContainer;
        }

        /**
         * Add new images to the gallery
         * 
         * @param {Array} attachments Array of media attachments
         */
        addImages(attachments) {
            if (!attachments || attachments.length === 0) {
                return;
            }
            
            console.log('Adding images to gallery:', attachments.length);
            
            // Create or get gallery container
            let galleryContainer = this.container.querySelector('.gallery-images-container');
            if (!galleryContainer) {
                galleryContainer = this.createGalleryContainer();
            }
            
            // Add each attachment to the gallery
            for (const attachment of attachments) {
                this.addImageToGallery(attachment, galleryContainer);
            }
            
            // Update the input value
            this.update();
        }

        /**
         * Add a single image to the gallery
         * 
         * @param {Object} attachment Media attachment object
         * @param {HTMLElement} container Container to add the image to
         */
        addImageToGallery(attachment, container) {
            const preview = this.createImagePreview(attachment);
            if (preview) {
                container.appendChild(preview);
                
                // Add event listener to remove button
                const removeButton = preview.querySelector('.remove-image');
                if (removeButton) {
                    removeButton.addEventListener('click', () => {
                        preview.remove();
                        this.update();
                    });
                }
            }
        }

        /**
         * Update gallery data in hidden input
         */
        update() {
            console.log('Updating gallery data...');
            try {
                const images = Array.from(this.container.querySelectorAll('img'))
                    .map(img => {
                        if (!img || !img.classList) {
                            return null;
                        }
                        
                        // Find wp-image class
                        const wpImageClass = Array.from(img.classList)
                            .find(className => className.startsWith('wp-image-'));
                        
                        if (!wpImageClass) {
                            return null;
                        }

                        const id = parseInt(wpImageClass.replace('wp-image-', ''), 10);
                        return isNaN(id) ? null : id;
                    })
                    .filter(id => id !== null);
                
                console.log('Collected image IDs:', images);
                
                // Create a single gallery entry with all images
                const galleryData = images.length > 0 ? [{
                    layout: 'gallery', // Single consistent layout type
                    images: images
                }] : [];
                
                console.log('New gallery data:', galleryData);
                
                this.input.value = JSON.stringify(galleryData);
                this.input.dispatchEvent(new Event('change', { bubbles: true }));
            } catch (error) {
                console.error('Error updating gallery data:', error);
                utils.createNotice(wp.i18n.__('Error updating gallery data', 're'), 'error');
            }
        }

        /**
         * Create image preview element
         * 
         * @param {Object} attachment Media attachment object
         * @return {HTMLElement|null} Image preview element or null if creation failed
         */
        createImagePreview(attachment) {
            try {
                console.log('Creating image preview for attachment:', attachment);
                
                if (!attachment || !attachment.get) {
                    console.error('Invalid attachment object');
                    return null;
                }

                const sizes = attachment.get('sizes');
                const imageSize = sizes && (sizes.medium || sizes.thumbnail);
                const imageUrl = imageSize ? imageSize.url : attachment.get('url');
                const altText = attachment.get('alt') || '';
                
                return utils.createElement('div', 
                    { className: 'gallery-image-preview' },
                    `<img src="${imageUrl}" 
                         alt="${wp.htmlEscape ? wp.htmlEscape(altText) : altText}" 
                         class="wp-image-${attachment.get('id')}"
                         width="${imageSize ? imageSize.width : ''}"
                         height="${imageSize ? imageSize.height : ''}" />
                    <button type="button" class="remove-image" data-id="${attachment.get('id')}">×</button>`
                );
            } catch (error) {
                console.error('Error creating image preview:', error);
                utils.createNotice(
                    wp.i18n.__('Error creating image preview', 're'),
                    'error'
                );
                return null;
            }
        }
    }

    /**
     * Media frame management
     */
    class MediaFrameManager {
        constructor() {
            this.frame = null;
            this.selectionCallback = null;
        }

        /**
         * Initialize and get media frame
         * 
         * @return {wp.media.view.MediaFrame.Select} Media frame instance
         */
        initialize() {
            // If frame exists, destroy it and create a new one to ensure proper state
            if (this.frame) {
                this.frame.dispose();
                this.frame = null;
            }

            // Create new frame with multiple selection enabled
            this.frame = wp.media({
                title: wp.i18n.__('Select Project Images', 're'),
                button: {
                    text: wp.i18n.__('Add to Gallery', 're')
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });

            // Add selection handler if callback is set
            if (this.selectionCallback) {
                this.frame.on('select', () => {
                    const selection = this.frame.state().get('selection');
                    this.selectionCallback(selection.models);
                });
            }

            return this.frame;
        }

        /**
         * Set frame state and open
         */
        open() {
            try {
                const frame = this.initialize();
                if (frame && frame.open) {
                    frame.open();
                }
            } catch (error) {
                console.error('Error opening media frame:', error);
                utils.createNotice(
                    wp.i18n.__('Error opening media library', 're'),
                    'error'
                );
            }
        }

        /**
         * Add selection handler
         * 
         * @param {Function} callback Selection callback
         */
        onSelect(callback) {
            this.selectionCallback = callback;
            // Initialize with the callback
            this.initialize();
        }
    }

    /**
     * Initialize gallery functionality
     */
    const initGallery = () => {
        // Add debug logging for initialization
        console.log('Initializing gallery functionality...');
        
        const uploadButton = document.getElementById('work_gallery_upload');
        const galleryInput = document.getElementById('work_gallery_data');
        const galleryContainer = document.querySelector('.work-gallery-sections');
        
        // Log element existence
        console.log('Required elements found:', {
            uploadButton: !!uploadButton,
            galleryInput: !!galleryInput,
            galleryContainer: !!galleryContainer
        });
        
        if (!uploadButton || !galleryInput || !galleryContainer) {
            console.error('Required gallery elements not found');
            utils.createNotice(wp.i18n.__('Error: Required gallery elements not found', 're'), 'error');
            return;
        }

        const state = new GalleryState(galleryContainer, galleryInput);
        const mediaManager = new MediaFrameManager();

        // Verify wp.media availability
        if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
            console.error('WordPress media library not available');
            utils.createNotice(wp.i18n.__('Error: Media library not available', 're'), 'error');
            return;
        }

        // Setup event handlers
        uploadButton.addEventListener('click', (event) => {
            console.log('Upload button clicked');
            event.preventDefault();
            
            // Verify wp.media state before opening
            if (!wp.media || !mediaManager.frame) {
                console.error('Media frame not properly initialized');
                utils.createNotice(wp.i18n.__('Error: Media library not properly initialized', 're'), 'error');
                return;
            }
            
            mediaManager.open();
        });

        // Add event delegation for removing sections and images
        galleryContainer.addEventListener('click', (event) => {
            // Handle remove section button clicks
            if (event.target.classList.contains('remove-section')) {
                const section = event.target.closest('.gallery-section');
                if (section) {
                    section.remove();
                    state.update();
                }
            }
        });

        // Initialize with logging
        try {
            mediaManager.onSelect((attachments) => {
                console.log('Media selection made:', attachments.length);
                state.addImages(attachments);
            });

            state.initialize().catch(error => {
                console.error('State initialization error:', error);
                utils.createNotice(wp.i18n.__('Error initializing gallery state', 're'), 'error');
            });
        } catch (error) {
            console.error('Gallery initialization error:', error);
            utils.createNotice(wp.i18n.__('Error initializing gallery', 're'), 'error');
        }
    };

    /**
     * Initialize the meta box functionality
     */
    const initWorkMetaBox = () => {
        initTabs();
        initGallery();
    };

    /**
     * Initialize tab functionality
     */
    const initTabs = () => {
        const tabButtons = document.querySelectorAll( '.work-meta-tabs__nav-item' );
        
        tabButtons.forEach( button => {
            button.addEventListener( 'click', () => {
                const tab = button.dataset.tab;
                
                // Update active states
                document.querySelectorAll( '.work-meta-tabs__nav-item' ).forEach( btn => {
                    btn.classList.remove( 'active' );
                } );
                button.classList.add( 'active' );
                
                // Show selected panel
                document.querySelectorAll( '.work-meta-tabs__panel' ).forEach( panel => {
                    panel.classList.remove( 'active' );
                } );
                document.querySelector( `.work-meta-tabs__panel[data-tab="${ tab }"]` ).classList.add( 'active' );
            } );
        } );
    };

    // Initialize when DOM is ready
    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', initWorkMetaBox );
    } else {
        initWorkMetaBox();
    }

    /**
     * Toggle external link field visibility
     */
    function toggleExternalLink() {
        const checkbox = document.getElementById('work_has_external_link');
        const wrapper = document.getElementById('work_external_link_wrapper');
        
        if (checkbox && wrapper) {
            wrapper.style.display = checkbox.checked ? 'block' : 'none';
        }
    }

    /**
     * Initialize work meta box functionality
     */
    function init() {
        const checkbox = document.getElementById('work_has_external_link');
        if (checkbox) {
            checkbox.addEventListener('change', toggleExternalLink);
            toggleExternalLink(); // Run on page load
        }
    }

    // Initialize when document is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
} )(); 