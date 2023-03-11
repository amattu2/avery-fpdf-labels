<?php
/*
 * Produced: Tue Jan 24 2023
 * Author: Alec M.
 * GitHub: https://amattu.com/links/github
 * Copyright: (C) 2023 Alec M.
 * License: License GNU Affero General Public License v3.0
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Files
require 'vendor/autoload.php';
require 'src/LabelSheet.php';

/**
 * This is a wrapper for FPDF to provide RoundedRect functionality
 *
 * It is not required
 */
class PDF extends Fpdf\Fpdf

{
  function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
  {
    $k = $this->k;
    $hp = $this->h;
    if ($style == 'F') {
      $op = 'f';
    } elseif ($style == 'FD' || $style == 'DF') {
      $op = 'B';
    } else {
      $op = 'S';
    }

    $MyArc = 4 / 3 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

    $xc = $x + $w - $r;
    $yc = $y + $r;
    $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
    if (strpos($corners, '2') === false) {
      $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $y) * $k));
    } else {
      $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
    }

    $xc = $x + $w - $r;
    $yc = $y + $h - $r;
    $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
    if (strpos($corners, '3') === false) {
      $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
    } else {
      $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
    }

    $xc = $x + $r;
    $yc = $y + $h - $r;
    $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
    if (strpos($corners, '4') === false) {
      $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - ($y + $h)) * $k));
    } else {
      $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
    }

    $xc = $x + $r;
    $yc = $y + $r;
    $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
    if (strpos($corners, '1') === false) {
      $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $y) * $k));
      $this->_out(sprintf('%.2F %.2F l', ($x + $r) * $k, ($hp - $y) * $k));
    } else {
      $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
    }

    $this->_out($op);
  }

  function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
  {
    $h = $this->h;
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1 * $this->k, ($h - $y1) * $this->k,
      $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
  }
}

/**
 * This is a example custom label class
 */
class ExampleLabel implements amattu2\LabelInterface
{
  private $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function render($pdf, array $dimensions): bool
  {
    [$x, $y, $t] = $dimensions;
    [$w, $h, $lines] = [$t['column_width'], $t['row_height'], $t['max_lines']];

    $pdf->MultiCell($w, $h / $lines, $this->data, 0, 'R');

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

// Get Template
$template = $_GET['template'] ?? "AveryPresta94107";
$templates = amattu2\LabelSheet::getTemplates();
$templateProps = $templates[$template];

// Create Label Sheet and PDF
$labelsheet = new amattu2\LabelSheet($template);
$pdf = new PDF();

// Set PDF Properties
$pdf->SetTitle("Avery Template Example");
$pdf->SetFont('Helvetica', '', 11);
$pdf->SetTextColor(25, 25, 25);

// Add Text Labels
for ($i = 0; $i < ($_GET['count'] ?? 35); $i++) {
  $index = $i + 1;
  $lab = ["Test User {$index}", rand(0, 10000) . " Red Way Drive"];

  if ($i % 3 == 0) {
    $lab[] = "APT #" . rand(0, 999);
  }
  // Demo: Add an extra line
  $lab[] = "Rockwell, IN 20580";

  $labelsheet->addTextLabel($lab);
}

// Add example image label
// Calculate Width and Height based on the selected template.
// You don't need to do this in your implementation. It's only for the placeholder image.
[$w, $h] = [
  floor($templateProps['column_width'] * 3.7795275591),
  floor($templateProps['row_height'] * 3.7795275591),
];
$labelsheet->addImageLabel("https://api.placeholder.app/image/{$w}x{$h}/.png");

// Add an example custom label
$customLab = new ExampleLabel("Custom Label");
$labelsheet->addCustomLabel($customLab);

// Write to PDF
$labelsheet->writeTo($pdf, true);

// Output PDF
$pdf->Output("I", "export.pdf");
