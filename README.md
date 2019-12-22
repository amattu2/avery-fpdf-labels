# Introduction
This is a simple PHP Avery label template implementation and extension to the FPDF library. 

Templates Included:
- Avery 5160

# Usage

>Class->**add**(*(string)* String, *(int)* Row, *(int)* Column)

- (String) Multiple line string (Capped at 4 line breaks)
- (Integer, 0-9) Custom Row Position [Optional] [No overwrite validation]
- (Integer, 0-2) Custom Column Position [Optional] [No overwrite validation]

Throws:
- BadValueException

>Class->**build**()

Throws:
- InvalidStateException

# Demo
Include both **fpdf.php** and **labels.class.php**
```
require("fpdf.php");
require("labels.class.php");
```

Create a new template (Eg. Avery_5160)
```
$pdf = new Avery_5160();
```

Configure the PDF (See Below)
```
$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages();
$pdf->SetTopMargin(13);
$pdf->AddPage("P", "Letter");
$pdf->SetFont('Helvetica', '', 11);
$pdf->SetLineWidth(0.1);
```

Add a label
```
$pdf->add("Mr. & Mrs. GitHub\nLine 2\nLine 3");
```

Build PDF
```
$pdf->build();
```

Export
```
$pdf->Output("I", "export.pdf");
````

# Notes
N/A

# Requirements & Dependencies
- FPDF 1.81 Minimum [http://www.fpdf.org/]
- PHP 5.3 Minimum (Built on PHP 7.2) [https://php.net]
