/**
 * Use this file for JavaScript code that you want to run in the front-end
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any
 * JavaScript running in the front-end, then you should delete this file and remove
 * the `viewScript` property from `block.json`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */

/* eslint-disable no-console */
/* eslint-enable no-console */
// loop for bullets
document.querySelectorAll('.js-carousel-bullet').forEach(element => {
	// add Event listener to every bullet
	element.addEventListener('click', (event) => {
		// block default click action
		event.preventDefault()
		// set clicked bullet
		const targetElement = event.target
		// get bullet index
		const index = targetElement.getAttribute('data-slide')
		// get parent carousel block
		const parentElement = targetElement.closest('.js-carousel')
		// loop for bullets inside carousel block
		parentElement.querySelectorAll('.js-carousel-bullet').forEach(elementBullet => {
			// if current bullet index equal to index of clicked bullet
			if (elementBullet.getAttribute('data-slide') === index) {
				// add active class to current bullet
				elementBullet.classList.add('carousel__bullet_active')
			} else {
				// remove active class from current bullet
				elementBullet.classList.remove('carousel__bullet_active')
			}
		})
		// try to get slides container inside parent carousel block
		const containerElement = parentElement.querySelector('.js-carousel-container')
		// if container exists
		if (containerElement) {
			// move it inside container to show inner elements
			containerElement.style.transform = 'translateX(' + (-100 * index) + '%)'
		}
	})
})

document.querySelectorAll('.js-offer-preview').forEach(element => {
	// add Event listener to every bullet
	element.addEventListener('click', (event) => {
		// block default click action
		event.preventDefault()
		// set clicked bullet
		const targetElement = event.target
		const src = targetElement.getAttribute('data-src')
		console.log(src)
		const imageElement = document.querySelector('.js-offers-modal-image')
		if (imageElement) {
			imageElement.src = src
			document.body.classList.add('modal-lock')
			const modalElement = imageElement.closest('.js-offers-modal')
			if (modalElement) {
				modalElement.classList.add('offers-modal_active')
			}
		}
	})
})

document.querySelectorAll('.js-offers-modal').forEach(element => {
	// add Event listener to every bullet
	element.addEventListener('click', (event) => {
		// block default click action
		event.preventDefault()
		event.target.classList.remove('offers-modal_active')
		document.body.classList.remove('modal-lock')
	})
})
