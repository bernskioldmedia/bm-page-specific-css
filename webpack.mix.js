/**
 * Laravel Mix Configuration
 *
 * We use Laravel Mix as an easy-to-understand interface for webpack,
 * which can otherwise be quite complicated. Mix is super simple and
 * works very well.
 *
 * @link https://laravel.com/docs/5.6/mix
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 **/

const mix = require( "laravel-mix" );

/**************************************************************
 * Build Process
 *
 * This part handles all the compilation and concatenation of
 * all the theme's resources.
 *************************************************************/

/*
 * Asset Directory Path
 */
const assetPaths = {
	scripts: "assets/scripts",
};

/*
 * Builds sources maps for assets.
 * if we are not in production.
 *
 * @link https://laravel.com/docs/5.6/mix#css-source-maps
 */
if ( ! mix.inProduction() ) {
	mix.sourceMaps();
}

/**
 * Internal JavaScript
 */
mix.react(
	   `${assetPaths.scripts}/src/editor.js`,
	   `${assetPaths.scripts}/dist/editor.js`
   );

/*
 * Custom Webpack Config
 *
 * @link https://laravel.com/docs/6.x/mix#custom-webpack-configuration
 * @link https://webpack.js.org/configuration/
 */
mix.webpackConfig( {
	mode: mix.inProduction() ? "production":"development",
	devtool: mix.inProduction() ? "":"cheap-source-map",
	stats: "minimal",
	performance: {
		hints: false
	},
	externals: {
		jquery: "jQuery",
		react: "React",
	},
	watchOptions: {
		ignored: /node_modules/
	}
} );
