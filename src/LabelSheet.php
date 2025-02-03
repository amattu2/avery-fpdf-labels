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

namespace amattu2;

use amattu2\LabelType\ImageLabel;
use amattu2\LabelType\TextLabel;
use InvalidArgumentException;
use TypeError;

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
  private const LABELS = [
    "Avery5160" => [
      "top" => 12.9,
      "left" => 5,
      "rows" => 10,
      "row_height" => 25.3,
      "row_gap" => 0.15,
      "column_gap" => 3,
      "columns" => 3,
      "column_width" => 66.675,
      "max_lines" => 4,
      "border_radius" => 2.3,
      "border_width" => 0.2,
      "size" => "Letter",
    ],
    "Avery5163" => [
      "top" => 12.9,
      "left" => 4,
      "rows" => 5,
      "row_height" => 50.6,
      "row_gap" => 0.15,
      "column_gap" => 5,
      "columns" => 2,
      "column_width" => 101.5,
      "max_lines" => 10,
      "border_radius" => 4.85,
      "border_width" => 0.1,
      "size" => "Letter",
    ],
    "AveryPresta94107" => [
      "top" => 15.9,
      "left" => 15.9,
      "rows" => 4,
      "row_height" => 50.8,
      "row_gap" => 14.775,
      "column_gap" => 15.867,
      "columns" => 3,
      "column_width" => 50.8,
      "max_lines" => 6,
      "border_radius" => 0,
      "border_width" => 0.4,
      "size" => "Letter",
    ],
    "Avery5392" => [
      "top" => 25.4,
      "left" => 6.35,
      "rows" => 3,
      "row_height" => 76.2,
      "row_gap" => 0,
      "column_gap" => 0,
      "columns" => 2,
      "column_width" => 101.6,
      "max_lines" => 3,
      "border_radius" => 0,
      "border_width" => 0,
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
   * Class Constructor
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

  /**
   * Returns the list of available templates
   *
   * @return array
   */
  public static function getTemplates(): array
  {
    return self::LABELS;
  }

  /**
   * Add a text label to the sheet
   *
   * @param  array        $lines an array of text lines to add to the label
   * @param  string|null  $align the alignment of the text
   * @param  integer|null $row
   * @param  integer|null $col
   * @return void
   */
  public function addTextLabel(array $lines, ?string $align = "C", int $row = null, int $col = null): void
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

    $this->labels[] = new TextLabel($row, $col, array_pad($lines, $this->template['max_lines'], " "), $align);
  }

  /**
   * Add a image label to the sheet
   *
   * @param  string       $path a local path or URL to an image
   * @param  integer|null $row
   * @param  integer|null $col
   * @return void
   */
  public function addImageLabel(string $path, int $row = null, int $col = null): void
  {
    if (!file_exists($path) && filter_var($path, FILTER_VALIDATE_URL) === FALSE) {
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

  /**
   * Add a custom label to the sheet
   *
   * @param  LabelInterface $label an instance of a custom label
   * @return void
   */
  public function addCustomLabel(LabelInterface $label): void
  {
    $this->labels[] = $label;
  }

  /**
   * Build the completed PDF with labels
   *
   * @param \FPDF\Fpdf $pdf FPDF instance to build onto
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
      $r = $item->getRow();
      if (null === $r || $r > $this->template['rows']) {
        $r = $current_row++;
      }
      $pdf->SetY($this->template['top'] + (($this->template['row_height'] + $this->template['row_gap']) * $r));

      // Get Column Placement
      $c = $item->getCol();
      if (null === $c || $c > $this->template['columns']) {
        $c = $current_col;
      }
      $pdf->SetX($this->template['left'] + (($this->template['column_width'] + $this->template['column_gap']) * $c));

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
