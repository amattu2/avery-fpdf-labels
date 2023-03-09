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

namespace Avery;

use TypeError;
use InvalidArgumentException;

require "LabelInterface.php";
require "LabelType/TextLabel.php";

/**
 * Avery Label FPDF Generator
 *
 * @author Alec M.
 */
class LabelSheet
{
  /**
   * Holds the label definitions
   *
   * @var array
   */
  public const LABELS = [
    "Avery5160" => [
      "top" => 13,
      "bottom" => 13,
      "left" => 5,
      "rows" => 10,
      "row_height" => 25.2,
      "row_gap" => false,
      "column_gap" => true,
      "columns" => 3,
      "column_width" => 66.675,
      "max_lines" => 4,
      "border_radius" => 2.5,
      "border_width" => 0.2,
      "size" => "Letter",
    ],
    "AveryPresta94107" => [
      "top" => 16,
      "bottom" => 16,
      "left" => 16,
      "rows" => 4,
      "row_height" => 50.8,
      "row_gap" => true,
      "column_gap" => true,
      "columns" => 3,
      "column_width" => 50.8,
      "max_lines" => 6,
      "border_radius" => 0,
      "border_width" => 0.4,
      "size" => "Letter",
    ],
  ];

  /**
   * References the label template to use
   *
   * @var string a label from the LABELS array
   */
  protected $template = null;

  /**
   * Holds the labels
   *
   * @var LabelInterface[]
   */
  protected $labels = [];

  /**
   * Contructor
   *
   * @param string $template a label from the LABELS array
   * @throws InvalidArgumentException
   * @throws TypeError
   */
  public function __construct(string $template)
  {
    if (!array_key_exists($template, self::LABELS)) {
      throw new InvalidArgumentException("Unknown label template {$template}");
    }

    $this->template = self::LABELS[$template];
  }

  public function addTextLabel(array $lines, int $row = null, int $col = null): void
  {
    if (empty($lines)) {
      throw new InvalidArgumentException("Cannot add a empty label to PDF");
    }
    if (count($lines) > $this->template['max_lines']) {
      throw new InvalidArgumentException("Cannot add a label with more than {$this->template['max_lines']} lines to PDF");
    }
    if ($row && ($row + 1) > $this->template['rows']) {
      throw new InvalidArgumentException("Cannot add a label to row {$row}");
    }
    if ($col && ($col + 1) > $this->template['columns']) {
      throw new InvalidArgumentException("Cannot add a label to column {$col}");
    }

    $this->labels[] = new TextLabel($row, $col, array_pad($lines, $this->template['max_lines'], " "));
  }

  public function addImageLabel(string $path, int $row = null, int $col = null): void
  {
    if (!file_exists($path) || filter_var($path, FILTER_VALIDATE_URL) === FALSE) {
      throw new InvalidArgumentException("Cannot add a label with an invalid image path");
    }
    if ($row && ($row + 1) > $this->template['rows']) {
      throw new InvalidArgumentException("Cannot add a label to row {$row}");
    }
    if ($col && ($col + 1) > $this->template['columns']) {
      throw new InvalidArgumentException("Cannot add a label to column {$col}");
    }

    $this->labels[] = new ImageLabel($row, $col, $path);
  }

  public function addCustomLabel(LabelInterface $type, int $row = null, int $col = null): void
  {
    if ($row && ($row + 1) > $this->template['rows']) {
      throw new InvalidArgumentException("Cannot add a label to row {$row}");
    }
    if ($col && ($col + 1) > $this->template['columns']) {
      throw new InvalidArgumentException("Cannot add a label to column {$col}");
    }

    $this->labels[] = new $type($row, $col);
  }

  /**
   * Build the completed PDF with labels
   *
   * @param $pdf FPDF instance to build onto
   * @param  bool $borders optional whether to draw borders around label boxes
   * @return bool true on success
   */
  public function writeTo($pdf, bool $borders = false): bool
  {
    if ($pdf->PageNo() <= 0) {
      $pdf->AddPage("P", $this->template['size']);
    }

    // PDF Configuration
    $pdf->SetTopMargin($this->template['top']);
    $pdf->SetLineWidth($this->template['border_width']);
    $pdf->SetAutoPageBreak(false);

    $useRoundedRect = method_exists($pdf, "RoundedRect") && $this->template['border_radius'] > 0;
    $itemsPerPage = $this->template['rows'] * $this->template['columns'];

    $colGap = $this->template['column_gap']
      ? ($pdf->GetPageWidth() - ($this->template['left'] + ($this->template['column_width'] * $this->template['columns']))) / $this->template['columns']
      : 0;
    $rowGap = $this->template['row_gap']
      ? ($pdf->GetPageHeight() - ($this->template['top'] + ($this->template['row_height'] * $this->template['rows']))) / $this->template['rows']
      : 0;

    $current_row = 0;
    $current_col = 0;
    $current_item_count = 0;
    foreach ($this->labels as $item) {
      // Check overflows
      if ($current_item_count++ > $itemsPerPage) {
        $pdf->AddPage("P", $this->template['size']);
        $current_item_count = 1;
        $current_col = 0;
        $current_row = 0;
      }
      if ($current_row >= $this->template['rows']) {
        $current_col++;
        $current_row = 0;
      }
      if ($current_col >= $this->template['columns']) {
        $pdf->AddPage("P", $this->template['size']);
        $current_item_count = 1;
        $current_col = 0;
        $current_row = 0;
      }

      // Get Row Placement
      $r = $item->GetRow();
      if (!$r || $r > $this->template['rows']) {
        $r = $current_row++;
      }
      $pdf->SetY($this->template['top'] + (($this->template['row_height'] + $rowGap) * $r));

      // Get Column Placement
      $c = $item->GetCol();
      if (!$c || $c > $this->template['columns']) {
        $c = $current_col;
      }
      $pdf->SetX($this->template['left'] + (($this->template['column_width'] + $colGap) * $c));

      // Draw Borders
      if ($borders) {
        if ($useRoundedRect) {
          $pdf->RoundedRect($pdf->GetX(), $pdf->GetY(), $this->template['column_width'], $this->template['row_height'], $this->template['border_radius']);
        } else {
          $pdf->Rect($pdf->GetX(), $pdf->GetY(), $this->template['column_width'], $this->template['row_height']);
        }

      }

      // Render Label
      $item->render($pdf, [
        // x1, y1
        $pdf->GetX(), $pdf->GetY(),
        // template
        $this->template,
      ]);
    }

    return true;
  }
}
