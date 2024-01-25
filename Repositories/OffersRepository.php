<?php
/**
 * Date: 24/1/24
 * @author Isaenko Aleksei <aisaenko2023@gmail.com>
 */


namespace My_first_block\Repositories;

use JsonException;

class OffersRepository {

	// choose pointed images type
	private static string $imageType = 'dark';
	// count of images in one slide
	private static int $itemsPerSlide = 4;

	private static function prepareLogo( array $offer ): string {
		if ( ! empty( $offer['logo'][ self::$imageType ] ) ) {
			return ImageCacheRepository::get( $offer['logo'][ self::$imageType ] );
		}

		return '';
	}

	private static function preparePreviewImage( array $offer, array $display ): string {
		if ( ! empty( $display['show_preview_button'] ) ) {
			// get largest preview image
			$offer['preview_image'] = array_values( array_filter( $offer['preview_image'] ) );
			if ( ! empty( $offer['preview_image'][0] ) ) {
				return ImageCacheRepository::get( $offer['preview_image'][0] );
			}

			return '';
		}

		return '';
	}

	private static function prepareHeadlines( array $offer ): array {
		if ( empty( $offer['headlines'] ) ) {
			return [];
		}

		return array_values(
			array_filter(
				array_map( static function ( $item, $key ) {
					return ! empty( $item['title'] ) ? [
						'name'  => $key,
						'value' => $item['title'],
					] : [];
				}, $offer['headlines'], array_keys( $offer['headlines'] ) )
			)
		);
	}

	private static function prepareBulletPoints( array $offer ): array {
		if ( empty( $offer['bullet_points'] ) ) {
			return [];
		}

		return [
			'items' => array_values(
				array_filter(
					array_map( static function ( $item ) {
						return ! empty( $item['title'] ) ? $item['title'] : '';
					}, $offer['bullet_points'] )
				)
			),
		];
	}

	private static function prepareStars( array $offer, array $display ): array {
		// if we don't have offer rating value AND $display data exists
		if ( ! isset( $offer['stars'] ) && ! empty( $display ) ) {
			// if we are able to set the default offer rating
			$offer['stars'] = ! empty( $display['auto_stars'] ) && ! empty( $display['auto_stars_minimum'] )
				? $display['auto_stars_minimum']
				: 0;
		}

		$starsData = [];
		// collect 5 stars
		for ( $i = 0; $i < 5; $i ++ ) {
			if ( $offer['stars'] >= 1 ) {
				$starsData[] = 'full';
			} elseif ( $offer['stars'] > 0 ) {
				$starsData[] = 'half';
			} else {
				$starsData[] = 'empty';
			}
			$offer['stars'] --;
		}

		return $starsData;
	}

	private static function prepareReview( array $offer ): string {
		return 'yes' === strtolower( $offer['reviewed'] )
		       && 'yes' === strtolower( $offer['reviewed_admin_approved'] )
		       && ! empty( $offer['links']['review'] )
		       && ! empty( $offer['review'] )
			? $offer['review']
			: '';
	}

	private static function prepareDeposits( array $offer ): array {
		if ( ! empty( $offer['deposits'] ) ) {
			$deposits = array_map( static function ( $item ) {
				return [
					'name' => $item['name'],
					'src'  => ImageCacheRepository::get( $item[ self::$imageType . '_url' ] ),
				];
			}, $offer['deposits'] );
			$slides   = [ 'items' => [], ];
			$bullets  = [];
			$count    = 0;
			$slide    = 0;
			// count of deposit items
			$imagesCount = count( $deposits );
			// loop for items
			foreach ( $deposits as $index => $item ) {
				// if count of items in the list less then we need
				if ( $count < self::$itemsPerSlide ) {
					// if slide list is empty
					if ( empty( $slides['items'][ $slide ] ) ) {
						// create a list
						$slides['items'][ $slide ] = [
							'items' => [],
						];
					}
					// add an item to the list
					$slides['items'][ $slide ]['items'][] = $item;

					// increment count of items in the list
					$count ++;
				} else {
					// start new list
					$count = 0;
					// add bullet
					$bullets[] = [
						'index' => $slide,
						'mixin' => 0 === $slide ? ' carousel__bullet_active' : '',
					];
					// increment slide index
					$slide ++;
				}
				// if the list of images is over
				if ( $index > $imagesCount - 2 ) {
					// add last bullet
					$bullets[] = [
						'index' => $slide,
						'mixin' => '',
					];
				}
			}

			return [ 'slides' => $slides, 'bullets' => $bullets, ];
		}

		return [];
	}

	/**
	 * Preparing data for use in the template.
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private static function prepareOffers( array $data ): array {
		return array_map( static function ( $offer ) use ( $data ) {
			return [
				'brand'         => $offer['brand'] ?? '',
				'alt'           => $offer['logo']['alt'] ?? '',
				'review'        => self::prepareReview( $offer ),
				'review_link'   => ! empty( $offer['links']['review'] ) ? esc_attr( $offer['links']['review'] ) : '',
				'terms'         => $offer['terms'] ?? '',
				'terms_link'    => ! empty( $offer['links']['terms'] ) ? esc_attr( $offer['links']['terms'] ) : '',
				'preview_image' => self::preparePreviewImage( $offer, $data['display'] ),
				'preview_text'  => __( 'Preview', 'my-first-block' ),
				'ribbon'        => $offer['ribbon'] ?? '',
				'logo'          => self::prepareLogo( $offer ),
				'headlines'     => self::prepareHeadlines( $offer ),
				'bullet_points' => self::prepareBulletPoints( $offer ),
				'fine_print'    => $offer['fine_print'] ?? '',
				'disclaimer'    => $offer['disclaimer'] ?? '',
				'cta'           => $offer['cta']['one'] ?? '',
				'show_footer'   => $data['display']['show_footer'] ?? '',
				'cta_link'      => ! empty( $offer['links']['offer'] ) ? esc_attr( $offer['links']['offer'] ) : '',
				'stars'         => self::prepareStars( $offer, $data['display'] ),
				'deposits'      => self::prepareDeposits( $offer ),

			];
		}, $data['offers'] );
	}


	/**
	 *
	 * @throws JsonException
	 */
	public static function render( $atts ): string {
		// fetch data from given endpoint
		$record = HelpersRepository::fetch( $atts['endpointUrl'] );
		if ( ! empty( $record['error'] ) ) {
			return sprintf(
				'<div class="error">%s</div>',
				$record['error']
			);
		}
		if ( empty( $record['record'] ) ) {
			return sprintf(
				'<div class="error">%s</div>',
				__( 'Check the server response, there is no correct data.', 'my-first-block' )
			);
		}
		$record = $record['record'];
		// prepare set of data for every offer
		$offers   = self::prepareOffers( $record );
		$template = '';
		foreach ( $offers as $offer ) {
			// get rendered template
			$template .= HelpersRepository::renderTemplate( 'offer.php', $offer );
		}
		if ( empty( $record['display']['colors'] ) ) {
			$record['display']['colors'] = [];
		}
		$record['display']['colors']['items_per_slide'] = self::$itemsPerSlide;
		// get CSS variables
		$styles = HelpersRepository::setStyleVars( $record['display']['colors'] );

		// return rendered result
		return $styles . $template.HelpersRepository::renderTemplate( 'offers-modal.php' );
	}
}

// eof
