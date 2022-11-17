<?php
/**
 * GlotPress Format CSV class.
 *
 * @since 1.0.0
 *
 * @package GP_Format_CSV
 */

namespace GP_Format_CSV;

use GP;
use GP_Format;
use GP_Project;
use GP_Locale;
use GP_Translation_Set;
use GP_Translation;
use Translations;
use Translation_Entry;


/**
 * Format class used to support CSV file format.
 *
 * @since 1.0.0
 */
class GP_Format_CSV extends GP_Format {


	/**
	 * Name of file format, used in file format dropdowns.
	 *
	 * @since 4.0.0
	 *
	 * @var string
	 */
	public $name = 'CSV (.csv)';

	/**
	 * File extension of the file format, used to autodetect formats and when creating the output file names.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $extension = 'csv';


	/**
	 * Generates a string the contains the $entries to export in the CSV file format.
	 *
	 * CSV columns convention:
	 *   "Context","Singular","Plural","Comments",..."Translation for %1"
	 *   The number of the columns for Translation plural forms depend on the exported translation locale.
	 *
	 * @since 1.0.0
	 *
	 * @param GP_Project         $project           The project the strings are being exported for, not used
	 *                                              in this format but part of the scaffold of the parent object.
	 * @param GP_Locale          $locale            The locale object the strings are being exported for. not used
	 *                                              in this format but part of the scaffold of the parent object.
	 * @param GP_Translation_Set $translation_set   The locale object the strings are being
	 *                                              exported for. not used in this format but part
	 *                                              of the scaffold of the parent object.
	 * @param GP_Translation     $entries           The entries to export.
	 *
	 * @return string   The exported CSV string.
	 */
	public function print_exported_file( $project, $locale, $translation_set, $entries ) {

		$result = array();

		// Add table header.
		$header = array(
			'Context',
			'Singular',
			'Plural',
			'Comments',
		);

		if ( 2 === $locale->nplurals && 'n != 1' === $locale->plural_expression ) {
			$header[] = sprintf(
				'Translation for %s',
				'Singular'
			);
			$header[] = sprintf(
				'Translation for %s',
				'Plural'
			);
		} else {
			foreach ( range( 0, $locale->nplurals - 1 ) as $plural_index ) {
				$plural_string = implode( ', ', $locale->numbers_for_index( $plural_index ) );

				$header[] = sprintf(
					'Translation for %s',
					$plural_string
				);
			}
		}

		$result[] = '"' . implode( '","', $header ) . '"';

		foreach ( $entries as $entry ) { // @phpstan-ignore-line

			// Add table row.
			$key = array();

			$key[] = $this->prepare( $entry->context );
			$key[] = $this->prepare( $entry->singular );
			$key[] = $this->prepare( $entry->plural );
			$key[] = $this->prepare( $entry->extracted_comments );

			if ( 2 === $locale->nplurals && 'n != 1' === $locale->plural_expression ) {
				$key[] = $this->prepare( $entry->translations[0] );
				$key[] = $this->prepare( $entry->translations[1] );
			} else {
				foreach ( range( 0, $locale->nplurals - 1 ) as $plural_index ) {
					$plural_string = implode( ', ', $locale->numbers_for_index( $plural_index ) );

					$key[] = ( ! isset( $entry->translations[ $plural_index ] ) || gp_is_empty_string( $entry->translations[ $plural_index ] ) ) ? '' : $this->prepare( $entry->translations[ $plural_index ] );
				}
			}

			$result[] = '"' . implode( '","', $key ) . '"';

		}

		// Use CRLF line endings with final new line.
		return implode( "\r\n", $result ) . "\r\n";
	}


	/**
	 * Prepare string to be printed out in a translation row by escaping newlines and tabs.
	 *
	 * @param string|null $text   The string to prepare.
	 *
	 * @return string|null   The prepared string for output.
	 */
	public function prepare( $text ) {

		if ( ! is_null( $text ) ) {
			$text = str_replace( array( "\r", "\n" ), "\\n", $text );
			$text = str_replace( "\t", "\\t", $text );
		}

		return $text;
	}


	/**
	 * Reads a set of translations from a CSV file.
	 *
	 * @since 1.0.0
	 *
	 * @param string     $file_name   The name of the uploaded properties file.
	 * @param GP_Project $project     Unused. The project object to read the translations into.
	 *
	 * @return Translations|false   The extracted translations on success, false on failure.
	 */
	public function read_originals_from_file( $file_name, $project = null ) {
		unset( $project );

		return $this->read_translations_from_file( $file_name );
	}


	/**
	 * Reads a set of translations from a CSV file.
	 *
	 * @since 1.0.0
	 *
	 * @param string     $file_name   The name of the uploaded CSV file.
	 * @param GP_Project $project     Unused. The project object to read the translations into.
	 *
	 * @return Translations|false   The extracted translations on success, false on failure.
	 */
	public function read_translations_from_file( $file_name, $project = null ) {
		unset( $project );

		$rows = $this->read_lines_from_csv( $file_name );

		if ( ! $rows ) {
			return false;
		}

		$entries = new Translations();

		foreach ( $rows as $row ) {

			// Get Comma Separated Values.
			$row = str_getcsv( $row );

			$entry = new Translation_Entry();

			foreach ( $row as $key => $value ) {

				switch ( $key ) {
					case 0:
						if ( $value ) {
							$entry->context = $value;
						}
						break;
					case 1:
						if ( $value ) {
							$entry->singular = $value;
						}
						break;
					case 2:
						if ( $value ) {
							$entry->plural = $value;
						}
						break;
					case 3:
						if ( $value ) {
							$entry->extracted_comments = $value;
						}
						break;
					default:
						$entry->translations[] = $value ? $value : null;
				}
			}

			$entries->add_entry( $entry );
		}

		return $entries;
	}


	/**
	 * Get CSV file rows as an array.
	 * The first header line and the empty rows are excluded.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name   Path to the text file.
	 *
	 * @return false|array<int, string>   Array of rows of the CSV file. Return false if file not found.
	 */
	public function read_lines_from_csv( $file_name = '' ) {

		if ( ! file_exists( $file_name ) || ! is_readable( $file_name ) ) {
			return false;
		}

		$file = file_get_contents( $file_name ); // phpcs:ignore

		if ( ! $file ) {
			return false;
		}

		$rows = explode( "\r\n", $file );

		// Ignore header and empty rows.
		foreach ( $rows as $key => $row ) {
			if ( 0 === $key || '' === $row ) {
				unset( $rows[ $key ] );
			}
		}

		return $rows;

	}

}

GP::$formats['csv'] = new GP_Format_CSV(); // phpcs:ignore.
