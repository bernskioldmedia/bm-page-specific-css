<?php

namespace BernskioldMedia\WP\PageSpecificCSS;

use MatthiasMullie\Minify\CSS;

defined( 'ABSPATH' ) || exit;

/**
 * Class Generate_Files
 *
 * @package BernskioldMedia\WP\PageSpecificCSS
 */
class Generate_Files {

	/**
	 * WordPress Hooks
	 */
	public static function hooks(): void {
		add_action( 'save_post', [ self::class, 'on_post_save' ] );
		add_action( 'delete_post', [ self::class, 'on_post_delete' ] );
	}

	/**
	 * Create/delete the file when post is saved.
	 *
	 * @param  int  $post_id
	 */
	public static function on_post_save( $post_id ) {

		// If this is just a revision, don't run.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$styles = get_post_meta( $post_id, Plugin::CSS_META_KEY, true );

		/**
		 * If empty or not exists, we try and remove any existing files.
		 */
		if ( ! $styles || empty( $styles ) ) {

			update_post_meta( $post_id, 'has_page_specific_css', 'false' );
			self::remove_file( $post_id );

			return;
		}

		$file_path = self::get_file_path( $post_id );

		self::maybe_create_directory();
		self::create_file( $post_id, $styles );

		$minifier = new CSS( $file_path );
		$minifier->minify( $file_path );

		update_post_meta( $post_id, 'has_page_specific_css', 'true' );

	}

	/**
	 * Handle post deletion.
	 *
	 * @param  int  $post_id
	 */
	public static function on_post_delete( $post_id ) {
		self::remove_file( $post_id );
	}

	/**
	 * Maybe remove the file.
	 *
	 * @param  int  $post_id
	 */
	protected static function remove_file( $post_id ) {
		$file_path = self::get_file_path( $post_id );

		if ( file_exists( $file_path ) ) {
			unlink( $file_path );
		}
	}

	/**
	 * Get the path to the storage directory.
	 *
	 * @return string
	 */
	protected static function get_storage_directory(): string {
		return WP_CONTENT_DIR . '/page-specific/css';
	}

	/**
	 * Get the URI to the storage directory.
	 *
	 * @return string
	 */
	protected static function get_storage_directory_uri(): string {
		return WP_CONTENT_URL . '/page-specific/css';
	}

	/**
	 * Maybe create the storage directory if it doesn't exist.
	 */
	protected static function maybe_create_directory() {
		if ( ! file_exists( self::get_storage_directory() ) ) {
			mkdir( self::get_storage_directory(), 0755, true );
		}
	}

	/**
	 * Get the version for the file URL.
	 *
	 * @param  int  $post_id
	 *
	 * @return string
	 */
	public static function get_file_version( $post_id ): string {
		return get_the_modified_time( 'U', $post_id );
	}

	/**
	 * Get the path to the file.
	 *
	 * @param  int  $post_id
	 *
	 * @return string
	 */
	public static function get_file_path( $post_id ): string {
		return self::get_storage_directory() . '/' . self::get_file_name( $post_id );
	}

	/**
	 * Get the URL to the file.
	 *
	 * @param  int  $post_id
	 *
	 * @return string
	 */
	public static function get_file_url( $post_id ): string {
		return self::get_storage_directory_uri() . '/' . self::get_file_name( $post_id );
	}

	/**
	 * Get/generate the file name.
	 *
	 * @param  int  $post_id
	 *
	 * @return string
	 */
	public static function get_file_name( $post_id ): string {
		$post_type = get_post_type( $post_id );

		return "$post_type-$post_id.css";
	}

	/**
	 * Create the file.
	 *
	 * @param  int     $post_id
	 * @param  string  $contents
	 */
	protected static function create_file( $post_id, $contents ) {
		file_put_contents( self::get_file_path( $post_id ), $contents );
	}

}
