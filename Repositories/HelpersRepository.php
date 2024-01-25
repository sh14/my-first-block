<?php
/**
 * Helpers functions.
 *
 * Date: 25/1/24
 * @author Isaenko Aleksei <aisaenko2023@gmail.com>
 */


namespace My_first_block\Repositories;

use JsonException;

use function My_first_block\pluginPath;

class HelpersRepository {
	/**
	 * Prepares CSS variables.
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	public static function setStyleVars( array $data ): string {
		$colors = [];
		// loop for properties
		foreach ( $data as $key => $value ) {
			// replace special chars with a underscore
			$key = preg_replace( '/[^a-zA-Z0-9-_]/', '_', $key );
			// combine new property name with value
			$colors[] = '--offers_' . $key . ':' . $value;
		}

		// put properties to style tag as a CSS variables
		return '<style>:root{' . implode( ';', $colors ) . '}</style>';
	}

	/**
	 * Render a template.
	 *
	 * @param string $templateName
	 * @param array $templateData
	 *
	 * @return string
	 */
	public static function renderTemplate( string $templateName, array $templateData = [] ): string {
		// get template path
		$path = pluginPath() . 'templates/' . $templateName;

		// check file existence
		if ( file_exists( $path ) ) {
			// this should be so that $templateData is in the function attributes and is not marked as an unused
			$data = $templateData;
			// put output to the buffer
			ob_start();
			include $path;

			// return rendered template data as a string
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Get offers data from endpoint. Fetched data has a limited shelf life.
	 *
	 * @param string $endpoint
	 * @param int $expiration
	 *
	 * @return array
	 * @throws JsonException
	 */
	public static function fetch( string $endpoint, int $expiration = HOUR_IN_SECONDS ): array {
		// generate transient name for given endpoint
		$transientName = 'offers_data_' . hash( 'md5', $endpoint );
		// if transient is empty
		if ( empty( $data = get_transient( $transientName ) ) ) {
			// request remote data
			$request = wp_remote_get( $endpoint );
			// retrieve response code
			$statusCode = wp_remote_retrieve_response_code( $request );
			// if server returns something instead of 200
			if ( 200 !== $statusCode ) {
				// delete transient
				delete_transient( $transientName );

				// return an error data
				return [
					'error' => sprintf(
						__( 'Check the endpoint URL, server answered with %s status code.', 'my-first-block' ),
						$statusCode
					),
				];
			}
			// if there is another problem
			if ( is_wp_error( $request ) ) {
				// delete transient
				delete_transient( $transientName );

				// return an error data
				return [ 'error' => __( 'Check the endpoint URL, there is nothing to parse.', 'my-first-block' ) ];
			}
			// get json from request
			$json = wp_remote_retrieve_body( $request );
			// decode json to an array
			$data = json_decode( $json, true, 512, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE );
			// save data to transient
			set_transient( $transientName, $data, $expiration );
		}

		// return data
		return $data;
	}
}

// eof
