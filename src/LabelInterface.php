<?php
/*
 * Produced: Sat Mar 11 2023
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

/**
 * Label Type Interface
 *
 * All label types must implement this interface,
 * including custom label types.
 */
interface LabelInterface
{
  /**
   * LabelInterface constructor
   *
   * @param int|null $row
   * @param int|null $col
   * ...any additional arguments required for this label type
   */
  // public function __construct(?int $row, ?int $col, ...$args);

  /**
   * Render the Label
   *
   * @param \TCPDF|\FPDF|\FPDI|\Fpdf\Fpdf $pdf to render to
   * @param array $dimensions [x1, y1, template]
   */
  public function render($pdf, array $dimensions): bool;

  /**
   * Get the requested placement row
   *
   * @return integer|null $row
   */
  public function getRow(): ?int;

  /**
   * Get the requested placement column
   *
   * @return integer|null $col
   */
  public function getCol(): ?int;
}
