<?php

namespace BernskioldMedia\WP\PageSpecificCSS;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin
 *
 * @package BernskioldMedia\WP\PageSpecificCSS
 */
class Plugin {

	/**
	 * Version
	 *
	 * @var string
	 */
	protected const VERSION = '1.0.1';

	/**
	 * Plugin Textdomain
	 *
	 * @var string
	 */
	public const TEXTDOMAIN = 'bm-page-specific-css';

	/**
	 * Meta Key for the CSS code.
	 *
	 * @var string
	 */
	public const CSS_META_KEY = 'page_specific_styles';

	/**
	 * Plugin Class Instance Variable
	 *
	 * @var object
	 */
	protected static $_instance = null;

	/**
	 * Plugin Instantiator
	 *
	 * @return object
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.2
	 */
	private function __clone() {
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.2
	 */
	private function __wakeup() {
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		do_action( 'before_bm_page_specific_css_loading' );

		$this->loaders();
		$this->init_hooks();

		do_action( 'after_bm_page_specific_css_loaded' );

	}

	/**
	 * Hooks that are run on the time of init.
	 */
	private function init_hooks(): void {

		add_action( 'init', [ self::class, 'load_languages' ] );
		add_action( 'init', [ self::class, 'register_meta' ] );

		Assets::hooks();
		Generate_Files::hooks();

		do_action( 'bm_page_specific_css_init' );
	}

	/**
	 * Register the styles as post meta.
	 */
	public static function register_meta() {

		foreach ( [ 'post', 'page' ] as $object_type ) {
			register_meta( $object_type, self::CSS_META_KEY, [
				'show_in_rest' => true,
				'type'         => 'string',
				'single'       => true,
			] );
		}

	}

	/**
	 * Load plugin translations.
	 */
	public static function load_languages(): void {

		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, self::TEXTDOMAIN );

		unload_textdomain( self::TEXTDOMAIN );

		// Start checking in the main language dir.
		load_textdomain( self::TEXTDOMAIN, WP_LANG_DIR . '/' . self::TEXTDOMAIN . '/' . self::TEXTDOMAIN . '-' . $locale . '.mo' );

		// Otherwise, load from the plugin.
		load_plugin_textdomain( self::TEXTDOMAIN, false, self::get_path( 'languages/' ) );

	}

	/**
	 * We have various points of data that need to be called and loaded.
	 * What we load are stored in class variables. Add your class name there.
	 */
	public function loaders(): void {
	}

	/**
	 * Get the path to the plugin folder, or the specified
	 * file relative to the plugin folder home.
	 *
	 * @param  string  $file
	 *
	 * @return string
	 */
	public static function get_path( $file = '' ): string {
		return untrailingslashit( plugin_dir_path( BM_PAGE_SPECIFIC_CSS_FILE_PATH ) ) . '/' . $file;
	}

	/**
	 * Get the URL to the plugin folder, or the specified
	 * file relative to the plugin folder home.
	 *
	 * @param  string  $file
	 *
	 * @return string
	 */
	public static function get_url( $file = '' ): string {
		return untrailingslashit( plugin_dir_url( BM_PAGE_SPECIFIC_CSS_FILE_PATH ) ) . '/' . $file;
	}

	/**
	 * Get the URL to the assets folder, or the specified
	 * file relative to the assets folder home.
	 *
	 * @param  string  $file
	 *
	 * @return string
	 */
	public static function get_assets_url( $file = '' ): string {
		return self::get_url( 'assets/' . $file );
	}

	/**
	 * Get the Plugin's Version
	 *
	 * @return string
	 */
	public static function get_version(): string {
		return self::VERSION;
	}

}
