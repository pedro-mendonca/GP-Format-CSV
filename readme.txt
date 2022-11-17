=== CSV Format for GlotPress ===
Contributors: pedromendonca
Donate link: https://github.com/sponsors/pedro-mendonca
Tags: WordPress, i18n, l10n, GlotPress, csv
Requires at least: 4.9
Tested up to: 6.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds the CSV format to GlotPress to export and import translations.

== Description ==

Adds the CSV format to GlotPress to export and import translations.

This plugin is properly prepared for localization.

== The CSV Format ==

The CSV output has values separated by `,` and enclosured by `" "`.
The total number of columns depends on the number of Plural Forms of the exported Locale.
See the example below for a **Portuguese** CSV export, which has `2` plural forms.

= The exported CSV in plain text =

```
"Context","Singular","Plural","Comments","Translation for Singular","Translation for Plural"
"","Singular in English.","","Comment 1.\nComment 2.","Singular em Português.",""
```

= The main columns =

The 4 main columns are `Context`, `Singular`, `Plural` and `Comments`.

= The Plural Forms variable columns =

The number of Plural Forms columns may vary depending on each Locale setting.
Check the below table for examples of [GlotPress Locales](https://github.com/GlotPress/GlotPress/blob/develop/locales/locales.php) from 1 to 6 Plural Forms.

== Included filters ==

The `gp_format_csv_header` allows you to add, remove or customize items from the CSV header.
The `gp_format_csv_row` allows you to add, remove or customize items from the CSV row.

== Usage ==

= Exporting translations to CSV =

1. Go to the bottom of the translation table.
2. Select `CSV (.csv)`.
3. Click on the `Export` link to download the CSV file.

=  Import translations from CSV =

1. Go to the bottom of the translation table.
2. Click on the `Import Translations` link.
3. Select the CSV file to upload, the `Format` is *Auto Detected*.
4. Click on the `Import` button.

== Changelog ==

= 1.0.0 =
*   Initial release.
*   All WPCS and PHPStan level 9 verified.
