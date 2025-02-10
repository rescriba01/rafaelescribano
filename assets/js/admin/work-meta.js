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
                const formData = new URLSearchParams({
                    action: 're_update_gallery_layout',
                    nonce: reWorkMeta.nonce,
                    post_id: reWorkMeta.post_id,
                    layout: layout
                });

                const response = await fetch(reWorkMeta.ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('AJAX Response:', data); // Debug log

                if (data.success) {
                    currentLayout = layout;
                    uploadButton.disabled = !currentLayout;
                    initializeMediaUploader();
                } else {
                    throw new Error(data.error || 'Unknown error occurred');
                }
            } catch (error) {
                console.error('Error details:', error);
                alert('Error updating layout. Please try again.');
                layoutSelect.value = '';
                uploadButton.disabled = true;
            }
        };

        // Initialize media uploader
        const initializeMediaUploader = () => {
            mediaUploader = wp.media({
                title: wp.i18n.__('Select Project Images', 're'),
                button: {
                    text: wp.i18n.__('Add to Gallery', 're')
                },
                multiple: currentLayout === 'split',
                library: {
                    type: 'image'
                }
            });

            mediaUploader.on('select', () => {
                const selection = mediaUploader.state().get('selection');
                const requiredCount = currentLayout === 'split' ? 2 : 1;

                if (selection.length !== requiredCount) {
                    alert(`Please select exactly ${requiredCount} image(s) for this layout.`);
                    return;
                }

                createGallerySection(currentLayout, selection.models);
                
                // Reset selection state
                layoutSelect.value = '';
                uploadButton.disabled = true;
                currentLayout = '';
            });
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

        // Create gallery section
        const createGallerySection = (layout, attachments) => {
            const section = document.createElement('div');
            section.className = 'gallery-section';
            section.dataset.layout = layout;

            const header = document.createElement('div');
            header.className = 'gallery-section-header';
            header.innerHTML = `
                <h4>${layout === 'full' ? 'Full Width Section' : '50/50 Split Section'}</h4>
                <button type="button" class="button-link remove-section">Remove Section</button>
            `;

            const imagesContainer = document.createElement('div');
            imagesContainer.className = `gallery-section-images ${layout}`;

            attachments.forEach(attachment => {
                const imagePreview = document.createElement('div');
                imagePreview.className = 'gallery-image-preview';
                imagePreview.innerHTML = `
                    <img src="${attachment.get('sizes').thumbnail.url}" alt="" class="wp-image-${attachment.get('id')}" />
                    <button type="button" class="remove-image" data-id="${attachment.get('id')}">×</button>
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
            const data = Array.from(galleryContainer.querySelectorAll('.gallery-section')).map(section => ({
                layout: section.dataset.layout,
                images: Array.from(section.querySelectorAll('.wp-image-[0-9]+')).map(img => {
                    const idMatch = img.className.match(/wp-image-(\d+)/);
                    return idMatch ? parseInt(idMatch[1]) : null;
                }).filter(id => id !== null)
            }));

            galleryInput.value = JSON.stringify(data);
            
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            galleryInput.dispatchEvent(event);
        };

        // Handle section and image removal
        galleryContainer.addEventListener('click', (e) => {
            if (e.target.matches('.remove-section')) {
                e.target.closest('.gallery-section').remove();
                updateGalleryData();
            } else if (e.target.matches('.remove-image')) {
                const preview = e.target.closest('.gallery-image-preview');
                preview.remove();
                updateGalleryData();
            }
        });

        // Initialize existing gallery data
        const initializeState = () => {
            const existingData = galleryInput.value;
            if (existingData) {
                try {
                    const parsedData = JSON.parse(existingData);
                    if (Array.isArray(parsedData)) {
                        parsedData.forEach(section => {
                            if (section.images && section.images.length > 0) {
                                const attachments = section.images.map(id => wp.media.attachment(id));
                                Promise.all(attachments.map(att => att.fetch())).then(() => {
                                    createGallerySection(section.layout, attachments);
                                });
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error parsing gallery data:', e);
                }
            }
        };

        initializeState();
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWorkMetaBox);
    } else {
        initWorkMetaBox();
    }
})(); 