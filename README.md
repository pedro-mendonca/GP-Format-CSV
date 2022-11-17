# CSV Format for GlotPress

Adds the CSV format to GlotPress to export and import translations.

[![Coding Standards](https://github.com/pedro-mendonca/GP-Format-CSV/actions/workflows/coding-standards.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Format-CSV/actions/workflows/coding-standards.yml)
[![Static Analysis](https://github.com/pedro-mendonca/GP-Format-CSV/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Format-CSV/actions/workflows/static-analysis.yml)
[![Sponsor](https://img.shields.io/badge/GitHub-🤍%20Sponsor-ea4aaa?logo=github)](https://github.com/sponsors/pedro-mendonca)

## Description

Adds the CSV format to GlotPress to export and import translations.

This plugin is properly prepared for localization.

### The table format

The CSV output has values separated by `,` and enclosured by `" "`.  
The total number of columns depends on the number of Plural Forms of the exported Locale.  
See the example below for a **Portuguese** CSV export, which has `2` plural forms.  

**The exported CSV in plain text**
```csv
"Context","Singular","Plural","Comments","Translation for Singular","Translation for Plural"
"","Singular in English.","","Comment 1.\nComment 2.","Singular em Português.",""
```

**The exported CSV in table format**
| Context | Singular | Plural | Comments | Translation for Singular | Translation for Plural |
| --- | --- | --- | --- | --- | --- |
| | *Singular in English.* | | *Comment 1.\nComment 2.* | *Singular em Português.* | |

### The main columns

The 4 main columns are `Context`, `Singular`, `Plural` and `Comments`.

| Context | Singular | Plural | Comments | Plural Form(s)... |
| --- | --- | --- | --- | --- |
| *value* | *value* | *value* | *value* | |
| *value* | *value* | *value* | *value* | |

### The Plural Forms variable columns
 
The number of Plural Forms columns may vary depending on each Locale setting.  
Check the below table for examples of [GlotPress Locales](https://github.com/GlotPress/GlotPress/blob/develop/locales/locales.php) from 1 to 6 Plural Forms.  
| Locale (nplurals) | ...Comments | Plural Form 1 | Plural Form 2 | Plural Form 3 | Plural Form 4 | Plural Form 5 | Plural Form 6 |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Japanese (1) | | Translation for 0, 1, 2 |
| Portuguese (2) | | Translation for Singular | Translation for Plural |
| Serbian (3) | | Translation for 1, 21, 31 | Translation for 2, 3, 4 | Translation for 0, 5, 6 |
| Slovenian (4) | | Translation for 1, 101, 201 | Translation for 2, 102, 202 | Translation for 3, 4, 103 | Translation for 0, 5, 6 |
| Irish (5) | | Translation for 1 | Translation for 2 | Translation for 3, 4, 5 | Translation for 7, 8, 9 | Translation for 0, 11, 12 |
| Arabic (6) | | Translation for 0 | Translation for 1 | Translation for 2 | Translation for 3, 4, 5 | Translation for 11, 12, 13 | Translation for 100, 101, 102 |

### Included filters

The `gp_format_csv_header` allows you to add, remove or customize items from the CSV header.  
The `gp_format_csv_row` allows you to add, remove or customize items from the CSV row.  

### Usage

#### Exporting translations to CSV
1. Go to the bottom of the translation table.
2. Select `CSV (.csv)`.
3. Click on the `Export` link to download the CSV file.

#### Import translations from CSV
1. Go to the bottom of the translation table.
2. Click on the `Import Translations` link.
3. Select the CSV file to upload, the `Format` is *Auto Detected*.
4. Click on the `Import` button.

## Changelog

### 1.0.0

* Initial release.
* All WPCS and PHPStan level 9 verified.
