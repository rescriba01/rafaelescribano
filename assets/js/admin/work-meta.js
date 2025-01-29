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
        const uploadButton = document.getElementById('work_gallery_upload');
        if (!uploadButton) return;

        let mediaUploader = null;

        uploadButton.addEventListener('click', (e) => {
            e.preventDefault();

            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Create the media uploader
            mediaUploader = wp.media({
                title: wp.i18n.__('Select Project Images', 're'),
                button: {
                    text: wp.i18n.__('Add to Gallery', 're')
                },
                multiple: true
            });

            // When images are selected, handle the data
            mediaUploader.on('select', () => {
                const attachments = mediaUploader.state().get('selection').map(
                    attachment => attachment.toJSON()
                );

                const galleryInput = document.getElementById('work_gallery_images');
                const galleryPreview = document.getElementById('work_gallery_preview');
                
                let currentImages = galleryInput.value ? galleryInput.value.split(',') : [];

                attachments.forEach(attachment => {
                    if (!currentImages.includes(attachment.id.toString())) {
                        currentImages.push(attachment.id);
                        
                        const imagePreview = document.createElement('div');
                        imagePreview.className = 'gallery-image-preview';
                        imagePreview.innerHTML = `
                            <img src="${attachment.sizes.thumbnail.url}" alt="" />
                            <button type="button" class="remove-image" data-id="${attachment.id}">×</button>
                        `;
                        
                        galleryPreview.appendChild(imagePreview);
                    }
                });

                galleryInput.value = currentImages.join(',');
            });

            mediaUploader.open();
        });

        // Handle image removal
        document.addEventListener('click', (e) => {
            if (!e.target.matches('.remove-image')) return;
            
            const imageId = e.target.dataset.id;
            const galleryInput = document.getElementById('work_gallery_images');
            const currentImages = galleryInput.value.split(',');
            
            galleryInput.value = currentImages.filter(id => id !== imageId).join(',');
            e.target.closest('.gallery-image-preview').remove();
        });
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWorkMetaBox);
    } else {
        initWorkMetaBox();
    }
})(); 