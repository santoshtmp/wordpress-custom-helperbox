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
	SelectControl,
	Spinner
} from '@wordpress/components';

/**
 * ServerSideRender
 * 
 */
import ServerSideRender from '@wordpress/server-side-render';

import { useEffect } from '@wordpress/element';

// Use global jQuery from WordPress
const $ = window.jQuery;

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
	const {
		heading,
		text,
		paragraphText,
		minHeight,
		bgImage,
		defaultBg,
		ctas = [],
	} = attributes;
	const imageUrl =
		bgImage?.sizes?.large?.url ||
		bgImage?.sizes?.medium?.url ||
		bgImage?.url;
	const blockProps = useBlockProps({ className: 'helperbox-cover-editor' });

	/**
	 * CTA helpers
	 */
	const updateCTA = (index, field, value) => {
		const newCtas = [...ctas];
		newCtas[index] = { ...newCtas[index], [field]: value };
		setAttributes({ ctas: newCtas });
	};

	const addCTA = () => {
		setAttributes({
			ctas: [
				...ctas,
				{
					text: '',
					url: '',
					variant: 'primary',
					newTab: false
				}
			]
		});
	};

	const removeCTA = (index) => {
		const newCtas = ctas.filter((_, i) => i !== index);
		setAttributes({ ctas: newCtas });
	};

	const moveCTA = (from, to) => {
		if (to < 0 || to >= ctas.length) {
			return;
		}
		const newCtas = [...ctas];
		const temp = newCtas[from];
		newCtas[from] = newCtas[to];
		newCtas[to] = temp;
		setAttributes({ ctas: newCtas });
	};



	/* =========================
	 * Auto-remove invalid CTAs
	 * ========================= */
	useEffect(() => {
		const cleaned = ctas.filter(cta => cta.text && cta.url);

		if (cleaned.length !== ctas.length) {
			setAttributes({ ctas: cleaned });
		}

	}, []);

	/**
	 * Return
	 */
	return (
		<>
			<InspectorControls>
				{/* Cover settings  */}
				<PanelBody title={__('Cover Settings', 'helperbox')} initialOpen>
					{/*  Media upload group */}
					<div
						className="helperbox-edit-media-field-group-control"
						style={{ marginBottom: '12px' }}
					>
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
						label={__('Min Height', 'helperbox')}
						value={minHeight}
						min={300}
						max={900}
						onChange={(value) => setAttributes({ minHeight: value })}
					/>

					<TextControl
						label={__('Heading', 'helperbox')}
						value={heading}
						onChange={(value) => setAttributes({ heading: value })}
					/>

					<TextareaControl
						label={__('Text', 'helperbox')}
						value={text}
						onChange={
							(value) => setAttributes({ text: value })
						}
					/>

					{/* CTA buttons  */}
					<div title={__('CTA Buttons', 'helperbox')}>
						{ctas.map((cta, index) => (
							<div
								key={index}
								style={{
									border: '1px solid #ddd',
									padding: '12px',
									marginBottom: '12px',
									borderRadius: '4px'
								}}
							>
								<TextControl
									label={__('Button Text', 'helperbox')}
									value={cta.text}
									help={!cta.text ? __('Button Text is required', 'helperbox') : ''}
									__experimentalShowError={!cta.text}
									onChange={(value) =>
										updateCTA(index, 'text', value)
									}
								/>

								<TextControl
									label={__('Button Link', 'helperbox')}
									value={cta.url}
									onChange={(url) =>
										updateCTA(index, 'url', url)
									}
								/>

								<SelectControl
									label={__('Button Variant', 'helperbox')}
									value={cta.variant}
									options={[
										{ label: __('Primary', 'helperbox'), value: 'primary' },
										{ label: __('Secondary', 'helperbox'), value: 'secondary' },
										{ label: __('Outline', 'helperbox'), value: 'outline' }
									]}
									onChange={(value) =>
										updateCTA(index, 'variant', value)
									}
								/>


								<ToggleControl
									label={__('Open in new tab', 'helperbox')}
									checked={cta.newTab}
									onChange={(value) =>
										updateCTA(index, 'newTab', value)
									}
								/>

								<div style={{ display: 'flex', gap: '8px', marginTop: '8px' }}>
									<Button
										variant="secondary"
										onClick={() => moveCTA(index, index - 1)}
										disabled={index === 0}
									>
										↑
									</Button>

									<Button
										variant="secondary"
										onClick={() => moveCTA(index, index + 1)}
										disabled={index === ctas.length - 1}
									>
										↓
									</Button>

									<Button
										variant="secondary"
										isDestructive
										onClick={() => removeCTA(index)}
									>
										{__('Remove', 'helperbox')}
									</Button>
								</div>
							</div>
						))}

						<Button variant="primary" onClick={addCTA}>
							{__('Add CTA Button', 'helperbox')}
						</Button>
					</div>

					{/*  */}

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
