=== CSV Format for GlotPress ===
Contributors: pedromendonca
Donate link: https://github.com/sponsors/pedro-mendonca
Tags: WordPress, i18n, l10n, GlotPress, csv
Requires at least: 4.9
Tested up to: 6.1
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds the CSV format to GlotPress to export/import translations and originals.

== Description ==

Adds the CSV format to GlotPress to export/import translations and originals.

This allows you to export a translation set to a CSV file, and use this to import the translations or originals into the project.

This plugin is properly prepared for localization.

== The CSV Format ==

The CSV output has values separated by `,` and enclosured by `" "`.

The total number of columns depends on the number of Plural Forms of the exported Locale.

For an sample file, please check out [example.csv](https://github.com/pedro-mendonca/GP-Format-CSV/blob/main/example.csv).

The header columns for the different translations have the `Translation (<plural-form>)` naming convention, being the Plural Forms depending on each exported Locale.

See the example below for a **Portuguese** CSV export, which has `2` plural forms.

= The exported CSV in plain text =

	"Context","Singular","Plural","Comments","References","Translation (Singular)","Translation (Plural)"
	"","Singular in English.","","Comment 1.\nComment 2.","","Singular em Português.",""

= The main columns =

The 5 main columns are `Context`, `Singular`, `Plural`, `Comments` and `References`.

= The Plural Forms variable columns =

The number of Plural Forms columns may vary depending on each Locale setting.

Check the below table for examples of [GlotPress Locales](https://github.com/GlotPress/GlotPress/blob/develop/locales/locales.php) from 1 to 6 Plural Forms.

== Included filters ==

The `gp_format_csv_header` allows you to add, remove or customize items from the CSV header.

The `gp_format_csv_row` allows you to add, remove or customize items from the CSV row.

== Usage ==

= Export translations to CSV =

1. Go to the bottom of the translation table.
2. Select `CSV (.csv)`.
3. Click on the `Export` link to download the CSV file.

=  Import translations from CSV =

1. Go to the bottom of the translation table.
2. Click on the `Import Translations` link.
3. Select the CSV file to upload, the `Format` is *Auto Detected*.
4. Click on the `Import` button.

=  Import originals from CSV =

1. Go to the project page.
2. On the project actions click on the `Import Originals` link.
3. Select the CSV file to upload, the `Format` is *Auto Detected*.
4. Click on the `Import` button.

== Frequently Asked Questions ==

= Can I help translating this plugin to my own language? =
Yes you can! If you want to translate this plugin to your language, please [click here](https://translate.wordpress.org/projects/wp-plugins/gp-format-csv).

= Can I contribute to this plugin? =
Sure! You are welcome to report any issues or add feature suggestions on the [GitHub repository](https://github.com/pedro-mendonca/GP-Format-CSV).

== Changelog ==

= 1.0.1 =
*   Fix log error message typo. Props @fxbenard.

= 1.0.0 =
*   Initial release.
*   All WPCS and PHPStan level 9 verified.
