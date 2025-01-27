import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, URLInput } from '@wordpress/block-editor';
import { TextControl, Button, Flex, FlexBlock } from '@wordpress/components';

const Edit = ({ attributes, setAttributes }) => {
    const { title, links } = attributes;
    const blockProps = useBlockProps();

    const addLink = () => {
        setAttributes({
            links: [...links, { text: '', url: '' }]
        });
    };

    const updateLink = (index, property, value) => {
        const newLinks = [...links];
        newLinks[index] = { ...newLinks[index], [property]: value };
        setAttributes({ links: newLinks });
    };

    const removeLink = (index) => {
        setAttributes({
            links: links.filter((_, i) => i !== index)
        });
    };

    return (
        <div {...blockProps}>
            <RichText
                tagName="h3"
                value={title}
                onChange={(title) => setAttributes({ title })}
                placeholder={__('Enter list title...', 're')}
                className="link-list-title"
            />
            
            <ul className="link-list">
                {links.map((link, index) => (
                    <li key={index} className="link-list-item">
                        <Flex>
                            <FlexBlock>
                                <TextControl
                                    placeholder={__('Link text...', 're')}
                                    value={link.text}
                                    onChange={(value) => updateLink(index, 'text', value)}
                                />
                                <URLInput
                                    value={link.url}
                                    onChange={(value) => updateLink(index, 'url', value)}
                                    placeholder={__('Enter URL or search for content...', 're')}
                                />
                            </FlexBlock>
                            <Button
                                isDestructive
                                onClick={() => removeLink(index)}
                                variant="secondary"
                            >
                                {__('Remove', 're')}
                            </Button>
                        </Flex>
                    </li>
                ))}
            </ul>
            
            <Button
                variant="secondary"
                onClick={addLink}
                className="link-list-add-button"
            >
                {__('Add Link', 're')}
            </Button>
        </div>
    );
};

export default Edit; 