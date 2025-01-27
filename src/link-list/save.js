import { useBlockProps } from '@wordpress/block-editor';

const Save = ({ attributes }) => {
    const { title, links } = attributes;
    const blockProps = useBlockProps.save();

    return (
        <div {...blockProps}>
            <h3 className="link-list-title">
                {title}
            </h3>
            <ul className="link-list">
                {links.map((link, index) => (
                    <li key={index}>
                        <a href={link.url}>{link.text}</a>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default Save; 