<?php

namespace Avery;

class TextLabel implements LabelInterface
{
  private $row;
  private $col;
  private $text;
  private $align;

  public function __construct(?int $row, ?int $col, array $text, string $align = "C")
  {
    $this->row = $row;
    $this->col = $col;
    $this->text = $text;
    $this->align = $align;
  }

  public function render($pdf, array $dimensions): bool
  {
    [$x1, $y1, $t] = $dimensions;

    $pdf->MultiCell($t['column_width'], $t['row_height'] / $t['max_lines'], implode("\n", $this->text), 0, $this->align);

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
