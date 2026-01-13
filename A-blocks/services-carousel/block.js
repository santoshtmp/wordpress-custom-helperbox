(function (blocks, editor, components, element) {
    const { registerBlockType } = blocks;
    const { useBlockProps, RichText, MediaUpload, URLInputButton } = editor;
    const { Button, TextControl } = components;
    const { createElement: el } = element;

    registerBlockType('sophia-blocks/services-carousel', {
        title: 'Services Carousel',
        icon: el('svg', {
            xmlns: 'http://www.w3.org/2000/svg',
            width: 50,
            height: 43,
            viewBox: '0 0 50 43',
            fill: 'none'
        },
            el('path', {
                fill: '#231F20',
                d: 'M47.995.89H2.005c-.95 0-1.72.77-1.72 1.72v38.38c0 .95.77 1.72 1.72 1.72h45.99c.95 0 1.72-.77 1.72-1.72V2.61c0-.95-.77-1.72-1.72-1.72Zm-5.15 3.48c.96 0 1.73.78 1.73 1.73s-.78 1.73-1.73 1.73-1.73-.78-1.73-1.73.78-1.73 1.73-1.73Zm-6.18 0c.96 0 1.73.78 1.73 1.73s-.78 1.73-1.73 1.73-1.73-.78-1.73-1.73.78-1.73 1.73-1.73Zm-6.17 0c.96 0 1.73.78 1.73 1.73s-.78 1.73-1.73 1.73-1.73-.78-1.73-1.73.78-1.73 1.73-1.73Zm15.78 34.9H3.725V10.96h42.55v28.31Z'
            }),
            el('path', {
                fill: '#231F20',
                d: 'M9.735 31.92c.1.05.21.08.32.08.14 0 .28-.04.39-.12l8.78-6.18c.18-.13.29-.34.29-.56v-2.6c0-.22-.11-.44-.29-.56l-8.78-6.13a.696.696 0 0 0-.71-.05.68.68 0 0 0-.37.61v3.05c0 .21.1.41.26.54l4.82 3.84-4.83 3.88c-.16.13-.26.33-.26.53v3.07c0 .26.14.49.37.61l.01-.01ZM19.725 30.9h11.37c.57 0 1.03.46 1.03 1.03v1.11c0 .57-.46 1.03-1.03 1.03h-11.37c-.57 0-1.03-.46-1.03-1.03v-1.11c0-.57.46-1.03 1.03-1.03Z'
            })
        ),
        category: 'sophia-blocks',
        attributes: {
            sectionHeading: { type: 'string', default: '' },
            sectionDescription: { type: 'string', default: '' },
            bulletPoints: {
                type: 'array',
                default: [],
                items: { type: 'string' }
            },
            services: {
                type: 'array',
                default: [],
                items: {
                    type: 'object',
                    properties: {
                        image: { type: 'string', default: '' },
                        title: { type: 'string', default: '' },
                        url: { type: 'string', default: '' }
                    }
                }
            }
        },

        edit({ attributes, setAttributes }) {
            const {
                sectionHeading,
                sectionDescription,
                bulletPoints,
                services
            } = attributes;

            // Bullet Point Handlers
            const addBulletPoint = () => setAttributes({ bulletPoints: [...bulletPoints, ''] });
            const removeBulletPoint = index => {
                const updated = [...bulletPoints];
                updated.splice(index, 1);
                setAttributes({ bulletPoints: updated });
            };
            const updateBulletPoint = (index, value) => {
                const updated = [...bulletPoints];
                updated[index] = value;
                setAttributes({ bulletPoints: updated });
            };

            // Services Handlers
            const addService = () => {
                setAttributes({ services: [...services, { image: '', title: '', url: '' }] });
            };
            const removeService = index => {
                const updated = [...services];
                updated.splice(index, 1);
                setAttributes({ services: updated });
            };
            const updateService = (index, field, value) => {
                const updated = [...services];
                updated[index][field] = value;
                setAttributes({ services: updated });
            };

            return el('div', useBlockProps({
                className: 'services-carousel-editor',
                style: {
                    backgroundColor: '#F8F8F8',
                    padding: '30px',
                    borderRadius: '10px'
                }
            }), [
                
                el('div', {
                    style: {
						marginBottom: '20px',
						display: 'inline-block', 
						width: 'fit-content'      
					}
				},
				    el('p', {
				        style: {
				            margin: '0',
				            fontSize: '12px',
				            fontWeight: '600',
				            letterSpacing: '0.5px',
				            textTransform: 'uppercase',
				            display: 'inline-flex',
				            padding: '4px 8px',
				            justifyContent: 'center',
				            alignItems: 'center',
				            gap: '10px',
				            borderRadius: '2px',
				            background: '#E7E7E7'
				        }
				    }, 'SERVICE CAROUSEL BLOCK')
				),
                

                // Section Heading
                el('div', { style: { marginBottom: '20px' } }, [
                    el('label', {
                        style: {
                            display: 'block',
                            marginBottom: '4px',
                            fontWeight: '500',
                            fontSize: '14px',
                            color: '#070707'
                        }
                    }, 'TITLE'),
                    el(RichText, {
                        tagName: 'p',
                        value: sectionHeading,
                        onChange: val => setAttributes({ sectionHeading: val }),
                        placeholder: 'Enter Section Heading...',
                        style: {
                            margin: 0,
                            fontSize: '16px',
                            border: '1px solid #AEAEAE',
                            borderRadius: '4px',
                            padding: '8px 12px',
                            backgroundColor: '#FFFFFF',
                            color: '#000',
                            marginBottom: '12px'
                        }
                    })
                ]),

                // Section Description
                el('div', { style: { marginBottom: '20px' } }, [
                    el('label', {
                        style: {
                            display: 'block',
                            marginBottom: '4px',
                            fontWeight: '500',
                            fontSize: '14px',
                            color: '#070707'
                        }
                    }, 'DESCRIPTION'),
                    el(RichText, {
                        tagName: 'p',
                        value: sectionDescription,
                        onChange: val => setAttributes({ sectionDescription: val }),
                        placeholder: 'Enter Section Description...',
                        style: {
                            margin: 0,
                            fontSize: '16px',
                            border: '1px solid #AEAEAE',
                            borderRadius: '4px',
                            padding: '8px 12px',
                            backgroundColor: '#FFFFFF',
                            color: '#000',
                            marginBottom: '12px'
                        }
                    })
                ]),

                // Bullet Points Repeater
                el('div', { style: { marginBottom: '30px' } }, [
                    
                    el('label', {
                        style: { display: 'block',
                            marginBottom: '4px',
                            fontWeight: '500',
                            fontSize: '14px',
                            color: '#070707' }
                    }, 'Bullet Points'),
                    bulletPoints.length === 0 &&
                        el('p', { style: { margin: 0,
                            fontSize: '16px',
                            border: '1px solid #AEAEAE',
                            borderRadius: '4px',
                            padding: '8px 12px',
                            backgroundColor: '#FFFFFF',
                            color: '#000',
                            marginBottom: '12px' } }, 'No bullet points yet.'),
                    bulletPoints.map((point, index) => el('div', {
                        key: index,
                        style: {
                            display: 'flex',
                            alignItems: 'center',
                            marginBottom: '8px'
                        }
                    }, [
                        el(TextControl, {
                            value: point,
                            onChange: val => updateBulletPoint(index, val),
                            placeholder: 'Enter bullet point text',
                            style: { margin: 0,
                            fontSize: '16px',
                            border: '1px solid #AEAEAE',
                            borderRadius: '4px',
                            padding: '8px 12px',
                            backgroundColor: '#FFFFFF',
                            color: '#000',
                            marginBottom: '12px'}
                        }),
                        el(Button, {
                            isDestructive: true,
                            onClick: () => removeBulletPoint(index),
                            style: { marginLeft: '8px' },
                            icon: 'no-alt',
                            label: 'Remove bullet point'
                        })
                    ])),
                    el(Button, {
                        isPrimary: true,
                        onClick: addBulletPoint,
                        icon: 'plus',
                        style: {
                            padding: '10px 20px',
                            backgroundColor: '#B75620'
                        }
                    }, 'Add Bullet Point')
                ]),
                
                // Divider and Label
				el('div', {}, [
					el('hr', {
						style: {
							borderTop: '1px solid #AEAEAE',
							margin: '20px 0'
						}
					}),
					el('p', {
						style: {
							fontSize: '20px',
							fontWeight: '500',
							marginBottom: '15px',
							color: '#000000'
						}
					}, 'Services')
				]),

                // Services Repeater
                el('div', { style: { marginBottom: '20px' } }, [
                    services.length === 0 &&
                        el('p', { style: { fontStyle: 'italic', color: '#666' } }, 'No services added.'),
                    services.map((service, index) => el('div', {
                        key: index,
                        style: {
                            border: '1px solid #ddd',
                            padding: '15px',
                            marginBottom: '15px',
                            borderRadius: '6px',
                            backgroundColor: '#F8F8F8'
                        }
                    }, [
                        
                        // Item Counter
					el('div', {
						style: {
							fontWeight: '600',
							fontSize: '14px',
							marginBottom: '10px',
							color: '#333'
						}
					}, `Item ${index + 1}`),

                        // Image Upload and Page Link in single row
                        el('div', {
                            style: {
                                display: 'flex',
                                alignItems: 'center',
                                gap: '15px',
                                marginBottom: '15px'
                            }
                        }, [
                            // Image Upload
                            el('div', {
                                style: {
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '10px'
                                }
                            }, [
                                el(MediaUpload, {
                                    onSelect: media => updateService(index, 'image', media.url),
                                    allowedTypes: ['image'],
                                    render: ({ open }) =>
                                        el(Button, {
                                            onClick: open,
                                            isSecondary: true,
                                            icon: 'format-gallery',
                                            style: {
                                                color: '#000',
                                                padding: '10px',
                                                borderRadius: '2px',
                                                border: '1px dashed #AEAEAE',
                                                background: '#FFF',
                                                boxShadow: '0 1px 1px rgba(0, 0, 0, 0.03)',
                                            }
                                        }, service.image ? 'Change Image' : 'Upload Image')
                                }),
                                service.image && el('img', {
                                    src: service.image,
                                    alt: 'Service Image',
                                    style: { 
                                        maxWidth: '50px',
                                        height: '50px',
                                        objectFit: 'cover',
                                        borderRadius: '4px'
                                    }
                                })
                            ]),
                            
                            // Page Link
                            el('div', {
                                style: {
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '6px',
                                },
                            }, [
                                el(URLInputButton, {
                                    url: service.url,
                                    onChange: (val) => updateService(index, 'url', val),
                                    render: ({ url, open }) =>
                                        el('button', {
                                            onClick: open,
                                            style: {
                                                display: 'flex',
                                                alignItems: 'center',
                                                background: 'none',
                                                border: 'none',
                                                cursor: 'pointer',
                                                padding: 0,
                                                fontSize: '15px',
                                                color: url ? '#000' : '#888',
                                            },
                                        }, [
                                            el('span', {
                                                className: 'dashicons dashicons-admin-links',
                                                style: {
                                                    fontSize: '18px',
                                                    marginRight: '6px',
                                                    color: '#0073aa',
                                                },
                                            }),
                                            el('span', null,
                                                url
                                                    ? (() => {
                                                        try {
                                                            const parts = new URL(url);
                                                            const slug = parts.pathname.split('/').filter(Boolean).pop();
                                                            return slug
                                                                ? slug.replace(/-/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())
                                                                : 'Home';
                                                        } catch {
                                                            return url;
                                                        }
                                                    })()
                                                    : 'Select page'
                                            ),
                                        ]),
                                }),
                                el('span', {
                                    style: {
                                        fontSize: '14px',
                                        color: '#070707',
                                        marginLeft: '10px',
                                    },
                                }, 'page link(Optional)'),
                            ])
                        ]),
                        
                            // Section Description
                el('div', { style: { marginBottom: '20px' } }, [
                    el('label', {
                        style: {
                            display: 'block',
                            marginBottom: '4px',
                            fontWeight: '500',
                            fontSize: '14px',
                            color: '#070707'
                        }
                    }, 'Service Title'),
                    el(RichText, {
                        tagName: 'p',
                        value: service.title,
                        onChange: val => updateService(index, 'title', val),
                        placeholder: 'Enter service title.',
                        style: {
                            margin: 0,
                            fontSize: '16px',
                            border: '1px solid #AEAEAE',
                            borderRadius: '4px',
                            padding: '8px 12px',
                            backgroundColor: '#FFFFFF',
                            color: '#000',
                            marginBottom: '12px'
                        }
                    })
                ]),
                        
                        
                        el('div', {
                            style: {
                                textAlign: 'right',
                                borderTop: '1px solid #eee',
                                paddingTop: '15px',
                                color: '#FF0E12'
                            }
                        }, [
                            el(Button, {
                                isDestructive: true,
                                onClick: () => removeService(index),
                                icon: 'minus'
                            }, 'Remove Service')
                        ])
                    ])),
                    el(Button, {
                        isPrimary: true,
                        onClick: addService,
                        icon: 'plus',
                        style: {
                            padding: '10px 20px',
                            backgroundColor: '#B75620'
                        }
                    }, 'Add Service')
                ])
            ]);
        },

        save() {
            return null; // Rendered via PHP
        }
    });
})(
    window.wp.blocks,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.element
);