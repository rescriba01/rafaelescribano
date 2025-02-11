import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, URLInput } from '@wordpress/block-editor';
import { Button, Flex, FlexBlock, Popover } from '@wordpress/components';
import { useState } from '@wordpress/element';

const Edit = ({ attributes, setAttributes }) => {
    const { title, links } = attributes;
    const blockProps = useBlockProps();
    const [localInputs, setLocalInputs] = useState(links.map(link => link.text));
    const [isEditingURL, setIsEditingURL] = useState({});

    const handleTitleChange = (newTitle) => {
        setAttributes({ title: newTitle });
    };

    const handleTextChange = (index, value) => {
        const newInputs = [...localInputs];
        newInputs[index] = value;
        setLocalInputs(newInputs);
    };

    const handleTextBlur = (index) => {
        const updatedLinks = [...links];
        updatedLinks[index] = {
            ...updatedLinks[index],
            text: localInputs[index]
        };
        setAttributes({ links: updatedLinks });
    };

    const handleURLChange = (index, url, post = null) => {
        const updatedLinks = [...links];
        // If a post is selected, use its title as the link text if no text was entered
        if (post && !localInputs[index]) {
            const newInputs = [...localInputs];
            newInputs[index] = post.title;
            setLocalInputs(newInputs);
            updatedLinks[index] = {
                ...updatedLinks[index],
                text: post.title
            };
        }
        updatedLinks[index] = {
            ...updatedLinks[index],
            url
        };
        setAttributes({ links: updatedLinks });
    };

    const addLink = () => {
        setAttributes({
            links: [...links, { text: '', url: '' }]
        });
        setLocalInputs([...localInputs, '']);
    };

    const removeLink = (index) => {
        setAttributes({
            links: links.filter((_, i) => i !== index)
        });
        setLocalInputs(localInputs.filter((_, i) => i !== index));
        // Clean up any editing state
        const newIsEditingURL = { ...isEditingURL };
        delete newIsEditingURL[index];
        setIsEditingURL(newIsEditingURL);
    };

    return (
        <div {...blockProps}>
            <RichText
                tagName="h3"
                value={title}
                onChange={handleTitleChange}
                placeholder={__('Enter list title...', 're')}
                className="link-list-title"
            />
            
            <ul className="link-list">
                {links.map((link, index) => (
                    <li key={`${index}-${link.url}`} className="link-list-item">
                        <Flex>
                            <FlexBlock>
                                <input
                                    type="text"
                                    value={localInputs[index]}
                                    onChange={(e) => handleTextChange(index, e.target.value)}
                                    onBlur={() => handleTextBlur(index)}
                                    placeholder={__('Link text...', 're')}
                                    className="link-text-input"
                                />
                                <div className="url-input-wrapper">
                                    <Button
                                        icon="admin-links"
                                        className="url-input-button"
                                        onClick={() => setIsEditingURL({ ...isEditingURL, [index]: true })}
                                    >
                                        {link.url ? link.url : __('Insert Link', 're')}
                                    </Button>
                                    {isEditingURL[index] && (
                                        <Popover
                                            position="bottom center"
                                            onClose={() => setIsEditingURL({ ...isEditingURL, [index]: false })}
                                        >
                                            <div className="url-input-popover">
                                                <URLInput
                                                    value={link.url}
                                                    onChange={(url, post) => handleURLChange(index, url, post)}
                                                    suggestions={true}
                                                    hasBorder={true}
                                                />
                                            </div>
                                        </Popover>
                                    )}
                                </div>
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