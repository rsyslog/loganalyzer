# Third-party and bundled components

This fork vendors several libraries inside `src/`. Versions below are taken from in-tree markers where available; upgrades should be smoke-tested (graphs, PDF export, ClickHouse sources, UI).

| Component | Location | Version / notes | License |
|-----------|----------|-----------------|---------|
| **JpGraph** | `src/classes/jpgraph/` | `JPG_VERSION` **4.4.1** (`jpgraph.php`) | QPL 1.0 (historical) |
| **html2fpdf** | `src/classes/html2fpdf/` | Legacy tree; `makefont.php` patched for PHP 8 (`preg_match` replaces `eregi`) | LGPL / project-specific—verify headers in tree |
| **phpClickHouse** | `src/classes/phpClickHouse/` | Packagist `smi2/phpclickhouse`; bundled `composer.json` requires `php: ^7.1` | MIT |
| **jQuery** | `src/js/jquery.js` | **3.7.1** (header comment) | MIT |
| **jQuery UI** | `src/js/jquery-ui.js` | **1.14.1** (file banner) | MIT |
| **Bitstream Vera Fonts** | `src/BitstreamVeraFonts/` | Bitmap fonts for charts/PDF | Bitstream Vera license |

## Suggested update order (risk / reward)

1. **jQuery / jQuery UI** — security and browser compatibility; retest all admin and grid interactions.
2. **phpClickHouse** — align with upstream or Composer pin; verify ClickHouse log streams and TLS.
3. **JpGraph** — evaluate modern fork or maintained package; retest `chartgenerator.php` and reports.

## PHP runtime

Supported baseline: **PHP 8.1+** (enforced in `functions_common.php`). Extensions commonly required: `mysqli`, `pdo_mysql`, `gd`, `curl`, `mbstring`, `xml`, `zip`; optional `mongodb` for MongoDB sources.
