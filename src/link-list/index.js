import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import Edit from './edit';
import Save from './save';

registerBlockType('re/link-list', {
    title: __('Link List', 're'),
    description: __('Display a list of links with hover effects.', 're'),
    category: 'widgets',
    icon: 'admin-links',
    attributes: {
        title: {
            type: 'string',
            default: 'Links'
        },
        links: {
            type: 'array',
            default: [],
            items: {
                type: 'object',
                properties: {
                    text: { type: 'string' },
                    url: { type: 'string' }
                }
            }
        }
    },
    edit: Edit,
    save: Save
}); 