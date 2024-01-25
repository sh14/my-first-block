/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import {__} from '@wordpress/i18n'

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import {InspectorControls, useBlockProps} from '@wordpress/block-editor'
import {PanelBody, TextControl} from '@wordpress/components'
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss'

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({attributes, setAttributes}) {
    const {endpointUrl} = attributes
    const {serverSideRender: ServerSideRender} = wp

    /**
     * URL validator.
     *
     * @param urlString
     * @returns {boolean}
     */
    function isValidUrl(urlString) {
        let urlPattern = new RegExp('^(https?:\\/\\/)?' + // validate protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // validate domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // validate OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // validate port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // validate query string
            '(\\#[-a-z\\d_]*)?$', 'i') // validate fragment locator
        return !!urlPattern.test(urlString)
    }

    // if the endpointUrl is invalid
    if (endpointUrl && !isValidUrl(endpointUrl)) {
        // lock the save/update button
        wp.data.dispatch('core/editor').lockPostSaving('my_lock_key')
        // add an error notice
        wp.data.dispatch('core/notices')
            .createErrorNotice("Check the endpoint URL, it should be a valid url.", {
                id: 'LOCK_NOTICE',
                isDismissible: false
            })
    } else {
        // unlock save/update button
        wp.data.dispatch('core/editor').unlockPostSaving('my_lock_key')
        // remove error notice
        wp.data.dispatch('core/notices').removeNotice('LOCK_NOTICE')
    }

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Settings', 'my-first-block')}>
                    <TextControl
                        label={__('Endpoint URL', 'my-first-block')}
                        placeholder={__('Enter an endpoint URL', 'my-first-block')}
                        value={endpointUrl || ''}
                        required={'required'}
                        onChange={(value) => {
                            setAttributes({'endpointUrl': value})
                        }}
                    ></TextControl>
                </PanelBody>
            </InspectorControls>
            <p {...useBlockProps()}>
                {__('Offers', 'my-first-block')}: {endpointUrl}
                <ServerSideRender
                    block="create-block/my-first-block"
                    attributes={{
                        endpointUrl: endpointUrl,
                    }}
                />
            </p>
        </>
    )
}
