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
require 'src/Avery5160.php';

$template = new amattu2\Avery5160(Fpdf\Fpdf::class);

// PDF Properties
$template->pdf->SetTitle("FPDF Template Example");
$template->pdf->AddPage("P", "Letter");
$template->pdf->SetFont('Helvetica', '', 11);
$template->pdf->SetTextColor(25, 25, 25);

// Build
addTestUsers($template, ($_GET['count'] ?? 35));
$template->writeLabels();

// Output PDF
$template->pdf->Output("I", "export.pdf");

/*
  Demo Function
*/
function addTestUsers(Avery\LabelInterface $template, int $amount = 5) : void
{
  for ($i = 0; $i < $amount; $i++) {
    $index = $i + 1;
    $lab = ["Test User {$index}", rand(0, 10000) . " Red Way Drive"];

    if ($i % 3 == 0)
      $lab[] = "APT #" . rand(0, 999); // Demo: Add an extra line
    $lab[] = "Rockwell, IN 20580";

    $template->add($lab);
  }
}
