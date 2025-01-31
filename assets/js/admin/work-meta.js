/**
 * Work Meta Box Functionality
 * 
 * @package re
 * @since 1.0.0
 */

(() => {
    'use strict';

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
        const tabButtons = document.querySelectorAll('.work-meta-tabs__nav-item');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tab = button.dataset.tab;
                
                // Update active states
                document.querySelectorAll('.work-meta-tabs__nav-item').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                
                // Show selected panel
                document.querySelectorAll('.work-meta-tabs__panel').forEach(panel => {
                    panel.classList.remove('active');
                });
                document.querySelector(`.work-meta-tabs__panel[data-tab="${tab}"]`).classList.add('active');
            });
        });
    };

    /**
     * Initialize gallery functionality
     */
    const initGallery = () => {
        const layoutSelect = document.getElementById('work_gallery_layout_select');
        const uploadButton = document.getElementById('work_gallery_upload');
        const galleryInput = document.getElementById('work_gallery_data');
        const galleryContainer = document.querySelector('.work-gallery-sections');
        let currentLayout = '';
        let mediaUploader = null;

        if (!layoutSelect || !uploadButton || !galleryInput || !galleryContainer) {
            console.error('Required gallery elements not found');
            return;
        }

        // Handle layout selection via AJAX
        const handleLayoutSelection = async (layout) => {
            try {
                const response = await fetch(reWorkMeta.ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 're_update_gallery_layout',
                        nonce: reWorkMeta.nonce,
                        post_id: reWorkMeta.post_id,
                        layout: layout
                    })
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Response not ok:', {
                        status: response.status,
                        statusText: response.statusText,
                        responseText: errorText
                    });
                    throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    currentLayout = layout;
                    uploadButton.disabled = !currentLayout;
                    
                    // Initialize media uploader if needed
                    initializeMediaUploader();
                } else {
                    throw new Error(data.data?.message || 'Error updating layout');
                }
            } catch (error) {
                console.error('Error details:', error);
                alert(wp.i18n.__('Error updating layout. Please try again.', 're'));
                layoutSelect.value = '';
                uploadButton.disabled = true;
            }
        };

        // Initialize media uploader
        const initializeMediaUploader = () => {
            if (!mediaUploader) {
                mediaUploader = wp.media({
                    title: wp.i18n.__('Select Project Images', 're'),
                    button: {
                        text: wp.i18n.__('Add to Gallery', 're')
                    },
                    multiple: true
                });

                mediaUploader.on('select', () => {
                    const attachments = mediaUploader.state().get('selection').map(
                        attachment => attachment.toJSON()
                    );

                    // Validate selection based on layout
                    if (currentLayout === 'full' && attachments.length > 1) {
                        alert(wp.i18n.__('Full width layout only allows one image.', 're'));
                        return;
                    }
                    if (currentLayout === 'split' && attachments.length !== 2) {
                        alert(wp.i18n.__('Split layout requires exactly two images.', 're'));
                        return;
                    }

                    createGallerySection(currentLayout, attachments);
                    
                    // Reset layout selection
                    layoutSelect.value = '';
                    uploadButton.disabled = true;
                    currentLayout = '';
                });
            }
        };

        // Layout selection handler
        layoutSelect.addEventListener('change', (event) => {
            const selectedLayout = event.target.value;
            if (selectedLayout) {
                handleLayoutSelection(selectedLayout);
            } else {
                uploadButton.disabled = true;
                currentLayout = '';
            }
        });

        // Render existing gallery sections
        const renderExistingSections = (sections) => {
            sections.forEach(section => {
                createGallerySection(section.layout, section.images.map(id => ({
                    id: id,
                    sizes: { thumbnail: { url: wp.media.attachment(id).get('url') } }
                })));
            });
        };

        // Create gallery section
        const createGallerySection = (layout, attachments) => {
            const section = document.createElement('div');
            section.className = 'gallery-section';
            section.dataset.layout = layout;

            const header = document.createElement('div');
            header.className = 'gallery-section-header';
            header.innerHTML = `
                <h4>${layout === 'full' ? 'Full Width Section' : '50/50 Split Section'}</h4>
                <button type="button" class="button-link remove-section">${wp.i18n.__('Remove Section', 're')}</button>
            `;

            const imagesContainer = document.createElement('div');
            imagesContainer.className = `gallery-section-images ${layout}`;

            attachments.forEach(attachment => {
                const imagePreview = document.createElement('div');
                imagePreview.className = 'gallery-image-preview';
                imagePreview.innerHTML = `
                    <img src="${attachment.sizes.thumbnail.url}" alt="" />
                    <button type="button" class="remove-image" data-id="${attachment.id}">×</button>
                `;
                imagesContainer.appendChild(imagePreview);
            });

            section.appendChild(header);
            section.appendChild(imagesContainer);
            galleryContainer.appendChild(section);

            updateGalleryData();
        };

        // Update gallery data
        const updateGalleryData = () => {
            const data = Array.from(document.querySelectorAll('.gallery-section')).map(section => ({
                layout: section.dataset.layout,
                images: Array.from(section.querySelectorAll('.remove-image')).map(btn => btn.dataset.id)
            }));

            galleryInput.value = JSON.stringify(data);
            
            // Trigger change event for WordPress to recognize the update
            const event = new Event('change', { bubbles: true });
            galleryInput.dispatchEvent(event);
        };

        // Initialize existing gallery data
        const initializeState = () => {
            const existingData = galleryInput.value;
            if (existingData) {
                try {
                    const parsedData = JSON.parse(existingData);
                    renderExistingSections(parsedData);
                } catch (e) {
                    console.error('Error parsing gallery data:', e);
                }
            }
        };

        // Upload button handler
        uploadButton.addEventListener('click', (e) => {
            e.preventDefault();
            if (!currentLayout) return;
            
            if (mediaUploader) {
                mediaUploader.open();
            }
        });

        // Gallery container event delegation
        galleryContainer.addEventListener('click', (e) => {
            if (e.target.matches('.remove-section')) {
                e.target.closest('.gallery-section').remove();
                updateGalleryData();
            } else if (e.target.matches('.remove-image')) {
                const section = e.target.closest('.gallery-section');
                e.target.closest('.gallery-image-preview').remove();
                
                if (section.dataset.layout === 'split' && 
                    section.querySelectorAll('.gallery-image-preview').length < 2) {
                    section.remove();
                }
                
                updateGalleryData();
            }
        });

        // Initialize the gallery state
        initializeState();
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWorkMetaBox);
    } else {
        initWorkMetaBox();
    }
})(); 