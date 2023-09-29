<?php
/**
 * CSV Format for GlotPress
 *
 * @package           GP_Format_CSV
 * @link              https://github.com/pedro-mendonca/GP-Format-CSV
 * @author            Pedro Mendonça
 * @copyright         2022 Pedro Mendonça
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       CSV Format for GlotPress
 * Plugin URI:        https://wordpress.org/plugins/gp-format-csv/
 * GitHub Plugin URI: https://github.com/pedro-mendonca/GP-Format-CSV
 * Description:       Adds the CSV format to GlotPress to export/import translations and originals.
 * Version:           1.0.1
 * Requires at least: 4.9
 * Tested up to:      6.1
 * Requires PHP:      7.4
 * Author:            Pedro Mendonça
 * Author URI:        https://profiles.wordpress.org/pedromendonca/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gp-format-csv
 * Domain Path:       /languages
 */

namespace GP_Format_CSV;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if get_plugin_data() function exists.
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Get plugin headers data.
$gp_format_csv = get_plugin_data( __FILE__, false, false );

// Set plugin version.
if ( ! defined( 'GP_FORMAT_CSV_VERSION' ) ) {
	define( 'GP_FORMAT_CSV_VERSION', $gp_format_csv['Version'] );
}

// Set plugin required PHP version. Needed for PHP compatibility check for WordPress < 5.1.
if ( ! defined( 'GP_FORMAT_CSV_REQUIRED_PHP' ) ) {
	define( 'GP_FORMAT_CSV_REQUIRED_PHP', $gp_format_csv['RequiresPHP'] );
}

// Set plugin URL.
define( 'GP_FORMAT_CSV_DIR_URL', plugin_dir_url( __FILE__ ) );

// Set plugin filesystem path.
define( 'GP_FORMAT_CSV_DIR_PATH', plugin_dir_path( __FILE__ ) );

// Set plugin file path.
define( 'GP_FORMAT_CSV_FILE', plugin_basename( __FILE__ ) );





// Check for PHP compatibility.
// Adapted from https://pento.net/2014/02/18/dont-let-your-plugin-be-activated-on-incompatible-sites/.
add_action( 'admin_init', __NAMESPACE__ . '\gp_format_csv_check_version' );


// Stop running the plugin if on an incompatible PHP version.
if ( ! gp_format_csv_compatible_version() ) {
	return;
}


/**
 * Backup sanity check, in case the plugin is activated, or the versions change after activation.
 * WordPress 5.1 news: https://wordpress.org/news/2019/04/minimum-php-version-update/.
 *
 * If incompatible, deactivate the plugin and add an admin notice.
 *
 * @since 1.0.0
 *
 * @return void
 */
function gp_format_csv_check_version() {

	if ( ! gp_format_csv_compatible_version() ) {

		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {

			// Deactivate the plugin.
			deactivate_plugins( plugin_basename( __FILE__ ) );

			// Show disabled admin notice.
			add_action( 'admin_notices', __NAMESPACE__ . '\gp_format_csv_disabled_notice' );

		}
	}
}


/**
 * Show disabled notice with the minimum required PHP version.
 * Adapted from https://pento.net/2014/02/18/dont-let-your-plugin-be-activated-on-incompatible-sites/.
 *
 * @since 1.0.0
 *
 * @return void
 */
function gp_format_csv_disabled_notice() {

	// Get plugin data.
	$plugin_data = get_plugin_data( __FILE__ );
	?>

	<div class="notice notice-error is-dismissible">
		<p>

			<?php
			printf(
				wp_kses_post(
					/* translators: 1: Plugin name. 2: Error message. */
					__( 'The plugin %1$s has been deactivated due to an error: %2$s', 'gp-format-csv' )
				),
				'<code>' . esc_html( $plugin_data['Name'] ) . '</code>',
				esc_html__( 'This plugin doesn&#8217;t work with your version of PHP.', 'gp-format-csv' )
			);
			?>

		</p>
		<p>

			<?php
			printf(
				/* translators: %s: Minimum PHP version required. */
				esc_html__( 'Requires PHP version %s or higher.', 'gp-format-csv' ),
				esc_html( GP_FORMAT_CSV_REQUIRED_PHP )
			);

			// Show aditional update link if on WP version 5.1 or higher.
			// Capability added in WP 5.1: https://core.trac.wordpress.org/ticket/44457.
			// Introduced in WP 5.1: https://developer.wordpress.org/reference/functions/wp_get_update_php_url/.
			if ( current_user_can( 'update_php' ) && version_compare( $GLOBALS['wp_version'], '5.1', '>=' ) ) {
				echo ' ' . sprintf(
					wp_kses_post(
						/* translators: %s: URL to Update PHP page. */
						__( '<a href="%s">Learn more about updating PHP</a>.', 'gp-format-csv' )
					),
					esc_url( wp_get_update_php_url() )
				);
			}
			?>

		</p>
	</div>

	<?php
}


/**
 * Check plugin minimum requirements.
 * Adapted from https://pento.net/2014/02/18/dont-let-your-plugin-be-activated-on-incompatible-sites/.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function gp_format_csv_compatible_version() {

	// Check minimum required PHP version.
	return version_compare( PHP_VERSION, GP_FORMAT_CSV_REQUIRED_PHP, '>=' );
}

/**
 * Initialize the plugin.
 *
 * @return void
 */
function gp_format_csv_init() {
	require_once 'includes/class-gp-format-csv.php';
}
add_action( 'gp_init', __NAMESPACE__ . '\gp_format_csv_init' );
