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
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	URLInput,
	RichText,
} from '@wordpress/block-editor';

/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Component
 * 
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/
 */
import {
	PanelBody,
	TextControl,
	TextareaControl,
	CodeEditor,
	RangeControl,
	Button,
	ToggleControl,
	Spinner
} from '@wordpress/components';

/**
 * ServerSideRender
 * 
 */
import ServerSideRender from '@wordpress/server-side-render';

/**
 * const variables
 */
const thisBlockName = 'helperbox/cover';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 * @see https://wordpress.github.io/gutenberg/?path=/docs/docs-introduction--page 
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
	const { heading, text, buttonText, buttonUrl, minHeight, bgImage, defaultBg, paragraphText } = attributes;
	const imageUrl =
		bgImage?.sizes?.large?.url ||
		bgImage?.sizes?.medium?.url ||
		bgImage?.url;
	const blockProps = useBlockProps({ className: 'helperbox-cover-editor' });

	return (
		<>
			<InspectorControls>
				<PanelBody title="Cover Settings" initialOpen>
					{/*  MEdia upload group */}
					<div className="helperbox-edit-media-field-group-control" style={{ marginBottom: '12px' }}>
						{/* Media preview */}
						{imageUrl && (
							<div className="helperbox-edit-media-preview">
								<img
									src={imageUrl}
									alt=""
									style={{
										width: '100px',
										height: 'auto',
										objectFit: 'cover',
										borderRadius: '4px',
									}}
								/>
							</div>
						)}

						{/* Media upload button */}
						<MediaUploadCheck>
							<MediaUpload
								onSelect={(media) =>
									setAttributes({
										bgImage: media,
									})
								}
								allowedTypes={['image']}
								value={bgImage?.id}
								render={({ open }) => (
									<Button
										onClick={open}
										variant="secondary"
									>
										{bgImage ? 'Replace Background Image' : 'Upload Background Image'}
									</Button>
								)}
							/>
						</MediaUploadCheck>

						{/* Remove image */}
						{bgImage && (
							<Button
								onClick={() => setAttributes({ bgImage: null })}
								variant="secondary"
								isDestructive
							>
								Remove image
							</Button>
						)}

						{/* Default background toggle: only show when bgImage is null */}
						{!bgImage && (
							<ToggleControl
								label={__('Use default background', 'helperbox')}
								checked={defaultBg}
								onChange={(value) => setAttributes({ defaultBg: value })}
							/>
						)}

					</div>

					<RangeControl
						label="Min Height"
						value={minHeight}
						min={300}
						max={900}
						onChange={(value) => setAttributes({ minHeight: value })}
					/>

					<TextControl
						label="Heading"
						value={heading}
						onChange={(value) => setAttributes({ heading: value })}
					/>

					<TextareaControl
						label="Text"
						value={text}
						onChange={(value) => setAttributes({ text: value })}
					/>

					<TextControl
						label="Button Text"
						value={buttonText}
						onChange={(value) => setAttributes({ buttonText: value })}
					/>

					<URLInput
						label="Button Link"
						value={buttonUrl}
						onChange={(url) => setAttributes({ buttonUrl: url })}
					/>

				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{/* Server-side rendered preview */}
				<ServerSideRender
					block={thisBlockName}
					attributes={attributes}
					LoadingResponsePlaceholder={
						() => (
							<div style={{ padding: '20px', textAlign: 'center' }}>
								<Spinner />
							</div>
						)
					}
				/>
			</div>
		</>
	);
}

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(
	thisBlockName,
	{
		/**
		 * @see ./edit.js
		 */
		edit: Edit,
	}
);
