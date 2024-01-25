<?php
/**
 * Caching images on your own server.
 *
 * Date: 25/1/24
 * @author Isaenko Aleksei <aisaenko2023@gmail.com>
 */


namespace My_first_block\Repositories;

use function My_first_block\pluginPath;
use function My_first_block\pluginUrl;

class ImageCacheRepository {
	/**
	 * Gets the name of the cache file depending on $src.
	 * The new name is generated without including the host name, because it can be changed, but the file structure cannot.
	 *
	 * @param string $src
	 *
	 * @return string
	 */
	private static function getFileName( string $src ): string {
		// get part included path after domain zone, example: http(s)://subdomain.domain.zone/path.extension
		// path.extension - is the fourth part
		$path = explode( '/', $src, 4 );
		// get only path
		$path = end( $path );
		// get parts separated by comma
		$extension = explode( '.', $path );
		// get an extension(last part)
		$extension = end( $extension );
		// generate new file name
		$hash = hash( 'md5', $path );

		// combine file name with extension and return
		return $hash . '.' . $extension;
	}

	/**
	 * Returns the URL of the cached file.
	 *
	 * @param string $src
	 *
	 * @return string
	 */
	public static function get( string $src ): string {
		// get file name
		$fileName = self::getFileName( $src );
		// set the new path
		$filePath = pluginPath() . 'assets/images/' . $fileName;
		// set the new url
		$fileUrl = pluginUrl() . 'assets/images/' . $fileName;
		// if file not exists yet OR file is empty
		if ( ! file_exists( $filePath ) || 0 === filesize( $filePath ) ) {
			// trying to download
			$response = wp_remote_get( $src );
			$contents = wp_remote_retrieve_body( $response );
			// if the download was successful
			if ( ! empty( $contents ) ) {
				// save data to the file
				$saveFile = fopen( $filePath, 'wb' );
				fwrite( $saveFile, $contents );
				fclose( $saveFile );
			} // if the download fails
			else {
				// return source url
				return $src;
			}
		}

		// returning local url
		return $fileUrl;
	}
}

// eof
