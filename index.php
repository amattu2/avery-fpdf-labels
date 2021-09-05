<?php
/*
	Produced 2019
	By https://github.com/amattu2
	Copy Alec M.
	License GNU Affero General Public License v3.0
*/

// Files
require(dirname(__FILE__) . "/classes/fpdf.class.php");
require("labels.class.php");

// Variables
$pdf = new Avery_5160();

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
//$pdf->add("Test E. Tester\n9094 Waterford Dr.\nRockwell, IN 20580");
//$pdf->add("Test #0\nBBBBB\nCCCCCC", 1, 1);
addTestUsers($pdf, ($_GET && isset($_GET['count']) && is_numeric($_GET['count']) && $_GET['count'] < 1001 ? $_GET['count'] : 35));
$pdf->build();

// Output PDF
$pdf->Output("I", "export.pdf");

/**
	Demo Function
**/
function addTestUsers($template, $amount = 5) {
	for ($i = 0; $i < $amount; $i++) {
		$index = $i + 1;
		$template->add("Test User {$index}\n9094 Red Way Drive\nAPT 494\nRockwell, IN 20580");
	}
}
?>
