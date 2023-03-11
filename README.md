# Introduction

This is a PHP project to implement support for generating [Avery.com](https://www.avery.com/templates) label templates using [FPDF](https://fpdf.org).

# Templates

## Supported

The currently implemented templates are listed below.

- Avery 5160
- Avery Presta 94107

## Plans to Support

Upcoming support for the following templates is planned. Feel free to contribute to the development effort by submitting a pull request. See [/templates](/templates/) for the PDF template that can be used during development to ensure sizing and spacing accuracy. **If you have an urgent need for a specific template to be supported, open a issue to let me know.**

- Avery 5162
- Avery 5163
- Avery 5195
- Avery 5816
- Avery 5817
- Avery 8160

# Usage

## Install

### Composer

To install via composer, follow the simple steps below.

```bash
composer install amattu2/avery-fpdf-labels
composer install fpdf/fpdf
```

**NOTE:** You can use any fork of FPDF you want as long as it implements the same methods as FPDF.

Then

```php
require 'vendor/autoload.php';

$template = new amattu2\LabelSheet("Avery5160");
$pdf = new Fpdf\Fpdf();
```

---

### Direct

If you choose to install without composer support, you can clone the repository directly. **You will need to include FPDF also.**

```bash
git clone https://github.com/amattu2/avery-fpdf-labels
```

Then

```php
require 'fpdf/Fpdf.php'; // Install FPDF manually
require 'src/LabelSheet.php';

$template = new amattu2\LabelSheet("Avery5160");
$pdf = new Fpdf\Fpdf();
```

---

## Usage

See [example.php](example.php) for demonstration of usage.

### Text Label

Use the `addTextLabel` method to add a text label to the template. The method accepts an array of strings, an optional alignment parameter, and optional row and column parameters. This would typically be used for address labels.

```php
addTextLabel(array $lines, ?string $align = "C", int $row = null, int $col = null): void
```

```php
$template->addTextLabel([
  "line 1",
  "line 2",
  "line 3",
  // ...
])
```

### Image Label

Use the `addImageLabel` method to add an image label to the template. The method accepts a path to an image file and optional row and column parameters.

```php
addImageLabel(string $path, int $row = null, int $col = null): void
```

```php
$template->addImageLabel("https://api.placeholder.app/image/350x350/.png");
```

### Custom Labels

The `addCustomLabel` method allows you to expand upon the current label types (e.g. adding barcodes). You must instantiate your own implementation of the custom label and pass it to this method.

```php
addCustomLabel(LabelInterface $label): void
```

The custom label must implement the `LabelInterface` interface. See [/src/LabelInterface.php](/src/LabelInterface.php) for more information.

# Requirements & Dependencies

- FPDF 1.81 Minimum [http://www.fpdf.org/]
- PHP 7.4+ [https://php.net]
