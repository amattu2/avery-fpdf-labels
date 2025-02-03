Documentation
=============

## Build the Label Sheet

```php
$labelSheet = new amattu2\LabelSheet('Avery5160');
```

### Add Text Labels

Use the `addTextLabel` method to add a text label to the template. The method accepts an array of strings, an optional alignment parameter, and optional row and column parameters. This would typically be used for address labels.

```php
addTextLabel(array $lines, ?string $align = "C", int $row = null, int $col = null): void
```

```php
$labelSheet->addTextLabel([
  "line 1",
  "line 2",
  "line 3",
  // ...
]);
```

### Add Image Labels

Use the `addImageLabel` method to add an image label to the template. The method accepts a path to an image file and optional row and column parameters.

```php
addImageLabel(string $path, int $row = null, int $col = null): void
```

```php
$labelSheet->addImageLabel('https://api.placeholder.app/image/350x350/.png');
```

### Add Custom Labels

The `addCustomLabel` method allows you to expand upon the current label types (e.g. adding barcodes).
Start by creating your custom label class.

```php
class ExampleLabel implements amattu2\LabelInterface
{
    private string $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function render($pdf, array $dimensions): bool
    {
        [$x1, $y1, $template] = $dimensions;
        [$w, $h] = [$t['column_width'], $t['row_height']];

        $pdf->Cell($w, $h, $this->data, 0, 0, 'C');

        return true;
    }

    public function GetRow(): ?int
    {
        return null;
    }

    public function GetCol(): ?int
    {
        return null;
    }
}
```

Then pass it to `addCustomLabel`.
```php
$labelSheet->addCustomLabel(new ExampleLabel('example data'));
```

## Export a PDF

```php
$pdf = new Fpdf\Fpdf();

// Or, if you want rounded borders to match the labels
// $pdf = new amattu2\PDF();

// Set PDF Properties
$pdf->SetTitle("Avery Template Example");
$pdf->SetFont('Helvetica', '', 11);
$pdf->SetTextColor(25, 25, 25);

$labelSheet->writeTo($pdf, true);

file_put_contents('output.pdf', $pdf->Output('S', 'output.pdf'));
```
