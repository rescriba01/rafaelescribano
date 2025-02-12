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
        }

        /**
         * Initialize state from existing data
         */
        async initialize() {
            if ( !this.input.value ) {
                return;
            }

            try {
                // Clear existing sections first to prevent duplicates
                while ( this.container.firstChild ) {
                    this.container.removeChild( this.container.firstChild );
                }

                const parsedData = JSON.parse( this.input.value );
                if ( Array.isArray( parsedData ) ) {
                    // Process sections sequentially to maintain order
                    for ( const section of parsedData ) {
                        if ( section.images?.length ) {
                            try {
                                const attachments = await Promise.all(
                                    section.images.map( async ( id ) => {
                                        const attachment = wp.media.attachment( id );
                                        await attachment.fetch();
                                        return attachment;
                                    } )
                                );
                                
                                // Only create section if all attachments were loaded successfully
                                if ( attachments.every( att => att.get( 'id' ) ) ) {
                                    this.createSection( section.layout, attachments, false );
                                }
                            } catch ( err ) {
                                console.error( `Error loading attachments for section: ${err}` );
                                utils.createNotice(
                                    wp.i18n.__( 'Error loading some images. They may have been deleted.', 're' ),
                                    'warning'
                                );
                            }
                        }
                    }
                }
            } catch ( e ) {
                console.error( 'Error parsing gallery data:', e );
                utils.createNotice( wp.i18n.__( 'Error loading gallery data', 're' ), 'error' );
            }
        }

        /**
         * Create gallery section with selected images
         * 
         * @param {string}  layout      Section layout type
         * @param {Array}   attachments Array of media attachments
         * @param {boolean} shouldUpdate Whether to update gallery data after creation
         */
        createSection( layout, attachments, shouldUpdate = true ) {
            try {
                // Validate required number of images
                const requiredCount = layout === 'split' ? 2 : 1;
                if ( attachments.length !== requiredCount ) {
                    utils.createNotice(
                        `${wp.i18n.__( 'Section requires exactly', 're' )} ${requiredCount} ${wp.i18n.__( 'image(s)', 're' )}`,
                        'error'
                    );
                    return;
                }

                const section = utils.createElement( 'div', {
                    className: 'gallery-section',
                    dataset: { layout }
                } );

                const header = utils.createElement( 'div', 
                    { className: 'gallery-section-header' },
                    `<h4>${layout === 'full' ? 
                        wp.i18n.__( 'Full Width Section', 're' ) : 
                        wp.i18n.__( '50/50 Split Section', 're' )
                    }</h4>
                    <button type="button" class="button-link remove-section">
                        ${wp.i18n.__( 'Remove Section', 're' )}
                    </button>`
                );

                const imagesContainer = utils.createElement( 'div', {
                    className: `gallery-section-images ${layout}`
                } );

                // Create and filter out any null previews
                const validPreviews = attachments
                    .map( attachment => this.createImagePreview( attachment ) )
                    .filter( preview => preview !== null );

                // Check if we still have the required number of valid previews
                if ( validPreviews.length !== requiredCount ) {
                    utils.createNotice(
                        wp.i18n.__( 'Error creating one or more image previews', 're' ),
                        'error'
                    );
                    return;
                }

                validPreviews.forEach( preview => {
                    imagesContainer.appendChild( preview );
                } );

                section.appendChild( header );
                section.appendChild( imagesContainer );
                this.container.appendChild( section );

                if ( shouldUpdate ) {
                    this.update();
                }
            } catch ( error ) {
                console.error( 'Error creating gallery section:', error );
                utils.createNotice(
                    wp.i18n.__( 'Error creating gallery section', 're' ),
                    'error'
                );
            }
        }

        /**
         * Update gallery data in hidden input
         */
        update() {
            try {
                // Ensure container exists
                if (!this.container) {
                    console.warn('Gallery container not found');
                    return;
                }

                const sections = Array.from(this.container.querySelectorAll('.gallery-section'));
                
                // If no sections found, set empty array in input
                if (!sections.length) {
                    this.input.value = JSON.stringify([]);
                    this.input.dispatchEvent(new Event('change', { bubbles: true }));
                    return;
                }

                const data = sections.map(section => {
                    // Ensure section has required data
                    if (!section || !section.dataset || !section.dataset.layout) {
                        console.warn('Invalid section found, skipping');
                        return null;
                    }

                    const images = Array.from(section.querySelectorAll('img'))
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

                    return {
                        layout: section.dataset.layout,
                        images
                    };
                }).filter(section => section !== null && section.images.length > 0);

                this.input.value = JSON.stringify(data);
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
         * @return {HTMLElement}      Image preview element
         */
        createImagePreview( attachment ) {
            try {
                if ( !attachment || !attachment.get ) {
                    console.error( 'Invalid attachment object' );
                    return null;
                }

                const sizes = attachment.get( 'sizes' );
                const imageSize = sizes && ( sizes.medium || sizes.thumbnail );
                const imageUrl = imageSize ? imageSize.url : attachment.get( 'url' );
                const altText = attachment.get( 'alt' ) || '';
                
                return utils.createElement( 'div', 
                    { className: 'gallery-image-preview' },
                    `<img src="${imageUrl}" 
                         alt="${wp.htmlEscape ? wp.htmlEscape( altText ) : altText}" 
                         class="wp-image-${attachment.get( 'id' )}"
                         width="${imageSize ? imageSize.width : ''}"
                         height="${imageSize ? imageSize.height : ''}" />
                    <button type="button" class="remove-image" data-id="${attachment.get( 'id' )}">×</button>`
                );
            } catch ( error ) {
                console.error( 'Error creating image preview:', error );
                utils.createNotice(
                    wp.i18n.__( 'Error creating image preview', 're' ),
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
        constructor( currentLayout ) {
            this.frame = null;
            this.currentLayout = currentLayout;
            this.selectionCallback = null;
        }

        /**
         * Initialize and get media frame
         * 
         * @return {wp.media.view.MediaFrame.Select} Media frame instance
         */
        initialize() {
            // If frame exists, destroy it and create a new one to ensure proper state
            if ( this.frame ) {
                this.frame.dispose();
                this.frame = null;
            }

            // Create new frame with current settings
            this.frame = wp.media( {
                title: wp.i18n.__( 'Select Project Images', 're' ),
                button: {
                    text: wp.i18n.__( 'Add to Gallery', 're' )
                },
                multiple: this.currentLayout === 'split' ? 'add' : false,
                library: {
                    type: 'image'
                }
            } );

            // Add selection handler if callback is set
            if ( this.selectionCallback ) {
                this.frame.on( 'select', () => {
                    const selection = this.frame.state().get( 'selection' );
                    const requiredCount = this.currentLayout === 'split' ? 2 : 1;

                    if ( selection.length !== requiredCount ) {
                        utils.createNotice(
                            `${wp.i18n.__( 'Please select exactly', 're' )} ${requiredCount} ${wp.i18n.__( 'image(s) for this layout', 're' )}`,
                            'error'
                        );
                        return;
                    }

                    this.selectionCallback( selection.models );
                } );
            }

            return this.frame;
        }

        /**
         * Set frame state and open
         */
        open() {
            try {
                const frame = this.initialize();
                if ( frame && frame.open ) {
                    frame.open();
                }
            } catch ( error ) {
                console.error( 'Error opening media frame:', error );
                utils.createNotice(
                    wp.i18n.__( 'Error opening media library', 're' ),
                    'error'
                );
            }
        }

        /**
         * Add selection handler
         * 
         * @param {Function} callback Selection callback
         */
        onSelect( callback ) {
            this.selectionCallback = callback;
            // Initialize with the callback
            this.initialize();
        }
    }

    /**
     * Initialize gallery functionality
     */
    const initGallery = () => {
        const layoutSelect = document.getElementById( 'work_gallery_layout_select' );
        const uploadButton = document.getElementById( 'work_gallery_upload' );
        const galleryInput = document.getElementById( 'work_gallery_data' );
        const galleryContainer = document.querySelector( '.work-gallery-sections' );
        
        if ( !layoutSelect || !uploadButton || !galleryInput || !galleryContainer ) {
            console.error( 'Required gallery elements not found' );
            return;
        }

        let currentLayout = '';
        const state = new GalleryState( galleryContainer, galleryInput );
        const mediaManager = new MediaFrameManager( currentLayout );

        /**
         * Handle layout selection via REST API
         * 
         * @param {string} layout Selected layout
         */
        const handleLayoutSelection = async ( layout ) => {
            try {
                const response = await fetch( reWorkMeta.ajaxurl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-WP-Nonce': reWorkMeta.nonce
                    },
                    body: new URLSearchParams( {
                        action: 're_update_gallery_layout',
                        nonce: reWorkMeta.nonce,
                        post_id: reWorkMeta.post_id,
                        layout
                    } )
                } );

                if ( !response.ok ) {
                    throw new Error( `HTTP error! status: ${ response.status }` );
                }

                const data = await response.json();

                if ( data.success ) {
                    currentLayout = layout;
                    mediaManager.currentLayout = layout;
                    uploadButton.disabled = !currentLayout;
                    mediaManager.initialize();
                } else {
                    throw new Error( data.error || wp.i18n.__( 'Unknown error occurred', 're' ) );
                }
            } catch ( error ) {
                console.error( 'Error details:', error );
                utils.createNotice( error.message, 'error' );
                layoutSelect.value = '';
                uploadButton.disabled = true;
            }
        };

        // Event Handlers
        layoutSelect.addEventListener( 'change', ( event ) => {
            const selectedLayout = event.target.value;
            if ( selectedLayout ) {
                handleLayoutSelection( selectedLayout );
            } else {
                uploadButton.disabled = true;
                currentLayout = '';
            }
        } );

        uploadButton.addEventListener( 'click', ( event ) => {
            event.preventDefault();
            mediaManager.open();
        } );

        galleryContainer.addEventListener( 'click', ( e ) => {
            if ( e.target.matches( '.remove-section' ) ) {
                e.target.closest( '.gallery-section' ).remove();
                state.update();
            } else if ( e.target.matches( '.remove-image' ) ) {
                const preview = e.target.closest( '.gallery-image-preview' );
                preview.remove();
                state.update();
            }
        } );

        // Initialize
        mediaManager.onSelect( ( attachments ) => {
            state.createSection( currentLayout, attachments );
            layoutSelect.value = '';
            uploadButton.disabled = true;
            currentLayout = '';
        } );

        state.initialize();
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
} )(); 