<?php
/**
 * PHPStan bootstrap file
 *
 * @package GP_Format_CSV
 */


// Set plugin version.
if ( ! defined( 'GP_FORMAT_CSV_VERSION' ) ) {
	define( 'GP_FORMAT_CSV_VERSION', '1.0.0' );
}

// Set plugin required PHP version. Needed for PHP compatibility check for WordPress < 5.1.
if ( ! defined( 'GP_FORMAT_CSV_REQUIRED_PHP' ) ) {
	define( 'GP_FORMAT_CSV_REQUIRED_PHP', '7.4' );
}

// Require plugin main file.
require_once 'gp-format-csv.php';
