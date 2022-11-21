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
	 * Set the standard column headers to use in the CSV header row.
	 * The column labels can be customized with the filter 'gp_format_csv_header'.
	 *
	 * The header row of the exported CSV includes this header.
	 * The header row of the CSV to import must match this header for successfully import.
	 * The translation plural forms from 1 to 5 are optional and set to null by default.
	 *
	 * Check out this example.csv file.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $standard_header = array(
		'context'       => 'Context',     // A string differentiating two equal strings used in different contexts.
		'singular'      => 'Singular',    // The string to translate.
		'plural'        => 'Plural',      // The plural form of the string.
		'comments'      => 'Comments',    // Comments left by developers.
		'references'    => 'References',  // Places in the code this string is used, in relative_to_root_path/file.php:linenum form.
		'translation_0' => 'Translation', // Translation for a singular form. Defaults to no plural.
		'translation_1' => null,          // Translation for a plural form. Optional.
		'translation_2' => null,          // Translation for a second plural form. Optional.
		'translation_3' => null,          // Translation for a third plural form. Optional.
		'translation_4' => null,          // Translation for a fourth plural form. Optional.
		'translation_5' => null,          // Translation for a fifth plural form. Optional.
	);


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
	 * @param GP_Locale          $locale            The locale object the strings are being exported for, not used
	 *                                              in this format but part of the scaffold of the parent object.
	 * @param GP_Translation_Set $translation_set   The Translation Set object the strings are being
	 *                                              exported for. not used in this format but part
	 *                                              of the scaffold of the parent object.
	 * @param GP_Translation     $entries           The entries to export.
	 *
	 * @return string   The exported CSV string.
	 */
	public function print_exported_file( $project, $locale, $translation_set, $entries ) {

		// Get the custom CSV header row for the specified locale.
		$header = $this->locale_header( $locale );

		$result = array();

		// Add header row to the CSV.
		$result[] = '"' . implode( '","', $header ) . '"';

		foreach ( $entries as $entry ) { // @phpstan-ignore-line

			// Add table row.
			$row = array();

			$row[] = $this->escape( $entry->context );
			$row[] = $this->escape( $entry->singular );
			$row[] = $this->escape( $entry->plural );
			// Developer comments are already stored as a string, no need to implode array.
			$row[] = $this->escape( $entry->extracted_comments );
			// Implode References array separated by '\n' newline.
			$row[] = $this->escape( implode( "\n", $entry->references ) );

			if ( 2 === $locale->nplurals && 'n != 1' === $locale->plural_expression ) {
				$row[] = $this->escape( $entry->translations[0] );
				$row[] = $this->escape( $entry->translations[1] );
			} else {
				foreach ( range( 0, $locale->nplurals - 1 ) as $plural_index ) {
					$row[] = ( ! isset( $entry->translations[ $plural_index ] ) || gp_is_empty_string( $entry->translations[ $plural_index ] ) ) ? '' : $this->escape( $entry->translations[ $plural_index ] );
				}
			}

			/**
			 * Filter the CSV row to allow plugins to add, remove or customize items.
			 *
			 * @since 1.0.0
			 *
			 * @param array             $row       The array of the row items.
			 * @param GP_Project        $project   The GP_Project object.
			 * @param GP_Locale         $locale    The GP_locale object.
			 * @param Translation_Entry $entry     The Translation_Entry object.
			 */
			$row = apply_filters( 'gp_format_csv_row', $row, $project, $locale, $entry ); // @phpstan-ignore-line

			$result[] = '"' . implode( '","', $row ) . '"';

		}

		// Use CRLF line endings with final new line.
		return implode( "\r\n", $result ) . "\r\n";
	}


	/**
	 * Get the custom CSV header row for the specified locale.
	 *
	 * @since 1.0.0

	 * @param GP_Locale $locale   The GP_Locale object.
	 *
	 * @return array   Array of the header row columns.
	 */
	public function locale_header( $locale ) {

		// Get the standard header.
		$header = $this->standard_header;

		if ( 2 === $locale->nplurals && 'n != 1' === $locale->plural_expression ) {
			// Add Single form label to translation column.
			$header['translation_0'] = sprintf(
				'Translation (%s)',
				'Singular'
			);
			// Add Plural form label to translation column.
			$header['translation_1'] = sprintf(
				'Translation (%s)',
				'Plural'
			);

		} elseif ( 1 !== $locale->nplurals ) {
			// Add Number Plural form label to translation column for all locales with more than 1 plural forms.
			foreach ( range( 0, $locale->nplurals - 1 ) as $plural_index ) {
				$plural_string = implode( ', ', $locale->numbers_for_index( $plural_index ) );

				$header[ "translation_$plural_index" ] = sprintf(
					'Translation (%s)',
					$plural_string
				);
			}
		}

		// Don't output unused plural form headers.
		foreach ( $header as $key => $value ) {
			if ( is_null( $value ) ) {
				unset( $header[ $key ] );
			}
		}

		/**
		 * Filter the CSV header to allow plugins to add, remove or customize items.
		 *
		 * @since 1.0.0
		 *
		 * @param array     $header   The array of the header items.
		 * @param GP_Locale $locale   The GP_locale object.
		 */
		$header = apply_filters( 'gp_format_csv_header', $header, $locale ); // @phpstan-ignore-line

		return $header;

	}


	/**
	 * Escape string to be printed out in a translation row by escaping newlines, tabs and double quotes.
	 *
	 * @param string|null $text   The string to escape.
	 *
	 * @return string|null   The prepared string for output.
	 */
	public function escape( $text ) {

		if ( ! is_null( $text ) ) {
			$text = addcslashes( $text, '"\\' );                     // Escape " (double quotes) and \ (backslash).
			$text = str_replace( array( "\r", "\n" ), '\n', $text ); // Escape Returns and New lines as plain text \n.
			$text = str_replace( "\t", '\t', $text );                // Escape tabs to plain text \t.
		}

		return $text;
	}


	/**
	 * Unescape string to be imported.
	 *
	 * @param string|null $text   The string to unescape.
	 *
	 * @return string|null   The unescaped string.
	 */
	public function unescape( $text ) {

		if ( ! is_null( $text ) ) {
			$text = stripcslashes( $text ); // Unescape \\ (double backslashes) and convert C-style escape sequences \n and \t to their actual meaning.
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

		$header = $rows[0];

		// Compare the header against the standard header.
		if ( ! $this->header_is_valid( $header ) ) {
			return false;
		} else {
			// Remove header for further processing.
			unset( $rows[0] );
		}

		$header = str_getcsv( $header );

		$entries = new Translations();

		foreach ( $rows as $key => $row ) {

			// Get Comma Separated Values.
			$row = str_getcsv( $row );

			// Check if the row columns count matches the header columns count.
			if ( count( $header ) !== count( $row ) ) {
				if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
					error_log( // phpcs:ignore.
						wp_sprintf(
							/* translators: 1: Total header columns. 2: Total row columns. */
							esc_html__( 'An error occurred while importing the CSV file: The file header has %1$d columns, and the rows have %2$d.', 'gp-format-csv' ),
							count( $header ),
							count( $row )
						)
					);
				}

				return false;

			}

			$entry = new Translation_Entry();

			foreach ( $row as $key => $value ) {

				switch ( $key ) {
					// Check Context. Import if not empty.
					case 0:
						if ( $value ) {
							$entry->context = $this->unescape( $value );
						}
						break;
					// Check Singular. Import if not empty.
					case 1:
						if ( $value ) {
							$entry->singular = $this->unescape( $value );
						}
						break;
					// Check Plural. Import if not empty.
					case 2:
						if ( $value ) {
							$entry->plural = $this->unescape( $value );
						}
						break;
					// Check Comments to translators. Import if not empty.
					case 3:
						if ( $value ) {
							$entry->extracted_comments = $this->unescape( $value );
						}
						break;
					// Check References. Explode string to array and import if not empty.
					case 4:
						if ( $value ) {
							$references = explode( '\\n', $value );
							foreach ( $references as $reference ) {
								$entry->references[] = $reference;
							}
						}
						break;
					// Check Translation for singular form. Import if not empty.
					case 5:
						if ( $value ) {
							$entry->translations[0] = $this->unescape( $value );
						}
						break;
					// Check Translation for a plural form. Import if not empty.
					case 6:
						if ( $value ) {
							$entry->translations[1] = $this->unescape( $value );
						}
						break;
					// Check Translation for a second plural form. Import if not empty.
					case 7:
						if ( $value ) {
							$entry->translations[2] = $this->unescape( $value );
						}
						break;
					// Check Translation for a third plural form. Import if not empty.
					case 8:
						if ( $value ) {
							$entry->translations[3] = $this->unescape( $value );
						}
						break;
					// Check Translation for a fourth plural form. Import if not empty.
					case 9:
						if ( $value ) {
							$entry->translations[4] = $this->unescape( $value );
						}
						break;
					// Check Translation for a fifth plural form. Import if not empty.
					case 10:
						if ( $value ) {
							$entry->translations[5] = $this->unescape( $value );
						}
						break;

				}
			}

			$entries->add_entry( $entry );

		}

		return $entries;
	}


	/**
	 * Compare the header against the standard header.
	 *
	 * Mandatory main columns:
	 *   - Context, Singular, Plural, Comments, References.
	 * Mandatory translation columns:
	 *   - 1 to 6 columns starting with 'Translation'.
	 *
	 * Any remaining columns after these, will be ignored and not imported.
	 *
	 * @since 1.0.0
	 *
	 * @param string $header   Header row of the CSV file to validate.
	 *
	 * @return bool   True if the header is valid, false if the header is invalid.
	 */
	public function header_is_valid( $header ) {

		// Get columns from the header.
		$header = str_getcsv( $header );

		// Get the standard header.
		$standard_header = $this->standard_header;

		// Check mandatory columns.
		$i      = 0;
		$errors = array();

		foreach ( $standard_header as $key => $standard_column ) {

			if ( ! array_key_exists( $i, $header ) ) {
				break;
			}

			// Check for exact main columns.
			if ( $standard_column !== $header[ $i ] && ( 'translation_' !== preg_replace( '/^(translation_)(\d)$/', '$1', $key ) || 'Translation' !== preg_replace( '/^(Translation).*$/', '$1', strval( $header[ $i ] ) ) ) ) {
				// Log invalid header column.
				$errors[] = $header[ $i ];
			}

			$i++;
		}

		if ( ! empty( $errors ) ) {
			if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
				error_log( // phpcs:ignore.
					wp_sprintf(
						/* translators: List of invalid column headers. */
						esc_html__( 'An error occurred while importing the CSV file: Invalid column headers found (%l)', 'gp-format-csv' ),
						$errors
					)
				);
			}

			return false;
		}

		return true;
	}


	/**
	 * Get CSV file rows as an array.
	 * Empty rows are excluded.
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

		// Ignore empty rows.
		foreach ( $rows as $key => $row ) {
			if ( '' === $row ) {
				unset( $rows[ $key ] );
			}
		}

		return $rows;

	}

}

GP::$formats['csv'] = new GP_Format_CSV(); // phpcs:ignore.
