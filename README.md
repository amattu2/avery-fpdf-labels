# Introduction
This is a simple [FPDF](www.fpdf.org) and [Avery](www.avery.com/templates) template class. It provides easy implementation of Avery shipping label templates.

## Supported Templates
- Avery 5160

## To-Do Templates
- Avery 5162
- Avery 5163
- Avery 5195
- Avery 5816
- Avery 5817
- Avery 8160

## To-Do Code
N/A

# Usage
See the documentation below or `index.php` or actual implementations.

## Setup
```PHP
// Import required files
require("FPDF.class.php");
require("AveryTemplates.class.php");

// Instantiate class (See supported Avery templates above)
$pdf = new amattu\Avery_5160();

// Set default properties
$pdf->SetTitle("FPDF Template Example");
$pdf->AddPage("P", "Letter");
$pdf->SetFont('Helvetica', '', 11); // Font optional
$pdf->SetTextColor(25, 25, 25); // Color optional

// Build PDF
$pdf->build();

// Output PDF
$pdf->Output("I", "Labels.pdf");
```
## add(array $lines, int $row = 0, int $col = 0)
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

$pdf->add($lines);
```

## build()
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
public function build() : void;
```

Usage
```PHP
$pdf->build();
```

# Requirements & Dependencies
- FPDF 1.81 Minimum [http://www.fpdf.org/]
- PHP 5.3 Minimum (Built on PHP 7.2) [https://php.net]
