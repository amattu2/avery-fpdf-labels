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
 * A Image Label for the LabelSheet
 */
class ImageLabel implements LabelInterface
{
  private $row;
  private $col;
  private $src;

  public function __construct(?int $row, ?int $col, string $src)
  {
    $this->row = $row;
    $this->col = $col;
    $this->src = $src;
  }

  public function render($pdf, array $dimensions): bool
  {
    [$x1, $y1, $t] = $dimensions;
    [$w, $h] = [$t['column_width'], $t['row_height']];

    $pdf->Image($this->src, $x1, $y1, $w, $h);

    return true;
  }

  public function GetRow(): ?int
  {
    return $this->row;
  }

  public function GetCol(): ?int
  {
    return $this->col;
  }
}
