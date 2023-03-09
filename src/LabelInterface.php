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

/**
 * Avery Label Template Interface
 */
interface LabelInterface
{
  /**
   * Add a single label to the PDF
   *
   * @param array $lines an array of label lines to add
   * @param integer $row optional desired insert row
   * @param integer $col optional diesired intert column
   * @throws TypeError
   * @throws BadValueException
   * @author Alec M. <https://amattu.com>
   * @date 2021-09-05T13:49:28-040
   */
  public function add(array $lines, int $row = 0, int $col = 0): void;

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
  public function writeLabels(): void;
}
