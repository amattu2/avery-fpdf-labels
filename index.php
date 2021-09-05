<?php
/*
  Produced 2019-2021
  By https://github.com/amattu2
  Copy Alec M.
  License GNU Affero General Public License v3.0
*/

// Files
require(dirname(__FILE__) . "/classes/fpdf.class.php");
require("AveryTemplates.class.php");

// Variables
$pdf = new amattu\Avery_5160();

// PDF Properties
$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages();
$pdf->SetTopMargin(13);
$pdf->SetTitle("FPDF Avery Templates");
$pdf->AddPage("P", "Letter");
$pdf->SetFont('Helvetica', '', 11);
$pdf->SetTextColor(25, 25, 25);
$pdf->SetLineWidth(0.1);

// Build
addTestUsers($pdf, ($_GET && isset($_GET['count']) && is_numeric($_GET['count']) && $_GET['count'] < 1001 ? $_GET['count'] : 35));
$pdf->build();

// Output PDF
$pdf->Output("I", "export.pdf");

/*
  Demo Function
*/
function addTestUsers($template, $amount = 5) {
  for ($i = 0; $i < $amount; $i++) {
    $index = $i + 1;
    $lab = ["Test User {$index}", "9094 Red Way Drive"];

    if ($i % 2 == 0)
      $lab[] = "APT #231";
    $lab[] = "Rockwell, IN 20580";

    $template->add($lab);
  }
}
?>
