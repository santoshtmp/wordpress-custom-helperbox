/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, RichText } from '@wordpress/block-editor';
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

const thisBlockName = 'helperbox/button';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
// export default function Edit() {
// 	return (
// 		<p { ...useBlockProps() }>
// 			{ __( 'Todo List – hello from the editor!', 'todo-list' ) }
// 		</p>
// 	);
// }

function Edit({ attributes, setAttributes }) {
    const { buttonText } = attributes;
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <RichText
                value={buttonText}
                onChange={(value) => setAttributes({ buttonText: value })}
                placeholder={__('Enter button text…', 'helperbox')}
                allowedFormats={[]}
            />
        </div>
    );
}

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */

registerBlockType(thisBlockName, {
    /**
     * @see ./edit.js
     */
    edit: Edit,
});
