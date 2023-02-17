# Introduction

This is a basic PHP project to implement support for generating [Avery.com](https://www.avery.com/templates) label templates using [FPDF](https://fpdf.org).

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

Then

```php
require 'vendor/autoload.php';

$template = new Avery\Avery5160(Fpdf\Fpdf::class);
```

---

### Direct

If you choose to install without composer support, you can clone the repository directly. **You will need to include FPDF also.**

```bash
git clone https://github.com/amattu2/avery-fpdf-labels/tree/master
```

Then

```php
require 'fpdf/Fpdf.php'; // Install FPDF manually
require 'src/Avery5160.php';

$template = new Avery\Avery5160(Fpdf\Fpdf::class);
```

---

### Usage

```PHP
/* follow the setup above */

// Set PDF properties
$template->pdf->SetTitle("FPDF Template Example");
$template->pdf->AddPage("P", "Letter");
$template->pdf->SetFont('Helvetica', '', 11); // Font optional
$template->pdf->SetTextColor(25, 25, 25); // Color optional

// Add labels
$template->add(["ln1", "ln2"]);
// see example.php

// Build PDF
$template->writeLabels();

// Output PDF
$template->pdf->Output("I", "Labels.pdf");
```

## Methods

### __construct($pdf)

This is a breaking change from pre-1.0.0 releases. The constructor now takes a classname as a argument, and no longer extends FPDF. This allows more flexibility if you have a need to pass a class that extends FPDF already.

```PHP
/**
 * Contructor
 *
 * @param  \FPDF|\Fpdf\Fpdf $pdf
 */
public function __construct($pdf)
```

### add(array $lines, int $row = 0, int $col = 0)

This function adds a new label to the list of labels to be produced. There is currently no way to revoke a label once it has added.

```PHP
/**
 * Add a single label to the PDF
 *
 * @param array $lines an array of label liens
 * @param integer $row optional desired insert row
 * @param integer $col optional diesired intert column
 * @throws TypeError
 * @throws BadValueException
 * @author Alec M. <https://amattu.com>
 * @date 2021-09-05T13:49:28-040
 */
public function add(array $lines, int $row = 0, int $col = 0) : void;
```

Usage

```PHP
$lines = Array(
  "Line 1",
  "Line 2",
  "Line 3",
  "Line 4"
);

$template->add($lines);
```

### writeLabels()

```PHP
/**
 * Build the completed PDF with labels
 *
 * NOTE:
 *   (1) To save resources, no PDF is built until
 *   this function is called.
 *
 * @return void
 * @throws InvalidStateException
 * @author Alec M. <https://amattu.com>
 * @date 2021-09-05T13:50:58-040
 */
public function writeLabels() : void;
```

Usage

```PHP
$template->writeLabels();

// PDF content is written, now output PDF as desired
```

## Variables

### `class->$pdf`

The variable `pdf` is available upon class instantiation. It references a instance of the FPDF class passed to the constructor.

```PHP
$template->pdf->Cell('xyz');
```

# Requirements & Dependencies

- FPDF 1.81 Minimum [http://www.fpdf.org/]
- PHP 7.4+ [https://php.net]
