# CSV Format for GlotPress

Adds the CSV format to GlotPress to export/import translations and originals.

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/gp-format-csv?label=Plugin%20Version&logo=wordpress)](https://wordpress.org/plugins/gp-format-csv/)
[![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/stars/gp-format-csv?label=Plugin%20Rating&logo=wordpress)](https://wordpress.org/support/plugin/gp-format-csv/reviews/)
[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/gp-format-csv.svg?label=Downloads&logo=wordpress)](https://wordpress.org/plugins/gp-format-csv/advanced/)
[![Sponsor](https://img.shields.io/badge/GitHub-🤍%20Sponsor-ea4aaa?logo=github)](https://github.com/sponsors/pedro-mendonca)

[![WordPress Plugin Required PHP Version](https://img.shields.io/wordpress/plugin/required-php/gp-format-csv?label=PHP%20Required&logo=php&logoColor=white)](https://wordpress.org/plugins/gp-format-csv/)
[![WordPress Plugin: Required WP Version](https://img.shields.io/wordpress/plugin/wp-version/gp-format-csv?label=WordPress%20Required&logo=wordpress)](https://wordpress.org/plugins/gp-format-csv/)
[![WordPress Plugin: Tested WP Version](https://img.shields.io/wordpress/plugin/tested/gp-format-csv.svg?label=WordPress%20Tested&logo=wordpress)](https://wordpress.org/plugins/gp-format-csv/)

[![Coding Standards](https://github.com/pedro-mendonca/GP-Format-CSV/actions/workflows/coding-standards.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Format-CSV/actions/workflows/coding-standards.yml)
[![Static Analysis](https://github.com/pedro-mendonca/GP-Format-CSV/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Format-CSV/actions/workflows/static-analysis.yml)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/48804e8b44f445afbed607c43ec3e9dd)](https://www.codacy.com/gh/pedro-mendonca/GP-Format-CSV/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=pedro-mendonca/GP-Format-CSV&amp;utm_campaign=Badge_Grade)

## Description

Adds the CSV format to GlotPress to export/import translations and originals.

This allows you to export a translation set to a CSV file, and use this to import the translations or originals into the project.

This plugin is properly prepared for localization.

## The CSV Format

The CSV output has values separated by `,` and enclosured by `" "`.  
The total number of columns depends on the number of Plural Forms of the exported Locale.  
For an sample file, please check out [example.csv](https://github.com/pedro-mendonca/GP-Format-CSV/blob/main/example.csv).  
The header columns for the different translations have the `Translation (<plural-form>)` naming convention, being the Plural Forms depending on each exported Locale.  
See the example below for a **Portuguese** CSV export, which has `2` plural forms.  

### The exported CSV in plain text

```csv
"Context","Singular","Plural","Comments","References","Translation (Singular)","Translation (Plural)"
"","Singular in English.","","Comment 1.\nComment 2.","","Singular em Português.",""
```

### The exported CSV in table format

| Context | Singular | Plural | Comments | References | Translation (Singular) | Translation (Plural) |
| --- | --- | --- | --- | --- | --- | --- |
| | *Singular in English.* | | *Comment 1.\nComment 2.* | | *Singular em Português.* | |

### The main columns

The 5 main columns are `Context`, `Singular`, `Plural`, `Comments` and `References`.

| Context | Singular | Plural | Comments | References | Translation(s)... |
| --- | --- | --- | --- | --- | --- |
| *value* | *value* | *value* | *value* | *value* | |
| *value* | *value* | *value* | *value* | *value* | |

### The Plural Forms variable columns
 
The number of Plural Forms columns may vary depending on each Locale setting.  
Check the below table for examples of [GlotPress Locales](https://github.com/GlotPress/GlotPress/blob/develop/locales/locales.php) from 1 to 6 Plural Forms.

| Locale (nplurals) | ...Comments | Translation (Singular) | Translation (Plural 1) | Translation (Plural 2) | Translation (Plural 3) | Translation (Plural 4) | Translation (Plural 5)
| --- | --- | --- | --- | --- | --- | --- | --- |
| Japanese (1) | | Translation (Single) |
| Portuguese (2) | | Translation (Singular) | Translation (Plural) |
| Serbian (3) | | Translation (1, 21, 31) | Translation (2, 3, 4) | Translation (0, 5, 6) |
| Slovenian (4) | | Translation (1, 101, 201) | Translation (2, 102, 202) | Translation (3, 4, 103) | Translation (0, 5, 6) |
| Irish (5) | | Translation (1) | Translation (2) | Translation (3, 4, 5) | Translation (7, 8, 9) | Translation (0, 11, 12) |
| Arabic (6) | | Translation (0) | Translation (1) | Translation (2) | Translation (3, 4, 5) | Translation (11, 12, 13) | Translation (100, 101, 102) |

## Included filters

The `gp_format_csv_header` allows you to add, remove or customize items from the CSV header.  
The `gp_format_csv_row` allows you to add, remove or customize items from the CSV row.  

## Usage

### Export translations to CSV

1. Go to the bottom of the translation table.
2. Select `CSV (.csv)`.
3. Click on the `Export` link to download the CSV file.

### Import translations from CSV

1. Go to the bottom of the translation table.
2. Click on the `Import Translations` link.
3. Select the CSV file to upload, the `Format` is *Auto Detected*.
4. Click on the `Import` button.

### Import originals from CSV

1. Go to the project page.
2. On the project actions click on the `Import Originals` link.
3. Select the CSV file to upload, the `Format` is *Auto Detected*.
4. Click on the `Import` button.

## Changelog

### 1.0.0

* Initial release.
* All WPCS and PHPStan level 9 verified.
