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

require "LabelInterface.php";

// Exception Classes
class BadValueException extends \Exception {}
class InvalidStateException extends \Exception {}

/**
 * Avery 5160 Label PDF Generator
 *
 * @author Alec M.
 */
class Avery5160 implements LabelInterface
{
  /**
   * FPDF instance
   *
   * @var \FPDF|\Fpdf\Fpdf
   */
  public $pdf = null;

  /**
   * Represents current PDF state
   *
   * @var int
   */
  protected $open_state = 1;

  /**
   * Holds the labels
   *
   * @var array
   */
  protected $labels = [];

  /**
   * PDF top margin
   *
   * @var int
   */
  protected $top = 13;

  /**
   * PDF left margin
   *
   * @var int
   */
  protected $left = 5;

  /**
   * Represents the PDF column width
   *
   * @var int
   */
  public const COLUMN_WIDTH = 66.5;

  /**
   * Represents the PDF maximum number of labels
   *
   * @var int
   */
  public const MAX_LABEL_LINES = 4;

  /**
   * PDF maximum number of columns
   *
   * @var int
   */
  public const COLUMNS = 3;

  /**
   * PDF maximum number of rows
   *
   * @var int
   */
  public const ROWS = 10;

  /**
   * Contructor
   *
   * @param  \FPDF|\Fpdf\Fpdf $pdf
   */
  public function __construct($pdf)
  {
    $this->pdf = new $pdf();
  }

  /**
   * {@inheritdoc}
   */
  public function add(array $lines, int $row = -1, int $col = -1): void
  {
    // Checks
    if (empty($lines)) {
      throw new BadValueException("Cannot add a empty label to PDF");
    }
    if (count($lines) > self::MAX_LABEL_LINES) {
      throw new BadValueException("Cannot add a label with more than {self::MAX_LABEL_LINES} lines to PDF");
    }
    if ($row < -1 || ($row + 1) > self::ROWS) {
      throw new BadValueException("Cannot add a label to that row");
    }
    if ($col < -1 || ($col + 1) > self::COLUMNS) {
      throw new BadValueException("Cannot add a label to that column");
    }

    // Append
    $this->labels[] = [
      array_pad($lines, 4, " "),
      $row,
      $col,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function writeLabels(): void
  {
    // Checks
    if ($this->open_state !== 1) {
      throw new InvalidStateException("Attempt to build onto an existing PDF");
    }

    // FPDF Configuration
    $this->pdf->SetTopMargin($this->top);
    $this->pdf->SetAutoPageBreak(false);

    // Variables
    $bottom = $this->pdf->GetPageHeight() - $this->top;
    $config_row_height = (($bottom - $this->top) / self::ROWS);
    $config_items_per_page = self::ROWS * self::COLUMNS;
    $current_row = 0;
    $current_col = 0;
    $current_page = 0;
    $current_item_count = 0;

    // Loop
    foreach ($this->labels as $item) {
      // Check page overflow
      if ($current_item_count++ > $config_items_per_page) {
        $this->pdf->AddPage("P", "Letter");
        $current_item_count = 1;
        $current_page++;
        $current_col = 0;
        $current_row = 0;
      }

      // Check row overflow
      if ($current_row >= self::ROWS) {
        $current_col++;
        $current_row = 0;
      }

      // Check column overflow
      if ($current_col >= self::COLUMNS) {
        $this->pdf->AddPage("P", "Letter");
        $current_item_count = 1;
        $current_page++;
        $current_col = 0;
        $current_row = 0;
      }

      // Check label position request
      if ($item[1] > self::ROWS || $item[1] < 0) {
        $item[1] = $current_row++;
      }
      if ($item[2] > self::COLUMNS || $item[2] < 0) {
        $item[2] = $current_col;
      }

      // Build Item
      $this->pdf->SetY(($item[1] > 0 ? $this->top + ($config_row_height * $item[1]) + 2 : $this->top + 2));
      $this->pdf->SetX(($item[2] > 0 ? $this->left + (self::COLUMN_WIDTH * $item[2]) + (3 * $item[2]) : $this->left));
      $this->pdf->MultiCell(self::COLUMN_WIDTH, ($config_row_height / 4.5), implode("\n", $item[0]), 0, "C");
    }

    $this->open_state = 0;
  }
}
